<!-- main-sidebar -->
		<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
		<aside class="app-sidebar sidebar-scroll">

            @if (Auth::check() && Auth::user()->role === 'admin')
                @include('Dashboard.layouts.main-sidebar.admin-main-sidebar')
            @endif

            @if (Auth::check() && Auth::user()->role === 'agent')
                @include('Dashboard.layouts.main-sidebar.agent-main-sidebar')
            @endif

            @if (Auth::check() && Auth::user()->role === 'provider')
                @include('Dashboard.layouts.main-sidebar.provider-main-sidebar')
            @endif

		</aside>
<!-- main-sidebar -->
