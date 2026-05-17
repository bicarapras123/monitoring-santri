<x-app-layout>

    <div class="p-3 md:p-5">

        <!-- Header -->
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">

            <div>
                <h1 class="text-xl md:text-2xl font-bold text-gray-800">
                    Profile Saya
                </h1>

                <p class="text-sm text-gray-500">
                    Pengaturan akun & keamanan
                </p>
            </div>

            <!-- Mini User -->
            <div class="flex items-center gap-2 bg-white border border-gray-100 rounded-xl px-3 py-2 shadow-sm">

                <div class="h-10 w-10 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-700 font-semibold text-sm shrink-0">
                    {{ strtoupper(substr(Auth::user()->name,0,1)) }}
                </div>

                <div class="leading-tight max-w-[140px]">
                    <h2 class="font-medium text-gray-800 text-sm truncate">
                        {{ Auth::user()->name }}
                    </h2>

                    <p class="text-xs text-gray-500 capitalize truncate">
                        {{ str_replace('_', ' ', Auth::user()->role) }}
                    </p>
                </div>

            </div>

        </div>

        <!-- Content -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-2">

            <!-- Left -->
            <div class="xl:col-span-2 space-y-4">

                <!-- Profile -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <!-- Password -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
                    @include('profile.partials.update-password-form')
                </div>

            </div>

                <!-- Delete -->
                <div class="bg-white rounded-2xl border border-red-100 shadow-sm p-4">
                    @include('profile.partials.delete-user-form')
                </div>

            </div>

        </div>

    </div>

</x-app-layout>