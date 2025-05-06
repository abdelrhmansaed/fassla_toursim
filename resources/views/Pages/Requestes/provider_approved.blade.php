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
        --transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    /* تصميم الخلفية المميز */
    body {
        font-family: 'Cairo', sans-serif;
        background-color: #f5f7fa;
        background-image: radial-gradient(circle at 10% 20%, rgba(94, 114, 228, 0.05) 0%, rgba(94, 114, 228, 0.05) 90%);
        color: var(--text-main);
        line-height: 1.7;
    }
    .card-3d {
        border: none;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        background: white;
        transition: var(--transition);
        overflow: hidden;
        position: relative;
        margin-bottom: 2rem;
    }
    /* كارد رئيسي بتأثير ثلاثي الأبعاد */
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

    /* هيدر الكارد مع تدرج لوني */


    .card-title {
        font-weight: 700;
        font-size: 1.5rem;
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
    }

    .card-title i {
        margin-left: 1rem;
        font-size: 1.8rem;
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
        padding: 1.2rem 1.5rem;
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
        padding: 1.5rem;
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

    /* تصميم البادجات المميزة */
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
    .btn-neon {
        border: none;
        border-radius: 50px;
        padding: 0.75rem 1.5rem;
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
    .empty-state-modern {
        padding: 4rem;
        text-align: center;
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        margin: 2rem 0;
    }

    .empty-icon {
        width: 100px;
        height: 100px;
        background: var(--primary-light);
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
    }

    .empty-icon i {
        font-size: 3rem;
        color: var(--primary);
    }

    .empty-icon {
        width: 100px;
        height: 100px;
        background: var(--primary-light);
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
    }

    .empty-icon i {
        font-size: 3rem;
        color: var(--primary);
    }
    .empty-icon {
        width: 100px;
        height: 100px;
        background: var(--primary-light);
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
    }

    .empty-icon i {
        font-size: 3rem;
        color: var(--primary);
    }


    .modal-content {
        background: rgba(255, 255, 255, 0.8) !important; /* شبه زجاج */
        backdrop-filter: blur(10px); /* تأثير الزجاج */
        border-radius: 15px;
        border: none;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
        opacity: 1 !important;
        animation: fadeInModal 0.4s ease-in-out;
    }
    .modal-glass {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: none;
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    }

    .modal-glass .modal-header {
        background: var(--primary-gradient);
        color: white;
        border-bottom: none;
        padding: 1.5rem;
    }

    .modal-glass .modal-body {
        padding: 2rem;
    }
    .modal-backdrop.show {
        opacity: 0.5 !important; /* بدل 1 */
        background-color: rgba(0, 0, 0, 0.5) !important; /* رمادي شفاف */
    }
    .modal-content {
        background-color: #fff !important; /* خلفية بيضاء للمودال */
        opacity: 1 !important;
    }
    .modal-backdrop {
        z-index: 1040 !important;
        background-color: rgba(0,0,0,0.5) !important; /* شفافية مناسبة */
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
    }

    @media (max-width: 576px) {
        .empty-state-modern {
            padding: 2rem 1rem;
        }

        .btn-neon {
            padding: 0.6rem 1rem;
            font-size: 0.75rem;
        }
    }


</style>

@section('page-header')
    <div class="breadcrumb-header justify-content-between animate-fade-up" style="animation-delay: 0.1s">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <div class="header-icon-box" style="background: var(--primary-gradient); width: 60px; height: 60px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-left: 1rem;">
                    <i class="fas fa-money-bill-wave" style="font-size: 1.8rem; color: white;"></i>
                </div>
                <div>
                    <h4 class="content-title mb-0 my-auto" style="font-weight: 700;">
                    <span style="background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                        الرحلات في انتظار الدفع
                    </span>
                    </h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0" style="background: transparent; padding: 0.25rem 0;">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" style="color: var(--text-muted);">الرئيسية</a></li>
                            <li class="breadcrumb-item active" aria-current="page" style="color: var(--primary);">الرحلات المعلقة</li>
                        </ol>
                    </nav>
                </div>
                <span class="badge-modern pulse-animation" style="background: var(--primary-light); color: var(--primary); margin-right: 1rem;">
                <i class="fas fa-clock"></i> {{ $trips->count() }} معلقة
            </span>
            </div>
        </div>
        <div class="d-flex align-items-center">
            <div class="btn-group" role="group">
                <button class="btn-neon btn-light" onclick="window.print()">
                    <i class="fas fa-print me-1"></i> طباعة
                </button>
                <button class="btn-neon" style="background: var(--primary); color: white;" onclick="location.reload()">
                    <i class="fas fa-sync-alt me-1"></i> تحديث
                </button>
            </div>
        </div>
    </div>

    <style>
        /* إضافة تأثير النبض للتنبيه */
        @keyframes pulse {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(94, 114, 228, 0.4); }
            70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(94, 114, 228, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(94, 114, 228, 0); }
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        /* تحسينات إضافية للهيدر */
        .header-icon-box {
            transition: var(--transition);
        }

        .header-icon-box:hover {
            transform: rotate(15deg) scale(1.1);
        }

        .breadcrumb-item a:hover {
            color: var(--primary) !important;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-12 animate-fade-up" style="animation-delay: 0.2s">
            <div class="card-3d">
                <div class="card-header-gradient">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title text-white">
                            <i class="fas fa-hourglass-half"></i> قائمة الرحلات المعلقة
                        </h5>
                        <div class="d-flex align-items-center">
                            <span class="badge-modern bg-white text-primary">
                                <i class="fas fa-list-ol"></i> {{ $trips->count() }} رحلة
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($trips->isEmpty())
                        <div class="empty-state-modern floating">
                            <div class="empty-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <h3 class="text-muted">لا توجد رحلات في انتظار الدفع</h3>
                            <p class="lead text-muted mb-4">عند وجود رحلات جديدة تحتاج إلى دفع، ستظهر هنا تلقائياً</p>
                            <a href="#" class="btn-neon" style="background: var(--primary); color: white;">
                                <i class="fas fa-sync-alt me-2"></i> تحديث الصفحة
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="modern-table">
                                <thead>
                                <tr>
                                    <th>تاريخ الرحلة</th>
                                    <th>رقم الملف</th>
                                    <th>اسم الفندق</th>
                                    <th>نوع الرحلة</th>
                                    <th>المزود</th>
                                    <th>الأشخاص</th>
                                    <th>الإجمالي</th>
                                    <th>إجراءات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($trips as $trip)

                                    <tr class="animate-fade-up" style="animation-delay: {{ $loop->index * 0.05 }}s">
                                        <td data-label="تاريخ الرحلة">
                                            <span class="badge-modern" style="background: var(--light); color: var(--text-main);">
                                                <i class="far fa-calendar-alt"></i>
                                                {{ \Carbon\Carbon::parse($trip->booking_datetime)->format('d/m/Y g:i A') ?? '--' }}
                                            </span>
                                        </td>
                                        <td data-label="رقم الملف">
                                            <span style="font-weight: 700; color: var(--primary);">#{{ optional($trip->tripRequest)->booking_number ?? '--' }}</span>
                                        </td>
                                        <td data-label="اسم الفندق">
                                            {{ optional($trip->tripRequest)->hotel_name ?? '--' }}
                                        </td>
                                        <td data-label="نوع الرحلة">
                                            <span class="badge-modern" style="background: rgba(17, 205, 239, 0.1); color: var(--secondary);">
                                                <i class="fas fa-route"></i>
                                                {{ optional($trip->tripType)->type ?? '--' }}
                                                @if($trip->subTripType)
                                                    - {{ optional($trip->subTripType)->type ?? '--' }}
                                                @endif
                                            </span>
                                        </td>
                                        <td data-label="المزود">
                                            {{ optional($trip->provider)->name ?? '--' }}
                                        </td>
                                        <td data-label="الأشخاص">
                                            <div class="d-flex justify-content-center gap-2">
                                                <span class="badge-modern" style="background: var(--primary-light); color: var(--primary);" data-toggle="tooltip" title="البالغين">
                                                    <i class="fas fa-male"></i> {{ $trip->adult_count ?? 0 }}
                                                </span>
                                                <span class="badge-modern" style="background: var(--primary-light); color: var(--primary);" data-toggle="tooltip" title="الأطفال">
                                                    <i class="fas fa-child"></i> {{ $trip->children_count ?? 0 }}
                                                </span>
                                            </div>
                                        </td>
                                        <td data-label="الإجمالي">
                                            <span style="font-weight: 700; color: var(--success);">
                                                {{ number_format($trip->total_price, 2) }} ج.م
                                            </span>
                                        </td>

                                        <td data-label="إجراءات">
                                            <div class="d-flex gap-2">
                                                <button class="btn-neon btn-pay" data-toggle="modal" data-target="#paymentModal-{{ $trip->id }}">
                                                    <i class="fas fa-money-bill-wave me-1"></i> دفع
                                                </button>

                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Payment Modal -->
                                    <!-- Payment Modal -->
                                    <div class="modal fade" id="paymentModal-{{ $trip->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content modal-glass">
                                                <div class="modal-header">
                                                    <div class="d-flex align-items-center">
                                                        <div class="modal-icon" style="width: 40px; height: 40px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-left: 10px;">
                                                            <i class="fas fa-money-bill-wave" style="color: white;"></i>
                                                        </div>
                                                        <h5 class="modal-title">إتمام عملية الدفع</h5>
                                                    </div>
                                                    <button type="button" class="btn-close text-white" data-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form id="payment-form-{{ $trip->id }}" action="{{ route('trips.uploadPayment', $trip->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="payment-summary" style="background: rgba(245, 247, 250, 0.8); border-radius: var(--border-radius); padding: 1.5rem; margin-bottom: 1.5rem;">
                                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                                <h6 style="font-weight: 600; color: var(--primary); margin-bottom: 0;">ملخص الرحلة</h6>
                                                                <span class="badge-modern" style="background: var(--primary-light); color: var(--primary);">
                                #{{ $trip->id }}
                            </span>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-6 mb-2">
                                                                    <small class="text-muted d-block">تاريخ الرحلة</small>
                                                                    <span style="font-weight: 500;">{{ \Carbon\Carbon::parse($trip->booking_datetime)->format('d/m/Y g:i A') }}</span>
                                                                </div>
                                                                <div class="col-6 mb-2">
                                                                    <small class="text-muted d-block">عدد الأشخاص</small>
                                                                    <span style="font-weight: 500;">{{ $trip->total_people }} ({{ $trip->adult_count }} بالغ - {{ $trip->children_count }} طفل)</span>
                                                                </div>
                                                                <div class="col-12">
                                                                    <small class="text-muted d-block">المبلغ المستحق</small>
                                                                    <h4 style="font-weight: 700; color: var(--success); margin-bottom: 0;">
                                                                        {{ number_format($trip->total_price, 2) }} ج.م
                                                                    </h4>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="payment-proof-{{ $trip->id }}" class="form-label">إثبات الدفع</label>
                                                            <div class="file-upload-wrapper">
                                                                <input type="file"
                                                                       class="file-upload-input"
                                                                       id="payment-proof-{{ $trip->id }}"
                                                                       name="payment_proof"
                                                                       accept="image/*,.pdf"
                                                                       required
                                                                       data-id="{{ $trip->id }}">
                                                                <label for="payment-proof-{{ $trip->id }}" class="file-upload-label">
                                                                    <div class="file-upload-icon">
                                                                        <i class="fas fa-cloud-upload-alt"></i>
                                                                    </div>
                                                                    <span class="file-upload-text" id="file-upload-text-{{ $trip->id }}">اسحب وأسقط الملف هنا أو انقر للاختيار</span>
                                                                    <span class="file-upload-hint">الحد الأقصى لحجم الملف: 5MB</span>
                                                                </label>
                                                            </div>
                                                            <!-- معاينة الصورة -->
                                                            <div id="image-preview-{{ $trip->id }}" style="margin-top: 10px; display: none;">
                                                                <img src="#" alt="معاينة الصورة" class="img-fluid rounded" style="max-height: 200px;">
                                                            </div>
                                                        </div>


                                                        <div class="form-group mt-4">
                                                            <label for="payment-note-{{ $trip->id }}" class="form-label">ملاحظات إضافية</label>
                                                            <textarea class="form-control" id="payment-note-{{ $trip->id }}" name="payment_note" rows="3" placeholder="أدخل أي ملاحظات حول عملية الدفع..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer" style="border-top: 1px solid rgba(0,0,0,0.05);">
                                                        <button type="button" class="btn-neon" style="background: var(--light); color: var(--dark);" data-dismiss="modal">
                                                            <i class="fas fa-times me-1"></i> إلغاء
                                                        </button>
                                                        <button type="submit" class="btn-neon btn-pay" id="submit-payment-{{ $trip->id }}">
                                                            <i class="fas fa-check-circle me-1"></i> تأكيد الدفع
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <style>
                                        /* تحسينات إضافية للمودال */
                                        .file-upload-wrapper {
                                            position: relative;
                                            margin-bottom: 1rem;
                                        }

                                        .file-upload-input {
                                            position: absolute;
                                            left: 0;
                                            top: 0;
                                            opacity: 0;
                                            width: 100%;
                                            height: 100%;
                                            cursor: pointer;
                                        }

                                        .file-upload-label {
                                            border: 2px dashed #e0e6ed;
                                            border-radius: var(--border-radius);
                                            padding: 2rem;
                                            text-align: center;
                                            transition: var(--transition);
                                            display: block;
                                        }

                                        .file-upload-label:hover {
                                            border-color: var(--primary);
                                            background: rgba(94, 114, 228, 0.03);
                                        }

                                        .file-upload-icon {
                                            font-size: 2rem;
                                            color: var(--primary);
                                            margin-bottom: 1rem;
                                        }

                                        .file-upload-text {
                                            display: block;
                                            font-weight: 600;
                                            color: var(--dark);
                                        }

                                        .file-upload-hint {
                                            display: block;
                                            font-size: 0.8rem;
                                            color: var(--text-muted);
                                            margin-top: 0.5rem;
                                        }

                                        .payment-summary {
                                            border-left: 4px solid var(--primary);
                                            transition: var(--transition);
                                        }

                                        .payment-summary:hover {
                                            transform: translateX(5px);
                                        }
                                    </style>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.file-upload-input').forEach(function (input) {
                input.addEventListener('change', function (event) {
                    const file = event.target.files[0];
                    const id = input.dataset.id;
                    const fileText = document.getElementById('file-upload-text-' + id);
                    const previewContainer = document.getElementById('image-preview-' + id);
                    const previewImage = previewContainer?.querySelector('img');

                    if (file) {
                        if (file.size > 5 * 1024 * 1024) {
                            alert('⚠️ حجم الملف يتجاوز 5 ميجابايت.');
                            input.value = '';
                            if (fileText) fileText.textContent = 'اسحب وأسقط الملف هنا أو انقر للاختيار';
                            if (previewContainer) previewContainer.style.display = 'none';
                            return;
                        }

                        if (fileText) fileText.textContent = file.name;

                        // عرض المعاينة إذا كان صورة
                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = function (e) {
                                if (previewImage) {
                                    previewImage.src = e.target.result;
                                    previewContainer.style.display = 'block';
                                }
                            };
                            reader.readAsDataURL(file);
                        } else {
                            if (previewContainer) previewContainer.style.display = 'none';
                        }
                    }
                });
            });
        });
    </script>

@endsection
