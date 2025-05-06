
@extends('Dashboard.layouts.master')

@section('content')


    <div class="container py-4">
        <div class="card shadow-lg">
            <div class="card-header bg-danger text-white text-center">
                <h2>الرحلات المرفوضة</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center">
                        <thead class="bg-dark text-white">
                        <tr>
                            <th>تاريخ الحجز</th>
                            <th>رقم الطلب</th>
                            <th>اسم الفندق</th>
                            <th>الرحلة</th>
                            <th>المزود</th>
                            <th>عدد الأشخاص</th>
                            <th>اخمالي سعر البيع بالدولار</th>
                            <th>اخمالي سعر البيع باليورو</th>
                            <th>اخمالي سعر البيع بالمصري</th>
                            <th>إجمالي السعر</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($trips as $trip)
                            @foreach($trip->details as $detail)

                                <tr>
                                    <td> {{ \Carbon\Carbon::parse($trip->booking_datetime)->format('d/m/Y g:i A') ?? 'غير متاح' }}</td>

                                    <td>{{ optional($trip)->receipt_number ?? 'غير متاح' }}</td>

                                    <td>{{ optional($trip)->hotel_name ?? 'غير متاح' }}</td>
                                    <td>
                                        {{ optional($detail->tripType)->type ?? 'غير متاح' }}
                                        @if($detail->subTripType)
                                            - {{ optional($detail->subTripType)->type ?? 'غير متاح'}}
                                        @endif
                                    </td>
                                    <td>{{ optional($detail->provider)->name ?? 'غير متاح' }}</td>
                                    <td>{{ $detail->total_people }}</td>
                                    <td>{{ number_format($detail->total_price_usd, 2) }} </td>
                                    <td>{{ number_format($detail->total_price_eur, 2) }} </td>
                                    <td>{{ number_format($detail->total_price_egp, 2) }} </td>
                                    <td class="fw-bold text-success">{{ number_format($detail->total_price, 2) }} جنيه</td>
                                </tr>
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
@endsection


