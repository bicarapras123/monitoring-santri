<section class="w-full">

    <div class="bg-white rounded-2xl border border-red-100 shadow-sm overflow-hidden">

        <!-- Header -->
        <div class="px-4 py-4 border-b border-red-100 flex items-center justify-between gap-3">

            <div class="min-w-0">

                <h2 class="text-base font-semibold text-gray-800">
                    Hapus Akun
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Semua data akan terhapus permanen.
                </p>

            </div>

            <x-danger-button
                x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                class="rounded-xl px-4 py-2 text-sm shrink-0">

                Hapus

            </x-danger-button>

        </div>

    </div>

    <!-- Modal -->
    <x-modal name="confirm-user-deletion"
        :show="$errors->userDeletion->isNotEmpty()"
        focusable>

        <form method="post"
            action="{{ route('profile.destroy') }}"
            class="p-5">

            @csrf
            @method('delete')

            <h2 class="text-lg font-semibold text-gray-800">
                Hapus akun?
            </h2>

            <p class="mt-2 text-sm text-gray-500">
                Tindakan ini permanen. Masukkan password untuk melanjutkan.
            </p>

            <!-- Password -->
            <div class="mt-4">

                <x-input-label
                    for="password"
                    value="Password"
                    class="text-sm" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-2 block w-full rounded-xl border-gray-300 text-sm focus:border-red-500 focus:ring-red-500"
                    placeholder="Masukkan password" />

                <x-input-error
                    :messages="$errors->userDeletion->get('password')"
                    class="mt-2" />

            </div>

            <!-- Action -->
            <div class="mt-5 flex flex-col-reverse sm:flex-row sm:justify-end gap-2">

                <x-secondary-button
                    x-on:click="$dispatch('close')"
                    class="justify-center rounded-xl px-4 py-2 text-sm">

                    Batal

                </x-secondary-button>

                <x-danger-button
                    class="justify-center rounded-xl px-4 py-2 text-sm">

                    Ya, Hapus

                </x-danger-button>

            </div>

        </form>

    </x-modal>

</section>