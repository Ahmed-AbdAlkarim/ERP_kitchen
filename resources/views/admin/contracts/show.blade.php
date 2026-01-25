@extends('layouts.master')

@section('content')
<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>عقد اتفاق رقم #{{ $contract->id }}</h4>

    {{-- زرار الطباعة --}}
    <a href="{{ route('admin.contracts.print', $contract->id) }}"
       target="_blank"
       class="btn btn-primary">
        🖨️ طباعة العقد
    </a>
</div>

<div class="card mb-4">
<div class="card-body">
<p><strong>العميل:</strong> {{ $contract->customer->name }}</p>
<p>
    <strong>عرض السعر:</strong>

    @if($contract->quotation)
        {{ $contract->quotation->quotation_number
            ?? 'QT-' . $contract->quotation->created_at->format('Y')
            . '-' . str_pad($contract->quotation->id, 4, '0', STR_PAD_LEFT)
        }}
    @else
        بدون عرض سعر
    @endif
</p>

<p><strong>تاريخ التسليم:</strong> {{ $contract->delivery_date }}</p>
</div>
</div>

<div class="card mb-4">
<div class="card-header">تفاصيل العقد</div>
<div class="card-body">

<table class="table table-bordered">
@foreach($contract->details->chunk(2) as $chunk)
<tr>
    @foreach($chunk as $detail)
        <th width="20%">{{ $detail->title }}</th>
        <td width="30%">{{ $detail->value }}</td>
    @endforeach

    {{-- لو العدد فردي نكمّل الصف --}}
    @if($chunk->count() < 2)
        <th></th>
        <td></td>
    @endif
</tr>
@endforeach
</table>

</div>
</div>


<div class="card">
<div class="card-header">الشروط والأحكام</div>
<div class="card-body">
<ol>
@foreach($terms as $term)
<li>{{ $term->term }}</li>
@endforeach
</ol>
</div>
</div>

</div>
@endsection
