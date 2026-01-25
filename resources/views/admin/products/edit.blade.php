@extends('layouts.master')
@section('title', 'تعديل الصنف')

@section('content')
<div class="card shadow-sm p-4">
    <h4 class="mb-4 text-primary fw-bold">تعديل الصنف</h4>

    {{-- رسائل الأخطاء --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">

            {{-- اسم الصنف --}}
            <div class="col-md-6 mb-3">
                <label class="form-label">اسم المطبخ</label>
                <input type="text"
                       name="name"
                       class="form-control"
                       value="{{ old('name', $product->name) }}">
            </div>

            {{-- الباركود --}}
            <div class="col-md-6 mb-3">
                <label class="form-label">الباركود / SKU</label>
                <input type="text"
                       name="sku"
                       class="form-control"
                       value="{{ old('sku', $product->sku) }}">
            </div>

            {{-- نوع المطبخ --}}
            <div class="col-md-6 mb-3">
                <label class="form-label">نوع المطبخ</label>
                <select name="type" class="form-select">
                    <option value="">-- اختر النوع --</option>
                    <option value="public_sector"
                        {{ old('type', $product->type) == 'public_sector' ? 'selected' : '' }}>
                        قطاع عام
                    </option>
                    <option value="aluminum_plastic_angles_sheet_door"
                        {{ old('type', $product->type) == 'aluminum_plastic_angles_sheet_door' ? 'selected' : '' }}>
                        المونيوم قطاع خاص زوايا بلاستيك باب صاج
                    </option>
                    <option value="aluminum_iron_angles_sheet_door"
                        {{ old('type', $product->type) == 'aluminum_iron_angles_sheet_door' ? 'selected' : '' }}>
                        المونيوم قطاع خاص زوايا حديد باب صاج
                    </option>
                    <option value="aluminum_iron_angles_wood_door"
                        {{ old('type', $product->type) == 'aluminum_iron_angles_wood_door' ? 'selected' : '' }}>
                        المونيوم قطاع خاص زوايا حديد باب خشب
                    </option>
                    <option value="full_wood"
                        {{ old('type', $product->type) == 'full_wood' ? 'selected' : '' }}>
                        خشب كامل
                    </option>
                </select>
            </div>

            {{-- الألوان --}}
            <div class="col-md-6 mb-3">
                <label class="form-label">اللون الأساسي</label>
                <input type="text"
                       name="color_primary"
                       class="form-control"
                       value="{{ old('color_primary', $product->color_primary) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">اللون الثانوي</label>
                <input type="text"
                       name="color_secondary"
                       class="form-control"
                       value="{{ old('color_secondary', $product->color_secondary) }}">
            </div>

            {{-- الضريبة --}}
            <div class="col-md-6 mb-3">
                <label class="form-label d-block">الضريبة</label>

                <div class="form-check form-check-inline">
                    <input class="form-check-input"
                           type="radio"
                           name="is_taxable"
                           value="1"
                           {{ old('is_taxable', $product->is_taxable) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label">خاضع للضريبة</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input"
                           type="radio"
                           name="is_taxable"
                           value="0"
                           {{ old('is_taxable', $product->is_taxable) == 0 ? 'checked' : '' }}>
                    <label class="form-check-label">غير خاضع للضريبة</label>
                </div>
            </div>

            {{-- الموديل --}}
            <div class="col-md-6 mb-3">
                <label class="form-label">الموديل</label>
                <input type="text"
                       name="model"
                       class="form-control"
                       value="{{ old('model', $product->model) }}">
            </div>

            {{-- الحالة --}}
            <div class="col-md-6 mb-3">
                <label class="form-label">الحالة</label>
                <select name="condition" class="form-select">
                    <option value="new" {{ old('condition', $product->condition) == 'new' ? 'selected' : '' }}>جديد</option>
                    <option value="used" {{ old('condition', $product->condition) == 'used' ? 'selected' : '' }}>مستعمل</option>
                    <option value="imported" {{ old('condition', $product->condition) == 'imported' ? 'selected' : '' }}>مستورد</option>
                </select>
            </div>
        </div>

        <hr>

        {{-- الأسعار --}}
        <h5 class="text-primary fw-bold mb-3">الأسعار</h5>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">سعر الشراء</label>
                <input type="number" step="0.01" name="purchase_price" class="form-control"
                       value="{{ old('purchase_price', $product->purchase_price) }}">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">سعر البيع</label>
                <input type="number" step="0.01" name="selling_price" class="form-control"
                       value="{{ old('selling_price', $product->selling_price) }}">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">أقل سعر مسموح</label>
                <input type="number" step="0.01" name="min_allowed_price" class="form-control"
                       value="{{ old('min_allowed_price', $product->min_allowed_price) }}">
            </div>
        </div>

        <hr>

        {{-- المخزون --}}
        <h5 class="text-primary fw-bold mb-3">المخزون</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">المخزون</label>
                <input type="number" name="stock" class="form-control"
                       value="{{ old('stock', $product->stock) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">حد إعادة الطلب</label>
                <input type="number" name="reorder_level" class="form-control"
                       value="{{ old('reorder_level', $product->reorder_level) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">هل هو خدمة؟</label>
                <select name="is_service" class="form-select">
                    <option value="0" {{ old('is_service', $product->is_service) == 0 ? 'selected' : '' }}>لا</option>
                    <option value="1" {{ old('is_service', $product->is_service) == 1 ? 'selected' : '' }}>نعم</option>
                </select>
            </div>
        </div>

        <hr>

        {{-- الضمان --}}
        <h5 class="text-primary fw-bold mb-3">الضمان</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">نوع الضمان</label>
                <input type="text" name="warranty_type" class="form-control"
                       value="{{ old('warranty_type', $product->warranty_type) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">مدة الضمان (أيام)</label>
                <input type="number" name="warranty_period_days" class="form-control"
                       value="{{ old('warranty_period_days', $product->warranty_period_days) }}">
            </div>
        </div>

        <hr>

        {{-- الصور --}}
        <h5 class="text-primary fw-bold mb-3">الصور</h5>

        @if($product->image && is_array($product->image))
        <div class="mb-3">
            <label class="form-label d-block">الصور الحالية</label>
            <div class="d-flex flex-wrap gap-2">
                @foreach($product->image as $img)
                    <img src="{{ asset('storage/' . $img) }}" width="120" class="rounded border shadow-sm">
                @endforeach
            </div>
        </div>
        @endif

        <div id="images-container">
            <div class="mb-3 image-input-group">
                <input type="file" name="images[]" accept="image/*" class="form-control">
                <button type="button" class="btn btn-danger btn-sm remove-image mt-2" style="display: none;">إزالة</button>
            </div>
        </div>

        <button type="button" id="add-image" class="btn btn-secondary btn-sm mb-3">إضافة صورة أخرى</button>

        <hr>

        {{-- ملاحظات --}}
        <div class="mb-3">
            <label class="form-label">ملاحظات</label>
            <textarea name="notes" class="form-control" rows="3">{{ old('notes', $product->notes) }}</textarea>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary px-4">تحديث</button>
        </div>
    </form>
</div>



<script>
document.getElementById('add-image').addEventListener('click', function() {
    const container = document.getElementById('images-container');
    const newGroup = document.createElement('div');
    newGroup.className = 'mb-3 image-input-group';
    newGroup.innerHTML = `
        <input type="file" name="images[]" accept="image/*" class="form-control">
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
