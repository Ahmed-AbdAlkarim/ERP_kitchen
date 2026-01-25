@extends('layouts.master')

@section('content')
<div class="container-fluid">

    <h4 class="mb-4">إدارة الشروط والأحكام</h4>

    {{-- رسائل --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- إضافة شرط --}}
    <div class="card mb-4">
        <div class="card-header">
            <strong>إضافة شرط جديد</strong>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.terms.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">نص الشرط</label>
                    <textarea name="term"
                              class="form-control"
                              rows="3"
                              required></textarea>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">الترتيب</label>
                        <input type="number"
                               name="sort_order"
                               class="form-control"
                               value="0">
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="active"
                                   checked>
                            <label class="form-check-label">
                                مفعل
                            </label>
                        </div>
                    </div>
                </div>

                <button class="btn btn-primary mt-3">
                    إضافة الشرط
                </button>
            </form>
        </div>
    </div>

    {{-- عرض الشروط --}}
    <div class="card">
        <div class="card-header">
            <strong>كل الشروط</strong>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">#</th>
                        <th>الشرط</th>
                        <th width="10%">الترتيب</th>
                        <th width="10%">الحالة</th>
                        <th width="20%">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($terms as $term)
                    <tr>
                        <td>{{ $term->id }}</td>
                        <td>{{ $term->term }}</td>
                        <td>{{ $term->sort_order }}</td>
                        <td>
                            @if($term->active)
                                <span class="badge bg-success">مفعل</span>
                            @else
                                <span class="badge bg-secondary">غير مفعل</span>
                            @endif
                        </td>
                        <td>
                            {{-- تعديل --}}
                            <button class="btn btn-sm btn-warning"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editTerm{{ $term->id }}">
                                تعديل
                            </button>

                            {{-- حذف --}}
                            <form action="{{ route('admin.terms.destroy', $term) }}"
                                  method="POST"
                                  class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('متأكد من الحذف؟')">
                                    حذف
                                </button>
                            </form>
                        </td>
                    </tr>

                    {{-- Modal تعديل --}}
                    <div class="modal fade" id="editTerm{{ $term->id }}">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">

                                <form method="POST"
                                      action="{{ route('admin.terms.update', $term) }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="modal-header">
                                        <h5 class="modal-title">تعديل الشرط</h5>
                                        <button type="button"
                                                class="btn-close"
                                                data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">نص الشرط</label>
                                            <textarea name="term"
                                                      class="form-control"
                                                      rows="3"
                                                      required>{{ $term->term }}</textarea>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="form-label">الترتيب</label>
                                                <input type="number"
                                                       name="sort_order"
                                                       class="form-control"
                                                       value="{{ $term->sort_order }}">
                                            </div>

                                            <div class="col-md-4 d-flex align-items-end">
                                                <div class="form-check">
                                                    <input class="form-check-input"
                                                           type="checkbox"
                                                           name="active"
                                                           {{ $term->active ? 'checked' : '' }}>
                                                    <label class="form-check-label">
                                                        مفعل
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-primary">
                                            حفظ التعديل
                                        </button>
                                        <button type="button"
                                                class="btn btn-secondary"
                                                data-bs-dismiss="modal">
                                            إلغاء
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>

                @empty
                    <tr>
                        <td colspan="5" class="text-center">
                            لا توجد شروط بعد
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
