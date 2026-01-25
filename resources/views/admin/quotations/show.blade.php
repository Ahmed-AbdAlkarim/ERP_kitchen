@extends('layouts.master')

@section('content')
<div class="container-fluid my-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-primary">عرض سعر</h1>
            <p class="text-muted mb-0">
                رقم عرض السعر:
                <strong>{{ $quotation->quotation_number }}</strong>
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.quotations.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right me-1"></i>
                رجوع
            </a>

            {{-- زرار الطباعة --}}
            @can('print_quotation')
                <a href="{{ route('admin.quotations.print', $quotation->id) }}"
                target="_blank"
                class="btn btn-info">
                    <i class="fas fa-print me-1"></i>
                    طباعة
                </a>
            @endcan

            {{-- تعديل --}}
            @can('edit_quotation')
                @if($quotation->status === 'pending')
                    <a href="{{ route('admin.quotations.edit', $quotation->id) }}"
                    class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i>
                        تعديل
                    </a>
                @endif
            @endcan

        </div>

    </div>

    <div class="row">

        {{-- بيانات العميل --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header">
                    <h6 class="mb-0">بيانات العميل</h6>
                </div>
                <div class="card-body">
                    <p><strong>الاسم:</strong> {{ $quotation->customer->name }}</p>
                    <p><strong>رقم التليفون:</strong> {{ $quotation->customer->phone ?? '-' }}</p>
                    <p><strong>العنوان:</strong> {{ $quotation->customer->address ?? '-' }}</p>
                </div>
            </div>
        </div>

        {{-- بيانات عرض السعر --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header">
                    <h6 class="mb-0">بيانات عرض السعر</h6>
                </div>
                <div class="card-body">
                    <p><strong>تاريخ الإنشاء:</strong> {{ $quotation->issue_date }}</p>
                    <p><strong>تاريخ الانتهاء:</strong> {{ $quotation->expiry_date }}</p>
                    <p>
                        <strong>الحالة:</strong>
                        @if($quotation->status === 'pending')
                            <span class="badge bg-warning">معلق</span>
                        @elseif($quotation->status === 'converted')
                            <span class="badge bg-success">متحول لفاتورة</span>
                        @else
                            <span class="badge bg-danger">مرفوض</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        {{-- الإجماليات --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header">
                    <h6 class="mb-0">الإجماليات</h6>
                </div>
                <div class="card-body">
                    <p><strong>الإجمالي قبل الضريبة:</strong>
                        {{ number_format($quotation->subtotal, 2) }} ج.م
                    </p>
                    <p><strong>الضريبة:</strong>
                        {{ number_format($quotation->tax, 2) }} ج.م
                    </p>
                    <hr>
                    <h5>
                        الإجمالي الكلي:
                        {{ number_format($quotation->total, 2) }} ج.م
                    </h5>
                </div>
            </div>
        </div>

    </div>

    {{-- جدول المنتجات --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header">
            <h6 class="mb-0">المنتجات</h6>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>المنتج</th>
                            <th>السعر</th>
                            <th>الكمية</th>
                            <th>الإجمالي</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($quotation->items as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ number_format($item->price, 2) }} ج.م</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->total, 2) }} ج.م</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
