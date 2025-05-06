@extends('Dashboard.layouts.master')

    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f8f9fc;
        }

        .card {
            border-radius: 1rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: 0.3s ease-in-out;
        }

        .card:hover {
            transform: translateY(-3px);
        }

        .table thead tr {
            background-color: #28a745;
            color: #fff;
        }

        .badge {
            font-size: 0.9rem;
            padding: 5px 10px;
            border-radius: 0.5rem;
        }

        .btn-sm {
            border-radius: 0.5rem;
            padding: 5px 10px;
        }

        .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }

        .table td, .table th {
            vertical-align: middle !important;
        }
    </style>
@endsection

@section('content')
    <div class="container py-4">
        <div class="card card-statistics h-100">
            <div class="card-header bg-success text-white text-center">
                <h2>الرحلات المقبولة</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-sm table-bordered text-center align-middle" data-page-length="50">
                        <thead>
                        <tr>
                            <th>التاريخ</th>
                            <th>رقم الملف</th>
                            <th>رقم الطلب</th>
                            <th>نوع الرحلة</th>
                            <th>اسم الفندق</th>
                            <th>المندوب</th>
                            <th>المزود</th>
                            <th>عدد الأشخاص</th>
                            <th>إجمالي السعر</th>
                            <th>الإجراءات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($trips as $trip)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($trip->tripRequest->booking_datetime)->translatedFormat('l d F Y') }}</td>
                                <td>{{ optional($trip)->tripRequest->booking_number ?? 'غير متاح' }}</td>
                                <td>{{ optional($trip)->tripRequest->receipt_number ?? 'غير متاح' }}</td>
                                <td>
                                    {{ optional($trip->tripType)->type }}
                                    @if($trip->subTripType)
                                        - {{ optional($trip->subTripType)->type }}
                                    @endif
                                </td>
                                <td>{{ optional($trip)->tripRequest->hotel_name ?? 'غير متاح' }}</td>
                                <td>{{ optional($trip->tripRequest->agent)->name ?? 'غير متاح' }}</td>
                                <td>{{ optional($trip->provider)->name ?? 'غير متاح' }}</td>
                                <td>{{ $trip->total_people }}</td>
                                <td class="fw-bold text-success">{{ number_format($trip->total_price, 2) }} جنيه</td>
                                <td>
                                    @if(optional($trip->detail)->image)
                                        <button type="button" class="btn btn-info btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#paymentModal{{ $trip->id }}">
                                            عرض الإيصال
                                        </button>

                                        <div class="modal fade" id="paymentModal{{ $trip->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">إيصال الدفع</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <img src="{{ Storage::url($trip->detail->image) }}" class="img-fluid" alt="إيصال الدفع">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <a href="{{ route('trips.downloadPDF', $trip->id) }}" class="btn btn-danger btn-sm mt-1">تنزيل PDF</a>
                                    @else
                                        <span class="text-danger">لا يوجد إيصال</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
