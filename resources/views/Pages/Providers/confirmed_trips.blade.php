@extends('Dashboard.layouts.master')
@section('content')
    <div class="container py-4">
        <div class="card shadow-lg">
            <div class="card-header bg-success text-white text-center">
                <h2>الرحلات المقبولة</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center">
                        <thead class="bg-dark text-white">
                        <tr>
                            <th>رقم الطلب</th>
                            <th>اسم الفندق</th>
                            <th>الرحلة</th>
                            <th>المندوب</th>
                            <th>عدد الأشخاص</th>
                            <th>إجمالي السعر</th>
                            <th>تاريخ الحجز</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($trips as $trip)
                            <tr>
                                <td>{{ optional($trip->tripRequest)->booking_number ?? 'غير متاح' }}</td>
                                <td>{{ optional($trip->tripRequest)->hotel_name ?? 'غير متاح' }}</td>
                                <td>{{ optional($trip->trip)->name ?? 'غير متاح' }}</td>
                                <td>{{ optional($trip->tripRequest->agent)->name ?? 'غير متاح' }}</td>
                                <td>{{ $trip->total_people }}</td>
                                <td class="fw-bold text-success">{{ number_format($trip->total_price, 2) }} جنيه</td>
                                <td>{{ $trip->booking_datetime }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
