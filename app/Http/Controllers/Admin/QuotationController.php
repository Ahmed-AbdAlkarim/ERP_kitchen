<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Customer;
use App\Models\Product;
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
        $quotation->load('items', 'customer');
        return view('admin.quotations.show', compact('quotation'));
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

    public function print(Quotation $quotation)
    {
        $quotation->load('items', 'customer');
        return view('admin.quotations.print', compact('quotation'));
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
}
