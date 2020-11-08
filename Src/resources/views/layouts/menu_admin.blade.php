<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
                           with font-awesome or any other icon font library -->
        <li class="nav-item has-treeview">
            <a href="" class="nav-link">
                <i class="nav-icon fas fa-list-alt"></i>
                <p>
                    Application List
                    <i class="fas fa-angle-down right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="" class="nav-link">
                        <i class="fas fa-angle-right left"></i>
                        <p>Applying</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/pages/examples/user-status.html" class="nav-link">
                        <i class="fas fa-angle-right left"></i>
                        <p>Approved/Under payment</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/pages/examples/user-status.html" class="nav-link">
                        <i class="fas fa-angle-right left"></i>
                        <p>Approved/in-processing <br>of payment</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/pages/examples/user-status.html" class="nav-link">
                        <i class="fas fa-angle-right left"></i>
                        <p>Declined</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/pages/examples/user-status.html" class="nav-link">
                        <i class="fas fa-angle-right left"></i>
                        <p>Reject</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/pages/examples/user-status.html" class="nav-link">
                        <i class="fas fa-angle-right left"></i>
                        <p>Completed</p>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item has-treeview">
            <a href="" class="nav-link">
                <i class="nav-icon fas fa-cog"></i>
                <p>
                    SETTINGS
                    <i class="right fas fa-angle-down"></i>
                </p>
            </a>
            <ul class="nav nav-treeview" style="display: none;">
                <li class="nav-item">
                    <a href="{{ route('admin.user.index') }}" class="nav-link">
                        <i class="fas fa-angle-right left"></i>
                        <p>Employee Setting</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="" class="nav-link">
                        <i class="fas fa-angle-right left"></i>
                        <p>Budget setting</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.flow.index') }}" class="nav-link">
                        <i class="fas fa-angle-right left"></i>
                        <p>Approval Flow Setting</p>
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
        <li class="nav-item">
            <a href="{{ route('admin.company.index') }}" class="nav-link">
                <i class="nav-icon far fa-building"></i>
                <p>
                    Company Registration
                </p>
            </a>
        </li>
    </ul>
</nav>
