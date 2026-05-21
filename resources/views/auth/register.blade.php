<x-guest-layout>

    <div class="max-w-2xl mx-auto">


        <div class="text-center mb-8">

            <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl
                        bg-gradient-to-r from-primary to-secondary shadow-xl mb-5">

                <span class="text-4xl">🛡️</span>

            </div>

            <h2 class="text-3xl font-black text-slate-800 mb-2">
                Креирај профил
            </h2>

            <p class="text-slate-500 max-w-xl mx-auto">
                Изберете тип на профил и започнете со користење на MKSafeNet
                платформата за дигитална безбедност.
            </p>

        </div>

        <form method="POST" action="{{ route('register') }}">

            @csrf


            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">


                <button type="button"
                        onclick="selectRole('student')"
                        id="btn-student"

                        class="role-btn relative overflow-hidden group
                               bg-white/90 backdrop-blur-xl border border-slate-200
                               rounded-3xl p-8 shadow-lg hover:shadow-2xl
                               hover:-translate-y-1 transition duration-300">

                    <div class="absolute top-0 right-0 w-40 h-40 bg-blue-100/40 rounded-full blur-3xl"></div>

                    <div class="relative z-10 text-center">

                        <div class="text-5xl mb-4 group-hover:scale-110 transition duration-300">
                            🎓
                        </div>

                        <h3 class="text-xl font-bold text-slate-800 mb-2">
                            Ученик
                        </h3>

                        <p class="text-sm text-slate-500">
                            Пополнување прашалници и следење на проценки.
                        </p>

                        <div class="indicator absolute top-4 right-4 opacity-0 transition duration-300 text-primary">

                            <svg class="w-6 h-6"
                                 fill="currentColor"
                                 viewBox="0 0 20 20">

                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                      clip-rule="evenodd"/>

                            </svg>

                        </div>

                    </div>

                </button>

                <button type="button"
                        onclick="selectRole('parent')"
                        id="btn-parent"

                        class="role-btn relative overflow-hidden group
                               bg-white/90 backdrop-blur-xl border border-slate-200
                               rounded-3xl p-8 shadow-lg hover:shadow-2xl
                               hover:-translate-y-1 transition duration-300">

                    <div class="absolute top-0 right-0 w-40 h-40 bg-cyan-100/40 rounded-full blur-3xl"></div>

                    <div class="relative z-10 text-center">

                        <div class="text-5xl mb-4 group-hover:scale-110 transition duration-300">
                            👨‍👧
                        </div>

                        <h3 class="text-xl font-bold text-slate-800 mb-2">
                            Родител
                        </h3>

                        <p class="text-sm text-slate-500">
                            Следење активности и проценки на дете.
                        </p>

                        <div class="indicator absolute top-4 right-4 opacity-0 transition duration-300 text-primary">

                            <svg class="w-6 h-6"
                                 fill="currentColor"
                                 viewBox="0 0 20 20">

                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                      clip-rule="evenodd"/>

                            </svg>

                        </div>

                    </div>

                </button>

            </div>

            <input type="hidden" name="role" id="role-input" value="{{ old('role') }}"/>

            <x-input-error :messages="$errors->get('role')" class="mb-5 text-center"/>

            <div id="form-fields"
                 class="{{ old('role') ? 'block' : 'hidden' }}
                        relative overflow-hidden bg-white/90 backdrop-blur-xl
                        border border-white/40 shadow-2xl rounded-3xl p-8 transition-all duration-500">

                <div class="absolute bottom-0 left-0 w-56 h-56 bg-blue-100/30 rounded-full blur-3xl"></div>

                <div class="relative z-10 space-y-5">

                    <div>

                        <x-input-label
                            for="name"
                            :value="__('Целосно име')"
                            class="text-slate-700 font-medium"
                        />

                        <x-text-input
                            id="name"
                            class="block mt-2 w-full rounded-2xl border-slate-200
                                   focus:border-primary focus:ring-primary py-3"
                            type="text"
                            name="name"
                            :value="old('name')"
                            required
                            autofocus
                        />

                        <x-input-error :messages="$errors->get('name')" class="mt-2"/>

                    </div>

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
                        />

                        <x-input-error :messages="$errors->get('email')" class="mt-2"/>

                    </div>

                    <!-- CHILD EMAIL -->
                    <div id="child-email-field"
                         class="{{ old('role') === 'parent' ? 'block' : 'hidden' }}
                                rounded-2xl bg-blue-50 border border-blue-100 p-5">

                        <x-input-label
                            for="child_email"
                            :value="__('Е-маил на детето')"
                            class="text-primary font-semibold"
                        />

                        <x-text-input
                            id="child_email"
                            class="block mt-2 w-full rounded-2xl border-blue-200
                                   focus:border-primary focus:ring-primary py-3"
                            type="email"
                            name="child_email"
                            :value="old('child_email')"
                            placeholder="example@student.com"
                        />

                        <p class="text-xs text-slate-500 mt-2">
                            Детето треба претходно да биде регистрирано.
                        </p>

                        <x-input-error :messages="$errors->get('child_email')" class="mt-2"/>

                    </div>


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <div>

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
                            />

                        </div>

                        <div>

                            <x-input-label
                                for="password_confirmation"
                                :value="__('Потврди лозинка')"
                                class="text-slate-700 font-medium"
                            />

                            <x-text-input
                                id="password_confirmation"
                                class="block mt-2 w-full rounded-2xl border-slate-200
                                       focus:border-primary focus:ring-primary py-3"
                                type="password"
                                name="password_confirmation"
                                required
                            />

                        </div>

                    </div>

                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pt-5">

                        <a class="text-sm font-medium text-slate-500 hover:text-primary transition"
                           href="{{ route('login') }}">

                            Веќе имате профил?

                        </a>

                        <button type="submit"
                                class="px-8 py-3.5 rounded-2xl text-white font-bold
                                       bg-gradient-to-r from-primary to-secondary
                                       hover:scale-105 hover:shadow-2xl
                                       transition duration-300">

                            Креирај профил

                        </button>

                    </div>

                </div>

            </div>

        </form>

    </div>

    <script>

        function selectRole(role) {

            document.getElementById('role-input').value = role;

            const formFields = document.getElementById('form-fields');
            formFields.classList.remove('hidden');

            const childField = document.getElementById('child-email-field');

            if (role === 'parent') {
                childField.classList.remove('hidden');
            } else {
                childField.classList.add('hidden');
            }

            highlightButton(role);
        }

        function highlightButton(role) {

            document.querySelectorAll('.role-btn').forEach(btn => {

                btn.classList.remove(
                    'border-primary',
                    'ring-2',
                    'ring-primary',
                    'bg-blue-50/40'
                );

                btn.classList.add(
                    'border-slate-200'
                );

                btn.querySelector('.indicator').classList.add('opacity-0');

            });

            const selected = document.getElementById('btn-' + role);

            selected.classList.remove('border-slate-200');

            selected.classList.add(
                'border-primary',
                'ring-2',
                'ring-primary',
                'bg-blue-50/40'
            );

            selected.querySelector('.indicator').classList.remove('opacity-0');
        }

        document.addEventListener('DOMContentLoaded', function () {

            const existing = document.getElementById('role-input').value;

            if (existing) {
                selectRole(existing);
            }

        });

    </script>

</x-guest-layout>
