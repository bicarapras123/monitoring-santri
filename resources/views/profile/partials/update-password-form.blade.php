<section class="w-full">

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">

        <!-- Header -->
        <div class="flex items-center justify-between gap-3 mb-4">

            <div>
                <h2 class="text-base font-semibold text-gray-800">
                    Update Password
                </h2>

                <p class="text-xs text-gray-500">
                    Ganti password akun Anda.
                </p>
            </div>

        </div>

        <!-- Form -->
        <form method="post"
            action="{{ route('password.update') }}"
            class="space-y-3">

            @csrf
            @method('put')

            <!-- Current Password -->
            <div>

                <x-text-input
                    id="update_password_current_password"
                    name="current_password"
                    type="password"
                    class="block w-full rounded-xl border-gray-300 text-sm"
                    autocomplete="current-password"
                    placeholder="Password saat ini" />

                <x-input-error
                    :messages="$errors->updatePassword->get('current_password')"
                    class="mt-1 text-xs" />

            </div>

            <!-- New Password -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

                <div>

                    <x-text-input
                        id="update_password_password"
                        name="password"
                        type="password"
                        class="block w-full rounded-xl border-gray-300 text-sm"
                        autocomplete="new-password"
                        placeholder="Password baru" />

                    <x-input-error
                        :messages="$errors->updatePassword->get('password')"
                        class="mt-1 text-xs" />

                </div>

                <div>

                    <x-text-input
                        id="update_password_password_confirmation"
                        name="password_confirmation"
                        type="password"
                        class="block w-full rounded-xl border-gray-300 text-sm"
                        autocomplete="new-password"
                        placeholder="Konfirmasi password" />

                    <x-input-error
                        :messages="$errors->updatePassword->get('password_confirmation')"
                        class="mt-1 text-xs" />

                </div>

            </div>

            <!-- Action -->
            <div class="flex items-center justify-between gap-3 pt-1">

                <x-primary-button
                    class="rounded-xl px-4 py-2 text-sm">

                    Simpan

                </x-primary-button>

                @if (session('status') === 'password-updated')

                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="text-xs text-emerald-600 font-medium">

                        Berhasil disimpan

                    </p>

                @endif

            </div>

        </form>

    </div>

</section>