<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\SalesInvoice;
use App\Models\SalesInvoiceItem;
use App\Models\TermCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuotationController extends Controller
{
    public function index()
    {
        $quotations = Quotation::with('customer')->latest()->get();
        return view('admin.quotations.index', compact('quotations'));
    }

    public function create()
    {
        $customers = Customer::all();
        $products  = Product::all();

        return view('admin.quotations.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'expiry_date' => 'required|date|after_or_equal:today',
            'items'       => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
        ], [
            'items.required' => 'لازم تضيف منتج واحد على الأقل',
            'items.min'      => 'لازم تضيف منتج واحد على الأقل',
        ]);

        DB::beginTransaction();

        try {

            $quotationNumber = 'QT-' . now()->format('Y') . '-' .
                str_pad(Quotation::count() + 1, 4, '0', STR_PAD_LEFT);

            $quotation = Quotation::create([
                'quotation_number' => $quotationNumber,
                'customer_id'      => $request->customer_id,
                'issue_date'       => now(),
                'expiry_date'      => $request->expiry_date,
                'subtotal'         => 0,
                'tax'              => 0,
                'discount'         => 0,
                'total'            => 0,
                'status'           => 'pending',
                'created_by'       => auth()->id(), 
            ]);

            $subtotal = 0;
            $taxTotal = 0;

            foreach ($request->items as $item) {

                $product  = Product::findOrFail($item['product_id']);
                $price    = $product->selling_price; // ✅ الصح
                $quantity = $item['quantity'];

                $lineTotal = $price * $quantity;
                $itemTax   = $product->is_taxable ? $lineTotal * 0.15 : 0;

                QuotationItem::create([
                    'quotation_id' => $quotation->id,
                    'product_id'   => $product->id,
                    'product_name' => $product->name,
                    'price'        => $price,
                    'quantity'     => $quantity,
                    'total'        => $lineTotal,
                ]);

                $subtotal += $lineTotal;
                $taxTotal += $itemTax;
            }

            $quotation->update([
                'subtotal' => $subtotal,
                'tax'      => $taxTotal,
                'total'    => $subtotal + $taxTotal,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.quotations.index')
                ->with('success', 'تم إنشاء عرض السعر بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage());
        }
    }

    public function show(Quotation $quotation)
    {
        $quotation->load('items', 'customer', 'salesInvoice');
        $cashboxes = \App\Models\Cashbox::where('type', 'daily')->get();
        return view('admin.quotations.show', compact('quotation', 'cashboxes'));
    }

    public function edit(Quotation $quotation)
    {
        if ($quotation->status !== 'pending') {
            abort(403);
        }

        $customers = Customer::all();
        $products  = Product::all();
        $quotation->load('items');

        return view('admin.quotations.edit', compact('quotation', 'customers', 'products'));
    }

    public function update(Request $request, Quotation $quotation)
    {
        if ($quotation->status !== 'pending') {
            abort(403);
        }

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'expiry_date' => 'required|date',
            'items'       => 'required|array|min:1',
        ]);

        DB::beginTransaction();

        try {

            $quotation->items()->delete();

            $subtotal = 0;
            $taxTotal = 0;

            foreach ($request->items as $item) {

                $product = Product::findOrFail($item['product_id']);
                $price   = $product->selling_price; // ✅ الصح
                $qty     = $item['quantity'];

                $lineTotal = $price * $qty;
                $itemTax   = $product->is_taxable ? $lineTotal * 0.15 : 0;

                QuotationItem::create([
                    'quotation_id' => $quotation->id,
                    'product_id'   => $product->id,
                    'product_name' => $product->name,
                    'price'        => $price,
                    'quantity'     => $qty,
                    'total'        => $lineTotal,
                ]);

                $subtotal += $lineTotal;
                $taxTotal += $itemTax;
            }

            $quotation->update([
                'customer_id' => $request->customer_id,
                'expiry_date' => $request->expiry_date,
                'subtotal'    => $subtotal,
                'tax'         => $taxTotal,
                'total'       => $subtotal + $taxTotal,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.quotations.index')
                ->with('success', 'تم تحديث عرض السعر بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage());
        }
    }

    public function print($quotation)
    {
        $quotation = Quotation::with([
            'customer',
            'items',
            'createdBy'
        ])->findOrFail($quotation);

        $terms = TermCondition::where('active', true)
            ->orderBy('sort_order')
            ->get();

        return view('admin.quotations.print', compact('quotation', 'terms'));
    }


    public function destroy(Quotation $quotation)
    {
        if ($quotation->status !== 'pending') {
            abort(403);
        }

        $quotation->delete();

        return redirect()
            ->route('admin.quotations.index')
            ->with('success', 'تم حذف عرض السعر');
    }


    public function convertToInvoice(Request $request, Quotation $quotation)
    {
        if ($quotation->status !== 'pending') {
            abort(403);
        }

        if (SalesInvoice::where('quotation_id', $quotation->id)->exists()) {
            abort(403);
        }

        $request->validate([
            'payment_status' => 'required|in:paid,partial,installment',
            'cashbox_id'     => 'nullable|required_if:payment_status,paid,partial|exists:cashboxes,id',
            'amount'         => 'nullable|required_if:payment_status,paid,partial|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {

            $total = $quotation->total;
            $paid  = 0;

            if (in_array($request->payment_status, ['paid', 'partial'])) {
                $paid = $request->amount;
            }

            // ✅ check الدفع الكلي
            if ($request->payment_status === 'paid' && $paid < $total) {
                throw new \Exception('يرجى إدخال المبلغ كامل في حالة الدفع الكلي');
            }

            $remaining = $total - $paid;

            $paymentMethod = match ($request->payment_status) {
                'paid', 'partial' => 'cash',
                'installment'     => 'installment',
            };

            // 1️⃣ إنشاء الفاتورة
            $invoice = SalesInvoice::create([
                'invoice_number'    => 'S-' . date('Ymd') . '-' . rand(1000, 9999),
                'invoice_date'      => now(),
                'user_id'           => auth()->id(),
                'customer_id'       => $quotation->customer_id,
                'quotation_id'      => $quotation->id,
                'subtotal'          => $quotation->subtotal,
                'discount'          => 0,
                'total'             => $total,
                'payment_method'    => $paymentMethod,
                'status'            => $request->payment_status,
                'paid_amount'       => $paid,
                'remaining_amount' => $remaining,
            ]);

            // 2️⃣ الأصناف + المخزون
            foreach ($quotation->items as $item) {

                $product = Product::lockForUpdate()->find($item->product_id);

                SalesInvoiceItem::create([
                    'sales_invoice_id' => $invoice->id,
                    'product_id'       => $product->id,
                    'qty'              => $item->quantity,
                    'price'            => $item->price,
                    'total'            => $item->total,
                    'profit'           => 0,
                ]);

                if (!$product->is_service) {
                    $product->decrement('stock', $item->quantity);
                }
            }

            // 3️⃣ تسجيل الدفع في الخزنة
            if ($paid > 0) {
                app(\App\Services\CashboxService::class)->addTransaction(
                    $request->cashbox_id,
                    'in',
                    $paid,
                    'sales_invoice',
                    $invoice->id,
                    'تحصيل فاتورة بيع من عرض سعر #' . $quotation->quotation_number
                );
            }

            // 4️⃣ تحديث حالة عرض السعر
            $quotation->update([
                'status' => 'converted',
                'converted_by' => auth()->id(),
                'converted_at' => now(),
            ]);

            DB::commit();

            return redirect()
                ->route('admin.sales-invoices.show', $invoice->id)
                ->with('success', 'تم تحويل عرض السعر إلى فاتورة بيع');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage());
        }
    }




}
