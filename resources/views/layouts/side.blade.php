<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('img/bitoff.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->username }}</a>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->is('dashboard*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                @if(!config('permission.enable') || auth()->user()->can('activities'))
                    <li class="nav-item">
                        <a href="{{ route('activities') }}"
                           class="nav-link {{ request()->is('activities*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Activities</p>
                        </a>
                    </li>
                @endif
                @if(!config('permission.enable') || auth()->user()->can('orders'))
                    <li class="nav-item">
                        <a href="{{ route('orders') }}" class="nav-link {{ request()->is('orders*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-shopping-bag"></i>
                            <p>Orders</p>
                        </a>
                    </li>
                @endif
                @if(!config('permission.enable') || auth()->user()->can('users'))
                    <li class="nav-item">
                        <a href="{{ route('users') }}" class="nav-link {{ request()->is('users*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-at"></i>
                            <p>Users</p>
                        </a>
                    </li>
                @endif
                @if(!config('permission.enable') || auth()->user()->can('products'))
                    <li class="nav-item">
                        <a href="{{ route('products') }}"
                           class="nav-link {{ request()->is('products*') ? 'active' : '' }}">
                            <i class="nav-icon fab fa-product-hunt"></i>
                            <p>Products</p>
                        </a>
                    </li>
                @endif
                @if(!config('permission.enable') || auth()->user()->can('contacts'))
                    <li class="nav-item">
                        <a href="{{ route('contacts') }}"
                           class="nav-link {{ request()->is('contacts*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-portrait"></i>
                            <p>Contacts</p>
                        </a>
                    </li>
                @endif
                @if(!config('permission.enable') || auth()->user()->can('transactions'))
                    <li class="nav-item">
                        <a href="{{ route('transactions') }}"
                           class="nav-link {{ request()->is('transactions*') ? 'active' : '' }}">
                            <i class="nav-icon fab fa-stripe-s"></i>
                            <p>Transactions</p>
                        </a>
                    </li>
                @endif
                @if(!config('permission.enable') || auth()->user()->can('pay.show'))
                    <li class="nav-item">
                        <a href="{{ route('pay.show') }}"
                           class="nav-link {{ request()->is('pay.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-wallet"></i>
                            <p>Pay</p>
                        </a>
                    </li>
                @endif
                @if(!config('permission.enable') || auth()->user()->can('tickets'))
                    <li class="nav-item">
                        <a href="{{ route('tickets') }}"
                           class="nav-link {{ request()->is('tickets*') ? 'active' : '' }}">
                            <i class="nav-icon far fa-paper-plane"></i>
                            <p>Tickets</p>
                        </a>
                    </li>
                @endif
                @if(!config('permission.enable') || auth()->user()->can('areas.index'))
                    <li class="nav-item">
                        <a href="{{ route('areas.index') }}"
                           class="nav-link {{ request()->is('areas*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-location-arrow"></i>
                            <p>Area</p>
                        </a>
                    </li>
                @endif
                @if(!config('permission.enable') || auth()->user()->can('acl.roles'))
                    <li class="nav-item">
                        <a href="{{ route('acl.roles') }}"
                           class="nav-link {{ request()->is('roles*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-fingerprint"></i>
                            <p>ACL</p>
                        </a>
                    </li>
                @endif
                @if(!config('permission.enable') || auth()->user()->can('settings'))
                    <li class="nav-item">
                        <a href="{{ route('settings') }}"
                           class="nav-link {{ request()->is('settings*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>Settings</p>
                        </a>
                    </li>
                @endif
                @if(!config('permission.enable') || auth()->user()->can('report.show'))
                    <li class="nav-item has-treeview  {{ request()->is('reports*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>
                                Report
                                <i class="right fas fa-angle-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('reports.orders') }}" class="nav-link  {{ request()->is('reports_order') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Orders</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('reports.users') }}" class="nav-link {{ request()->is('reports_user') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Users</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                    <li class="nav-item has-treeview border-top border-bottom  {{ request()->is('mantis/*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fab fa-bitcoin"></i>
                            <p>
                                Exchanger
                                <i class="right fas fa-angle-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('mantis.payment_methods.index') }}" class="nav-link  {{ request()->is('mantis/payment-methods') ? 'active' : '' }}">
                                    <i class="fas fa-wallet nav-icon"></i>
                                    <p>Payment Method</p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('mantis.offers.index') }}" class="nav-link  {{ request()->is('mantis/offers') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-shopping-bag"></i>
                                    <p>Offers</p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('mantis.trades.index') }}" class="nav-link  {{ request()->is('mantis/trades') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-shopping-bag"></i>
                                    <p>Trades</p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('mantis.settings.index') }}" class="nav-link  {{ request()->is('mantis/settings') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-cog"></i>
                                    <p>Settings</p>
                                </a>
                            </li>
                        </ul>

                    </li>
                    
                <li class="nav-item">
                    <a href="{{ route('logout') }}" class="nav-link">
                        <i class="nav-icon fas fa-power-off"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
