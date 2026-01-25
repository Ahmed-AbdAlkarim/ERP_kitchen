@extends('layouts.master')

@section('content')
<div class="container-fluid my-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-primary">تعديل عرض سعر</h1>
            <p class="text-muted mb-0">
                رقم عرض السعر: {{ $quotation->quotation_number }}
            </p>
        </div>
    </div>

    {{-- Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.quotations.update', $quotation->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">

            {{-- بيانات عرض السعر --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">بيانات عرض السعر</h6>
                    </div>
                    <div class="card-body">

                        <div class="mb-3">
                            <label class="form-label">رقم عرض السعر</label>
                            <input type="text"
                                   class="form-control"
                                   value="{{ $quotation->quotation_number }}"
                                   disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">تاريخ الإنشاء</label>
                            <input type="date"
                                   class="form-control"
                                   value="{{ $quotation->issue_date }}"
                                   disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">تاريخ الانتهاء</label>
                            <input type="date"
                                   name="expiry_date"
                                   class="form-control"
                                   value="{{ $quotation->expiry_date }}"
                                   required>
                        </div>

                    </div>
                </div>
            </div>

            {{-- بيانات العميل --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">بيانات العميل</h6>
                    </div>
                    <div class="card-body">

                        <div class="mb-3">
                            <label class="form-label">العميل</label>
                            <select name="customer_id"
                                    id="customer"
                                    class="form-control"
                                    required>
                                <option value="">اختر العميل</option>
                                @foreach($customers as $customer)
                                    <option
                                        value="{{ $customer->id }}"
                                        data-phone="{{ $customer->phone }}"
                                        data-address="{{ $customer->address }}"
                                        {{ $quotation->customer_id == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">رقم التليفون</label>
                            <input type="text"
                                   id="customer_phone"
                                   class="form-control"
                                   value="{{ $quotation->customer->phone }}"
                                   readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">العنوان</label>
                            <textarea id="customer_address"
                                      class="form-control"
                                      rows="2"
                                      readonly>{{ $quotation->customer->address }}</textarea>
                        </div>

                    </div>
                </div>
            </div>

            {{-- المنتجات --}}
            <div class="col-md-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0">المنتجات</h6>
                        <button type="button"
                                class="btn btn-sm btn-success"
                                onclick="addRow()">
                            <i class="fas fa-plus"></i> إضافة منتج
                        </button>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>المنتج</th>
                                        <th>السعر</th>
                                        <th>الكمية</th>
                                        <th>الضريبة</th>
                                        <th>الإجمالي</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="items-table"></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer text-end">
                        <h5>
                            الإجمالي:
                            <span id="grand_total">0.00</span> ج.م
                        </h5>
                    </div>
                </div>
            </div>

        </div>

        <div class="mt-4 text-end">
            <button class="btn btn-primary">
                <i class="fas fa-save me-1"></i>
                حفظ التعديلات
            </button>
        </div>
    </form>
</div>

{{-- JS --}}
<script>
let products = @json($products);
let items = @json($quotation->items);
let index = 0;

/* تحميل المنتجات القديمة */
window.onload = function () {
    items.forEach(item => {
        addRow(item);
    });
};

/* إضافة صف */
function addRow(existingItem = null) {

    let rowIndex = index;

    let row = `
        <tr>
            <td>
                <select name="items[${rowIndex}][product_id]"
                        class="form-control"
                        data-index="${rowIndex}"
                        onchange="updateRow(this)"
                        required>
                    <option value="">اختر المنتج</option>
                    ${products.map(p =>
                        `<option value="${p.id}"
                                 data-price="${p.selling_price}"
                                 data-taxable="${p.is_taxable}"
                                 ${existingItem && existingItem.product_id == p.id ? 'selected' : ''}>
                            ${p.name}
                        </option>`
                    ).join('')}
                </select>
            </td>

            <td>
                <input type="number"
                       class="form-control"
                       id="price_${rowIndex}"
                       readonly>
            </td>

            <td>
                <input type="number"
                       name="items[${rowIndex}][quantity]"
                       class="form-control"
                       value="${existingItem ? existingItem.quantity : 1}"
                       min="1"
                       onchange="calculate(${rowIndex})">
            </td>

            <td>
                <input type="text"
                       class="form-control"
                       id="tax_${rowIndex}"
                       readonly>
            </td>

            <td>
                <input type="text"
                       class="form-control"
                       id="total_${rowIndex}"
                       readonly>
            </td>

            <td>
                <button type="button"
                        class="btn btn-danger btn-sm"
                        onclick="this.closest('tr').remove(); calculateGrandTotal();">
                    ×
                </button>
            </td>
        </tr>
    `;

    document.getElementById('items-table').insertAdjacentHTML('beforeend', row);

    if (existingItem) {
        document.getElementById(`price_${rowIndex}`).value = existingItem.price;
        calculate(rowIndex);
    }

    index++;
}

/* تحديث صف */
function updateRow(select) {
    let i = select.dataset.index;
    let option = select.options[select.selectedIndex];
    document.getElementById(`price_${i}`).value = option.dataset.price;
    calculate(i);
}

/* حساب */
function calculate(i) {
    let price = parseFloat(document.getElementById(`price_${i}`).value || 0);
    let qty   = parseInt(document.querySelector(`[name="items[${i}][quantity]"]`).value || 1);

    let select = document.querySelector(`select[data-index="${i}"]`);
    let taxableValue = select?.selectedOptions[0]?.dataset.taxable;
    let taxable = taxableValue === '1' || taxableValue === 'true';

    let subtotal = price * qty;
    let tax = taxable ? subtotal * 0.15 : 0;

    document.getElementById(`tax_${i}`).value = tax.toFixed(2);
    document.getElementById(`total_${i}`).value = (subtotal + tax).toFixed(2);

    calculateGrandTotal();
}

/* إجمالي */
function calculateGrandTotal() {
    let total = 0;
    document.querySelectorAll('[id^="total_"]').forEach(el => {
        total += parseFloat(el.value || 0);
    });
    document.getElementById('grand_total').innerText = total.toFixed(2);
}

/* بيانات العميل */
document.getElementById('customer').addEventListener('change', function () {
    let opt = this.options[this.selectedIndex];
    document.getElementById('customer_phone').value = opt.dataset.phone || '';
    document.getElementById('customer_address').value = opt.dataset.address || '';
});
</script>

@endsection
