<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
   

    <style>
        body {
            font-family: 'Tahoma', Arial, sans-serif;
            font-size: 14px;
            color: #000;
        }

        h2, h3 {
            margin: 0;
        }

        .text-center { text-align: center; }
        .text-right  { text-align: right; }
        .text-left   { text-align: left; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table, th, td {
            border: 1px solid #000;
        }

        th, td {
            padding: 6px;
            text-align: center;
        }

        .no-border td {
            border: none;
            padding: 4px;
        }

        .summary-table td {
            text-align: right;
            padding: 6px;
        }

        .mb-10 { margin-bottom: 10px; }
        .mb-20 { margin-bottom: 20px; }

        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>

<div class="no-print text-left mb-20">
    <button onclick="window.print()">🖨️ طباعة</button>
</div>

{{-- العنوان --}}
<h2 class="text-center mb-20">عرض سعر</h2>

{{-- بيانات الشركة --}}
<table class="no-border mb-20">
    <tr>
        <td><strong>شركة: </strong> كيتشن ميتر للمطابخ</td>
        <td><strong>العنوان:</strong> الرياض - الشفا - بدر</td>
    </tr>
    <tr>
        <td><strong>الرقم الضريبي:</strong> </td>
        <td></td>
    </tr>
</table>

{{-- بيانات عرض السعر --}}
<table class="no-border mb-20">
    <tr>
        <td><strong>رقم عرض السعر:</strong> {{ $quotation->quotation_number }}</td>
        <td><strong>أنشئ بواسطة:</strong> {{ $quotation->createdBy->name ?? '-' }}</td>
    </tr>
    <tr>
        <td><strong>تاريخ الإنشاء:</strong> {{ $quotation->issue_date }}</td>
        <td><strong>تاريخ الانتهاء:</strong> {{ $quotation->expiry_date }}</td>
    </tr>
</table>

{{-- بيانات العميل --}}
<table class="no-border mb-20">
    <tr>
        <td><strong>اسم العميل:</strong> {{ $quotation->customer->name }}</td>
        <td><strong>رقم الجوال:</strong> {{ $quotation->customer->phone }}</td>
    </tr>
    <tr>
        <td colspan="2"><strong>عنوان العميل:</strong> {{ $quotation->customer->address }}</td>
    </tr>
</table>

{{-- جدول المنتجات --}}
<table class="mb-20">
    <thead>
        <tr>
            <th>#</th>
            <th>اسم المنتج</th>
            <th>الكمية</th>
            <th>السعر غير شامل الضريبة</th>
            <th>الضريبة</th>
            <th>الإجمالي شامل الضريبة</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalQty = 0;
            $totalWithoutTax = 0;
        @endphp

        @foreach($quotation->items as $item)
            @php
                $itemSubtotal = $item->price * $item->quantity;
                $itemTax = $item->product?->is_taxable ? $itemSubtotal * 0.15 : 0;
            @endphp

            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->product_name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($itemSubtotal, 2) }}</td>
                <td>{{ number_format($itemTax, 2) }}</td>
                <td>{{ number_format($itemSubtotal + $itemTax, 2) }}</td>
            </tr>

            @php
                $totalQty += $item->quantity;
                $totalWithoutTax += $itemSubtotal;
            @endphp
        @endforeach
    </tbody>
</table>

{{-- جدول الإجماليات --}}
<table class="summary-table mb-20" style="width: 40%; float: left;">
    <tr>
        <td>إجمالي الكمية</td>
        <td>{{ $totalQty }}</td>
    </tr>
    <tr>
        <td>الإجمالي غير شامل الضريبة</td>
        <td>{{ number_format($totalWithoutTax, 2) }}</td>
    </tr>
    <tr>
        <td>قيمة الضريبة</td>
        <td>{{ number_format($quotation->tax, 2) }}</td>
    </tr>
    <tr>
        <td><strong>المجموع الشامل</strong></td>
        <td><strong>{{ number_format($quotation->total, 2) }}</strong></td>
    </tr>
</table>

<div style="clear: both;"></div>

{{-- الشروط والأحكام --}}
@if($terms->count())
    <h3 class="mb-10">الشروط والأحكام</h3>
    <ol>
        @foreach($terms as $term)
            <li>{{ $term->term }}</li>
        @endforeach
    </ol>
@endif


<script>
    window.onload = function () {
        window.print();
    }
</script>

</body>
</html>
