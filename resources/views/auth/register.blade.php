<x-guest-layout>
    <div class="max-w-md mx-auto">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900">Регистрирај се</h2>
            <p class="text-sm text-gray-500 mt-2">Изберете го типот на вашиот профил за да започнете со SafeNet.</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-8">
                <div class="grid grid-cols-2 gap-4">
                    <button type="button" onclick="selectRole('student')" id="btn-student"
                            class="role-btn group relative flex flex-col items-center justify-center p-6 border-2 border-gray-200 rounded-2xl bg-white transition-all duration-200 hover:border-indigo-300 hover:shadow-md cursor-pointer focus:outline-none">
                        <span class="text-4xl mb-3 group-hover:scale-110 transition-transform duration-300">🎓</span>
                        <span class="text-sm font-semibold text-gray-800">Студент</span>
                        <div class="indicator absolute top-3 right-3 opacity-0 transition-opacity duration-200 text-indigo-600">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        </div>
                    </button>

                    <button type="button" onclick="selectRole('parent')" id="btn-parent"
                            class="role-btn group relative flex flex-col items-center justify-center p-6 border-2 border-gray-200 rounded-2xl bg-white transition-all duration-200 hover:border-indigo-300 hover:shadow-md cursor-pointer focus:outline-none">
                        <span class="text-4xl mb-3 group-hover:scale-110 transition-transform duration-300">👨‍👧</span>
                        <span class="text-sm font-semibold text-gray-800">Родител</span>
                        <div class="indicator absolute top-3 right-3 opacity-0 transition-opacity duration-200 text-indigo-600">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        </div>
                    </button>
                </div>
                <input type="hidden" name="role" id="role-input" value="{{ old('role') }}" />
                <x-input-error :messages="$errors->get('role')" class="mt-2 text-center" />
            </div>

            <div id="form-fields" class="{{ old('role') ? 'block' : 'hidden' }} space-y-5 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm transition-all duration-500">

                <div>
                    <x-input-label for="name" :value="__('Целосно име')" class="text-gray-700 font-medium" />
                    <x-text-input id="name" class="block mt-1.5 w-full" type="text" name="name" :value="old('name')" required autofocus />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="email" :value="__('Е-маил адреса')" class="text-gray-700 font-medium" />
                    <x-text-input id="email" class="block mt-1.5 w-full" type="email" name="email" :value="old('email')" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div id="child-email-field" class="{{ old('role') === 'parent' ? 'block' : 'hidden' }} border-l-4 border-indigo-500 pl-4 py-1 bg-indigo-50/30 rounded-r-lg">
                    <x-input-label for="child_email" :value="__('Е-маил на вашето дете')" class="text-indigo-900 font-medium" />
                    <x-text-input id="child_email" class="block mt-1.5 w-full border-indigo-200" type="email" name="child_email" :value="old('child_email')" placeholder="example@student.com" />
                    <p class="text-xs text-gray-500 mt-1.5 italic">Внесете го е-маилот со кој вашето дете веќе е регистрирано на SafeNet.</p>
                    <x-input-error :messages="$errors->get('child_email')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-2">
                    <div>
                        <x-input-label for="password" :value="__('Лозинка')" class="text-gray-700 font-medium" />
                        <x-text-input id="password" class="block mt-1.5 w-full" type="password" name="password" required />
                    </div>
                    <div>
                        <x-input-label for="password_confirmation" :value="__('Потврди')" class="text-gray-700 font-medium" />
                        <x-text-input id="password_confirmation" class="block mt-1.5 w-full" type="password" name="password_confirmation" required />
                    </div>
                </div>

                <div class="flex items-center justify-between mt-8 pt-4">
                    <a class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition-colors" href="{{ route('login') }}">
                        {{ __('Веќе сте регистрирани?') }}
                    </a>
                    <x-primary-button class="px-8 py-3 bg-indigo-600 rounded-xl shadow-md">
                        {{ __('Креирај сметка') }}
                    </x-primary-button>
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
                btn.classList.remove('border-indigo-600', 'ring-1', 'ring-indigo-600', 'bg-indigo-50/50');
                btn.classList.add('border-gray-200', 'bg-white');
                btn.querySelector('.indicator').classList.add('opacity-0');
            });

            const selected = document.getElementById('btn-' + role);
            selected.classList.remove('border-gray-200', 'bg-white');
            selected.classList.add('border-indigo-600', 'ring-1', 'ring-indigo-600', 'bg-indigo-50/50');
            selected.querySelector('.indicator').classList.remove('opacity-0');
        }

        document.addEventListener('DOMContentLoaded', function () {
            const existing = document.getElementById('role-input').value;
            if (existing) selectRole(existing);
        });
    </script>
</x-guest-layout>
