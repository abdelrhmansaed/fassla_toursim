@extends('Dashboard.layouts.master')

    <style>
        .trip-form-container {
            padding: 20px;
            background: #fff;
            border-radius: 8px;
        }
        .form-section {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f0f0f0;
        }
        .form-section h5 {
            color: #3b4863;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
        }
        .form-control {
            border-radius: 6px;
            border: 1px solid #ddd;
            padding: 10px 15px;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #6576ff;
            box-shadow: 0 0 0 2px rgba(101, 118, 255, 0.1);
        }
        .btn-primary {
            background-color: #6576ff;
            border-color: #6576ff;
            padding: 10px 25px;
            border-radius: 6px;
            font-weight: 500;
        }
        .price-inputs {
            display: flex;
            gap: 20px;
        }
        .price-inputs .form-group {
            flex: 1;
        }
        .sub-trip-section {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            margin-top: 15px;
            border-left: 3px solid #6576ff;
        }
        .sub-trip-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }
        .btn-add-subtrip {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
        }
        .btn-remove-subtrip {
            background: #f44336;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
        }
    </style>

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الرحلات</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تعديل رحلة</span>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-header">
                    <h5 class="card-title">تعديل رحلة: {{ $tripType->type }}</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('trips.update', $tripType->id) }}" autocomplete="off">
                        @method('PUT')
                        @csrf

                        <div class="form-section">
                            <h5>المعلومات الأساسية</h5>

                            <div class="form-group">
                                <label for="provider_id">مزود الخدمة</label>
                                <select name="provider_id" id="provider_id" class="form-control" required>
                                    @foreach($providers as $provider)
                                        <option value="{{ $provider->id }}" {{ $tripType->user_id == $provider->id ? 'selected' : '' }}>
                                            {{ $provider->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="type">نوع الرحلة الرئيسية</label>
                                <input type="text" id="type" name="type" class="form-control"
                                       value="{{ $tripType->type }}" required>
                            </div>
                        </div>

                        <div class="form-section">
                            <h5>أسعار الرحلة الرئيسية</h5>
                            <div class="price-inputs">
                                <div class="form-group">
                                    <label for="adult_price">سعر البالغ (ر.س)</label>
                                    <input type="number" step="0.01" id="adult_price" name="adult_price"
                                           class="form-control" value="{{ $tripType->adult_price }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="child_price">سعر الطفل (ر.س)</label>
                                    <input type="number" step="0.01" id="child_price" name="child_price"
                                           class="form-control" value="{{ $tripType->child_price }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h5>الرحلات الفرعية</h5>
                            <div id="sub-trips-container">
                                @foreach($tripType->subTripTypes as $index => $subTrip)
                                    <div class="sub-trip-section" data-index="{{ $index }}">
                                        <input type="hidden" name="sub_trips[{{ $index }}][id]" value="{{ $subTrip->id }}">
                                        <div class="form-group">
                                            <label>نوع الرحلة الفرعية</label>
                                            <input type="text" name="sub_trips[{{ $index }}][type]"
                                                   class="form-control" value="{{ $subTrip->type }}" required>
                                        </div>
                                        <div class="price-inputs">
                                            <div class="form-group">
                                                <label>سعر البالغ (ر.س)</label>
                                                <input type="number" step="0.01"
                                                       name="sub_trips[{{ $index }}][adult_price]"
                                                       class="form-control" value="{{ $subTrip->adult_price }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label>سعر الطفل (ر.س)</label>
                                                <input type="number" step="0.01"
                                                       name="sub_trips[{{ $index }}][child_price]"
                                                       class="form-control" value="{{ $subTrip->child_price }}" required>
                                            </div>
                                        </div>
                                        <div class="sub-trip-actions">
                                            <button type="button" class="btn btn-remove-subtrip remove-subtrip">
                                                حذف
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" id="add-subtrip" class="btn btn-add-subtrip mt-3">
                                إضافة رحلة فرعية جديدة
                            </button>
                        </div>

                        <div class="form-group text-right mt-4">
                            <button type="submit" class="btn btn-primary">
                                حفظ التعديلات
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
        let subTripIndex = {{ $tripType->subTripTypes->count() }};

        // إضافة رحلة فرعية جديدة
        document.getElementById('add-subtrip').addEventListener('click', function() {
            const container = document.getElementById('sub-trips-container');
            const newIndex = subTripIndex++;

            const html = `
                <div class="sub-trip-section" data-index="${newIndex}">
                    <div class="form-group">
                        <label>نوع الرحلة الفرعية</label>
                        <input type="text" name="sub_trips[${newIndex}][type]" class="form-control" required>
                    </div>
                    <div class="price-inputs">
                        <div class="form-group">
                            <label>سعر البالغ (ر.س)</label>
                            <input type="number" step="0.01" name="sub_trips[${newIndex}][adult_price]" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>سعر الطفل (ر.س)</label>
                            <input type="number" step="0.01" name="sub_trips[${newIndex}][child_price]" class="form-control" required>
                        </div>
                    </div>
                    <div class="sub-trip-actions">
                        <button type="button" class="btn btn-remove-subtrip remove-subtrip">
                            حذف
                        </button>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', html);
        });

        // حذف رحلة فرعية
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-subtrip')) {
                e.target.closest('.sub-trip-section').remove();
            }
        });
    </script>
@endsection
