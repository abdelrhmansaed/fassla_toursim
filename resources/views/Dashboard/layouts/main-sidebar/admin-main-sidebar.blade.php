<!-- main-sidebar -->
<style>
    /* التنسيقات الأساسية */
    .main-sidebar-header {
        padding: 1rem;
        background: linear-gradient(135deg, #3f37c9, #4361ee);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        height: 80px;
        display: flex;
        align-items: center;
    }

    .sidebar-logo-container {
        width: 100%;
        display: flex;
        justify-content: center;
    }

    .sidebar-logo-link {
        display: flex;
        align-items: center;
        text-decoration: none;
        gap: 12px;
    }

    /* تصميم الأيقونة */
    .sidebar-logo-icon {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .sidebar-logo-icon i {
        font-size: 1.2rem;
        color: white;
    }

    /* تصميم النص */
    .sidebar-logo-text {
        display: flex;
        flex-direction: column;
        color: white;
    }

    .sidebar-brand-name {
        font-size: 1.3rem;
        font-weight: 700;
        line-height: 1.2;
    }

    .sidebar-brand-slogan {
        font-size: 0.7rem;
        opacity: 0.8;
        margin-top: 3px;
    }

    /* تأثيرات التحويم */
    .sidebar-logo-link:hover .sidebar-logo-icon {
        transform: rotate(15deg);
        background: rgba(255, 255, 255, 0.2);
    }

    /* للشاشات الصغيرة */
    @media (max-width: 768px) {
        .sidebar-brand-name {
            font-size: 1.1rem;
        }
    }
</style>
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar sidebar-scroll">
    <div class="main-sidebar-header active">
        <div class="sidebar-logo-container">
            <a href="#" class="sidebar-logo-link">
                <div class="sidebar-logo-icon">
                    <i class="fas fa-plane-departure"></i>
                </div>
                <div class="sidebar-logo-text">
                    <span class="sidebar-brand-name">ZM HEROS</span>
                </div>
            </a>
        </div>
    </div>

    <div class="main-sidemenu">
        <div class="app-sidebar__user clearfix">
            <div class="dropdown user-pro-body">
                <div class="">
                    <img alt="user-img" class="avatar avatar-xl brround" src="{{URL::asset('Dashboard/img/faces/6.jpg')}}"><span class="avatar-status profile-status bg-green"></span>
                </div>
                <div class="user-info">
                    <h4 class="font-weight-semibold mt-3 mb-0">
                        @if (Auth::check() && Auth::user()->role === 'admin')
                            {{ Auth::user()->name }}
                        @endif
                    </h4>
                </div>
            </div>
        </div>
        <ul class="side-menu">
            <li class="side-item side-item-category">Main</li>
            <li class="slide">
                    <a class="side-menu__item" href="{{  route('admin.dashboard') }}"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3"/><path d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z"/></svg><span class="side-menu__label">{{trans('dashboard/main_trans.dashboard')}}</span><span class="badge badge-success side-badge">1</span></a>
            </li>
            <li class="slide">
                <a class="side-menu__item" data-toggle="slide"  href="{{ url('/' . $page='#') }}"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M3.31 11l2.2 8.01L18.5 19l2.2-8H3.31zM12 17c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z" opacity=".3"/><path d="M22 9h-4.79l-4.38-6.56c-.19-.28-.51-.42-.83-.42s-.64.14-.83.43L6.79 9H2c-.55 0-1 .45-1 1 0 .09.01.18.04.27l2.54 9.27c.23.84 1 1.46 1.92 1.46h13c.92 0 1.69-.62 1.93-1.46l2.54-9.27L23 10c0-.55-.45-1-1-1zM12 4.8L14.8 9H9.2L12 4.8zM18.5 19l-12.99.01L3.31 11H20.7l-2.2 8zM12 13c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg><span class="side-menu__label">المناديب</span><i class="angle fe fe-chevron-down"></i></a>
                <ul class="slide-menu">
                    <li><a class="slide-item" href="{{ url('/agents' )  }}">قائمة المناديب</a></li>
                    <li><a class="slide-item" href="{{ route('requests') }}">الرحلات المطلوبة</a></li>
                    <li><a class="slide-item" href="{{ route('agent.confirmedTrips') }}"> رحلاتي المؤكدة</a></li>
                    <li><a class="slide-item" href="{{ route('agent.rejectedTrips') }}"> رحلاتي الملغاة</a></li>                        </ul>
            </li>
            <li class="slide">
                <a class="side-menu__item"  data-toggle="slide"   href="{{ url('/') }}"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19 5H5v14h14V5zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z" opacity=".3"/><path d="M3 5v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2zm2 0h14v14H5V5zm2 5h2v7H7zm4-3h2v10h-2zm4 6h2v4h-2z"/></svg><span class="side-menu__label">مزودين الخدمة</span><i class="angle fe fe-chevron-down"></i></a>
                <ul class="slide-menu">
                    <li><a class="slide-item" href="{{ url('/providers' ) }}"> مزودين الخدمة</a></li>


                </ul>
            </li>
            <li class="slide">
                <a class="side-menu__item" data-toggle="slide"  href="{{ url('/' . $page='#') }}"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M3.31 11l2.2 8.01L18.5 19l2.2-8H3.31zM12 17c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z" opacity=".3"/><path d="M22 9h-4.79l-4.38-6.56c-.19-.28-.51-.42-.83-.42s-.64.14-.83.43L6.79 9H2c-.55 0-1 .45-1 1 0 .09.01.18.04.27l2.54 9.27c.23.84 1 1.46 1.92 1.46h13c.92 0 1.69-.62 1.93-1.46l2.54-9.27L23 10c0-.55-.45-1-1-1zM12 4.8L14.8 9H9.2L12 4.8zM18.5 19l-12.99.01L3.31 11H20.7l-2.2 8zM12 13c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg><span class="side-menu__label">الرحلات</span><i class="angle fe fe-chevron-down"></i></a>
                <ul class="slide-menu">
                    <li><a class="slide-item" href="{{ url('/trips' )  }}">قائمة الرحلات</a></li>
                    <li><a class="slide-item" href="{{ route('requests') }}"> الرحلات المطلوبة</a></li>
                    <li><a class="slide-item" href="{{ route('trips.providerApproved') }}"> قائمة الرحلات ف انتظار الدفع</a></li>
                    <li><a class="slide-item" href="{{ route('confirmedTrips') }}"> قائمة الرحلات الموكدة</a></li>
                    <li><a class="slide-item" href="{{ route('rejectedTrips') }}">  قائمة الرحلات الملغية</a></li>
                </ul>
            </li>


            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . $page='#') }}"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M6.26 9L12 13.47 17.74 9 12 4.53z" opacity=".3"/><path d="M19.37 12.8l-7.38 5.74-7.37-5.73L3 14.07l9 7 9-7zM12 2L3 9l1.63 1.27L12 16l7.36-5.73L21 9l-9-7zm0 11.47L6.26 9 12 4.53 17.74 9 12 13.47z"/></svg><span class="side-menu__label">التقارير</span><i class="angle fe fe-chevron-down"></i></a>
                <ul class="slide-menu">
                    <li><a class="slide-item" href="{{ route('transactions.index') }}">التقارير والمعاملات المالية </a></li>
                    <li><a class="slide-item" href="{{ route('reports.file') }}">التقارير الملفات</a></li>
                    <li><a class="slide-item" href="{{ route('reports.financing') }}">التقارير المالية </a></li>

                </ul>
            </li>
            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . $page='#') }}"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0z" fill="none"/><path d="M12 4c-4.41 0-8 3.59-8 8s3.59 8 8 8c.28 0 .5-.22.5-.5 0-.16-.08-.28-.14-.35-.41-.46-.63-1.05-.63-1.65 0-1.38 1.12-2.5 2.5-2.5H16c2.21 0 4-1.79 4-4 0-3.86-3.59-7-8-7zm-5.5 9c-.83 0-1.5-.67-1.5-1.5S5.67 10 6.5 10s1.5.67 1.5 1.5S7.33 13 6.5 13zm3-4C8.67 9 8 8.33 8 7.5S8.67 6 9.5 6s1.5.67 1.5 1.5S10.33 9 9.5 9zm5 0c-.83 0-1.5-.67-1.5-1.5S13.67 6 14.5 6s1.5.67 1.5 1.5S15.33 9 14.5 9zm4.5 2.5c0 .83-.67 1.5-1.5 1.5s-1.5-.67-1.5-1.5.67-1.5 1.5-1.5 1.5.67 1.5 1.5z" opacity=".3"/><path d="M12 2C6.49 2 2 6.49 2 12s4.49 10 10 10c1.38 0 2.5-1.12 2.5-2.5 0-.61-.23-1.21-.64-1.67-.08-.09-.13-.21-.13-.33 0-.28.22-.5.5-.5H16c3.31 0 6-2.69 6-6 0-4.96-4.49-9-10-9zm4 13h-1.77c-1.38 0-2.5 1.12-2.5 2.5 0 .61.22 1.19.63 1.65.06.07.14.19.14.35 0 .28-.22.5-.5.5-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.14 8 7c0 2.21-1.79 4-4 4z"/><circle cx="6.5" cy="11.5" r="1.5"/><circle cx="9.5" cy="7.5" r="1.5"/><circle cx="14.5" cy="7.5" r="1.5"/><circle cx="17.5" cy="11.5" r="1.5"/></svg><span class="side-menu__label"> ارقام الملفات</span><i class="angle fe fe-chevron-down"></i></a>
                <ul class="slide-menu">
                    <li><a class="slide-item" href="{{ route('file_numbers.create') }}">اضافة</a></li>

                </ul>
            </li>
            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . $page='#') }}"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M15 11V4H4v8.17l.59-.58.58-.59H6z" opacity=".3"/><path d="M21 6h-2v9H6v2c0 .55.45 1 1 1h11l4 4V7c0-.55-.45-1-1-1zm-5 7c.55 0 1-.45 1-1V3c0-.55-.45-1-1-1H3c-.55 0-1 .45-1 1v14l4-4h10zM4.59 11.59l-.59.58V4h11v7H5.17l-.58.59z"/></svg><span class="side-menu__label">المستخدمين </span><i class="angle fe fe-chevron-down"></i></a>
                <ul class="slide-menu">
                    <li><a class="slide-item" href="{{route('roles.index') }}">قائمة المستخدمين</a></li>
                    <li><a class="slide-item" href="{{route('users.create') }}">اضافة مستخدم</a></li>

                </ul>
            </li>
        </ul>
    </div>
</aside>
<!-- main-sidebar -->
