@extends('Dashboard.layouts.master')

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            --secondary-gradient: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
            --danger-gradient: linear-gradient(135deg, #e74a3b 0%, #be2617 100%);
            --dark-color: #5a5c69;
            --light-bg: #f8f9fc;
        }

        .financial-report {
            font-family: 'Tajawal', sans-serif;
        }

        .report-card {
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            border: none;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .report-card:hover {
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.12);
        }

        .report-header {
            background: var(--primary-gradient);
            color: white;
            padding: 1.75rem;
            border-radius: 16px 16px 0 0;
            position: relative;
            overflow: hidden;
        }

        .report-header::after {
            content: "";
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
        }

        .report-header h3 {
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .report-header p {
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .filter-section {
            background-color: var(--light-bg);
            padding: 1.75rem;
            border-bottom: 1px solid #e3e6f0;
        }

        .form-label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .form-select, .form-control {
            border-radius: 8px;
            border: 1px solid #d1d3e2;
            padding: 0.5rem 1rem;
            transition: all 0.3s;
        }

        .form-select:focus, .form-control:focus {
            border-color: #bac8f3;
            box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.15);
        }

        .filter-btn {
            background: var(--primary-gradient);
            border: none;
            padding: 0.65rem 1.75rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 4px 6px rgba(78, 115, 223, 0.1);
        }

        .filter-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(78, 115, 223, 0.15);
        }

        .btn-outline-secondary {
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-outline-secondary:hover {
            background-color: #e3e6f0;
        }

        .export-btns {
            margin-top: 1.5rem;
        }

        .btn-success {
            background: var(--secondary-gradient);
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1.25rem;
            font-weight: 600;
            box-shadow: 0 4px 6px rgba(28, 200, 138, 0.1);
        }

        .btn-danger {
            background: var(--danger-gradient);
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1.25rem;
            font-weight: 600;
            box-shadow: 0 4px 6px rgba(231, 74, 59, 0.1);
        }

        .table-responsive {
            border-radius: 0 0 16px 16px;
            overflow: hidden;
        }

        .table {
            margin-bottom: 0;
            font-size: 0.9rem;
        }

        .table thead th {
            background-color: #f0f3ff;
            color: #4e73df;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            border-bottom-width: 2px;
            padding: 1rem 0.75rem;
            white-space: nowrap;
        }

        .table tbody td {
            padding: 0.75rem;
            vertical-align: middle;
        }

        .table tbody tr {
            transition: all 0.2s;
        }

        .table tbody tr:hover {
            background-color: rgba(78, 115, 223, 0.05);
            transform: scale(1.005);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        }

        .profit-cell {
            font-weight: 700;
            font-size: 1.05rem;
        }

        .profit-positive {
            color: #1cc88a;
        }

        .profit-negative {
            color: #e74a3b;
        }

        .total-row {
            background-color: #f0f3ff;
            font-weight: 700;
        }

        .total-row td {
            border-top: 2px solid #d1d3e2;
        }

        .text-muted {
            color: #858796 !important;
        }

        .empty-state {
            padding: 3rem;
            text-align: center;
        }

        .empty-state i {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #d1d3e2;
        }

        .currency-badge {
            font-size: 0.7rem;
            padding: 0.15rem 0.4rem;
            background-color: #e3e6f0;
            border-radius: 4px;
            font-weight: 600;
        }

        @media (max-width: 992px) {
            .filter-section .col-md-2 {
                margin-bottom: 1rem;
            }

            .table-responsive {
                overflow-x: auto;
            }

            .table {
                min-width: 1000px;
            }
        }

        /* Animation for empty state */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .empty-state {
            animation: fadeIn 0.5s ease-out;
        }

        /* Hover effects for buttons */
        .btn-success:hover, .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        /* Tooltip styling */
        .tooltip-inner {
            border-radius: 6px;
            padding: 0.5rem 0.75rem;
            font-size: 0.8rem;
        }
    </style>

@section('content')
    <div class="container-fluid py-5 financial-report">
        <div class="report-card">
            <div class="report-header text-center">
                <h3 class="mb-1">تقرير المكاسب المالية للمندوبين</h3>
                <p class="mb-0 opacity-85">تحليل شامل ومفصل لأداء المندوبين المالي</p>
            </div>

            <div class="filter-section">
                <form method="GET" action="{{ route('reports.financing') }}" id="report-form">
                    <div class="row align-items-end g-3">
                        <div class="col-md-2">
                            <label class="form-label">المندوب</label>
                            <select name="agent_id" class="form-select">
                                <option value="">جميع المندوبين</option>
                                @foreach ($agents as $agent)
                                    <option value="{{ $agent->id }}" {{ request('agent_id') == $agent->id ? 'selected' : '' }}>
                                        {{ $agent->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">من تاريخ</label>
                            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">إلى تاريخ</label>
                            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">رقم الملف</label>
                            <select name="file_code" class="form-select">
                                <option value="">جميع الملفات</option>
                                @foreach($files as $file)
                                    <option value="{{ $file->file_code }}" {{ request('file_code') == $file->file_code ? 'selected' : '' }}>
                                        {{ $file->file_code }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn filter-btn text-white w-100">
                                <i class="fas fa-filter me-2"></i> تطبيق الفلتر
                            </button>
                            <a href="{{ route('reports.financing') }}" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="إعادة تعيين الفلتر">
                                <i class="fas fa-sync-alt"></i>
                            </a>
                        </div>
                    </div>
                </form>

                <div class="export-btns d-flex justify-content-end gap-2">
                    <button class="btn btn-success" onclick="exportToExcel()" data-bs-toggle="tooltip" title="تصدير إلى Excel">
                        <i class="fas fa-file-excel me-2"></i> إكسل
                    </button>
                    <button class="btn btn-danger" onclick="exportToPDF()" data-bs-toggle="tooltip" title="تصدير إلى PDF">
                        <i class="fas fa-file-pdf me-2"></i> PDF
                    </button>
                    <button class="btn btn-dark" onclick="window.print()" data-bs-toggle="tooltip" title="طباعة التقرير">
                        <i class="fas fa-print me-2"></i> طباعة
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-bordered mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>التاريخ</th>
                        <th>المندوب</th>
                        <th>مزود الخدمة</th>
                        <th>نوع الرحلة</th>
                        <th>سعر البيع (EGP)</th>
                        <th>سعر البيع (USD)</th>
                        <th>سعر البيع (EUR)</th>
                        <th>المعامل (EGP)</th>
                        <th>التكلفة</th>
                        <th>العمولة</th>
                        <th>الخصم</th>
                        <th>المكسب</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($reportData as $data)
                        <tr>
                            <td>{{ $data['booking_date'] }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="agent-avatar me-2">
                                        <i class="fas fa-user-circle text-primary"></i>
                                    </div>
                                    <span>{{ $data['agent_name'] }}</span>
                                </div>
                            </td>
                            <td>{{ $data['provider'] }}</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span>{{ $data['trip_type'] }}</span>
                                    @if($data['sub_trip_type'])
                                        <small class="text-muted">{{ $data['sub_trip_type'] }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>{{ number_format($data['sale_price_egp'], 2) }}</td>
                            <td>{{ $data['sale_price_usd'] ? number_format($data['sale_price_usd'], 2) : '-' }}</td>
                            <td>{{ $data['sale_price_eur'] ? number_format($data['sale_price_eur'], 2) : '-' }}</td>
                            <td>{{ number_format($data['converted_total_price_egp'], 2) }}</td>
                            <td>{{ number_format($data['cost_price'], 2) }}</td>
                            <td>{{ number_format($data['commission'], 2) }}</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span>{{ number_format($data['discount'], 2) }}</span>
                                    <small class="currency-badge">{{ strtoupper($data['currency'] ?? '') }}</small>
                                    <small class="text-muted">≈ {{ number_format($data['discountInEGP'], 2) }} EGP</small>
                                </div>
                            </td>
                            <td class="profit-cell {{ $data['profit'] >= 0 ? 'profit-positive' : 'profit-negative' }}">
                                {{ number_format($data['profit'], 2) }}
                                @if($data['profit'] >= 0)
                                    <i class="fas fa-arrow-up ms-1"></i>
                                @else
                                    <i class="fas fa-arrow-down ms-1"></i>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="empty-state">
                                <i class="fas fa-exclamation-circle"></i>
                                <h5 class="mt-2 mb-1">لا توجد بيانات متاحة</h5>
                                <p class="text-muted mb-0">لم يتم العثور على أي سجلات تطابق معايير البحث</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                    @if(count($reportData) > 0)
                        <tfoot class="total-row">
                        <tr>
                            <td colspan="4" class="text-end"><strong>الإجمالي:</strong></td>
                            <td>{{ number_format($totals['total_sale_egp'], 2) }}</td>
                            <td>{{ $totals['total_sale_usd'] ? number_format($totals['total_sale_usd'], 2) : '-' }}</td>
                            <td>{{ $totals['total_sale_eur'] ? number_format($totals['total_sale_eur'], 2) : '-' }}</td>
                            <td>{{ number_format($totals['total_converted'], 2) }}</td>
                            <td>{{ number_format($totals['total_cost'], 2) }}</td>
                            <td>{{ number_format($totals['total_commission'], 2) }}</td>
                            <td>{{ number_format($totals['total_discount_egp'], 2) }} EGP</td>
                            <td class="{{ $totals['total_profit'] >= 0 ? 'profit-positive' : 'profit-negative' }}">
                                {{ number_format($totals['total_profit'], 2) }}
                                @if($totals['total_profit'] >= 0)
                                    <i class="fas fa-arrow-up ms-1"></i>
                                @else
                                    <i class="fas fa-arrow-down ms-1"></i>
                                @endif
                            </td>
                        </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function exportToExcel() {
            // يمكنك استخدام مكتبة مثل SheetJS لتصدير البيانات
            Swal.fire({
                title: 'جاري التصدير',
                text: 'سيتم تصدير البيانات إلى ملف Excel',
                icon: 'info',
                showConfirmButton: false,
                timer: 1500
            });

            // Simulate export delay
            setTimeout(() => {
                // Replace with actual export logic
                const table = document.querySelector('.table');
                const html = table.outerHTML;
                const blob = new Blob([html], {type: 'application/vnd.ms-excel'});
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'تقرير_المكاسب_المالية.xls';
                a.click();

                Swal.fire({
                    title: 'تم التصدير بنجاح',
                    text: 'تم تنزيل ملف Excel بنجاح',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            }, 2000);
        }

        function exportToPDF() {
            // يمكنك استخدام مكتبة مثل jsPDF أو html2pdf
            Swal.fire({
                title: 'جاري التصدير',
                text: 'سيتم تصدير البيانات إلى ملف PDF',
                icon: 'info',
                showConfirmButton: false,
                timer: 1500
            });

            // Simulate export delay
            setTimeout(() => {
                // Replace with actual PDF export logic
                window.print();

                Swal.fire({
                    title: 'جاهز للطباعة',
                    text: 'تم فتح نافذة الطباعة',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            }, 2000);
        }

        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Add animation to table rows
            const tableRows = document.querySelectorAll('.table tbody tr');
            tableRows.forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateY(10px)';
                row.style.transition = `all 0.3s ease ${index * 0.05}s`;

                setTimeout(() => {
                    row.style.opacity = '1';
                    row.style.transform = 'translateY(0)';
                }, 100);
            });
        });
    </script>
@endsection
