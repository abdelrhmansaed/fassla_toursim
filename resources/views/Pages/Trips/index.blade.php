@extends('Dashboard.layouts.master')

<style>
    :root {
        --primary: #5e72e4;
        --primary-light: rgba(94, 114, 228, 0.1);
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --secondary: #11cdef;
        --success: #2dce89;
        --warning: #fb6340;
        --danger: #f5365c;
        --light: #f8f9fe;
        --dark: #1a1a2e;
        --text-main: #2d3748;
        --text-muted: #718096;
        --border-radius: 12px;
        --box-shadow: 0 15px 35px rgba(50, 50, 93, 0.1), 0 5px 15px rgba(0, 0, 0, 0.07);
        --transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    /* تصميم الهيدر المميز - يبقى كما هو */
    .page-header-modern {
        background: white;
        padding: 1.5rem 0;
        margin-bottom: 2rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.03);
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .page-title {
        display: flex;
        align-items: center;
        margin: 0;
        font-weight: 700;
        font-size: 1.5rem;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .page-title i {
        margin-left: 1rem;
        font-size: 1.8rem;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    /* تحسينات الجدول الجديدة */
    .luxury-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin: 0;
        background: white;
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: var(--box-shadow);
    }

    .luxury-table thead th {
        background: var(--primary-gradient);
        color: white;
        padding: 1rem 1.5rem;
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .luxury-table tbody tr {
        transition: var(--transition);
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .luxury-table tbody tr:last-child {
        border-bottom: none;
    }

    .luxury-table tbody tr:hover {
        background-color: rgba(94, 114, 228, 0.03);
    }

    .luxury-table td {
        padding: 1.25rem 1.5rem;
        vertical-align: middle;
        border: none;
        color: var(--text-main);
    }

    /* تحسينات الأزرار */
    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem;
        border-radius: 50%;
        transition: var(--transition);
        width: 36px;
        height: 36px;
    }

    .btn-edit {
        background: rgba(17, 205, 239, 0.1);
    }



    .btn-delete {
        background: rgba(245, 54, 92, 0.1);
        color: var(--danger);
    }



    .btn-request {
        background: var(--primary);
        border-radius: 50px;
        padding: 0.5rem 1.25rem;
        font-weight: 500;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        transition: var(--transition);
        box-shadow: 0 2px 10px rgba(94, 114, 228, 0.3);
    }



    .btn-request i {
        margin-left: 0.5rem;
    }

    /* تحسينات البادجات */
    .price-badge {
        padding: 0.5rem 0.75rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.8rem;
        display: inline-block;
    }

    .adult-price {
        background: rgba(45, 206, 137, 0.1);
        color: var(--success);
    }

    .child-price {
        background: rgba(94, 114, 228, 0.1);
        color: var(--primary);
    }

    /* تحسينات المودال */
    .modal {
        z-index: 99999 !important;
    }

    .modal-content {
        border: none;
        border-radius: var(--border-radius);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        background: var(--primary-gradient);
        color: white;
        border-bottom: none;
        border-radius: var(--border-radius) var(--border-radius) 0 0;
        padding: 1.25rem 1.5rem;
    }

    .modal-title {
        font-weight: 600;
    }

    .close {
        color: white;
        opacity: 0.8;
        font-size: 1.5rem;
    }

    .close:hover {
        opacity: 1;
    }

    /* تصميم متجاوب */
    @media (max-width: 992px) {
        .luxury-table thead {
            display: none;
        }

        .luxury-table tbody tr {
            display: block;
            padding: 1rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .luxury-table td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            text-align: right;
            padding: 0.75rem 0;
            border: none !important;
        }

        .luxury-table td::before {
            content: attr(data-label);
            font-weight: 600;
            color: var(--primary);
            margin-left: 1rem;
        }

        .action-buttons {
            justify-content: flex-end !important;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }
    }

    @media (max-width: 576px) {
        .btn-request {
            padding: 0.5rem 0.75rem;
            font-size: 0.75rem;
        }

        .btn-request i {
            margin-left: 0.3rem;
            font-size: 0.8rem;
        }
    }
</style>

@section('page-header')
    <div class="page-header-modern animate-fade-up">
        <div class="container-fluid">
            <div class="header-content">
                <h1 class="page-title">
                    <i class="fas fa-money-bill-wave"></i> إدارة الرحلات
                </h1>
                <div class="header-actions">
                    <a href="{{route('trips.create')}}" class="btn-luxury btn-add">
                        <i class="fas fa-plus-circle me-2"></i> إضافة رحلة جديدة
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card animate-fade-up" style="animation-delay: 0.1s; border: none; border-radius: var(--border-radius); box-shadow: var(--box-shadow);">
                    <div class="card-body p-0">
                        @if($trips->isEmpty())
                            <div class="text-center p-5">
                                <div class="empty-icon">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <h3 class="text-muted">لا توجد رحلات مسجلة</h3>
                                <p class="lead text-muted mb-4">يمكنك البدء بإضافة رحلات جديدة لتظهر هنا</p>
                                <a href="{{route('trips.create')}}" class="btn btn-success">
                                    <i class="fas fa-plus-circle me-2"></i> إضافة رحلة جديدة
                                </a>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="luxury-table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>نوع الرحلة</th>
                                        <th>سعر البالغ</th>
                                        <th>سعر الطفل</th>
                                        <th>مزود الخدمة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($trips as $trip)
                                        <tr>
                                            <td data-label="#">{{ $loop->iteration }}</td>
                                            <td data-label="نوع الرحلة">
                                                <span style="font-weight: 600; color: var(--primary);">{{$trip->type}}</span>
                                            </td>
                                            <td data-label="سعر البالغ">
                                                <span class="price-badge adult-price">
                                                    {{ number_format($trip->adult_price, 2) }} ج.م
                                                </span>
                                            </td>
                                            <td data-label="سعر الطفل">
                                                <span class="price-badge child-price">
                                                    {{ number_format($trip->child_price, 2) }} ج.م
                                                </span>
                                            </td>
                                            <td data-label="مزود الخدمة">
                                                <span style="font-weight: 500;">{{$trip->provider->name}}</span>
                                            </td>
                                            <td data-label="الإجراءات">
                                                <div class="d-flex align-items-center gap-2 action-buttons">
                                                    <a href="{{route('trips.edit', $trip->id)}}" class="btn-action btn-edit" title="تعديل">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn-action btn-delete" data-toggle="modal" data-target="#delete_trip{{ $trip->id }}" title="حذف">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                    <button type="button" class="btn-request request-trip-btn"
                                                            data-toggle="modal"
                                                            data-target="#request_trip{{ $trip->id }}"
                                                            data-trip-id="{{ $trip->id }}"
                                                            data-adult-price="{{ $trip->adult_price }}"
                                                            data-child-price="{{ $trip->child_price }}">
                                                        <i class="fas fa-paper-plane"></i> طلب
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @include('pages.Trips.delete')
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('pages.Trips.request-modal')
@endsection
