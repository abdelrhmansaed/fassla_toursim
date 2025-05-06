




<div class="modal fade" id="delete_trip{{$trip->id}}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">حذف رحلة {{$trip->type}}</h5>
                <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-exclamation-triangle text-danger fa-4x mb-3"></i>
                <h4 class="text-danger mb-3">هل أنت متأكد من حذف هذه الرحلة؟</h4>

                @if($trip->subTripTypes->count() > 0)
                    <div class="alert alert-warning">
                        سيتم حذف {{ $trip->subTripTypes->count() }} رحلة فرعية مرتبطة
                    </div>
                @endif

                <form id="deleteForm{{$trip->id}}" action="{{ route('trips.destroy', $trip->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')

                    <div class="d-flex justify-content-center gap-3 mt-4">
                        <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">
                            <i class="fas fa-times me-2"></i> إلغاء
                        </button>
                        <button type="submit" class="btn btn-danger px-4" id="confirmDeleteBtn">
                            <i class="fas fa-check me-2"></i> تأكيد الحذف
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // إعداد علم (flag) لتتبع حالة الحذف
        let isDeleting = false;

        $('#deleteForm{{$trip->id}}').on('submit', function(e) {
            e.preventDefault();
            isDeleting = true; // وضعنا في حالة حذف

            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: "لن تتمكن من استعادة هذه البيانات!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، احذفها!',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: $(this).attr('action'),
                        method: 'POST',
                        data: $(this).serialize(),
                        success: function(response) {
                            if(response.success) {
                                $('#delete_trip{{$trip->id}}').modal('hide');
                                location.reload();
                            }
                        },
                        error: function(xhr) {
                            isDeleting = false;
                            Swal.fire('خطأ!', xhr.responseJSON.message, 'error');
                        }
                    });
                } else {
                    isDeleting = false;
                }
            });
        });

        // منع فتح المودال إذا كنا في حالة حذف
        $(document).on('click', '.request-trip-btn', function(e) {
            if (isDeleting) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
        });
    });
</script>
