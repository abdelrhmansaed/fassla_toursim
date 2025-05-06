@extends('Dashboard.layouts.master')
<style>
    :root {
        --primary-color: #4361ee;
        --secondary-color: #3f37c9;
        --primary: #5e72e4;
        --primary-light: rgba(94, 114, 228, 0.1);
        --secondary: #825ee4;
        --success: #2dce89;
        --success-light: rgba(45, 206, 137, 0.1);
        --info: #11cdef;
        --info-light: rgba(17, 205, 239, 0.1);
        --warning: #fb6340;
        --danger: #f5365c;
        --danger-light: rgba(245, 54, 92, 0.1);
        --light: #f8f9fa;
        --dark: #212529;
        --gray: #8898aa;
        --gray-light: #f6f9fc;
        --border: #e9ecef;
    }

    body {
        font-family: 'Cairo', sans-serif;
        background-color: #f5f7fb;
        color: #4a5568;
    }

    /* Card Styles */
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
        background-color: white;
    }

    .card:hover {
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08);
    }

    .card-header {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border-bottom: none;
        padding: 1.25rem 1.5rem;
        border-radius: 12px 12px 0 0 !important;
        padding-bottom: 1.5rem;
    }

    .card-title {
        font-weight: 600;
        font-size: 1.125rem;
        margin-bottom: 0;
    }

    /* Table Styles */
    .table-responsive {
        padding: 0 1.5rem;
    }

    .table {
        width: 100%;
        margin-bottom: 0;
        color: #525f7f;
    }

    .table thead th {
        background-color: var(--gray-light);
        color: var(--gray);
        border-bottom: 1px solid var(--border);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05rem;
        padding: 1rem;
        text-align: center;
        margin-top: 1rem;
    }
    .table-responsive {
        padding-top: 1rem;
    }
    .table tbody tr {
        border-bottom: 1px solid var(--border);
        transition: all 0.15s ease;
    }

    .table tbody tr:hover {
        background-color: var(--gray-light);
    }

    .table td {
        padding: 1rem;
        vertical-align: middle;
        font-size: 0.875rem;
    }

    /* Badge Styles */
    .badge {
        font-weight: 600;
        padding: 0.5rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        line-height: 1;
    }

    .badge-primary {
        background-color: var(--primary-light);
        color: var(--primary);
    }

    .badge-success {
        background-color: var(--success-light);
        color: var(--success);
    }

    .badge-info {
        background-color: var(--info-light);
        color: var(--info);
    }

    .badge-danger {
        background-color: var(--danger-light);
        color: var(--danger);
    }

    /* Button Styles */
    .btn {
        border-radius: 50px;
        font-weight: 600;
        padding: 0.625rem 1.25rem;
        font-size: 0.875rem;
        transition: all 0.15s ease;
        box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
        border: none;
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.75rem;
    }

    .btn-primary {
        background-color: var(--primary);
    }

    .btn-primary:hover {
        background-color: #4a5fd1;
        transform: translateY(-1px);
        box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
    }

    .btn-info {
        background-color: var(--info);
    }

    .btn-info:hover {
        background-color: #0da5c0;
        transform: translateY(-1px);
    }

    /* Page Header */
    .page-header {
        background-color: white;
        border-radius: 0.75rem;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.03);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .page-header i {
        background-color: rgba(67, 97, 238, 0.1);
        padding: 0.75rem;
        border-radius: 10px;
        color: var(--primary-color);
    }
    .page-header h4 {
        color: var(--primary-color);
        font-weight: 700;
    }

    .page-header i {
        background-color: var(--primary-light);
        padding: 0.875rem;
        border-radius: 50%;
        color: var(--primary);
        margin-left: 1rem;
        font-size: 1.25rem;
    }

    /* Text Colors */
    .text-success {
        color: var(--success) !important;
        font-weight: 600;
    }

    .text-danger {
        color: var(--danger) !important;
    }

    /* Pagination */
    .pagination .page-item.active .page-link {
        background-color: var(--primary);
        border-color: var(--primary);
        box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
    }

    .pagination .page-link {
        color: var(--primary);
        border-radius: 50px !important;
        margin: 0 0.25rem;
        border: 1px solid var(--border);
        font-weight: 600;
        padding: 0.5rem 1rem;
    }

    /* Modal Styles */
    .modal-content {
        border-radius: 0.75rem;
        overflow: hidden;
        border: none;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        background: linear-gradient(87deg, var(--primary) 0, var(--secondary) 100%) !important;
        color: white;
    }

    /* Form Elements */
    .form-control {
        border-radius: 50px;
        padding: 0.5rem 1rem;
        border: 1px solid var(--border);
    }

    .input-group .form-control {
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
    }

    .input-group-append .btn {
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
    }

    /* DataTables Customization */
    .dataTables_wrapper .dataTables_filter input {
        border-radius: 50px;
        padding: 0.5rem 1rem;
        border: 1px solid var(--border);
    }

    .dataTables_wrapper .dataTables_length select {
        border-radius: 50px;
        padding: 0.5rem 1rem;
        border: 1px solid var(--border);
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .table-responsive {
            padding: 0 0.5rem;
        }

        .table td, .table th {
            padding: 0.75rem 0.5rem;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .page-header .btn {
            margin-top: 1rem;
        }
        @media (max-width: 768px) {
            .table-responsive {
                padding: 0 0.5rem;
            }

            .table td, .table th {
                padding: 0.75rem 0.5rem;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .page-header .btn {
                margin-top: 1rem;
            }
        }
    }
</style>

@section('page-header')
    <div class="page-header">
        <h4 class="mb-0">
            <i class="fas fa-times-circle"></i>
            الرحلات المرفوضة
        </h4>
        <button class="btn btn-primary">
            <i class="fas fa-file-export ml-2"></i> تصدير البيانات
        </button>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title text-white">قائمة الرحلات المرفوضة</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" placeholder="بحث...">
                            <div class="input-group-append">
                                <button class="btn btn-light" type="button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="datatable" class="table table-hover" style="width:100%">
                        <thead>
                        <tr>
                            <th class="text-center">التاريخ</th>
                            <th class="text-center">رقم الملف</th>
                            <th class="text-center">رقم الطلب</th>
                            <th class="text-center">نوع الرحلة</th>
                            <th class="text-center">اسم الفندق</th>
                            <th class="text-center">المندوب</th>
                            <th class="text-center">المزود</th>
                            <th class="text-center">عدد الأشخاص</th>
                            <th class="text-center">إجمالي السعر</th>
                            <th class="text-center">سبب الرفض</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($trips as $trip)
                            <tr>
                                <td class="text-center">
                                    <div class="d-flex flex-column">
                                        <span class="font-weight-bold">{{ \Carbon\Carbon::parse($trip->tripRequest->booking_datetime)->translatedFormat('d F Y') }}</span>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($trip->tripRequest->booking_datetime)->translatedFormat('l') }}</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-primary">{{ optional($trip->tripRequest)->booking_number ?? '--' }}</span>
                                </td>
                                <td class="text-center">{{ optional($trip->tripRequest)->receipt_number ?? '--' }}</td>
                                <td class="text-center">
                                    <div class="d-flex flex-column">
                                        <span class="font-weight-bold">{{ optional($trip->tripType)->type }}</span>
                                        @if($trip->subTripType)
                                            <small class="text-muted">{{ optional($trip->subTripType)->type }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">{{ optional($trip->tripRequest)->hotel_name ?? '--' }}</td>
                                <td class="text-center">
                                    <span class="badge badge-info">{{ optional($trip->tripRequest->agent)->name ?? '--' }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-success">{{ optional($trip->provider)->name ?? '--' }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-primary">{{ $trip->total_people }}</span>
                                </td>
                                <td class="text-center font-weight-bold text-success">{{ number_format($trip->total_price, 2) }} ج.م</td>
                                <td class="text-center">
                                    @if(optional($trip)->rejection_reason)
                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#rejectionModal-{{ $trip->id }}">
                                            <i class="fas fa-eye ml-1"></i> عرض السبب
                                        </button>

                                        <!-- Modal for Rejection Reason -->
                                        <div class="modal fade" id="rejectionModal-{{ $trip->id }}" tabindex="-1" aria-labelledby="rejectionModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="rejectionModalLabel">
                                                            <i class="fas fa-info-circle ml-2"></i> سبب الرفض
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="alert alert-warning">
                                                            <h5 class="alert-heading">تفاصيل الرفض:</h5>
                                                            <hr>
                                                            <p class="mb-0">{{ $trip->rejection_reason }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                            <i class="fas fa-times ml-1"></i> إغلاق
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="badge badge-danger">
                                            <i class="fas fa-times-circle ml-1"></i> غير متوفر
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                @if($trips instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                عرض <span class="font-weight-bold">{{ $trips->firstItem() }}</span> إلى <span class="font-weight-bold">{{ $trips->lastItem() }}</span> من <span class="font-weight-bold">{{ $trips->total() }}</span> رحلات
                            </div>
                            <div>
                                {{ $trips->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/Arabic.json"
                },
                dom: '<"top"f>rt<"bottom"lip><"clear">',
                responsive: true,
                pageLength: 50,
                order: [[0, 'desc']],
                paging: false,
                info: false,
                initComplete: function() {
                    $('.dataTables_filter input').attr('placeholder', 'ابحث هنا...');
                }
            });
        });
    </script>
@endpush
