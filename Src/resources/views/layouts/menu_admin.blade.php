<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
                           with font-awesome or any other icon font library -->
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>{{ __('label.menu.dashboard') }}</p>
            </a>
        </li>
        <li class="nav-item has-treeview">
            <a href="" class="nav-link">
                <i class="nav-icon fas fa-filter"></i>
                <p>
                    {{ __('label.menu.application_list') }}
                    <i class="fas fa-angle-down right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('admin.status',config('const.application.status.applying'))}}" class="nav-link">
                        <i class="fas fa-angle-right nav-icon"></i>
                        <p>{{ __('label.menu.applying') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.status',config('const.application.status.approvel_un'))}}" class="nav-link">
                        <i class="fas fa-angle-right nav-icon"></i>
                        <p>{{ __('label.menu.approved_un') }}<br>{{ __('label.menu.approved_un2') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.status',config('const.application.status.approvel_in'))}}" class="nav-link">
                        <i class="fas fa-angle-right nav-icon"></i>
                        <p>{{ __('label.menu.approved_in') }}<br>{{ __('label.menu.approved_in2') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.status',config('const.application.status.declined'))}}" class="nav-link">
                        <i class="fas fa-angle-right nav-icon"></i>
                        <p>{{ __('label.menu.declined') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.status',config('const.application.status.reject'))}}" class="nav-link">
                        <i class="fas fa-angle-right nav-icon"></i>
                        <p>{{ __('label.menu.reject') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.status',config('const.application.status.completed'))}}" class="nav-link">
                        <i class="fas fa-angle-right nav-icon"></i>
                        <p>{{ __('label.menu.completed') }}</p>
                    </a>
                </li>
            </ul>
        </li>
        @if (Auth::user()->approval == config('const.approval.on'))
        <li class="nav-item">
            <a href="{{ route('user.approval.index') }}" class="nav-link">
                <i class="nav-icon fa fa-check-square"></i>
                <p>{{ __('label.menu.approval') }}</p>
            </a>
        </li>
        @endif
        <li class="nav-item has-treeview">
            <a href="" class="nav-link">
                <i class="nav-icon fas fa-cog"></i>
                <p>
                    {{ __('label.menu.settings') }}
                    <i class="right fas fa-angle-down"></i>
                </p>
            </a>
            <ul class="nav nav-treeview" style="display: none;">
                <li class="nav-item">
                    <a href="{{ route('admin.user.index') }}" class="nav-link">
                        <i class="fas fa-angle-right nav-icon"></i>
                        <p>{{ __('label.title.user.list') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.company.index') }}" class="nav-link">
                        <i class="fas fa-angle-right nav-icon"></i>
                        <p>
                            {{ __('label.menu.company_list') }}
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.budget.show') }}" class="nav-link">
                        <i class="fas fa-angle-right nav-icon"></i>
                        <p>{{ __('label.menu.budget_setting') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.flow.index') }}" class="nav-link">
                        <i class="fas fa-angle-right nav-icon"></i>
                        <p>{{ __('label.menu.approval_flow_setting') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('changepass.show') }}" class="nav-link">
                        <i class="fas fa-angle-right nav-icon"></i>
                        <p>{{ __('label.menu.change_password') }}</p>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</nav>
