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

        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>

<div class="no-print mb-20">
    <button onclick="window.print()">🖨️ طباعة</button>
</div>

{{-- العنوان --}}
<h2 class="text-center mb-20">عقد اتفاق</h2>

{{-- بيانات الشركة --}}
<table class="no-border mb-20">
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
<table class="no-border mb-20">
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
<table class="mb-20">
    <thead>
        <tr>
            <th>#</th>
            <th>البند</th>
            <th>التفاصيل</th>
        </tr>
    </thead>
    <tbody>
        @foreach($contract->details as $detail)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $detail->title }}</td>
                <td>{{ $detail->value }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

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
