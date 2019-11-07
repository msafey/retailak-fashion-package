<div class="topbar">
    <!-- LOGO -->
    <div class="topbar-left">
        <a href="{{url('/admin/home')}}" class="logo">
            <span><img src="{{url('public/mlogo.png')}}" style="width: 200px;height: auto;"></span>
        </a>
    </div>
    <nav class="navbar navbar-custom">
        <ul class="nav navbar-nav">
            <li class="nav-item">
                <button class="button-menu-mobile open-left waves-light waves-effect">
                <i class="zmdi zmdi-menu"></i>
                </button>
            </li>
            
        </ul>
        <ul class="nav navbar-nav pull-right">      
            <li class="nav-item dropdown notification-list">
                <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
                    aria-haspopup="false" aria-expanded="false">
                    <img src="{{url('public/admin/images/users/avatar-1.jpg')}}" alt="user" class="img-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-arrow profile-dropdown " aria-labelledby="Preview">
                    <!-- item-->
                    <div class="dropdown-item noti-title">
{{--                        <h5 class="text-overflow"><small>Welcome {{ Auth::guard('admin_user')->user()->name }}</small> </h5>--}}
                    </div>
                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="zmdi zmdi-account-circle"></i> <span>Profile</span>
                    </a>
                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="zmdi zmdi-settings"></i> <span>Settings</span>
                    </a>
                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="zmdi zmdi-lock-open"></i> <span>Lock Screen</span>
                    </a>
                    <!-- item-->
                    <a href="{{ url('/admin/logout') }}"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();" class="dropdown-item notify-item">
                        <i class="zmdi zmdi-power"></i> <span>Logout</span>
                    </a>
                    <form id="logout-form" action="{{ url('/admin/logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </div>
            </li>
        </ul>
    </nav>
</div>