
@extends('Dashboard.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{trans('dashboard/main_trans.agents')}}</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0"></span>
            </div>
        </div>

    </div>


@endsection
@section('content')


    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <div class="col-xl-12 mb-30">
                        <div class="card card-statistics h-100">
                            <div class="card-body">

                                <div class="table-responsive">
                                    <table id="datatable" class="table  table-hover table-sm table-bordered p-0"
                                           data-page-length="50" style="text-align: center">
                                        <thead>
                                        <tr class="alert-success">

                                       <th> رقم الملف</th>
                                       <th>الحالة</th>
                                       <th>تفاصيل الطلب</th>
                                   </tr>
                                   </thead>
                                   <tbody>
                                   @foreach($requests as $request)
                                                <tr>
                                                    <td>

                                                            {{ $request->receipt_number?? 'غير متاح' }}<br>

                                                    </td>
                             <td>
                                 @if($request->details->contains('status', 'pending'))
                                     <span class="badge bg-warning">قيد الانتظار</span>
                                 @elseif($request->details->contains('status', 'confirmed'))
                                     <span class="badge bg-success">مقبولة</span>
                                 @else
                                     <span class="badge bg-danger">مرفوضة</span>
                                 @endif

                             </td>
                                 <td>
                                     <a href="{{ route('trips.details', $request->id) }}" class="btn btn-primary">
                                         تفاصيل الرحلة
                                     </a>

                                     <a href="{{ route('agent.cancelTrip', $request->id) }}" class="btn btn-danger">
                                         الغاء الرحلة
                                     </a>
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
            </div>
        </div>
    </div>

    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
@endsection

