<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
                           with font-awesome or any other icon font library -->
        <li class="nav-item has-treeview">
            <a href="" class="nav-link">
                <i class="nav-icon fas fa-list-alt"></i>
                <p>
                    {{ __('label.menu.application_list') }}
                    <i class="fas fa-angle-down right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('admin.status',config('const.application.status.applying'))}}" class="nav-link">
                        <i class="fas fa-angle-right left"></i>
                        <p>{{ __('label.menu.applying') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.status',config('const.application.status.approvel_un'))}}" class="nav-link">
                        <i class="fas fa-angle-right left"></i>
                        <p>{{ __('label.menu.approved_un') }}<br>{{ __('label.menu.approved_un2') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.status',config('const.application.status.approvel_in'))}}" class="nav-link">
                        <i class="fas fa-angle-right left"></i>
                        <p>{{ __('label.menu.approved_in') }}<br>{{ __('label.menu.approved_in2') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.status',config('const.application.status.declined'))}}" class="nav-link">
                        <i class="fas fa-angle-right left"></i>
                        <p>{{ __('label.menu.declined') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.status',config('const.application.status.reject'))}}" class="nav-link">
                        <i class="fas fa-angle-right left"></i>
                        <p>{{ __('label.menu.reject') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.status',config('const.application.status.completed'))}}" class="nav-link">
                        <i class="fas fa-angle-right left"></i>
                        <p>{{ __('label.menu.completed') }}</p>
                    </a>
                </li>
            </ul>
        </li>
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
                        <i class="fas fa-angle-right left"></i>
                        <p>{{ __('label.menu.employee_setting') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.budget.show') }}" class="nav-link">
                        <i class="fas fa-angle-right left"></i>
                        <p>{{ __('label.menu.budget_setting') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.flow.index') }}" class="nav-link">
                        <i class="fas fa-angle-right left"></i>
                        <p>{{ __('label.menu.approval_flow_setting') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('changepass.show') }}" class="nav-link">
                        <i class="fas fa-angle-right left"></i>
                        <p>{{ __('label.menu.change_password') }}</p>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.company.index') }}" class="nav-link">
                <i class="nav-icon far fa-building"></i>
                <p>
                    {{ __('label.menu.company_registration') }}
                </p>
            </a>
        </li>
    </ul>
</nav>
