<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CashboxTransaction;
use App\Models\Customer;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\View;

class CashboxReceiptController extends Controller
{
    public function print(CashboxTransaction $transaction)
    {
        // نسمح بالوارد فقط
        if (
            $transaction->type !== 'in' ||
            !in_array($transaction->module, ['customer_payment', 'sales_invoice'])
        ) {
            abort(403);
        }

        $customer = null;

        if ($transaction->module === 'customer_payment') {
            $customer = Customer::find($transaction->module_id);
        }

        // Render Blade to HTML
        $html = View::make(
            'admin.cashboxes.receipts.pdf',
            compact('transaction', 'customer')
        )->render();

        // إنشاء PDF
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'direction' => 'rtl',
            'default_font' => 'dejavusans'
        ]);

        $mpdf->WriteHTML($html);

        return response(
            $mpdf->Output('receipt-'.$transaction->id.'.pdf', 'S'),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="receipt-'.$transaction->id.'.pdf"',
            ]
        );
    }
}
