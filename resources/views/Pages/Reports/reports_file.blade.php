@extends('Dashboard.layouts.master')

@section('content')
    <div class="container py-4">
        <div class="card border-0 shadow-lg rounded-3 overflow-hidden">
            <!-- Card Header -->
            <div class="card-header bg-gradient-primary text-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i> تقرير أداء المجموعة
                    </h3>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-filter me-2"></i>
                        <span>فلاتر التقرير</span>
                    </div>
                </div>
            </div>

            <!-- Card Body -->
            <div class="card-body p-4">
                <!-- Filter Form -->
                <form method="GET" action="{{ route('reports.file') }}" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <div class="form-floating">
                                <select name="file_code" class="form-select" id="fileSelect">
                                    <option value="">جميع الملفات</option>
                                    @foreach($files as $fileOption)
                                        <option value="{{ $fileOption->file_code }}" {{ request('file_code') == $fileOption->file_code ? 'selected' : '' }}>
                                            {{ $fileOption->file_code }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="fileSelect">
                                    <i class="fas fa-file-alt me-2"></i>رقم الملف
                                </label>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="form-floating">
                                <select name="agent_id" class="form-select" id="agentSelect">
                                    <option value="">جميع المندوبين</option>
                                    @foreach($agents as $agent)
                                        <option value="{{ $agent->id }}" {{ request('agent_id') == $agent->id ? 'selected' : '' }}>
                                            {{ $agent->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="agentSelect">
                                    <i class="fas fa-user-tie me-2"></i>المندوب
                                </label>
                            </div>
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100 py-3">
                                <i class="fas fa-search me-2"></i>عرض التقرير
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Report Table -->
                @if(!empty($report) && count($report) > 0)
                    <div class="table-responsive rounded-3 overflow-hidden border">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                            <tr>
                                <th class="text-center">
                                    <i class="fas fa-user-tie me-2"></i>المندوب
                                </th>
                                <th class="text-center">
                                    <i class="fas fa-file-code me-2"></i>رقم الملف
                                </th>
                                <th class="text-center">
                                    <i class="fas fa-male me-2"></i>البالغين
                                </th>
                                <th class="text-center">
                                    <i class="fas fa-child me-2"></i>الأطفال
                                </th>
                                <th class="text-center">
                                    <i class="fas fa-chart-pie me-2"></i>نسبة البالغين
                                </th>
                                <th class="text-center">
                                    <i class="fas fa-chart-pie me-2"></i>نسبة الأطفال
                                </th>
                                <th class="text-center">
                                    <i class="fas fa-star me-2"></i>التقييم
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($report as $data)
                                <tr class="border-bottom">
                                    <td class="text-center fw-semibold">
                                        {{ $data['agent_name'] }}
                                    </td>

                                    <td class="text-center">
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">
                                                {{ $data['file_code'] }}
                                            </span>
                                    </td>

                                    <td class="text-center">
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold">{{ $data['adult_count'] }}</span>
                                            <small class="text-muted">من أصل {{ $data['adult_limit'] }}</small>
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold">{{ $data['child_count'] }}</span>
                                            <small class="text-muted">من أصل {{ $data['child_limit'] }}</small>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="progress-container">
                                            <div class="progress-info d-flex justify-content-between mb-1">
                                                <span class="text-info fw-semibold">{{ $data['adult_percent'] }}%</span>
                                            </div>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-info bg-opacity-75"
                                                     style="width: {{ $data['adult_percent'] }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="progress-container">
                                            <div class="progress-info d-flex justify-content-between mb-1">
                                                <span class="text-warning fw-semibold">{{ $data['child_percent'] }}%</span>
                                            </div>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-warning bg-opacity-75"
                                                     style="width: {{ $data['child_percent'] }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        @php
                                            $ratingConfig = [
                                                'ممتاز' => ['color' => 'success', 'icon' => 'fas fa-star'],
                                                'جيد جدًا' => ['color' => 'primary', 'icon' => 'fas fa-star-half-alt'],
                                                'جيد' => ['color' => 'warning', 'icon' => 'far fa-star'],
                                                'default' => ['color' => 'danger', 'icon' => 'fas fa-times-circle'],
                                            ];
                                            $rating = $data['rating'];
                                            $config = $ratingConfig[$rating] ?? $ratingConfig['default'];
                                        @endphp

                                        <span class="badge bg-{{ $config['color'] }}-subtle text-{{ $config['color'] }} p-2">
                                                <i class="{{ $config['icon'] }} me-1"></i>
                                                {{ $rating }}
                                            </span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @elseif(request()->has('file_code') || request()->has('agent_id'))
                    <div class="empty-state text-center py-5">
                        <div class="empty-state-icon bg-light-primary rounded-circle p-4 mb-3">
                            <i class="fas fa-search text-primary fs-1"></i>
                        </div>
                        <h4 class="mb-3">لا توجد نتائج مطابقة</h4>
                        <p class="text-muted mb-4">لم يتم العثور على أي نتائج تطابق معايير البحث المحددة</p>
                        <a href="{{ route('reports.file') }}" class="btn btn-outline-primary">
                            <i class="fas fa-redo me-2"></i>إعادة تعيين الفلاتر
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        :root {
            --primary-color: #4e73df;
            --primary-hover: #3a5ec0;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --light-color: #f8f9fc;
            --dark-color: #5a5c69;
        }

        body {
            background-color: #f8f9fc;
            font-family: 'Tajawal', sans-serif;
        }

        .card {
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.2);
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, var(--primary-color), #224abe);
        }

        .form-floating label {
            color: var(--secondary-color);
            font-weight: 500;
        }

        .form-select, .form-control {
            border: 1px solid #d1d3e2;
            border-radius: 0.375rem;
            padding: 0.75rem 1rem;
            transition: all 0.3s;
        }

        .form-select:focus, .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
        }

        .table {
            --bs-table-bg: transparent;
            --bs-table-striped-bg: rgba(0, 0, 0, 0.02);
            --bs-table-hover-bg: rgba(0, 0, 0, 0.03);
        }

        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            color: var(--dark-color);
            background-color: var(--light-color);
            border-bottom-width: 1px;
        }

        .table td {
            vertical-align: middle;
            padding: 1rem;
            border-bottom: 1px solid #e3e6f0;
        }

        .progress {
            border-radius: 0.375rem;
            background-color: #eaecf4;
        }

        .progress-bar {
            border-radius: 0.375rem;
        }

        .badge {
            font-weight: 600;
            letter-spacing: 0.5px;
            padding: 0.5rem 0.75rem;
        }

        .empty-state {
            padding: 3rem 1rem;
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        }

        .empty-state-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media (max-width: 768px) {
            .card-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .form-floating {
                margin-bottom: 1rem;
            }

            .table-responsive {
                border: none;
            }

            .table th, .table td {
                padding: 0.75rem;
            }
        }
    </style>
@endsection
