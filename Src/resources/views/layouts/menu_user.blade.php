<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
        <li class="nav-item">
            <a href="{{ route('user.form.index') }}" class="nav-link">
                <i class="nav-icon fa fa-home"></i>
                <p>APPLICATION</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('user.draft')}}" class="nav-link">
                <i class="nav-icon fas fa-edit"></i>
                <p>DRAFT</p>
            </a>
        </li>
        <li class="nav-item has-treeview">
            <a href="" class="nav-link">
                <!--<i class="nav-icon fas fa-list-alt"></i>-->
                <i class="nav-icon fas fa-file-alt" aria-hidden="true"></i>
                <p>
                    STATUS
                    <i class="fas fa-angle-down right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('user.status',config('const.application.status.applying'))}}" class="nav-link">
                        <i class="fas fa-angle-right left"></i>
                        <p>Applying</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('user.status',config('const.application.status.approvel_un'))}}" class="nav-link">
                        <i class="fas fa-angle-right left"></i>
                        <p>Approved/Under payment</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('user.status',config('const.application.status.approvel_in'))}}" class="nav-link">
                        <i class="fas fa-angle-right left"></i>
                        <p>Approved/in-processing <br />of payment</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('user.status',config('const.application.status.declined'))}}" class="nav-link">
                        <i class="fas fa-angle-right left"></i>
                        <p>Declined</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('user.status',config('const.application.status.reject'))}}" class="nav-link">
                        <i class="fas fa-angle-right left"></i>
                        <p>Reject</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('user.status',config('const.application.status.completed'))}}" class="nav-link">
                        <i class="fas fa-angle-right left"></i>
                        <p>Completed</p>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="/pages/examples/08_waiting_approval_list.html" class="nav-link">
                <i class="nav-icon fa fa-check-square"></i>
                <p>APPROVAL</p>
            </a>
        </li>
        <li class="nav-item has-treeview">
            <a href="" class="nav-link">
                <i class="nav-icon fas fa-cog"></i>
                <p>
                    SETTINGS
                    <i class="right fas fa-angle-down"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('user.company.create') }}" class="nav-link">
                        <i class="fas fa-angle-right left"></i>
                        <p>Company Registration</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('changepass.show') }}" class="nav-link">
                        <i class="fas fa-angle-right left"></i>
                        <p>Change Password</p>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</nav>
