<nav x-data="{ open: false }"
     class="sticky top-0 z-50 bg-white/70 backdrop-blur-xl border-b border-white/20 shadow-sm">

    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">

                <div class="shrink-0 flex items-center">
                    <a href="{{ auth()->user()->isParent() ? route('parent.dashboard') : route('dashboard') }}"
                       class="flex items-center gap-3 group">

                        <img src="{{ asset('images/mksafenet-logo.png') }}"
                             class="h-11 w-auto object-contain transition duration-300 group-hover:scale-105"
                             alt="MKSafenet Logo">

                        <div class="hidden md:block">
                            <p class="text-sm text-slate-500 leading-none">
                                Платформа за дигитална безбедност
                            </p>

                            <p class="font-bold text-slate-800 text-lg leading-none mt-1">
                                MKSafeNet
                            </p>
                        </div>

                    </a>
                </div>


                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @if(auth()->user()->isParent())
                        <x-nav-link :href="route('parent.dashboard')" :active="request()->routeIs('parent.dashboard')">
                            {{ __('Почетна') }}
                        </x-nav-link>
                    @else
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Почетна') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>


            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-3 px-4 py-2 rounded-2xl bg-white/70 border border-slate-200 hover:shadow-md transition duration-300">
                            <div class="flex items-center gap-3">

                                <div class="w-10 h-10 rounded-xl bg-gradient-to-r from-primary to-secondary
                flex items-center justify-center text-white font-bold shadow-md">

                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}

                                </div>

                                <div class="text-left hidden md:block">
                                    <p class="text-sm font-semibold text-slate-800 leading-none">
                                        {{ Auth::user()->name }}
                                    </p>

                                    <p class="text-xs text-slate-500 mt-1">
                                        {{ Auth::user()->isParent() ? 'Профил на родител' : 'Профил на дете' }}
                                    </p>
                                </div>

                            </div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Профил') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Одјави се') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>


            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl
                    bg-white/70 border border-slate-200 text-slate-600
                    hover:bg-slate-100 transition duration-300">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>


    <div :class="{'block': open, 'hidden': ! open}"
          class="hidden sm:hidden bg-white/90 backdrop-blur-xl border-t border-white/20 shadow-xl">
        <div class="pt-2 pb-3 space-y-1">
            @if(auth()->user()->isParent())
                <x-responsive-nav-link :href="route('parent.dashboard')" :active="request()->routeIs('parent.dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
            @endif
        </div>


        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
