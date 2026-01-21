<aside class="sidebar">
    <nav>
        <ul id="myAccountSidebarMenu">
            <li>
                <a href="{{ route('dashboard.index') }}"
                    class="{{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                    <img src="{{ asset('assets/images/account_black.png') }}" alt=""> Account information
                </a>
            </li>

            <li>
                <a href="{{ route('dashboard.trusted-devices') }}"
                    class="{{ request()->routeIs('dashboard.trusted-devices') ? 'active' : '' }}">
                    <img src="{{ asset('assets/images/280_662.svg') }}" alt=""> Trusted devices
                </a>
            </li>

            <li>
                <a href="{{ route('dashboard.miravault') }}"
                    class="{{ request()->routeIs('dashboard.miravault') ? 'active' : '' }}">
                    <img src="{{ asset('assets/images/280_667.svg') }}" alt=""> MiraVault
                </a>
            </li>

            <li>
                <a href="{{ route('dashboard.refer-earn') }}"
                    class="{{ request()->routeIs('dashboard.refer-earn') ? 'active' : '' }}">
                    <img src="{{ asset('assets/images/280_674.svg') }}" alt=""> Refer and earn
                </a>
            </li>

            <li>
                <a href="{{ route('dashboard.orders') }}"
                    class="{{ request()->routeIs('dashboard.orders') ? 'active' : '' }}" data-target="orders"
                    data-icon-black="{{ asset('assets/images/280_680.svg') }}"
                    data-icon-white="{{ asset('assets/images/order_white.png') }}">
                    <img src="{{ asset('assets/images/280_680.svg') }}" alt=""> Orders
                </a>
            </li>

            <li>
                <a href="{{ route('dashboard.help') }}"
                    class="{{ request()->routeIs('dashboard.help') ? 'active' : '' }}" >
                    <img src="{{ asset('assets/images/280_685.svg') }}" alt=""> Help
                </a>
            </li>

            <li>
                <a href="{{ route('dashboard.support') }}"
                    class="{{ request()->routeIs('dashboard.support') ? 'active' : '' }}" >
                    <img src="{{ asset('assets/images/280_690.svg') }}" alt=""> Support
                </a>
            </li>
        </ul>
    </nav>
</aside>
