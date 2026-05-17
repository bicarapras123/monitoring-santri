<section class="w-full h-full">

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden h-full flex flex-col">

        <!-- Header -->
        <div class="px-4 md:px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-white shrink-0">

            <h2 class="text-lg md:text-xl font-semibold text-gray-800">
                Profile Information
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Update informasi akun & email.
            </p>

        </div>

        <!-- Scroll Area -->
        <div class="overflow-y-auto max-h-[75vh]">

            <!-- Verification Form -->
            <form id="send-verification"
                method="post"
                action="{{ route('verification.send') }}">

                @csrf

            </form>

            <!-- Main Form -->
            <form method="post"
                action="{{ route('profile.update') }}"
                class="p-4 md:p-5 space-y-5">

                @csrf
                @method('patch')

                <!-- Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <!-- Name -->
                    <div>

                        <x-input-label
                            for="name"
                            :value="__('Nama Lengkap')"
                            class="text-sm font-medium text-gray-700" />

                        <x-text-input
                            id="name"
                            name="name"
                            type="text"
                            class="mt-2 block w-full rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                            :value="old('name', $user->name)"
                            required
                            autofocus
                            autocomplete="name" />

                        <x-input-error
                            class="mt-1"
                            :messages="$errors->get('name')" />

                    </div>

                    <!-- Email -->
                    <div>

                        <x-input-label
                            for="email"
                            :value="__('Email')"
                            class="text-sm font-medium text-gray-700" />

                        <x-text-input
                            id="email"
                            name="email"
                            type="email"
                            class="mt-2 block w-full rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                            :value="old('email', $user->email)"
                            required
                            autocomplete="username" />

                        <x-input-error
                            class="mt-1"
                            :messages="$errors->get('email')" />

                    </div>

                </div>

                <!-- Verification -->
                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())

                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">

                        <div class="flex items-start gap-3">

                            <div class="bg-yellow-100 p-2 rounded-lg shrink-0">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 text-yellow-600"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor">

                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8v4m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/>

                                </svg>

                            </div>

                            <div class="flex-1 min-w-0">

                                <p class="text-sm text-yellow-800">
                                    Email belum diverifikasi.
                                </p>

                                <button
                                    form="send-verification"
                                    class="mt-3 inline-flex items-center px-3 py-2 rounded-lg bg-yellow-500 text-white text-xs font-medium hover:bg-yellow-600 transition">

                                    Kirim Verifikasi

                                </button>

                                @if (session('status') === 'verification-link-sent')

                                    <p class="mt-2 text-xs font-medium text-green-600">
                                        Link berhasil dikirim ulang.
                                    </p>

                                @endif

                            </div>

                        </div>

                    </div>

                @endif

                <!-- Action -->
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 pt-1">

                    <x-primary-button class="px-5 py-2.5 rounded-xl text-sm">

                        Simpan Perubahan

                    </x-primary-button>

                    @if (session('status') === 'profile-updated')

                        <p
                            x-data="{ show: true }"
                            x-show="show"
                            x-transition
                            x-init="setTimeout(() => show = false, 2000)"
                            class="text-sm text-green-600 font-medium">

                            Profil berhasil diperbarui.

                        </p>

                    @endif

                </div>

            </form>

        </div>

    </div>

</section>