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
            text-align: right;
        }

        .mb-10 { margin-bottom: 10px; }
        .mb-20 { margin-bottom: 20px; }
        .terms { font-size: 12px; }

        @media print {
            .no-print { display: none; }
            body { margin: 0; padding: 10px; }
            * { page-break-inside: avoid; }
            @page { size: A4; margin: 0.5cm; }
        }
    </style>
</head>
<body>

<div class="no-print mb-20">
    <button onclick="window.print()">🖨️ طباعة</button>
</div>

{{-- العنوان --}}
<h2 class="text-center mb-10">عقد اتفاق</h2>

{{-- بيانات الشركة --}}
<table class="no-border mb-10">
    <tr>
        <td><strong>اسم الشركة:</strong> كيتشن ميتر للمطابخ</td>
        <td><strong>العنوان:</strong> الرياض - الشفا - بدر</td>
    </tr>
    <tr>
        <td><strong>الرقم الضريبي:</strong></td>
        <td></td>
    </tr>
</table>

{{-- بيانات العقد --}}
<table class="no-border mb-10">
    <tr>
        <td>
            <strong>رقم العقد:</strong>
            CN-{{ $contract->created_at->format('Y') }}-{{ str_pad($contract->id, 4, '0', STR_PAD_LEFT) }}
        </td>
        <td>
            <strong>رقم عرض السعر:</strong>
            {{ $contract->quotation->display_number ?? '-' }}
        </td>
    </tr>
    <tr>
        <td><strong>اسم العميل:</strong> {{ $contract->customer->name }}</td>
        <td><strong>رقم الجوال:</strong> {{ $contract->customer->phone }}</td>
    </tr>
    <tr>
        <td colspan="2">
            <strong>عنوان العميل:</strong>
            {{ $contract->customer->address }}
        </td>
    </tr>
    <tr>
        <td><strong>تاريخ إنشاء العقد:</strong> {{ $contract->created_at->format('Y-m-d') }}</td>
        <td><strong>تاريخ التسليم:</strong> {{ $contract->delivery_date }}</td>
    </tr>
</table>

{{-- تفاصيل العقد --}}
<h3 class="mb-10">تفاصيل العقد</h3>
@php
    $details = $contract->details;
    $mid = ceil($details->count() / 2);
    $firstHalf = $details->take($mid);
    $secondHalf = $details->skip($mid);
@endphp
<div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
    <table style="width: 48%;">
        <thead>
            <tr>
                <th>#</th>
                <th>البند</th>
                <th>التفاصيل</th>
            </tr>
        </thead>
        <tbody>
            @foreach($firstHalf as $detail)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $detail->title }}</td>
                    <td>{{ $detail->value }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <table style="width: 48%;">
        <thead>
            <tr>
                <th>#</th>
                <th>البند</th>
                <th>التفاصيل</th>
            </tr>
        </thead>
        <tbody>
            @foreach($secondHalf as $detail)
                <tr>
                    <td>{{ $loop->iteration + $mid }}</td>
                    <td>{{ $detail->title }}</td>
                    <td>{{ $detail->value }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- الشروط والأحكام --}}
@if($terms->count())
    <h3 class="mb-10">الشروط والأحكام</h3>
    <ol class="terms">
        @foreach($terms as $term)
            <li>{{ $term->term }}</li>
        @endforeach
    </ol>
@endif

{{-- توقيعات --}}
<div style="margin-top: 50px;">
    <table class="no-border" style="width: 100%;">
        <tr>
            <td style="width: 50%; text-align: center;">
                <strong>توقيع العميل</strong><br>
                {{ $contract->customer->name }}<br>
                ____________________
            </td>
            <td style="width: 50%; text-align: center;">
                <strong>توقيع الموظف المسؤول</strong><br>
                {{ $contract->quotation->createdBy->name ?? 'غير محدد' }}<br>
                ____________________
            </td>
        </tr>
    </table>
</div>

<script>
    window.onload = function () {
        window.print();
    }
</script>

</body>
</html>
