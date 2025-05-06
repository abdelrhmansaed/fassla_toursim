
<style>
    /* تحسينات عامة */
    #tripRequestModal .modal-content {
        border-radius: 20px;
        overflow: hidden;
        border: none;
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
        background: #ffffff;
    }

    #tripRequestModal .modal-header {
        background: linear-gradient(135deg, #1a73e8, #0d47a1);
        color: white;
        padding: 20px 30px;
        border-bottom: none;
        position: relative;
    }

    #tripRequestModal .modal-header:after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #ff9800, #ff5722);
    }

    #tripRequestModal .modal-title {
        font-weight: 700;
        font-size: 1.8rem;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    #tripRequestModal .modal-body {
        padding: 30px;
        background-color: #f8fafc;
    }

    /* تحسينات الفورم */
    .form-label {
        font-weight: 800;
        color: #37474F;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-control, .form-select {
        border-radius: 12px;
        border: 1px solid #e0e0e0;
        transition: all 0.3s;
        box-shadow: none;
        font-size: 0.95rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: #2196F3;
        box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.2);
        transform: translateY(-2px);
    }
    .form-select option {
        padding: 12px 15px;
        font-size: 0.95rem;
        border-bottom: 1px solid #f0f0f0;
    }

    .form-select optgroup {
        font-weight: 700;
        color: #1a73e8;
        padding: 10px;
        background: #f5f9ff;
    }
    /* تحسينات أقسام الرحلات */
    .trip-details {
        background-color: white;
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.05);
        position: relative;
        transition: all 0.3s;
    }



    .trip-details h5 {
        color: #1a73e8;
        border-bottom: 2px solid #e3f2fd;
        padding-bottom: 12px;
        margin-bottom: 20px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.25rem;
    }

    /* قسم إجمالي الأسعار الجديد */
    .total-prices-section {
        background: linear-gradient(135deg, #f5f7fa, #e4e7eb);
        border-radius: 16px;
        padding: 20px;
        margin: 25px 0;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .total-prices-header {
        font-weight: 700;
        color: #1a73e8;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.1rem;
    }

    .currency-total {
        display: flex;
        align-items: center;
        margin-bottom: 12px;
        background: white;
        padding: 12px 15px;
        border-radius: 12px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
    }

    .currency-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 10px;
        font-size: 1.3rem;
        background: #f0f4f8;
    }

    .currency-label {
        font-weight: 600;
        color: #4a5568;
        flex: 1;
    }

    .currency-value {
        font-weight: 700;
        color: #2d3748;
        font-size: 1.1rem;
    }

    /* تحسينات للعرض على الجوال */
    @media (max-width: 768px) {
        #tripRequestModal .modal-dialog {
            margin: 10px;
        }

        #tripRequestModal .modal-body {
            padding: 20px;
        }

        .trip-details {
            padding: 20px;
        }

        .currency-total {
            flex-direction: column;
            align-items: flex-start;
        }

        .currency-icon {
            margin-left: 0;
            margin-bottom: 8px;
        }
    }
</style>

<div class="modal fade" id="tripRequestModal" tabindex="-1" aria-labelledby="tripRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="tripRequestModalLabel">طلب رحلة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form  id="tripRequestForm" action="{{ route('agent.storeTripRequest')}}" method="POST">

                    @csrf
                    <!-- رقم الإيصال -->
                    <div class="mb-3">
                        <label class="form-label">🧾 رقم الإيصال</label>
                        <input list="receipt_suggestions" id="receipt_number" class="form-control" required>
                        <input type="hidden" name="receipt_number" id="receipt_number_hidden"> <!-- الرقم الفعلي -->
                        <datalist id="receipt_suggestions"></datalist>
                        @error('receipt_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>



                    <script>
                        const receiptInput = document.getElementById('receipt_number');
                        const receiptHidden = document.getElementById('receipt_number_hidden');
                        const datalist = document.getElementById('receipt_suggestions');

                        const currentYear =  {{ now()->year }}; // السنة ثابتة
                        const allReceipts = Array.from({length: 5000}, (_, i) => (i + 1).toString());

                        // أول ما الصفحة تفتح: نحط أول رقم تلقائي


                        // لما المستخدم يكتب، نعرض له اقتراحات
                        receiptInput.addEventListener('input', function () {
                            const rawValue = this.value.replace(`/${currentYear}`, '').replace(/^0+/, '');
                            datalist.innerHTML = '';

                            if (rawValue.length === 0 || isNaN(rawValue)) return;

                            const suggestions = allReceipts
                                .filter(num => num.includes(rawValue))
                                .slice(0, 300); // عدد كبير من النتائج

                            suggestions.forEach(suggestion => {
                                const option = document.createElement('option');
                                option.value = `${suggestion}/${currentYear}`; // بدون أصفار
                                datalist.appendChild(option);
                            });
                        });

                        // عند تغيير القيمة
                        receiptInput.addEventListener('change', function () {
                            let val = this.value.replace(`/${currentYear}`, '').replace(/^0+/, '');
                            if (!val || isNaN(val)) return;

                            const formatted = `${val}/${currentYear}`;
                            this.value = formatted;
                            receiptHidden.value = `${val}/${currentYear}`;
                        });
                    </script>


                    <div class="mb-3">
                        <label class="form-label">🔢 رقم الملف</label>
                        <select name="booking_number" class="form-control @error('booking_number') is-invalid @enderror" required>
                            <option value="">اختر رقم الملف</option>
                            @foreach($fileNumbers as $file)
                                <option value="{{ $file->file_code }}">{{ $file->file_code }} ({{ $file->adult_limit }} بالغ / {{ $file->child_limit }} طفل)</option>
                            @endforeach
                        </select>
                        @error('booking_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>






                    <div class="mb-3">
                        <label class="form-label">🏨 اسم الفندق</label>
                        <select name="hotel_name" class="form-select w-50 custom-select-style @error('hotel_name') is-invalid @enderror" required>
                            <option value="">اختر الفندق</option>
                            <option value="المنتزة">المنتزة</option>
                            <option value="فايف ستار">فايف ستار</option>
                            <option value="فور ستار">فور ستار</option>
                        </select>
                        @error('hotel_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <style>
                        .custom-select-style {
                            border-radius: 10px;
                            box-shadow: 0 0 5px rgba(0,0,0,0.1);
                            padding: 10px;
                        }

                        .custom-select-style:focus {
                            border-color: #0d6efd;
                            box-shadow: 0 0 5px rgba(13, 110, 253, 0.5);
                        }
                    </style>





                    <div id="trip-details-container">
                        <div class="trip-details mb-3">
                            <h5>تفاصيل الرحلة</h5>
                            <!-- 📅 تاريخ ووقت الحجز -->
                            <div id="dates-container">
                                <div class="flex-grow-1 mb-2 date-select">
                                    <label class="form-label">📅 تاريخ الحجز</label>
                                    <select name="booking_datetime[]" class="form-control booking-select" required>
                                        <option value="" disabled selected>اختر التاريخ</option>
                                        @foreach($available_dates as $date)
                                            <option value="{{ $date }}">{{ $date }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            {{-- الرحلة الرئيسية --}}
                            <div class="mb-3">
                                <label class="form-label">📌 اختر مزود الخدمة</label>
                                <select id="provider_id"  name="trip_type_id[]"  class="form-control" required>

                                    <option value="">اختر مزود الخدمة</option>
                                    @foreach($providers as $provider)
                                        <optgroup label="{{ $provider->name }}">
                                            @foreach($trips as $trip)
                                                @if($trip->user_id == $provider->id)
                                                    <option
                                                        value="{{ $trip->id }}"
                                                        data-adult-price="{{ $trip->adult_price }}"
                                                        data-child-price="{{ $trip->child_price }}"
                                                        data-trip-type="{{ $trip->type }}"
                                                        data-provider-name="{{ $provider->name }}"
                                                        data-provider-id="{{ $provider->id }}"

                                                    >
                                                        {{ $trip->type }} - {{ $provider->name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                <input type="hidden" name="provider_id[]" class="provider-id-hidden">

                            </div>

                            <div class="mb-3">
                                <strong>📝 الرحلة المختارة:</strong>
                                <span id="selected-trip-info" class="text-info"></span>
                            </div>



                            <div class="row">


                                <div class="col-md-4 mb-3">
                                    <label class="form-label">🧑 عدد البالغين</label>
                                    <input type="number" name="adult_count[]" class="form-control @error('adult_count.*') is-invalid @enderror" required>
                                    @error('adult_count.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">👶 عدد الأطفال</label>
                                    <input type="number" name="children_count[]" class="form-control @error('children_count.*') is-invalid @enderror" required>
                                    @error('children_count.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">👥 عدد الأفراد</label>
                                    <input type="number" name="total_people[]" class="form-control @error('total_people.*') is-invalid @enderror" required readonly>
                                    @error('total_people.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <input type="hidden" class="adult-price" name="adult_price[]" value="0">
                                <input type="hidden" class="child-price" name="child_price[]" value="0">
                                <label class="form-label">💰 سعر التكلفة  </label>
                                <input type="text" name="total_price[]" class="form-control total-price" readonly>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">💵 سعر البيع بالجنيه المصري</label>
                                    <input type="number" name="price_egp[]" class="form-control @error('price_egp') is-invalid @enderror" required>
                                    @error('price_egp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">💵 سعر البيع بالدولار</label>
                                    <input type="number" name="price_usd[]" class="form-control @error('price_usd') is-invalid @enderror" required>
                                    @error('price_usd')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">💵 سعر البيع باليورو</label>
                                    <input type="number" name="price_eur[]" class="form-control @error('price_eur') is-invalid @enderror" required>
                                    @error('price_eur')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3" id="discount-container">
                                    <label class="form-label">💸 الخصم</label>
                                    <div class="input-group" style="max-width: 600px;">
                                        <input type="text" id="result" class="form-control" placeholder="أدخل قيمة الخصم"
                                               style="height: 40px; border-top-right-radius: 0; border-bottom-right-radius: 0;" name="discount_value[]">

                                        <select id="discount_currency" class="form-select"
                                                style="max-width: 400px; height: 40px; font-size: 1rem; font-weight: 500; border-top-left-radius: 0; border-bottom-left-radius: 0;" name="currency[]" required >
                                            <option value="egp">💴 جنيه</option>
                                            <option value="usd">💵 دولار</option>
                                            <option value="eur">💶 يورو</option>
                                        </select>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                    <!-- خصم مع اختيار العملة -->



                    <div class="mb-3">
                        <label class="form-label">حالة التحصيل</label>
                        <select name="payment_status" class="form-select" >
                            <option value="" disabled selected>اختر حالة التحصيل</option>
                            <option value="collected">تم التحصيل</option>
                            <option value="not_collected">لم يتم التحصيل</option>
                        </select>
                    </div>





                    <button type="button" id="add-trip-type-btn" class="btn btn-secondary mb-3">➕ طلب نوع رحلة أخرى</button>
                    <div class="alert alert-primary w-50 mx-auto text-center" id="total-trips-display">
                        ✈️ إجمالي الرحلات: 0 | 💰 السعر الإجمالي: 0 ريال
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-2">🚀 إرسال الطلب</button>
                </form>
            </div>
        </div>
    </div>
</div>


@section('js')


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).on("change", ".provider-select", function () {
            var container = $(this).closest('.trip-details');
            var selected = $(this).find(':selected');
            var tripId = selected.val();
            var tripType = selected.data('trip-type') || '';
            var providerName = selected.data('provider-name') || '';
            var providerId = selected.data('provider-id') || '';
            container.find('.provider-id-hidden').val(providerId);

            // حفظ id مزود الخدمة في الحقل المخفي
            container.find('.selected-trip-info')
                .removeClass('d-none')
                .text(`${tripType} - ${providerName}`);



            // قراءة السعر من الرحلة الرئيسية إذا لم يتم اختيار فرعي
            var adultPrice = parseFloat(selected.data('adult-price')) || 0;
            var childPrice = parseFloat(selected.data('child-price')) || 0;

            container.find('.adult-price').val(adultPrice);
            container.find('.child-price').val(childPrice);

            // تحديث السعر الإجمالي
            calculateTotalPrice(container);

            // جلب الرحلات الفرعية
            $.ajax({
                url: `/ar/get-sub-trips/${tripId}`,
                method: 'GET',
                success: function (response) {
                    var subTripSelect = container.find('.sub-trip-select');
                    subTripSelect.empty().append('<option value="">اختر نوع فرعي</option>');
                    $.each(response, function (i, subTrip) {
                        subTripSelect.append(`
                    <option value="${subTrip.id}" data-adult-price="${subTrip.adult_price}" data-child-price="${subTrip.child_price}">
                        ${subTrip.type} (بالغ: ${subTrip.adult_price} / طفل: ${subTrip.child_price})
                    </option>
                `);
                    });
                },
            });
        });
        $(document).on('change', '.sub-trip-select', function () {
            var container = $(this).closest('.trip-details');
            var selected = $(this).find(':selected');

            var adultPrice = parseFloat(selected.data('adult-price')) || 0;
            var childPrice = parseFloat(selected.data('child-price')) || 0;

            container.find('.adult-price').val(adultPrice);
            container.find('.child-price').val(childPrice);

            calculateTotalPrice(container);
        });
        function calculateTotalPrice(container) {
            var selectedSubTrip = container.find('.sub-trip-select option:selected');
            var subTripSelected = selectedSubTrip && selectedSubTrip.val();

            var adultPrice = 0;
            var childPrice = 0;

            if (subTripSelected) {
                adultPrice = parseFloat(selectedSubTrip.data('adult-price')) || 0;
                childPrice = parseFloat(selectedSubTrip.data('child-price')) || 0;
            } else {
                // استخدم أسعار الرحلة الرئيسية
                adultPrice = parseFloat(container.find(".adult-price").val()) || 0;
                childPrice = parseFloat(container.find(".child-price").val()) || 0;
            }

            var adults = parseInt(container.find("[name='adult_count[]']").val()) || 0;
            var children = parseInt(container.find("[name='children_count[]']").val()) || 0;

            var total = (adults * adultPrice) + (children * childPrice);
            container.find(".total-price").val(total.toFixed(2));

            // تحديث عدد الأفراد
            container.find("[name='total_people[]']").val(adults + children);
        }


    </script>
    <script>



        $(document).on("change", "#provider_id", function () {
            var selectedOption = $(this).find(":selected");

            var adultPrice = parseFloat(selectedOption.attr("data-adult-price")) || 0;
            var childPrice = parseFloat(selectedOption.attr("data-child-price")) || 0;
            var tripType = selectedOption.attr("data-trip-type") || "غير محدد";
            var providerName = selectedOption.closest("optgroup").attr("label") || "غير محدد";
            var providerId = selectedOption.attr("data-provider-id") || "";
            $(".provider-id-hidden").val(providerId);
            // تحديث الأسعار
            $(".adult-price").val(adultPrice);
            $(".child-price").val(childPrice);

            // عرض المعلومات المختارة
            $("#selected-provider-name").text(providerName);
            $("#selected-trip-type").text(tripType);

            // تحديث السعر الكلي حسب الأعداد المدخلة
            var container = $(this).closest(".trip-details");
            calculateTotalPrice(container);
        });

    </script>
    <script>
        $(document).ready(function() {

            $(".request-trip-btn").click(function() {
                var tripId = $(this).data("trip-id");
                var adultPrice = $(this).data("adult-price");
                var childPrice = $(this).data("child-price");





                $("#tripRequestModal").modal("show");
                var option = $('#provider_id option[value="' + tripId + '"]');

                if (option.length) {
                    // ✅ اختار الرحلة تلقائيًا
                    $('#provider_id').val(tripId).trigger('change');

                    // ✅ النص المعروض للرحلة
                    var tripText = option.text();

                    // ✅ أضف الأسعار داخل النص
                    var displayText = `${tripText} (البالغ: ${adultPrice} الطفل: ${childPrice} )`;

                    // ✅ عرض النص
                    $("#selected-trip-info").text(displayText);
                }
                updateTotalTripsCount();

            });







            // إذا كان هناك أي أخطاء في الفورم، أظهر المودال تلقائيًا
            @if ($errors->any())
            $("#tripRequestModal").modal("show");
            @endif
        });
        $("#add-trip-type-btn").click(function () {
            // إنشاء قائمة مزودي الخدمة مع الرحلات الخاصة بكل منهم
            var providerOptions = '';

            // إنشاء قائمة مزودي الخدمة مع الرحلات الخاصة بكل منهم
            @foreach($providers as $provider)
                providerOptions += `<optgroup label="{{ $provider->name }}">`;
            @foreach($trips as $trip)
                @if($trip->user_id == $provider->id)
                providerOptions += `
                    <option value="{{ $trip->id }}"
                            data-adult-price="{{ $trip->adult_price }}"
                            data-child-price="{{ $trip->child_price }}"
                            data-provider-id="{{ $provider->id }}"
                            data-provider-name="{{ $provider->name }}">
                        {{ $trip->type }} - {{ $provider->name }}
            </option>`;
            @endif
                @endforeach
                providerOptions += `</optgroup>`;
            @endforeach

            var tripDetailsHtml = `
    <div class="trip-details mb-3 border p-3 rounded shadow-sm">
        <h5>تفاصيل نوع رحلة إضافية</h5>
        <button type="button" class="btn btn-danger btn-sm remove-trip-btn mb-3">❌ حذف نوع الرحلة</button>
                               <div class="flex-grow-1">
                                    <label class="form-label">📅 تاريخ الحجز</label>
                                    <select name="booking_datetime[]" class="form-control @error('booking_datetime') is-invalid @enderror" required>
                                        <option value="" disabled selected>اختر التاريخ</option>
                                        @foreach($available_dates as $date)
            <option value="{{ $date }}">{{ $date }}</option>
                                        @endforeach
            </select>
                           @error('booking_datetime')
            <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
            </div>
<div class="mb-3">
<label class="form-label">📌 اختر نوع الرحلة الرئيسي</label>
<select class="form-control provider-select" name="trip_type_id[]">
<option value="">اختر نوع رئيسي</option>
${providerOptions}
            </select>
                        <input type="hidden" name="provider_id[]" class="provider-id-hidden">

        </div>

        <div class="mb-3">
            <label class="form-label">📌 اختر نوع فرعي</label>
            <select class="form-control sub-trip-select" name="sub_trip_type_id[]">
                <option value="">اختر نوع فرعي</option>
            </select>
        </div>
        <div class="mb-3 alert alert-info d-none selected-trip-info"></div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">🧑 عدد البالغين</label>
                <input type="number" name="adult_count[]" class="form-control" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">👶 عدد الأطفال</label>
                <input type="number" name="children_count[]" class="form-control" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">👥 عدد الأفراد</label>
                <input type="number" name="total_people[]" class="form-control" required readonly>
            </div>
        </div>

        <input type="hidden" class="adult-price" name="adult_price[]" value="0">
        <input type="hidden" class="child-price" name="child_price[]" value="0">

        <div class="mb-3">
            <label class="form-label">💰 السعر الكلي</label>
            <input type="text" name="total_price[]" class="form-control total-price" readonly>
        </div>
   <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">💵 سعر البيع بالجنيه المصري</label>
                                <input type="number" name="price_egp[]" class="form-control @error('price_egp') is-invalid @enderror" required>
                                @error('price_egp')
            <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">💵 سعر البيع بالدولار</label>
                <input type="number" name="price_usd[]" class="form-control @error('price_usd') is-invalid @enderror" required>
                                @error('price_usd')
            <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">💵 سعر البيع باليورو</label>
                <input type="number" name="price_eur[]" class="form-control @error('price_eur') is-invalid @enderror" required>
                                @error('price_eur')
            <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
            </div>
                                            <div class="mb-3" id="discount-container">
                                    <label class="form-label">💸 الخصم</label>
                                    <div class="input-group" style="max-width: 600px;">
                                        <input type="text" id="result" class="form-control" placeholder="أدخل قيمة الخصم"
                                               style="height: 40px; border-top-right-radius: 0; border-bottom-right-radius: 0;" name="discount_value[]">

                                        <select id="discount_currency" class="form-select"
                                                style="max-width: 400px; height: 40px; font-size: 1rem; font-weight: 500; border-top-left-radius: 0; border-bottom-left-radius: 0;" name="currency[]">
                                            <option value="egp">💴 جنيه</option>
                                            <option value="usd">💵 دولار</option>
                                            <option value="eur">💶 يورو</option>
                                        </select>
                                    </div>
                                </div>

        </div>

</div>

`;

            var newTrip = $(tripDetailsHtml);
            $("#trip-details-container").append(newTrip);

            // بعد إضافة الرحلة الجديدة، إذا كان هناك رحلة محددة، اخترها
            if (selectedTripId) {
                newTrip.find(".provider-select").val(selectedTripId).trigger('change');
            }
            updateTotalTripsCount();

        });
    </script>

    <script>
        $(document).on("input", "[name='adult_count[]'], [name='children_count[]']", function () {
            var container = $(this).closest(".trip-details");
            calculateTotalPrice(container);
        });









    </script>
    <script>

        $(document).on('click', '.remove-trip-btn', function () {
            $(this).closest('.trip-details').remove();
            updateTotalTripsCount();

        });


    </script>

    @if ($errors->any())
        <script>
            $(document).ready(function() {
                $("#tripRequestModal").modal("show");
            });
        </script>
    @endif
@endsection
