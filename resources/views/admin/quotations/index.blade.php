@extends('layouts.master')

@section('content')
<div class="container-fluid my-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-primary">عروض الأسعار</h1>
            <p class="text-muted mb-0">إدارة عروض الأسعار للعملاء</p>
        </div>

        {{-- زرار إضافة عرض سعر --}}
        <div>
            @can('create_quotation')
                <a href="{{ route('admin.quotations.create') }}" class="btn btn-success">
                    <i class="fas fa-plus me-2"></i>
                    إضافة عرض سعر
                </a>
            @endcan
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Quotations Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header">
            <h6 class="mb-0">قائمة عروض الأسعار</h6>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>رقم عرض السعر</th>
                            <th>العميل</th>
                            <th>تاريخ الإنشاء</th>
                            <th>تاريخ الانتهاء</th>
                            <th>الإجمالي</th>
                            <th>الحالة</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($quotations as $q)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    <strong>{{ $q->quotation_number }}</strong>
                                </td>

                                <td>
                                    {{ $q->customer->name ?? '-' }}
                                </td>

                                <td>{{ $q->issue_date }}</td>
                                <td>{{ $q->expiry_date }}</td>

                                <td>{{ number_format($q->total, 2) }} ج.م</td>

                                <td>
                                    @if($q->status === 'pending')
                                        <span class="badge bg-warning">معلق</span>
                                    @elseif($q->status === 'converted')
                                        <span class="badge bg-success">متحول</span>
                                    @else
                                        <span class="badge bg-danger">مرفوض</span>
                                    @endif
                                </td>

                                <td>
                                    @can('show_quotation_details')
                                        <a href="{{ route('admin.quotations.show', $q->id) }}"
                                           class="btn btn-info btn-sm">
                                            عرض
                                        </a>
                                    @endcan

                                    @can('edit_quotation')
                                        @if($q->status === 'pending')
                                            <a href="{{ route('admin.quotations.edit', $q->id) }}"
                                               class="btn btn-warning btn-sm">
                                                تعديل
                                            </a>
                                        @endif
                                    @endcan

                                    @can('delete_quotation')
                                        @if($q->status === 'pending')
                                            <form action="{{ route('admin.quotations.destroy', $q->id) }}"
                                                  method="POST"
                                                  class="d-inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm"
                                                        onclick="return confirm('هل أنت متأكد من حذف عرض السعر؟')">
                                                    حذف
                                                </button>
                                            </form>
                                        @endif
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">
                                    <i class="fas fa-file-invoice fa-2x mb-2"></i><br>
                                    لا توجد عروض أسعار
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination لو هتستخدم paginate --}}
        @if(method_exists($quotations, 'links'))
            <div class="card-footer bg-white border-0">
                {{ $quotations->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
