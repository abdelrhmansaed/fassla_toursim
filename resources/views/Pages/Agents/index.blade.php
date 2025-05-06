@extends('Dashboard.layouts.master')

<style>
    /* تحميل خط Cairo */
    @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap');

    /* أنماط أساسية */
    body {
        font-family: 'Cairo', sans-serif;
        background-color: #f8fafc;
        color: #1e293b;
        line-height: 1.6;
    }

    /* تحسينات للهيدر */
    .dashboard-header {
        background: linear-gradient(to right, #4f46e5, #6366f1);
        color: white;
        padding: 1.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        position: relative;
        overflow: hidden;
    }
    .search-box {
        width: 350px;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .search-box:hover {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .search-box .form-control {
        height: 38px;
        border-color: #e2e8f0;
        background-color: #f8fafc;
        transition: all 0.3s ease;
    }

    .search-box .form-control:focus {
        background-color: white;
        box-shadow: none;
        border-color: #c7d2fe;
    }

    .search-box .input-group-text {
        padding: 0 15px;
    }

    .search-box .btn {
        border-radius: 0 8px 8px 0;
        padding: 0.35rem 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    /* للهواتف المحمولة */
    @media (max-width: 576px) {
        .search-box {
            width: 100%;
        }

        .search-box .btn span {
            display: none;
        }

        .search-box .btn i {
            margin-left: 0 !important;
        }
    }
    .dashboard-header::before {
        content: "";
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        background: radial-gradient(circle at top right, rgba(255,255,255,0.2) 0%, transparent 50%);
    }

    .dashboard-header h2 {
        font-weight: 700;
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        position: relative;
    }

    .dashboard-header i {
        margin-left: 0.75rem;
        font-size: 1.75rem;
    }

    /* تحسينات لـ breadcrumb */
    .breadcrumb-header {
        background: white;
        border-radius: 10px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .icon-shape {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #eef2ff;
        color: #4f46e5;
        font-size: 1.25rem;
    }

    .content-title {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    /* تحسينات للبطاقة الرئيسية */
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .card-header {
        background-color: white;
        border-bottom: 1px solid #f1f5f9;
        padding: 1.25rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-header h5 {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0;
    }

    /* تحسينات لمربع البحث */
    .input-group {
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    .input-group-text {
        background-color: white;
        border: none;
        color: #64748b;
    }

    .form-control {
        border: none;
        padding: 0.5rem 0.75rem;
    }

    .form-control:focus {
        box-shadow: none;
    }

    /* تحسينات للجدول */
    .table {
        margin-bottom: 0;
    }

    .table thead th {
        background-color: #f8fafc;
        color: #4f46e5;
        font-weight: 600;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e2e8f0;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }

    .table tbody td {
        padding: 1rem 1.25rem;
        vertical-align: middle;
        border-color: #f1f5f9;
    }

    .table-hover tbody tr:hover {
        background-color: #f8fafc;
    }

    /* تحسينات للأزرار */
    .btn {
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: all 0.2s;
        border-width: 1px;
    }

    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }

    .btn-primary {
        background-color: #4f46e5;
        border-color: #4f46e5;
    }

    .btn-primary:hover {
        background-color: #4338ca;
        border-color: #4338ca;
    }

    .btn-success {
        background-color: #10b981;
        border-color: #10b981;
    }

    .btn-success:hover {
        background-color: #059669;
        border-color: #059669;
    }

    .btn-outline-primary {
        color: #4f46e5;
        border-color: #4f46e5;
    }

    .btn-outline-primary:hover {
        background-color: #4f46e5;
        color: white;
    }

    .btn-outline-info {
        color: #3b82f6;
        border-color: #3b82f6;
    }

    .btn-outline-info:hover {
        background-color: #3b82f6;
        color: white;
    }

    .btn-outline-danger {
        color: #ef4444;
        border-color: #ef4444;
    }

    .btn-outline-danger:hover {
        background-color: #ef4444;
        color: white;
    }

    /* تحسينات للأفاتار */
    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 1rem;
        font-size: 1rem;
        font-weight: 600;
        color: white;
        background: linear-gradient(to right, #4f46e5, #6366f1);
    }

    /* تحسينات للبادجات */
    .badge {
        padding: 0.35em 0.65em;
        font-weight: 500;
        border-radius: 6px;
        font-size: 0.75rem;
    }

    .bg-primary-light {
        background-color: #eef2ff;
        color: #4f46e5;
    }

    /* تأثيرات الحركة */
    .fade-in {
        animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* تحسينات للهواتف */
    @media (max-width: 768px) {
        .dashboard-header {
            padding: 1.25rem;
        }

        .card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .input-group {
            width: 100%;
        }

        .table thead {
            display: none;
        }

        .table, .table tbody, .table tr, .table td {
            display: block;
            width: 100%;
        }

        .table tr {
            margin-bottom: 1rem;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .table td {
            padding: 0.75rem;
            text-align: left;
            position: relative;
            padding-left: 50%;
        }

        .table td::before {
            content: attr(data-label);
            position: absolute;
            left: 1rem;
            top: 0.75rem;
            font-weight: 600;
            color: #4f46e5;
        }

        .table td .btn {
            margin: 0.25rem;
        }
    }
</style>

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <div class="icon-shape">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="ms-3">
                    <h4 class="content-title mb-0">{{ trans('dashboard/main_trans.agents') }}</h4>
                    <small class="text-muted">إدارة مندوبي النظام</small>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center">
            <a href="{{ route('agents.create') }}" class="btn btn-success">
                <i class="fas fa-plus-circle me-1"></i> إضافة مندوب
            </a>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    <!-- row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">قائمة المندوبين</h5>
                    <div class="d-flex align-items-center">
                        <div class="input-group search-box">

                            <input type="text" class="form-control border-start-0 ps-0"
                                   placeholder="ابحث باسم المندوب أو الكود..."
                                   id="searchInput">
                            <button class="btn btn-primary px-3" type="button" id="searchBtn">
                                <span>بحث</span>
                                <i class="fas fa-search ms-2 d-none d-sm-inline"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-hover align-middle" data-page-length="50">
                            <thead>
                            <tr>
                                <th width="50">#</th>
                                <th>الاسم</th>
                                <th>السن</th>
                                <th>البريد الإلكتروني</th>
                                <th>رقم الهوية</th>
                                <th>كود المندوب</th>
                                <th width="180" class="text-center">العمليات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($agents as $agent)
                                <tr class="fade-in">
                                    <td class="fw-semibold">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-3">
                                                {{ substr($agent->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $agent->name }}</h6>
                                                <small class="text-muted">{{ $agent->phone }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $agent->age ?? '-' }}</td>
                                    <td>{{ $agent->email }}</td>
                                    <td>{{ $agent->national_id ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-primary-light">{{ $agent->code }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('admin.agents.profile', $agent->id) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               data-bs-toggle="tooltip"
                                               title="عرض الملف الشخصي">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('agents.edit', $agent->id) }}"
                                               class="btn btn-sm btn-outline-info"
                                               data-bs-toggle="tooltip"
                                               title="تعديل البيانات">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#delete_agent{{ $agent->id }}"
                                                    data-bs-tooltip="tooltip"
                                                    title="حذف المندوب">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @include('pages.Agents.delete')
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        عرض <strong>{{ $agents->count() }}</strong> مندوب
                    </div>
                    <!-- يمكن إضافة الترقيم هنا إذا لزم الأمر -->
                </div>
            </div>
        </div>
    </div>
    <!-- row closed -->
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // البحث في الجدول
            const searchInput = document.getElementById('searchInput');
            const searchBtn = document.getElementById('searchBtn');
            const table = document.getElementById('datatable');
            const rows = table.querySelectorAll('tbody tr');

            function performSearch() {
                const searchTerm = searchInput.value.toLowerCase();

                rows.forEach(row => {
                    const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const code = row.querySelector('td:nth-child(6)').textContent.toLowerCase();

                    if (name.includes(searchTerm) || code.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            searchBtn.addEventListener('click', performSearch);
            searchInput.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });

            // باقي الكود...
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // جعل الجدول متجاوب على الهواتف
            function responsiveTable() {
                if (window.innerWidth <= 768) {
                    const headers = document.querySelectorAll('#datatable thead th');
                    const cells = document.querySelectorAll('#datatable tbody td');

                    headers.forEach((header, index) => {
                        const text = header.textContent;
                        cells.forEach(cell => {
                            if (cell.cellIndex === index) {
                                cell.setAttribute('data-label', text);
                            }
                        });
                    });
                }
            }

            responsiveTable();
            window.addEventListener('resize', responsiveTable);

            // تفعيل أدوات التلميح
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endsection
