@extends('Dashboard.layouts.master')

@section('page-header')
    <div class="breadcrumb-header justify-content-between animate__animated animate__fadeIn">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <div class="header-icon-box" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); width: 60px; height: 60px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-left: 1rem;">
                    <i class="fas fa-suitcase-rolling" style="font-size: 1.8rem; color: white;"></i>
                </div>
                <div>
                    <h4 class="content-title mb-0 my-auto" style="font-weight: 700;">
                        <span style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                            الرحلات المطلوبة
                        </span>
                    </h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0" style="background: transparent; padding: 0.25rem 0;">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" style="color: #718096;">الرئيسية</a></li>
                            <li class="breadcrumb-item active" aria-current="page" style="color: #667eea;">الرحلات المطلوبة</li>
                        </ol>
                    </nav>
                </div>
                <span class="badge-modern pulse-animation" style="background: rgba(102, 126, 234, 0.1); color: #667eea; margin-right: 1rem;">
                    <i class="fas fa-clock"></i> {{ $requests->count() }} طلب
                </span>
            </div>
        </div>
    </div>

    <style>
        @keyframes pulse {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.4); }
            70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(102, 126, 234, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(102, 126, 234, 0); }
        }
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        .header-icon-box {
            transition: all 0.3s ease;
        }
        .header-icon-box:hover {
            transform: rotate(15deg) scale(1.1);
        }
        .breadcrumb-item a:hover {
            color: #667eea !important;
        }
    </style>
@endsection

@section('content')
    <div class="row animate__animated animate__fadeInUp">
        <div class="col-12">
            <div class="card card-3d">
                <div class="card-header-gradient">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title text-white">
                            <i class="fas fa-list-alt"></i> قائمة الرحلات المطلوبة
                        </h5>
                        <div class="d-flex align-items-center">
                            <span class="badge-modern bg-white text-primary">
                                <i class="fas fa-filter"></i>
                                <select class="form-select-sm border-0 bg-transparent" id="status-filter">
                                    <option value="all">الكل</option>
                                    <option value="pending" selected>قيد الانتظار</option>
                                    <option value="accepted">مقبولة</option>
                                    <option value="rejected">مرفوضة</option>
                                </select>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="modern-table">
                            <thead>
                            <tr>
                                <th>تاريخ الرحلة</th>
                                <th>اسم الرحلة</th>
                                <th>المندوب</th>
                                <th>المزود</th>
                                <th>الأشخاص</th>
                                <th>السعر</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($requests as $request)
                                <tr class="animate__animated animate__fadeIn" style="animation-delay: {{ $loop->index * 0.05 }}s">
                                    <td data-label="تاريخ الرحلة">
                                        <span class="badge-modern" style="background: rgba(245, 247, 250, 0.8); color: #2d3748;">
                                            <i class="far fa-calendar-alt"></i>
                                            {{ \Carbon\Carbon::parse($request->booking_datetime)->translatedFormat('l d F Y') }}
                                        </span>
                                    </td>
                                    <td data-label="اسم الرحلة">
                                        <span style="font-weight: 600;">{{ optional($request->tripType)->type }}</span>
                                        @if($request->subTripType)
                                            <small class="d-block text-muted">{{ optional($request->subTripType)->type }}</small>
                                        @endif
                                    </td>
                                    <td data-label="المندوب">
                                        {{ optional($request->tripRequest->agent)->name ?? '--' }}
                                    </td>
                                    <td data-label="المزود">
                                        {{ $request->provider->name ?? '--' }}
                                    </td>
                                    <td data-label="الأشخاص">
                                        <div class="d-flex justify-content-center gap-2">
                                            <span class="badge-modern" style="background: rgba(94, 114, 228, 0.1); color: #5e72e4;" data-toggle="tooltip" title="البالغين">
                                                <i class="fas fa-male"></i> {{ $request->adult_count ?? 0 }}
                                            </span>
                                            <span class="badge-modern" style="background: rgba(94, 114, 228, 0.1); color: #5e72e4;" data-toggle="tooltip" title="الأطفال">
                                                <i class="fas fa-child"></i> {{ $request->children_count ?? 0 }}
                                            </span>
                                        </div>
                                    </td>
                                    <td data-label="السعر">
                                        <span style="font-weight: 700; color: #2dce89;">
                                            {{ number_format($request->total_price, 2) }} ج.م
                                        </span>
                                    </td>
                                    <td data-label="الحالة">
                                        <span class="badge-modern" style="background: rgba(251, 99, 64, 0.1); color: #fb6340;">
                                            <i class="fas fa-clock"></i> قيد الانتظار
                                        </span>
                                    </td>
                                    <td data-label="الإجراءات">
                                        <div class="d-flex gap-2">
                                            <button class="btn-neon btn-success" data-bs-toggle="modal" data-bs-target="#approveModal{{ $request->id }}">
                                                <i class="fas fa-check-circle me-1"></i> قبول
                                            </button>
                                            <button class="btn-neon btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $request->id }}">
                                                <i class="fas fa-times-circle me-1"></i> رفض
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    @foreach($requests as $request)
        <!-- Approve Modal -->
        <div class="modal fade" id="approveModal{{ $request->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content modal-glass">
                    <div class="modal-header">
                        <div class="d-flex align-items-center">
                            <div class="modal-icon" style="width: 40px; height: 40px; background: rgba(45, 206, 137, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-left: 10px;">
                                <i class="fas fa-check" style="color: #2dce89;"></i>
                            </div>
                            <h5 class="modal-title">تأكيد قبول الرحلة</h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('provider.WaitingPayment', $request->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="modal-body">
                            <div class="trip-summary mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="text-primary">ملخص الرحلة</h6>
                                    <span class="badge bg-success bg-opacity-10 text-success">#{{ $request->id }}</span>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <small class="text-muted d-block">تاريخ الرحلة</small>
                                        <span class="d-block">{{ \Carbon\Carbon::parse($request->booking_datetime)->translatedFormat('l d F Y') }}</span>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <small class="text-muted d-block">عدد الأشخاص</small>
                                        <span class="d-block">{{ $request->total_people }} ({{ $request->adult_count }} بالغ - {{ $request->children_count }} طفل)</span>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <small class="text-muted d-block">المبلغ الإجمالي</small>
                                        <h4 class="text-success">{{ number_format($request->total_price, 2) }} ج.م</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="booking_time" class="form-label">حدد وقت الرحلة</label>
                                <input type="time" class="form-control time-picker" name="booking_time" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check-circle me-1"></i> تأكيد القبول
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Reject Modal -->
        <div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content modal-glass">
                    <div class="modal-header">
                        <div class="d-flex align-items-center">
                            <div class="modal-icon" style="width: 40px; height: 40px; background: rgba(245, 54, 92, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-left: 10px;">
                                <i class="fas fa-times" style="color: #f5365c;"></i>
                            </div>
                            <h5 class="modal-title">تأكيد رفض الرحلة</h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('provider.rejectRequest', $request->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="modal-body">
                            <div class="trip-summary mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="text-primary">ملخص الرحلة</h6>
                                    <span class="badge bg-danger bg-opacity-10 text-danger">#{{ $request->id }}</span>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <small class="text-muted d-block">تاريخ الرحلة</small>
                                        <span class="d-block">{{ \Carbon\Carbon::parse($request->booking_datetime)->translatedFormat('l d F Y') }}</span>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <small class="text-muted d-block">عدد الأشخاص</small>
                                        <span class="d-block">{{ $request->total_people }} ({{ $request->adult_count }} بالغ - {{ $request->children_count }} طفل)</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="rejection_reason" class="form-label">سبب الرفض</label>
                                <textarea class="form-control" name="rejection_reason" rows="3" placeholder="الرجاء ذكر سبب الرفض..." required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-times-circle me-1"></i> تأكيد الرفض
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

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

        /* تصميم الكارد الرئيسي */
        .card-3d {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            background: white;
            transition: var(--transition);
            overflow: hidden;
            position: relative;
            z-index: 1;
            margin-bottom: 2rem;
        }

        .card-3d::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: var(--primary-gradient);
            z-index: 2;
        }

        .card-3d:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(50, 50, 93, 0.15), 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        /* هيدر الكارد */
        .card-header-gradient {
            background: var(--primary-gradient);
            color: white;
            border-bottom: none;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .card-header-gradient::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 70%);
            transform: rotate(30deg);
        }

        .card-title {
            font-weight: 700;
            font-size: 1.25rem;
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
        }

        .card-title i {
            margin-left: 1rem;
            font-size: 1.5rem;
            opacity: 0.8;
        }

        /* تصميم الجدول المميز */
        .modern-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
            margin: 0;
        }

        .modern-table thead th {
            background: white;
            color: var(--primary);
            border: none;
            padding: 1rem 1.5rem;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .modern-table tbody tr {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
            transition: var(--transition);
            margin-bottom: 15px;
        }

        .modern-table tbody tr:hover {
            transform: translateX(5px);
            box-shadow: 0 10px 25px rgba(94, 114, 228, 0.1);
        }

        .modern-table td {
            padding: 1.25rem 1.5rem;
            vertical-align: middle;
            border: none;
            border-top: 1px solid rgba(0, 0, 0, 0.03);
            border-bottom: 1px solid rgba(0, 0, 0, 0.03);
        }

        .modern-table td:first-child {
            border-left: 1px solid rgba(0, 0, 0, 0.03);
            border-radius: var(--border-radius) 0 0 var(--border-radius);
        }

        .modern-table td:last-child {
            border-right: 1px solid rgba(0, 0, 0, 0.03);
            border-radius: 0 var(--border-radius) var(--border-radius) 0;
        }

        /* تصميم البادجات */
        .badge-modern {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .badge-modern i {
            margin-left: 0.5rem;
        }

        /* تصميم الأزرار */
        .btn-neon {
            border: none;
            border-radius: 50px;
            padding: 0.6rem 1.25rem;
            font-weight: 600;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-neon::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(rgba(255,255,255,0.3), rgba(255,255,255,0));
            opacity: 0;
            transition: var(--transition);
        }

        .btn-neon:hover::after {
            opacity: 1;
        }

        .btn-neon:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .btn-neon.btn-success {
            background: var(--success);
            color: white;
        }

        .btn-neon.btn-danger {
            background: var(--danger);
            color: white;
        }

        /* تصميم المودال */
        .modal-glass {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-bottom: none;
            padding: 1.25rem 1.5rem;
        }

        .modal-title {
            font-weight: 600;
            font-size: 1.25rem;
            margin-bottom: 0;
        }

        .modal-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 10px;
        }

        .modal-icon i {
            font-size: 1.2rem;
            color: white;
        }

        .btn-close {
            color: white;
            opacity: 0.8;
            font-size: 1.5rem;
            transition: var(--transition);
        }

        .btn-close:hover {
            opacity: 1;
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 1.5rem;
        }

        .trip-summary {
            background: rgba(245, 247, 250, 0.8);
            border-radius: 10px;
            padding: 1.25rem;
            border-left: 4px solid #667eea;
        }

        /* تأثيرات الحركة */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate__fadeIn {
            animation: fadeIn 0.6s ease-out forwards;
        }

        /* تصميم متجاوب */
        @media (max-width: 992px) {
            .modern-table thead {
                display: none;
            }

            .modern-table tbody tr {
                display: block;
                margin-bottom: 1.5rem;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            }

            .modern-table td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                text-align: right;
                padding: 1rem;
                border: none !important;
                border-bottom: 1px solid rgba(0, 0, 0, 0.05) !important;
            }

            .modern-table td::before {
                content: attr(data-label);
                font-weight: 600;
                color: var(--primary);
                margin-left: 1rem;
            }

            .modern-table td:first-child,
            .modern-table td:last-child {
                border-radius: 0 !important;
            }

            .modern-table td:first-child {
                border-radius: var(--border-radius) var(--border-radius) 0 0 !important;
            }

            .modern-table td:last-child {
                border-radius: 0 0 var(--border-radius) var(--border-radius) !important;
                border-bottom: none !important;
            }

            .btn-neon {
                padding: 0.5rem 1rem;
                font-size: 0.75rem;
            }
        }

        @media (max-width: 576px) {
            .modal-dialog {
                margin: 0.5rem;
            }

            .modal-body {
                padding: 1rem;
            }
        }
    </style>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // فلترة الجدول حسب الحالة
            $('#status-filter').change(function() {
                const status = $(this).val();
                if (status === 'all') {
                    $('tbody tr').show();
                } else {
                    $('tbody tr').hide();
                    $(`tbody tr td:nth-child(7) span:contains(${status === 'pending' ? 'قيد الانتظار' : status === 'accepted' ? 'مقبولة' : 'مرفوضة'})`).parent().parent().show();
                }
            });

            // تحسين اختيار الوقت
            $('.time-picker').timepicker({
                timeFormat: 'h:mm p',
                interval: 30,
                minTime: '6:00am',
                maxTime: '11:30pm',
                defaultTime: 'now',
                startTime: '06:00',
                dynamic: false,
                dropdown: true,
                scrollbar: true
            });
        });
    </script>
@endsection
