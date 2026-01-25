@extends('layouts.master')
@section('title', 'إضافة صنف جديد')

@section('content')
<div class="form-card stat-card p-4">
    <h4 class="mb-4 text-primary fw-bold">إضافة صنف جديد</h4>

    {{-- رسائل الخطأ --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>فيه أخطاء:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- معلومات أساسية --}}
        <h5 class="section-title mb-3 text-secondary fw-bold">المعلومات الأساسية</h5>

        <div class="row g-3">

            {{-- اسم الصنف --}}
            <div class="col-md-6">
                <label class="form-label fw-semibold">اسم المطبخ</label>
                <input type="text"
                       name="name"
                       class="form-control rounded-3 shadow-sm"
                       value="{{ old('name') }}">
            </div>

            {{-- الباركود --}}
            <div class="col-md-6">
                <label class="form-label fw-semibold">باركود / كود الصنف</label>
                <input type="text"
                    name="sku"
                    class="form-control rounded-3 shadow-sm"
                    value="{{ old('sku') }}"
                    placeholder="مثال: KIT-001"
                    required>
                <small class="text-muted">
                    يُسمح بحروف وأرقام وشرطة (-)
                </small>
            </div>

            {{-- النوع --}}
            <div class="col-md-6">
                <label class="form-label fw-semibold">نوع المطبخ</label>
                <select name="type" class="form-select rounded-3 shadow-sm" required>
                    <option value="">-- اختر النوع --</option>
                    <option value="public_sector" {{ old('type')=='public_sector'?'selected':'' }}>قطاع عام</option>
                    <option value="aluminum_plastic_angles_sheet_door" {{ old('type')=='aluminum_plastic_angles_sheet_door'?'selected':'' }}>
                        المونيوم قطاع خاص زوايا بلاستيك باب صاج
                    </option>
                    <option value="aluminum_iron_angles_sheet_door" {{ old('type')=='aluminum_iron_angles_sheet_door'?'selected':'' }}>
                        المونيوم قطاع خاص زوايا حديد باب صاج
                    </option>
                    <option value="aluminum_iron_angles_wood_door" {{ old('type')=='aluminum_iron_angles_wood_door'?'selected':'' }}>
                        المونيوم قطاع خاص زوايا حديد باب خشب
                    </option>
                    <option value="full_wood" {{ old('type')=='full_wood'?'selected':'' }}>خشب كامل</option>
                </select>
            </div>

            {{-- الألوان --}}
            <div class="col-md-6">
                <label class="form-label fw-semibold">اللون الأساسي</label>
                <input type="text"
                    name="color_primary"
                    class="form-control rounded-3 shadow-sm"
                    value="{{ old('color_primary') }}"
                    placeholder="مثال: أبيض">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">اللون الثانوي</label>
                <input type="text"
                    name="color_secondary"
                    class="form-control rounded-3 shadow-sm"
                    value="{{ old('color_secondary') }}"
                    placeholder="مثال: بني">
            </div>

            {{-- الضريبة --}}
            <h5 class="section-title mb-3 text-secondary fw-bold">الضريبة</h5>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold d-block">حالة الضريبة</label>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input"
                            type="radio"
                            name="is_taxable"
                            value="1"
                            {{ old('is_taxable','1')=='1' ? 'checked' : '' }}>
                        <label class="form-check-label">خاضع للضريبة</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input"
                            type="radio"
                            name="is_taxable"
                            value="0"
                            {{ old('is_taxable')=='0' ? 'checked' : '' }}>
                        <label class="form-check-label">غير خاضع للضريبة</label>
                    </div>
                </div>
            </div>

            {{-- الموديل --}}
            <div class="col-md-6">
                <label class="form-label fw-semibold">الموديل</label>
                <input type="text"
                       name="model"
                       class="form-control rounded-3 shadow-sm"
                       value="{{ old('model') }}">
            </div>

        </div>

        <hr class="my-4">

        {{-- الأسعار --}}
        <h5 class="section-title mb-3 text-secondary fw-bold">الأسعار</h5>

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold">سعر الشراء</label>
                <input type="number" step="0.01"
                       name="purchase_price"
                       class="form-control rounded-3 shadow-sm"
                       value="{{ old('purchase_price',0) }}">
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">سعر البيع</label>
                <input type="number" step="0.01"
                       name="selling_price"
                       class="form-control rounded-3 shadow-sm"
                       value="{{ old('selling_price',0) }}">
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">أقل سعر مسموح</label>
                <input type="number" step="0.01"
                       name="min_allowed_price"
                       class="form-control rounded-3 shadow-sm"
                       value="{{ old('min_allowed_price',0) }}">
            </div>
        </div>

        <hr class="my-4">

        {{-- المخزون --}}
        <h5 class="section-title mb-3 text-secondary fw-bold">المخزون</h5>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">المخزون</label>
                <input type="number"
                       name="stock"
                       class="form-control rounded-3 shadow-sm"
                       value="{{ old('stock',0) }}">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">حد إعادة الطلب</label>
                <input type="number"
                       name="reorder_level"
                       class="form-control rounded-3 shadow-sm"
                       value="{{ old('reorder_level',0) }}">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">هل هو خدمة؟</label>
                <select name="is_service" class="form-select rounded-3 shadow-sm">
                    <option value="0" {{ old('is_service')=='0'?'selected':'' }}>لا</option>
                    <option value="1" {{ old('is_service')=='1'?'selected':'' }}>نعم</option>
                </select>
            </div>
        </div>

        <hr class="my-4">

        {{-- الضمان --}}
        <h5 class="section-title mb-3 text-secondary fw-bold">الضمان</h5>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">نوع الضمان</label>
                <input type="text"
                       name="warranty_type"
                       class="form-control rounded-3 shadow-sm"
                       value="{{ old('warranty_type') }}">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">مدة الضمان (أيام)</label>
                <input type="number"
                       name="warranty_period_days"
                       class="form-control rounded-3 shadow-sm"
                       value="{{ old('warranty_period_days') }}">
            </div>
        </div>

        <hr class="my-4">

        {{-- حالة المنتج --}}
        <h5 class="section-title mb-3 text-secondary fw-bold">الحالة</h5>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">الحالة</label>
                <select name="condition" class="form-select rounded-3 shadow-sm">
                    <option value="new" {{ old('condition')=='new'?'selected':'' }}>جديد</option>
                    <option value="used" {{ old('condition')=='used'?'selected':'' }}>مستعمل</option>
                    <option value="imported" {{ old('condition')=='imported'?'selected':'' }}>مستورد</option>
                </select>
            </div>
        </div>

        <hr class="my-4">

        {{-- صور المنتج --}}
        <h5 class="section-title mb-3 text-secondary fw-bold">صور المنتج</h5>

        <div id="images-container">
            <div class="mb-3 image-input-group">
                <input type="file" name="images[]" accept="image/*" class="form-control rounded-3 shadow-sm">
                <button type="button" class="btn btn-danger btn-sm remove-image mt-2" style="display: none;">إزالة</button>
            </div>
        </div>

        <button type="button" id="add-image" class="btn btn-secondary btn-sm mb-3">إضافة صورة أخرى</button>

        {{-- ملاحظات --}}
        <h5 class="section-title mb-3 text-secondary fw-bold">ملاحظات</h5>

        <div class="mb-3">
            <textarea name="notes"
                      class="form-control rounded-3 shadow-sm"
                      rows="3">{{ old('notes') }}</textarea>
        </div>

        <div class="text-end mt-4">
            <button type="submit" class="btn btn-primary px-4 shadow-sm rounded-3 fw-bold">
                حفظ الصنف
            </button>
        </div>

    </form>
</div>

<script>
document.getElementById('add-image').addEventListener('click', function() {
    const container = document.getElementById('images-container');
    const newGroup = document.createElement('div');
    newGroup.className = 'mb-3 image-input-group';
    newGroup.innerHTML = `
        <input type="file" name="images[]" accept="image/*" class="form-control rounded-3 shadow-sm">
        <button type="button" class="btn btn-danger btn-sm remove-image mt-2">إزالة</button>
    `;
    container.appendChild(newGroup);
    updateRemoveButtons();
});

function updateRemoveButtons() {
    const groups = document.querySelectorAll('.image-input-group');
    groups.forEach((group, index) => {
        const removeBtn = group.querySelector('.remove-image');
        if (groups.length > 1) {
            removeBtn.style.display = 'inline-block';
        } else {
            removeBtn.style.display = 'none';
        }
    });
}

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-image')) {
        e.target.closest('.image-input-group').remove();
        updateRemoveButtons();
    }
});

// Initial update
updateRemoveButtons();
</script>
@endsection
