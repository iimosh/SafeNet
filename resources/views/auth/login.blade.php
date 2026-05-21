<x-guest-layout>

    <div class="max-w-md mx-auto">

        <div class="text-center mb-8">

            <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl
                        bg-gradient-to-r from-primary to-secondary shadow-xl mb-5">

                <span class="text-4xl">🔐</span>

            </div>

            <h2 class="text-3xl font-black text-slate-800 mb-2">
                Добредојде назад
            </h2>

            <p class="text-slate-500">
                Најавете се за да пристапите до вашиот MKSafeNet профил.
            </p>

        </div>


        <x-auth-session-status class="mb-4" :status="session('status')"/>


        <div class="relative overflow-hidden bg-white/90 backdrop-blur-xl
                    border border-white/40 shadow-2xl rounded-3xl p-8">


            <div class="absolute top-0 right-0 w-52 h-52 bg-blue-200/30 rounded-full blur-3xl"></div>

            <div class="relative z-10">

                <form method="POST" action="{{ route('login') }}">

                    @csrf

                    <div>

                        <x-input-label
                            for="email"
                            :value="__('Е-маил адреса')"
                            class="text-slate-700 font-medium"
                        />

                        <x-text-input
                            id="email"
                            class="block mt-2 w-full rounded-2xl border-slate-200
                                   focus:border-primary focus:ring-primary py-3"
                            type="email"
                            name="email"
                            :value="old('email')"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="example@email.com"
                        />

                        <x-input-error :messages="$errors->get('email')" class="mt-2"/>

                    </div>

                    <div class="mt-5">

                        <x-input-label
                            for="password"
                            :value="__('Лозинка')"
                            class="text-slate-700 font-medium"
                        />

                        <x-text-input
                            id="password"
                            class="block mt-2 w-full rounded-2xl border-slate-200
                                   focus:border-primary focus:ring-primary py-3"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                        />

                        <x-input-error :messages="$errors->get('password')" class="mt-2"/>

                    </div>

                    <div class="flex items-center justify-between mt-5">

                        <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer">

                            <input
                                id="remember_me"
                                type="checkbox"
                                class="rounded border-slate-300 text-primary shadow-sm focus:ring-primary"
                                name="remember"
                            >

                            <span class="text-sm text-slate-600">
                                Запомни ме
                            </span>

                        </label>

                        @if (Route::has('password.request'))

                            <a class="text-sm font-medium text-primary hover:text-secondary transition"
                               href="{{ route('password.request') }}">

                                Заборавена лозинка?

                            </a>

                        @endif

                    </div>


                    <div class="mt-8">

                        <button type="submit"
                                class="w-full py-3.5 rounded-2xl text-white font-bold
                                       bg-gradient-to-r from-primary to-secondary
                                       hover:scale-[1.02] hover:shadow-2xl
                                       transition duration-300">

                            Најави се

                        </button>

                    </div>

                    <div class="mt-6 text-center">

                        <p class="text-sm text-slate-500">

                            Немате профил?

                            <a href="{{ route('register') }}"
                               class="font-semibold text-primary hover:text-secondary transition">

                                Регистрирај се

                            </a>

                        </p>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-guest-layout>
