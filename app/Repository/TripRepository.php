<?php

namespace App\Repository;

use App\Models\Agent;
use App\Models\FileNumber;
use App\Models\Provider;
use App\Models\SubTripType;
use App\Models\Trip;
use App\Models\TripType;
use App\Models\User;
use App\Notifications\TripAccepted;
use App\Notifications\TripRequested;
use App\Repository\TripRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TripRepository implements TripRepositoryInterface
{

    public function index()
    {
        $providers = User::where('role', 'provider')->get();
        $trips = TripType::whereIn('user_id', $providers->pluck('id'))->get();
        $fileNumbers = FileNumber::all();
        $currentYear = now()->year;
        $currentMonth = now()->month;

// توليد التواريخ من الشهر الحالي وحتى 5 أشهر قادمة
        $available_dates = [];
        for ($i = 0; $i < 12; $i++) {
            // حساب السنة والشهر الحاليين بناءً على الدورة
            $month = ($currentMonth + $i) > 12 ? ($currentMonth + $i) - 12 : ($currentMonth + $i);
            $year = ($currentMonth + $i) > 12 ? $currentYear + 1 : $currentYear;

            // حساب عدد الأيام في الشهر الحالي
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            // توليد التواريخ لهذا الشهر
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $available_dates[] = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
            }
        }


        $available_times = [];

        for ($hour = 0; $hour < 24; $hour++) {
            // تحديد الصيغة AM/PM
            $period = ($hour < 12) ? 'AM' : 'PM';
            $displayHour = ($hour % 12 == 0) ? 12 : $hour % 12; // تحويل 0 إلى 12
            for ($minute = 0; $minute < 60; $minute += 30) { // عرض الأوقات كل 30 دقيقة
                $formattedTime = sprintf("%02d:%02d %s", $displayHour, $minute, $period);
                $available_times[] = $formattedTime;
            }
        }
        $exchangeRates = [
            'USD' => 50, // الدولار = 30 جنيه مصري (كمثال)
            'EUR' => 55, // اليورو = 32 جنيه مصري (كمثال)
            'EGP' => 1   // الجنيه المصري = 1 جنيه
        ];
        $tripTypes = ['رحلة بحرية', 'رحلة جبلية', 'رحلة تاريخية', 'رحلة سفاري']; // مثال على الأنواع

        return view('Pages.Trips.index',compact('trips', 'exchangeRates','available_times','available_dates','providers','tripTypes','fileNumbers')) ;
    }
    public function getSubTripTypes($tripId)
    {
        $subTrips = SubTripType::where('trip_type_id', $tripId)->get();

        // تأكد من وجود بيانات قبل الإرجاع
        if ($subTrips->isEmpty()) {
            return response()->json(['message' => 'لا توجد أنواع فرعية لهذه الرحلة.'], 404);
        }

        return response()->json($subTrips);
    }

    public function create()
    {
        $providers = User::where('role', 'provider')->get();

        return view('Pages.Trips.add',compact('providers')) ;
    }

    public function store(Request $request)
    {
        try {
            // التحقق من صحة المدخلات
            $request->validate([
                'provider_id' => 'required|exists:users,id',
                'trip_types' => 'required|array',
                'trip_types.*.type' => 'required|string',
                'trip_types.*.adult_price' => 'required|numeric',
                'trip_types.*.child_price' => 'required|numeric',
                'trip_types.*.sub_trip_types' => 'nullable|array',
                'trip_types.*.sub_trip_types.*.type' => 'nullable|string',
                'trip_types.*.sub_trip_types.*.adult_price' => 'nullable|numeric',
                'trip_types.*.sub_trip_types.*.child_price' => 'nullable|numeric',
            ]);

            // التحقق إذا كان المستخدم اختار مزود موجود أو أضاف جديد
            $provider_id = $request->provider_id;

            // إذا اختار إضافة مزود جديد
            if ($request->new_provider_name) {
                $provider = User::create([
                    'name' => $request->new_provider_name,
                    'email' => $request->new_provider_email,
                    'password' => bcrypt('password'), // يمكنك تغييره أو توليده تلقائي
                    'role' => 'provider',
                ]);

                $provider_id = $provider->id;
            }

            // نمر على كل نوع رئيسي ونسجله
            foreach ($request->trip_types as $index => $mainTrip) {
                $tripType = TripType::create([
                    'type' => $mainTrip['type'],
                    'adult_price' => $mainTrip['adult_price'],
                    'child_price' => $mainTrip['child_price'],
                    'user_id' => $provider_id, // ربط النوع بالمزود
                ]);

                // الأنواع الفرعية لهذا النوع الرئيسي
                if (isset($mainTrip['sub_trip_types'])) {
                    foreach ($mainTrip['sub_trip_types'] as $sub) {
                        SubTripType::create([
                            'type' => $sub['type'],
                            'adult_price' => $sub['adult_price'],
                            'child_price' => $sub['child_price'],
                            'trip_type_id' => $tripType->id, // ربط النوع الفرعي بالنوع الرئيسي
                        ]);
                    }
                }
            }

            toastr()->success('تم إضافة الرحلة بنجاح');
            return redirect()->route('trips.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $tripType = TripType::with(['subTripTypes'])->findOrFail($id);
        $providers = User::where('role', 'provider')->get();
        return view('pages.Trips.edit', compact('tripType', 'providers'));
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'provider_id' => 'required|exists:users,id',
                'type' => 'required|string',
                'adult_price' => 'required|numeric',
                'child_price' => 'required|numeric',
                'sub_trips' => 'nullable|array',
                'sub_trips.*.id' => 'nullable|exists:sub_trip_types,id',
                'sub_trips.*.type' => 'nullable|string',
                'sub_trips.*.adult_price' => 'nullable|numeric',
                'sub_trips.*.child_price' => 'nullable|numeric',
            ]);

            // تحديث الرحلة الرئيسية
            $tripType = TripType::findOrFail($id);
            $tripType->update([
                'user_id' => $request->provider_id,
                'type' => $request->type,
                'adult_price' => $request->adult_price,
                'child_price' => $request->child_price,
            ]);

            // معالجة الأنواع الفرعية
            if ($request->has('sub_trips')) {
                foreach ($request->sub_trips as $subTripData) {
                    if (isset($subTripData['id'])) {
                        // تحديث النوع الفرعي الموجود
                        $subTrip = SubTripType::find($subTripData['id']);
                        if ($subTrip) {
                            $subTrip->update([
                                'type' => $subTripData['type'],
                                'adult_price' => $subTripData['adult_price'],
                                'child_price' => $subTripData['child_price'],
                            ]);
                        }
                    } else {
                        // إنشاء نوع فرعي جديد
                        SubTripType::create([
                            'trip_type_id' => $tripType->id,
                            'type' => $subTripData['type'],
                            'adult_price' => $subTripData['adult_price'],
                            'child_price' => $subTripData['child_price'],
                        ]);
                    }
                }
            }

            toastr()->success('تم تحديث الرحلة بنجاح');
            return redirect()->route('trips.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $tripType = TripType::with(['subTripTypes'])->findOrFail($id);

            // حذف الرحلات الفرعية واحدة واحدة

            // حذف الرحلة الرئيسية
            $tripType->delete();

            DB::commit();

            toastr()->success('تم حذف الرحلة وجميع أنواعها الفرعية بنجاح');
            return redirect()->route('trips.index');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'حدث خطأ أثناء الحذف: ' . $e->getMessage()]);
        }
    }





}
