@extends('layouts.master')

@section('content')
<div class="container-fluid">

<h4 class="mb-4">إنشاء عقد اتفاق</h4>

<form action="{{ route('admin.contracts.store') }}" method="POST">
@csrf

{{-- بيانات أساسية --}}
<div class="card mb-4">
<div class="card-body">
<div class="row">

<div class="col-md-4 mb-3">
<label class="form-label">العميل</label>
<select name="customer_id"
        id="customerSelect"
        class="form-select"
        required>
    <option value="">اختر العميل</option>
    @foreach($customers as $customer)
        <option value="{{ $customer->id }}">
            {{ $customer->name }}
        </option>
    @endforeach
</select>
</div>

<div class="col-md-4 mb-3">
<label class="form-label">عرض السعر</label>
<select name="quotation_id"
        id="quotationSelect"
        class="form-select"
        required>
    <option value="">اختر العميل أولًا</option>
</select>
</div>

<div class="col-md-4 mb-3">
<label class="form-label">تاريخ التسليم</label>
<input type="date"
       name="delivery_date"
       class="form-control"
       required>
</div>

</div>
</div>
</div>

{{-- تفاصيل العقد --}}
<div class="card mb-4">
<div class="card-header"><strong>تفاصيل العقد</strong></div>
<div class="card-body" id="detailsWrapper">

@foreach($defaultDetails as $i => $title)
<div class="row mb-2">
    <div class="col-md-4">
        <input type="text"
               class="form-control"
               name="details[{{ $i }}][title]"
               value="{{ $title }}"
               readonly>
    </div>
    <div class="col-md-8">
        <input type="text"
               class="form-control"
               name="details[{{ $i }}][value]"
               placeholder="اكتب التفاصيل">
    </div>
</div>
@endforeach

</div>

<div class="card-footer">
<button type="button"
        id="addDetail"
        class="btn btn-sm btn-success">
    + إضافة تفصيلة
</button>
</div>
</div>

<div class="text-end">
<button type="submit" class="btn btn-primary">
    حفظ العقد
</button>
</div>

</form>
</div>

{{-- JS --}}
<script>
let index = {{ count($defaultDetails) }};

// تحميل عروض الأسعار حسب العميل
document.getElementById('customerSelect').addEventListener('change', function () {

    let customerId = this.value;
    let quotationSelect = document.getElementById('quotationSelect');

    quotationSelect.innerHTML = `<option>جاري التحميل...</option>`;

    if (!customerId) {
        quotationSelect.innerHTML = `<option>اختر العميل أولًا</option>`;
        return;
    }

    fetch(`/admin/customers/${customerId}/quotations`)
        .then(res => res.json())
        .then(data => {

            quotationSelect.innerHTML =
                `<option value="">اختر عرض السعر</option>`;

            if (data.length === 0) {
                quotationSelect.innerHTML =
                    `<option value="">لا توجد عروض أسعار</option>`;
                return;
            }

            data.forEach(q => {
                quotationSelect.innerHTML +=
                    `<option value="${q.id}">${q.code}</option>`;
            });
        });
});

// إضافة تفصيلة
document.getElementById('addDetail').onclick = function () {
    let html = `
    <div class="row mb-2 align-items-center contract-detail-row">
        <div class="col-md-4">
            <input type="text"
                   class="form-control"
                   name="details[${index}][title]"
                   placeholder="اسم التفصيلة">
        </div>
        <div class="col-md-7">
            <input type="text"
                   class="form-control"
                   name="details[${index}][value]"
                   placeholder="تفاصيل التفصيلة">
        </div>
        <div class="col-md-1 text-center">
            <button type="button"
                    class="btn btn-sm btn-danger remove-detail">
                ✖
            </button>
        </div>
    </div>`;
    document.getElementById('detailsWrapper')
        .insertAdjacentHTML('beforeend', html);
    index++;
};

// حذف تفصيلة
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-detail')) {
        e.target.closest('.contract-detail-row').remove();
    }
});
</script>
@endsection
