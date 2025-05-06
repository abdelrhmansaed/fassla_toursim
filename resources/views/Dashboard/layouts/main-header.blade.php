<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center py-2">
        <!-- الجزء الأيسر: الشعار وزر القائمة -->
        <div class="d-flex align-items-center">
            <!-- الشعار -->
            <div class="responsive-logo mr-3">
                <a href="{{ url('/' . $page='index') }}">
                    <img src="{{URL::asset('Dashboard/img/brand/logo.png')}}" class="logo-1" alt="logo" style="height: 40px;">
                </a>
            </div>

            <!-- زر القائمة الجانبية -->

        </div>

        <!-- الجزء الأوسط: شريط البحث -->


        <!-- الجزء الأيمن: العناصر الإضافية -->
        <div class="d-flex align-items-center">
            <!-- تغيير اللغة -->
            <div class="dropdown mx-2">
                <a href="#" class="d-flex align-items-center text-decoration-none" data-toggle="dropdown">
                    @if (App::getLocale() == 'ar')
                        <img src="{{URL::asset('Dashboard/img/flags/FlagEgypt.png')}}" alt="العربية" style="height: 20px; width: 30px;">
                        <span class="mx-2">العربية</span>
                    @else
                        <img src="{{URL::asset('Dashboard/img/flags/us_flag.jpg')}}" alt="English" style="height: 20px; width: 30px;">
                        <span class="mx-2">English</span>
                    @endif
                    <i class="fas fa-chevron-down"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                        <a class="dropdown-item" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                            @if($properties['native'] == "English")
                                <img src="{{URL::asset('Dashboard/img/flags/us_flag.jpg')}}" alt="English" style="height: 15px; width: 25px;" class="mr-2">
                            @elseif($properties['native'] == "العربية")
                                <img src="{{URL::asset('Dashboard/img/flags/FlagEgypt.png')}}" alt="العربية" style="height: 15px; width: 25px;" class="mr-2">
                            @endif
                            {{ $properties['native'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- الإشعارات -->
            <div class="dropdown mx-2">
                <a class="nav-link position-relative" href="#" title="الإشعارات">
                    <i class="fas fa-bell" style="font-size: 1.2rem;"></i>
                    @if(Auth::check() && Auth::user()->unreadNotifications->count() > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                            {{ Auth::user()->unreadNotifications->count() }}
                        </span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-right p-0" style="width: 300px;">
                    <div class="p-3 bg-primary text-white">
                        <h6 class="mb-0">الإشعارات</h6>
                        @if(Auth::check())
                            <small>{{ Auth::user()->unreadNotifications->count() }} غير مقروء</small>
                        @endif
                    </div>
                    <div class="notification-list" style="max-height: 300px; overflow-y: auto;">
                        @if(Auth::check())
                            @forelse(Auth::user()->notifications->take(5) as $notification)
                                <a href="{{ route('notifications.markAsRead', $notification->id) }}" class="dropdown-item border-bottom {{ $notification->read_at ? '' : 'bg-light' }}">
                                    <div class="d-flex justify-content-between">
                                        <strong>{{ $notification->data['title'] }}</strong>
                                        <small>{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-0 text-muted">{{ Str::limit($notification->data['message'], 40) }}</p>
                                </a>
                            @empty
                                <div class="p-3 text-center text-muted">لا توجد إشعارات</div>
                            @endforelse
                        @else
                            <div class="p-3 text-center text-muted">يجب تسجيل الدخول</div>
                        @endif
                    </div>
                    <div class="p-2 text-center border-top">
                        <a href="" class="btn btn-sm btn-link">عرض الكل</a>
                        @if(Auth::check())
                            <a href="{{ route('notifications.markAllAsRead') }}" class="btn btn-sm btn-primary">تحديد الكل كمقروء</a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- وضع ملء الشاشة -->
            <div class="mx-2">
                <a href="#" class="nav-link" title="ملء الشاشة" id="fullscreen-button">
                    <i class="fas fa-expand" style="font-size: 1.2rem;"></i>
                </a>
            </div>

            <!-- الملف الشخصي -->
            <div class="dropdown mx-2">
                <a href="#" class="d-flex align-items-center text-decoration-none" data-toggle="dropdown">
                    <img src="{{URL::asset('Dashboard/img/faces/6.jpg')}}" class="rounded-circle" style="width: 35px; height: 35px; object-fit: cover;" alt="Profile">
                    <span class="mx-2">Petey Cruiser</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="px-4 py-3">
                        <div class="d-flex align-items-center">
                            <img src="{{URL::asset('Dashboard/img/faces/6.jpg')}}" class="rounded-circle mr-3" style="width: 50px; height: 50px; object-fit: cover;" alt="Profile">
                            <div>
                                <h6 class="mb-0">Petey Cruiser</h6>
                                <small class="text-muted">Premium Member</small>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#"><i class="fas fa-user-circle mr-2"></i>الملف الشخصي</a>
                    <a class="dropdown-item" href="#"><i class="fas fa-cog mr-2"></i>الإعدادات</a>
                    <form id="logout-form" method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt mr-2"></i>تسجيل الخروج</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // دالة ملء الشاشة
    document.getElementById('fullscreen-button').addEventListener('click', function() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen();
            this.innerHTML = '<i class="fas fa-compress" style="font-size: 1.2rem;"></i>';
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
                this.innerHTML = '<i class="fas fa-expand" style="font-size: 1.2rem;"></i>';
            }
        }
    });
</script>
