@extends('Dashboard.layouts.master')

<style>
    .trip-section {
        border: 1px solid #e0e6ed;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        background: #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .trip-section h5 {
        color: #3b4863;
        border-bottom: 1px solid #e0e6ed;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }
    .sub-trip-section {
        border-left: 3px solid #6576ff;
        padding-left: 15px;
        margin: 15px 0;
        background: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
    }
    .form-control {
        border-radius: 5px;
    }
    .btn-add {
        background: #6576ff;
        color: white;
        border-radius: 5px;
        padding: 8px 15px;
    }
    .btn-add:hover {
        background: #4a5acf;
        color: white;
    }
    .btn-remove {
        background: #f44336;
        color: white;
        border-radius: 5px;
        padding: 8px 15px;
    }
    .btn-remove:hover {
        background: #d32f2f;
        color: white;
    }
    .price-inputs {
        display: flex;
        gap: 15px;
    }
    .price-inputs .form-group {
        flex: 1;
    }
    .provider-selector {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .provider-selector select {
        flex: 1;
    }
    .section-title {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">اضافة رحلة</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0"></span>
            </div>
        </div>

    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-header">
                    <h5 class="card-title">إضافة رحلة جديدة</h5>
                </div>
                <div class="card-body">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h5>حدث خطأ!</h5>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="post" action="{{route('trips.store')}}" autocomplete="off">
                        @csrf

                        <!-- اختيار مزود الخدمة -->
                        <div class="form-group mb-4">
                            <label for="provider_id" class="mb-2"><strong>مزود الخدمة</strong></label>
                            <div class="provider-selector">
                                <select name="provider_id" id="provider_id" class="form-control select2" required>
                                    <option value="" disabled selected>اختر مزود الخدمة</option>
                                    @foreach($providers as $provider)
                                        <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                                    @endforeach
                                </select>
                                <a href="{{ route('providers.create') }}" class="btn btn-add">
                                    <i class="fas fa-plus"></i> إضافة مزود جديد
                                </a>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="mb-3"><strong>أنواع الرحلات</strong></h5>
                            <div id="trip-type-container"></div>
                            <button type="button" id="add-trip-type" class="btn btn-add">
                                <i class="fas fa-plus"></i> إضافة نوع رئيسي
                            </button>
                        </div>

                        <div class="form-group text-center mt-4">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save"></i> حفظ البيانات
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        let tripTypeIndex = 0;

        document.getElementById('add-trip-type').addEventListener('click', function () {
            let container = document.getElementById('trip-type-container');

            let html = `
                <div class="trip-section" id="trip-type-${tripTypeIndex}">
                    <div class="section-title">
                        <h5><i class="fas fa-map-marked-alt"></i> نوع الرحلة الرئيسي #${tripTypeIndex + 1}</h5>
                        <button type="button" class="btn btn-sm btn-remove remove-trip-type">
                            <i class="fas fa-trash"></i> حذف
                        </button>
                    </div>

                    <div class="form-group">
                        <label>اسم النوع الرئيسي</label>
                        <input type="text" name="trip_types[${tripTypeIndex}][type]"
                               class="form-control" placeholder="مثال: رحلة يومية، رحلة لمدة أسبوع..." required>
                    </div>

                    <div class="price-inputs">
                        <div class="form-group">
                            <label>سعر البالغ (ر.س)</label>
                            <input type="number" step="0.01" name="trip_types[${tripTypeIndex}][adult_price]"
                                   class="form-control" placeholder="0.00" required>
                        </div>
                        <div class="form-group">
                            <label>سعر الطفل (ر.س)</label>
                            <input type="number" step="0.01" name="trip_types[${tripTypeIndex}][child_price]"
                                   class="form-control" placeholder="0.00" required>
                        </div>
                    </div>

                    <div class="sub-trip-types mt-4"></div>

                    <button type="button" class="btn btn-sm btn-add add-sub-trip" data-index="${tripTypeIndex}">
                        <i class="fas fa-plus"></i> إضافة نوع فرعي
                    </button>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', html);
            tripTypeIndex++;
        });

        document.addEventListener('click', function (e) {
            // إضافة نوع فرعي
            if (e.target && (e.target.classList.contains('add-sub-trip') || e.target.closest('.add-sub-trip'))) {
                let btn = e.target.classList.contains('add-sub-trip') ? e.target : e.target.closest('.add-sub-trip');
                let parentIndex = btn.getAttribute('data-index');
                let subContainer = btn.previousElementSibling;
                let subIndex = subContainer.children.length;

                let html = `
                    <div class="sub-trip-section" id="sub-trip-${parentIndex}-${subIndex}">
                        <div class="section-title">
                            <h6><i class="fas fa-map-marker-alt"></i> النوع الفرعي #${subIndex + 1}</h6>
                            <button type="button" class="btn btn-sm btn-remove remove-sub-trip">
                                <i class="fas fa-trash"></i> حذف
                            </button>
                        </div>

                        <div class="form-group">
                            <label>اسم النوع الفرعي</label>
                            <input type="text" name="trip_types[${parentIndex}][sub_trip_types][${subIndex}][type]"
                                   class="form-control" placeholder="مثال: غرفة عادية، جناح فاخر..." required>
                        </div>

                        <div class="price-inputs">
                            <div class="form-group">
                                <label>سعر البالغ (ر.س)</label>
                                <input type="number" step="0.01" name="trip_types[${parentIndex}][sub_trip_types][${subIndex}][adult_price]"
                                       class="form-control" placeholder="0.00" required>
                            </div>
                            <div class="form-group">
                                <label>سعر الطفل (ر.س)</label>
                                <input type="number" step="0.01" name="trip_types[${parentIndex}][sub_trip_types][${subIndex}][child_price]"
                                       class="form-control" placeholder="0.00" required>
                            </div>
                        </div>
                    </div>
                `;

                subContainer.insertAdjacentHTML('beforeend', html);
            }

            // حذف نوع رئيسي
            if (e.target && (e.target.classList.contains('remove-trip-type') || e.target.closest('.remove-trip-type'))) {
                let tripTypeElement = e.target.closest('.trip-section');
                tripTypeElement.remove();
            }

            // حذف نوع فرعي
            if (e.target && (e.target.classList.contains('remove-sub-trip') || e.target.closest('.remove-sub-trip'))) {
                let subTripElement = e.target.closest('.sub-trip-section');
                subTripElement.remove();
            }
        });

        // Initialize select2 if available
        if ($().select2) {
            $('#provider_id').select2({
                placeholder: "اختر مزود الخدمة",
                allowClear: true
            });
        }
    </script>
@endsection
