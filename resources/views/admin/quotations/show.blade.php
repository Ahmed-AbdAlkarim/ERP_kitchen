@extends('layouts.master')

@section('content')
<div class="container-fluid my-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-primary mb-1">عرض سعر</h1>
            <p class="text-muted mb-0">
                رقم عرض السعر:
                <strong>{{ $quotation->quotation_number }}</strong>
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.quotations.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right me-1"></i> رجوع
            </a>

            @can('print_quotation')
                <a href="{{ route('admin.quotations.print', $quotation->id) }}"
                   target="_blank"
                   class="btn btn-info">
                    <i class="fas fa-print me-1"></i> طباعة
                </a>
            @endcan

            @can('edit_quotation')
                @if($quotation->status === 'pending')
                    <a href="{{ route('admin.quotations.edit', $quotation->id) }}"
                       class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i> تعديل
                    </a>
                @endif
            @endcan

            @can('convert_quotation')
                @if($quotation->status === 'pending')
                    <button class="btn btn-success"
                            data-bs-toggle="modal"
                            data-bs-target="#convertQuotationModal">
                        <i class="fas fa-file-invoice me-1"></i>
                       اصدار فاتورة
                    </button>
                @endif
            @endcan

            @can('show_sales_invoice_details')
                @if($quotation->status === 'converted' && $quotation->salesInvoice)
                    <a href="{{ route('admin.sales-invoices.show', $quotation->salesInvoice->id) }}"
                       class="btn btn-primary">
                        <i class="fas fa-eye me-1"></i> عرض الفاتورة
                    </a>
                @endif
            @endcan

        </div>
    </div>

    {{-- Info Cards --}}
    <div class="row">

        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header fw-bold">بيانات العميل</div>
                <div class="card-body">
                    <p><strong>الاسم:</strong> {{ $quotation->customer->name }}</p>
                    <p><strong>الهاتف:</strong> {{ $quotation->customer->phone ?? '-' }}</p>
                    <p><strong>العنوان:</strong> {{ $quotation->customer->address ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header fw-bold">بيانات عرض السعر</div>
                <div class="card-body">
                    <p><strong>تاريخ الإنشاء:</strong> {{ $quotation->issue_date }}</p>
                    <p><strong>تاريخ الانتهاء:</strong> {{ $quotation->expiry_date }}</p>
                    <p>
                        <strong>الحالة:</strong>
                        @if($quotation->status === 'pending')
                            <span class="badge bg-warning">معلق</span>
                        @elseif($quotation->status === 'converted')
                            <span class="badge bg-success">متحول لفاتورة</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header fw-bold">الإجماليات</div>
                <div class="card-body">
                    <p>قبل الضريبة: {{ number_format($quotation->subtotal, 2) }} ر.س</p>
                    <p>الضريبة: {{ number_format($quotation->tax, 2) }} ر.س</p>
                    <hr>
                    <h5 class="text-success">
                        الإجمالي: {{ number_format($quotation->total, 2) }} ر.س
                    </h5>
                </div>
            </div>
        </div>

    </div>

    {{-- Products --}}
    <div class="card shadow-sm">
        <div class="card-header fw-bold">المنتجات</div>
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
                            <td>{{ number_format($item->price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ================= Modal ================= --}}
<div class="modal fade" id="convertQuotationModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form id="convertForm"
              action="{{ route('admin.quotations.convert', $quotation->id) }}"
              method="POST"
              class="modal-content">
            @csrf

            <input type="hidden" id="quotation_total" value="{{ $quotation->total }}">

            <div class="modal-header">
                <h5 class="modal-title">
                    تحويل إلى فاتورة بيع
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="alert alert-info">
                    الإجمالي: <strong>{{ number_format($quotation->total, 2) }} ج.م</strong>
                </div>

                <div class="mb-3">
                    <label class="form-label">حالة الدفع</label>
                    <select id="payment_status"
                            name="payment_status"
                            class="form-select"
                            required>
                        <option value="installment">آجل</option>
                        <option value="partial">دفع جزئي</option>
                        <option value="paid">مدفوع كلي</option>
                    </select>
                </div>

                <div id="payment_fields" style="display:none">
                    <div class="mb-3">
                        <label class="form-label">الخزنة</label>
                        <select name="cashbox_id" class="form-select">
                            <option value="">اختر الخزنة</option>
                            @foreach($cashboxes as $cashbox)
                                <option value="{{ $cashbox->id }}">{{ $cashbox->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">المبلغ المدفوع</label>
                        <input type="number"
                               name="amount"
                               step="0.01"
                               class="form-control payment-amount">
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" id="convertBtn" class="btn btn-success">تحويل</button>
            </div>

        </form>
    </div>
</div>

{{-- JS --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const status = document.getElementById('payment_status');
    const fields = document.getElementById('payment_fields');
    const amount = document.querySelector('.payment-amount');
    const total  = parseFloat(document.getElementById('quotation_total').value);

    function toggle() {
        fields.style.display = (status.value === 'paid' || status.value === 'partial')
            ? 'block' : 'none';
        if (status.value === 'installment') amount.value = '';
    }

    toggle();
    status.addEventListener('change', toggle);

    document.getElementById('convertForm').addEventListener('submit', function (e) {
        if (status.value === 'paid' && parseFloat(amount.value || 0) < total) {
            alert('يرجى إدخال المبلغ كامل في حالة الدفع الكلي');
            e.preventDefault();
        }
    });

});
</script>
@endsection
