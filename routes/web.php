<?php

use App\Http\Controllers\Agents\AgentController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Dashboard\AdminDashboardController;
use App\Http\Controllers\FileNumber\FileNumberController;
use App\Http\Controllers\Notifications\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\providers\ProviderController;
use App\Http\Controllers\Reports\ReportController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\Transactions\TransactionReportController;
use App\Http\Controllers\Trips\TripController;
use App\Http\Controllers\Users\UserController;
use App\Services\CurrencyConverter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\Requestes\TripRequestController;
//Route::get('/', function () {
//    return view('welcome');
//});
//

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
    ], function(){
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('auth.login');
    });

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// تسجيل خروج لكل مستخدم (Admin, Provider, Agent)
    Route::post('/admin/logout', [AuthenticatedSessionController::class, 'destroy'])->name('admin.logout');
    Route::post('/provider/logout', [AuthenticatedSessionController::class, 'destroy'])->name('provider.logout');
    Route::post('/agent/logout', [AuthenticatedSessionController::class, 'destroy'])->name('agent.logout');


    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/admin/roles', [RolePermissionController::class, 'index'])->name('roles.index');
        Route::post('/admin/roles/{role}', [RolePermissionController::class, 'update'])->name('roles.update');
        Route::get('/admin/roles/create', [RolePermissionController::class, 'create'])->name('roles.create');
        Route::post('/admin/roles', [RolePermissionController::class, 'store'])->name('roles.store');
        Route::get('users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('users', [UserController::class, 'store'])->name('users.store');
        Route::get('admin/agents/{id}/profile', [AdminDashboardController::class, 'showAgentProfile'])->name('admin.agents.profile');
        Route::get('admin/providers/{id}/profile', [AdminDashboardController::class, 'showProviderProfile'])->name('admin.providers.profile');

        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
            ->name('admin.dashboard');
    });

    Route::middleware(['auth', 'role:provider'])->group(function () {
        Route::get('/provider/requests', [ProviderController::class, 'tripRequests'])->name('provider.requests');

        Route::get('/provider/dashboard', function () {
            return view('Dashboard.provider.index');
        })->name('provider.dashboard');
    });

    Route::middleware(['auth', 'role:agent'])->group(function () {
        Route::get('/trips/request/{trip_id}', [AgentController::class, 'requestTrip'])->name('trip.request');
        Route::post('/agent/requests/store', [AgentController::class, 'storeTripRequest'])->name('agent.storeTripRequest');
        Route::get('/dashboard', )->name('dashboard');

        Route::get('/agent/dashboard',[AgentController::class, 'dashboard'])->name('agent.dashboard');
    });

    Route::middleware('auth.multi')->group(function () {
        Route::resource('agents', AgentController::class);
        Route::resource('providers', ProviderController::class);
        Route::resource('trips', TripController::class)->except(['show']);

        Route::post('/trip/accept/{trip_id}/{agent_id}', [TripController::class, 'acceptTrip'])->name('trip.accept');
        Route::get('/notifications', [NotificationController::class, 'fetch'])->name('notifications.fetch');
        Route::get('/notifications/all', [NotificationController::class, 'notifications_all'])->name('notifications.all');
        Route::get('/my-requests', [AgentController::class, 'myRequests'])->name('agent.requests');
        Route::patch('/provider/approve/{request_id}', [ProviderController::class, 'approveRequest'])->name('provider.approveRequest');
        Route::patch('/provider/requests/{request_id}/approve', [ProviderController::class, 'approveRequestWaitingPayment'])->name('provider.WaitingPayment');
        Route::patch('/provider/requests/{request_id}/reject', [ProviderController::class, 'rejectRequest'])->name('provider.rejectRequest');
        Route::get('/provider/confirmed-trips', [ProviderController::class, 'confirmedTrips'])->name('provider.confirmedTrips');
        Route::get('/provider/rejected-trips', [ProviderController::class, 'rejectedTrips'])->name('provider.rejectedTrips');
        Route::get('/agent/confirmed-trips', [AgentController::class, 'confirmedTrips'])->name('agent.confirmedTrips');
        Route::get('/agent/rejected-trips', [AgentController::class, 'rejectedTrips'])->name('agent.rejectedTrips');
        Route::get('/requests', [TripRequestController::class, 'tripRequests'])->name('requests');

        Route::get('/confirmed-trips', [TripRequestController::class, 'confirmedTrips'])->name('confirmedTrips');
        Route::get('/rejected-trips', [TripRequestController::class, 'rejectedTrips'])->name('rejectedTrips');


        Route::get('/trips/provider-approved', [TripRequestController::class, 'providerApprovedTrips'])->name('trips.providerApproved');
        Route::get('/provider/provider-approved', [ProviderController::class, 'providerApprovedTrips'])->name('provider.providerApproved');

        Route::post('/trips/upload-payment/{trip_id}', [TripRequestController::class, 'uploadPaymentProof'])->name('trips.uploadPayment');


        Route::get('/provider/WatingConfirm', [ProviderController::class, 'TripsWatingConfirm'])->name('WatingConfirm.requests');



        Route::get('/agent/bookings', [TripRequestController::class, 'showBookings'])->name('agent.bookings');
        Route::patch('/agent/requests/{request_id}/confirm', [AgentController::class, 'confirmTrip'])->name('agent.confirmTrip');
        Route::get('/agent/requests/{request_id}/cancel', [AgentController::class, 'cancelTrip'])->name('agent.cancelTrip');

        Route::get('/trips/details/{id}', [TripRequestController::class, 'showTripDetails'])->name('trips.details');



        Route::get('/trips/download-pdf/{id}', [TripController::class, 'downloadPDF'])->name('trips.downloadPDF');


        Route::get('/notifications/mark-as-read/{id}', [NotificationController::class, 'Read'])
            ->name('notifications.markAsRead');

        Route::get('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])
            ->name('notifications.markAllAsRead');

        Route::get('/agents/profile/{id}', [AgentController::class, 'showProfile'])->name('agents.profile');
        Route::get('/providers/profile/{id}', [ProviderController::class, 'showProfile'])->name('providers.profile');


        Route::post('/trips/pay/{tripRequestDetail}', [AgentController::class, 'pay'])->name('trips.pay');
        Route::get('/admin/transactions', [TransactionReportController::class, 'index'])->name('transactions.index');
        Route::get('/admin/transactions/export/pdf', [TransactionReportController::class, 'exportPDF'])->name('transactions.export.pdf');
        Route::get('/admin/transactions/export/excel', [TransactionReportController::class, 'exportExcel'])->name('transactions.export.excel');
        Route::get('/admin/transactions/ajax', [TransactionReportController::class, 'ajaxReport'])->name('transactions.ajaxReport');
        Route::get('/get-sub-trips/{tripId}', [TripController::class, 'getSubTripTypes']);
        Route::get('/file-numbers/create', [FileNumberController::class, 'create'])->name('file_numbers.create');
        Route::post('/file-numbers/store', [FileNumberController::class, 'store'])->name('file_numbers.store');

        Route::get('/reports/file', [ReportController::class, 'fileReport'])->name('reports.file');
        Route::get('/report/financing',[ReportController::class, 'RepotsFinance'])->name('reports.financing');

        Route::get('/transactions/pay', [TransactionReportController::class, 'createPayForm'])->name('transactions.pay.form');
        Route::post('/transactions/pay', [TransactionReportController::class, 'pay'])->name('transactions.pay');


        Route::get('/test-convert', function () {
            $usdToEgp = CurrencyConverter::convertToEGP(1, 'USD');
            $eurToEgp = CurrencyConverter::convertToEGP(1, 'EUR');
            return response()->json([
                'USD' => $usdToEgp,
                'EUR' => $eurToEgp,
            ]);
        });

    });

});


