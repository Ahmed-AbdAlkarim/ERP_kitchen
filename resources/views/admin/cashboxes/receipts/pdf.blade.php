<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #000;
        }

        .receipt {
            border: 2px solid #000;
            padding: 15px;
            width: 100%;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            font-size: 18px;
        }

        .header span {
            font-size: 12px;
        }

        hr {
            border: none;
            border-top: 1px dashed #000;
            margin: 10px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 6px 0;
            font-size: 13px;
        }

        .label {
            width: 35%;
            font-weight: bold;
        }

        .value {
            width: 65%;
            text-align: left;
        }

        .amount {
            font-size: 16px;
            font-weight: bold;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 8px 0;
        }

        .footer {
            margin-top: 25px;
            font-size: 12px;
        }

        .signature {
            margin-top: 35px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="receipt">

    <!-- Header -->
    <div class="header">
        <h2>وصل استلام نقدية</h2>
        <span>Cash Receipt</span>
    </div>

    <hr>

    <!-- Receipt Data -->
    <table>
        <tr>
            <td class="label">رقم الوصل</td>
            <td class="value">#{{ $transaction->id }}</td>
        </tr>

        <tr>
            <td class="label">اسم العميل</td>
            <td class="value">{{ $customer?->name ?? '—' }}</td>
        </tr>

        <tr>
            <td class="label">رقم الهاتف</td>
            <td class="value">{{ $customer?->phone ?? '—' }}</td>
        </tr>

        <tr>
            <td class="label">التاريخ</td>
            <td class="value">{{ $transaction->formatted_date }}</td>
        </tr>

        <tr>
            <td colspan="2" class="amount">
                المبلغ المستلم:
                {{ number_format($transaction->amount, 2) }} ر.س
            </td>
        </tr>

        <tr>
            <td class="label">المستخدم</td>
            <td class="value">{{ $transaction->user->name ?? '—' }}</td>
        </tr>
    </table>

    <!-- Footer -->
    <div class="footer">
        تم استلام المبلغ الموضح أعلاه من العميل المذكور، وهذا إقرار بذلك.
    </div>

    <div class="signature">
        توقيع المستلم
        <br><br>
        ________________________
    </div>

</div>

</body>
</html>
