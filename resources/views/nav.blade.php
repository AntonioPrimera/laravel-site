<nav class="site-nav"
     x-data="{
        mobileMenuOpen: false
     }"
>
    <div class="nav-header">
        <div class="nav-header-inner">
            {{-- Logo (mobile & desktop) --}}
            <div class="flex">
                <a class="flex flex-shrink-0 items-center" href="/">
                    {{-- Logo (falls back to a default if no logo slot was provided --}}
                    @if(isset($logo))
                        {{ $logo }}
                    @else
                        <div class="bg-white h-16">
                            <svg viewBox="0 0 128 44" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-full">
                                <path d="M0 8.55488V0H10.1994C16.7463 0 20.9511 0.0922648 22.8137 0.276794C24.6763 0.46347 26.4105 0.978437 27.9969 1.83457C31.3239 3.6112 33.467 6.33409 34.4261 10.0032C34.6873 10.9216 34.8179 12.2905 34.8179 14.1101C34.8179 15.9296 34.6873 17.3007 34.4261 18.2234C32.8418 24.0811 28.7398 27.3791 22.1201 28.1172C20.9383 28.1858 18.6282 28.2223 15.1899 28.2266H9.8654V43.8945H0V24.506C2.77036 23.3945 5.96677 22.0792 9.58922 20.56L15.0229 20.5021C17.7975 20.4635 19.5445 20.3991 20.2639 20.309C20.9832 20.2189 21.7475 19.9678 22.5632 19.5559C23.5588 19.0409 24.3359 18.2234 24.8947 17.1162C25.1902 16.4854 25.3379 15.4812 25.3379 14.1101C25.3379 12.739 25.1902 11.6704 24.8947 11.1104C24.0084 9.29515 22.3063 8.2223 19.7886 7.89186C18.4591 7.776 16.6093 7.7245 14.2457 7.7245H9.8654V13.0544L5.20888 15.0048C2.28651 16.2214 0.366099 17.0969 0 17.2063V8.55488Z" fill="black"/>
                                <path d="M60.1554 0C63.1869 0 66.0515 0.647999 68.7491 1.944C72.1874 3.60906 74.8293 6.18389 76.6748 9.6685C78.045 12.1875 78.8778 15.4104 79.1732 19.337C79.2439 20.3734 79.2824 21.1329 79.2824 21.6157C79.2824 24.7227 78.8307 27.6945 77.9272 30.5311C77.0237 33.3677 75.8312 35.6743 74.3497 37.4509C73.5726 38.3779 72.4743 39.337 71.0484 40.3412C69.6225 41.3454 68.3573 42.047 67.2525 42.4526C65.6597 43.0448 64.1246 43.3967 62.6474 43.5082C61.1702 43.6198 57.3272 43.7121 51.1185 43.785L40.5273 43.8945C41.1953 43.2336 42.9145 41.7531 45.6848 39.4529L50.1744 35.6743L55.0557 35.6164C57.7147 35.5778 59.5709 35.4812 60.6242 35.3138C61.6132 35.1647 62.5757 34.8742 63.4824 34.4513C67.2161 32.5588 69.1944 28.8531 69.417 23.3344V22.0599C69.417 14.5027 66.7366 10.0569 61.3757 8.72225C60.3737 8.46476 58.8772 8.33602 56.8862 8.33602H50.2835V22.8388L45.7362 26.6173C42.6362 29.2437 41.0283 30.5955 40.9127 30.6727L40.8613 30.7242C40.6729 30.7242 40.5809 25.6496 40.5851 15.5005V0.00643698L60.1554 0Z" fill="black"/>
                                <path d="M93.7521 21.2237L101.684 0.167969H111.717L119.758 21.7258C121.047 25.2104 122.376 28.7765 123.747 32.4242C125.115 36.074 127.973 43.8693 127.902 43.8693H117.915L112.494 27.8925C109.058 17.8571 106.707 11.0595 106.668 11.0595C106.63 11.0595 104.324 17.3743 99.8538 30.0038L94.9403 43.8693H85.2227L85.4924 43.1162C85.5245 43.0068 85.9099 41.9961 86.6292 40.0908L89.7893 31.729C91.1766 28.0598 92.4975 24.5581 93.7521 21.2237Z" fill="black"/>
                            </svg>
                        </div>
                    @endif
                </a>
            </div>

            {{-- Desktop menu --}}
            <div class="nav-header-menu">
                @foreach($menuItems as $menuItem)
                    <a href="{{ $menuItem['url'] }}" class="nav-header-menu-item {{ $menuItem['active'] ? 'active-menu-item' : 'default-menu-item' }}" aria-current="page">{{ $menuItem['label'] }}</a>
                @endforeach
            </div>

            {{-- Hamburger / Close Menu --}}
            <div class="mobile-nav-control-container -mr-2 flex items-center sm:hidden">
                <button type="button"
                        class="mobile-nav-control relative inline-flex items-center justify-center rounded-md bg-white p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        aria-controls="mobile-menu"
                        aria-expanded="false"
                        @click="mobileMenuOpen = !mobileMenuOpen"
                >
                    <span class="absolute -inset-0.5"></span>
                    <span class="sr-only">Open main menu</span>

                    {{-- Hamburger --}}
                    <div x-show="!mobileMenuOpen" class="mobile-nav-burger">
                        @if(isset($burger))
                            {{ $burger }}
                        @else
                            <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        @endif
                    </div>

                    {{-- X - Close --}}
                    <div x-cloak x-show="mobileMenuOpen">
                        @if(isset($close))
                            {{ $close }}
                        @else
                            <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        @endif
                    </div>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div x-cloak x-show="mobileMenuOpen" class="mobile-menu" id="mobile-menu">
        @if(isset($mobileMenu))
            {{ $mobileMenu }}
        @else
            <div class="mobile-menu-container">
                @foreach($menuItems as $menuItem)
                    <a href="{{ $menuItem['url'] }}" class="mobile-menu-item {{ $menuItem['active'] ? 'active-menu-item' : 'default-menu-item' }}" aria-current="page">{{ $menuItem['label'] }}</a>
                @endforeach
            </div>
        @endif
    </div>
</nav>
