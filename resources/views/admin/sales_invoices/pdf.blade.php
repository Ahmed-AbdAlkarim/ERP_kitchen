<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>فاتورة - كيتشن ميتر للمطابخ</title>

    <style>
        /* إعدادات أساسية */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Arial, sans-serif;
            background: #ffffff;
            direction: rtl;
            color: #000;
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 10mm 15mm;
        }

        @media print {
            body {
                padding: 5mm 10mm;
                font-size: 12px;
            }
        }

        /* رأس الفاتورة */
        .header {
            border-bottom: 3px solid #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .company-name {
            font-size: 28px;
            font-weight: 900;
        }

        .invoice-title {
            font-size: 32px;
            font-weight: 700;
        }

        /* معلومات الفاتورة */
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin: 25px 0;
            font-size: 14px;
        }

        .info-box {
            flex: 1;
            padding: 0 15px;
        }

        .info-box:first-child {
            border-left: 2px solid #000;
        }

        .info-box h3 {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 10px;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }

        .info-row {
            display: flex;
            margin-bottom: 6px;
        }

        .info-label {
            min-width: 120px;
            font-weight: 600;
        }

        /* عنوان القسم */
        .section-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 10px;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
        }

        /* ========================= */
        /* جدول المنتجات (معدل) */
        /* ========================= */

        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
            font-size: 13px;
            border: 2px solid #000;
        }

        .products-table thead {
            background: #eaeaea;
        }

        .products-table th {
            padding: 10px 6px;
            text-align: center;
            font-weight: 700;
            border: 1px solid #000;
            white-space: nowrap;
        }

        .products-table td {
            padding: 8px 6px;
            border: 1px solid #000;
            text-align: center;
        }

        .products-table tbody tr:nth-child(even) {
            background: #f8f8f8;
        }

        .products-table th:first-child,
        .products-table td:first-child {
            border-right: 2px solid #000;
        }

        .products-table th:last-child,
        .products-table td:last-child {
            border-left: 2px solid #000;
        }

        .product-name {
            text-align: right;
            font-weight: 600;
            line-height: 1.4;
        }

        /* المجاميع */
        .totals-section {
            margin-top: 30px;
        }

        .totals-container {
            width: 50%;
            margin-right: auto;
            border: 2px solid #000;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 14px;
            border-bottom: 1px solid #000;
            font-size: 14px;
        }

        .total-row:last-child {
            border-bottom: none;
        }

        .subtotal-row { background: #f7f7f7; }
        .tax-row { background: #ededed; }
        .payment-row { background: #f3f3f3; }
        .remaining-row { background: #e6e6e6; font-weight: 700; }

        .final-row {
            background: #000;
            color: #fff;
            font-size: 16px;
            font-weight: 700;
        }

        .final-row span {
            color: #fff;
        }

        /* زر الطباعة */
        .no-print {
            text-align: center;
            margin-top: 20px;
        }

        .no-print button {
            padding: 10px 25px;
            background: #000;
            color: #fff;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

    <!-- رأس الفاتورة -->
    <div class="header">
        <div>
            <div class="company-name">زووم للمطابخ</div>
            <div>رقم السجل التجاري: 1010685762</div>
            <div>الرقم الضريبي: 310858801200003</div>
        </div>
        <div class="invoice-title">فاتورة بيع</div>
    </div>

    <!-- معلومات الفاتورة -->
    <div class="invoice-info">
        <div class="info-box">
            <h3>بيانات الفاتورة</h3>
            <div class="info-row"><span class="info-label">رقم الفاتورة:</span>{{ $invoice->invoice_number }}</div>
            <div class="info-row"><span class="info-label">التاريخ:</span>{{ $invoice->invoice_date }}</div>
            <div class="info-row"><span class="info-label">الموظف:</span>{{ $invoice->user->name }}</div>
        </div>

        <div class="info-box">
            <h3>بيانات العميل</h3>
            <div class="info-row"><span class="info-label">الاسم:</span>{{ $invoice->customer->name ?? '-' }}</div>
            <div class="info-row"><span class="info-label">الهاتف:</span>{{ $invoice->customer->phone ?? '-' }}</div>
            <div class="info-row"><span class="info-label">العنوان:</span>{{ $invoice->customer->address ?? '-' }}</div>
        </div>
    </div>

    <!-- جدول المنتجات -->
    <div>
        <div class="section-title">تفاصيل المنتجات</div>
        <table class="products-table">
            <thead>
                <tr>
                    <th width="40%">المنتج</th>
                    <th width="12%">الكمية</th>
                    <th width="16%">سعر الوحدة</th>
                    <th width="16%">الضريبة</th>
                    <th width="16%">الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td class="product-name">{{ $item->product->name }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ number_format($item->price, 2) }} ر.س</td>
                    <td>{{ number_format($item->qty * $item->price * 0.15, 2) }} ر.س</td>
                    <td>{{ number_format($item->qty * $item->price, 2) }} ر.س</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- المجاميع -->
    <div class="totals-section">
        <div class="totals-container">
            <div class="total-row subtotal-row">
                <span>المجموع الفرعي</span>
                <span>{{ number_format($invoice->subtotal, 2) }} ر.س</span>
            </div>
            <div class="total-row tax-row">
                <span>الضريبة (15%)</span>
                <span>{{ number_format($invoice->tax, 2) }} ر.س</span>
            </div>
            <div class="total-row">
                <span>الخصم</span>
                <span>- {{ number_format($invoice->discount ?? 0, 2) }} ر.س</span>
            </div>
            <div class="total-row final-row">
                <span>الإجمالي النهائي</span>
                <span>{{ number_format($invoice->total, 2) }} ر.س</span>
            </div>
            <div class="total-row payment-row">
                <span>المدفوع</span>
                <span>{{ number_format($invoice->paid_amount ?? 0, 2) }} ر.س</span>
            </div>
            <div class="total-row remaining-row">
                <span>المتبقي</span>
                <span>{{ number_format($invoice->remaining_amount, 2) }} ر.س</span>
            </div>
        </div>
    </div>

    <!-- زر الطباعة -->
    <div class="no-print">
        <button onclick="window.print()">طباعة الفاتورة</button>
    </div>

</body>
</html>
