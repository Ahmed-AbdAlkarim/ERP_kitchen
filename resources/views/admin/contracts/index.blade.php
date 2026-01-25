@extends('layouts.master')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between mb-4">
        <h4>عقود الاتفاق</h4>
        @can('create_contract')
        <a href="{{ route('admin.contracts.create') }}" class="btn btn-primary">
            إضافة عقد
        </a>
        @endcan
    </div>

    <div class="card">
        <div class="card-body">

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>العميل</th>
                        <th>عرض السعر</th>
                        <th>تاريخ التسليم</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contracts as $contract)
                    <tr>
                        <td>{{ $contract->id }}</td>
                        <td>{{ $contract->customer->name }}</td>
                        <td>
                            @if($contract->quotation)
                                {{ $contract->quotation->quotation_number
                                    ?? 'QT-' . $contract->quotation->created_at->format('Y')
                                    . '-' . str_pad($contract->quotation->id, 4, '0', STR_PAD_LEFT)
                                }}
                            @else
                                <span class="badge bg-secondary">بدون عرض سعر</span>
                            @endif

                        </td>
                        <td>{{ $contract->delivery_date }}</td>
                        <td>
                            <a href="{{ route('admin.contracts.show',$contract) }}"
                               class="btn btn-sm btn-info">
                                عرض
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            لا توجد عقود
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

</div>
@endsection
