<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\CurrencyConverter;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TripRequest;
use App\Models\TripRequestDetail;
use App\Models\TripType;
use App\Models\SubTripType;
use App\Models\FileNumber;
class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        // الإحصائيات العامة
        $totalTrips = TripRequest::count();
        $confirmedTrips = TripRequestDetail::where('status', 'confirmed')->count();
        $agentsCount = User::where('role', 'agent')->count();
        $providersCount = User::where('role', 'provider')->count();
        $mainTypesCount = TripType::count();
        $subTypesCount = SubTripType::count();
        $totalTrips = TripRequestDetail::count();
        $confirmedTrips = TripRequestDetail::where('status', 'confirmed')->count();
        $canceledTrips = TripRequestDetail::where('status', 'canceled')->count();
        $pendingTrips = TripRequestDetail::where('status', 'pending')->count();
        $waitingPaymentTrips = TripRequestDetail::where('status', 'waiting_payment')->count();
        $filter = $request->input('range', 'monthly'); // الافتراضي شهري

        $tripDetails = TripRequestDetail::where('status', 'confirmed')
            ->with('tripRequest')
            ->get();

        // تجميع حسب الفلتر المختار
        $grouped = match ($filter) {
            'weekly' => $tripDetails->groupBy(function ($item) {
                return \Carbon\Carbon::parse($item->booking_datetime)->startOfWeek()->format('Y-m-d');
            }),
            'yearly' => $tripDetails->groupBy(function ($item) {
                return \Carbon\Carbon::parse($item->booking_datetime)->format('Y');
            }),
            default => $tripDetails->groupBy(function ($item) {
                return \Carbon\Carbon::parse($item->booking_datetime)->format('Y-m');
            }),
        };
        $statusFilter = $request->input('status_range', 'monthly');

        $tripStatusDetails = TripRequestDetail::with('tripRequest')->get();

// نجمع حسب الفلتر المختار
        $tripStatusGrouped = match ($statusFilter) {
            'weekly' => $tripStatusDetails->groupBy(function ($item) {
                return \Carbon\Carbon::parse($item->booking_datetime)->startOfWeek()->format('Y-m-d');
            }),
            'yearly' => $tripStatusDetails->groupBy(function ($item) {
                return \Carbon\Carbon::parse($item->booking_datetime)->format('Y');
            }),
            default => $tripStatusDetails->groupBy(function ($item) {
                return \Carbon\Carbon::parse($item->booking_datetime)->format('Y-m');
            }),
        };

// بنحسب إجمالي الحالات
        $tripStatusData = [
            'confirmed' => 0,
            'canceled' => 0,
            'pending' => 0,
            'waiting_payment' => 0,
        ];

        foreach ($tripStatusGrouped as $period => $items) {
            foreach ($items as $item) {
                $status = $item->status;
                if (isset($tripStatusData[$status])) {
                    $tripStatusData[$status]++;
                }
            }
        }

        $reportData = [];

        foreach ($grouped as $period => $details) {
            $totalSale = $totalCost = $totalCommission = $totalDiscount = 0;

            foreach ($details as $detail) {
                $requestData = $detail->tripRequest;
                $currency = $requestData->currency;
                $discount = $requestData->discount ?? 0;

                $convertedPriceEGP = $detail->converted_total_price_egp;

                $discountInEGP = match($currency) {
                    'egp' => $discount,
                    'usd' => CurrencyConverter::convertToEGP($discount, 'USD'),
                    'eur' => CurrencyConverter::convertToEGP($discount, 'EUR'),
                    default => 0
                };

                $totalSale += $convertedPriceEGP;
                $totalCost += $detail->total_price;
                $totalCommission += $detail->commission_value;
                $totalDiscount += $discountInEGP;
            }

            $profit = $totalSale - ($totalCost + $totalCommission + $totalDiscount);

            $reportData[] = [
                'period' => $period,
                'sale' => round($totalSale, 2),
                'cost' => round($totalCost, 2),
                'commission' => round($totalCommission, 2),
                'discount' => round($totalDiscount, 2),
                'profit' => round($profit, 2),
            ];
        }
        $agents = User::where('role', 'agent')->get();
        $providers = User::where('role', 'provider')->get();

        return view('Dashboard.admin.index', compact(
            'totalTrips',
            'confirmedTrips',
            'agentsCount',
            'providersCount',
            'mainTypesCount',
            'subTypesCount',
            'confirmedTrips',
            'canceledTrips',
            'pendingTrips',
            'waitingPaymentTrips',
            'reportData',
            'filter',
            'tripStatusData',
            'statusFilter',
             'agents',
            'providers'
        ));
    }
    public function showAgentProfile($id)
    {
        $user = User::where('role', 'agent')->findOrFail($id);
        return $this->buildUserProfile($user);
    }

    // عرض بروفايل المزود
    public function showProviderProfile($id)
    {
        $user = User::where('role', 'provider')->findOrFail($id);
        return $this->buildUserProfile($user);
    }


    private function buildUserProfile(User $user)
    {
        $tripsQuery = TripRequestDetail::whereHas('tripRequest', function ($q) use ($user) {
            if ($user->role === 'agent') {
                $q->where('agent_id', $user->id);
            } elseif ($user->role === 'provider') {
                $q->where('provider_id', $user->id);
            }
        });

        $trips = $tripsQuery->with(['tripRequest', 'tripType'])->latest()->paginate(10);

        $stats = [
            'total' => $tripsQuery->count(),
            'confirmed' => (clone $tripsQuery)->where('status', 'confirmed')->count(),
            'canceled' => (clone $tripsQuery)->where('status', 'canceled')->count(),
            'profit' => 0,
        ];

        if ($user->role === 'agent') {
            $confirmedTrips = (clone $tripsQuery)->where('status', 'confirmed')->get();

            foreach ($confirmedTrips as $trip) {
                $requestData = $trip->tripRequest;
                $currency = $requestData->currency;
                $discount = $requestData->discount ?? 0;

                $discountInEGP = match ($currency) {
                    'egp' => $discount,
                    'usd' => CurrencyConverter::convertToEGP($discount, 'USD'),
                    'eur' => CurrencyConverter::convertToEGP($discount, 'EUR'),
                    default => 0
                };

                $stats['profit'] += $trip->converted_total_price_egp - (
                        $trip->total_price + $trip->commission_value + $discountInEGP
                    );
            }
        }

        return view('Pages.Users.profile', compact('user', 'trips', 'stats'));
    }


}
