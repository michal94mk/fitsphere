<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium text-gray-900">Profil trenera</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Zarządzaj swoim profilem, danymi osobowymi oraz innymi ustawieniami.
                </p>
            </div>
        </div>

        <div class="mt-5 md:mt-0 md:col-span-2">
            <!-- Komponent do aktualizacji profilu trenera -->
            <livewire:trainer.profile.update-trainer-profile />
        </div>
    </div>

    <div class="hidden sm:block" aria-hidden="true">
        <div class="py-5">
            <div class="border-t border-gray-200"></div>
        </div>
    </div>

    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium text-gray-900">Hasło</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Upewnij się, że Twoje konto używa silnego hasła, aby zapewnić bezpieczeństwo.
                </p>
            </div>
        </div>

        <div class="mt-5 md:mt-0 md:col-span-2">
            <!-- Komponent do aktualizacji hasła -->
            <livewire:trainer.profile.update-trainer-password />
        </div>
    </div>

    <div class="hidden sm:block" aria-hidden="true">
        <div class="py-5">
            <div class="border-t border-gray-200"></div>
        </div>
    </div>

    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium text-gray-900">Usunięcie konta</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Jeśli chcesz trwale usunąć swoje konto, kliknij poniższy przycisk. Operacja jest nieodwracalna.
                </p>
            </div>
        </div>

        <div class="mt-5 md:mt-0 md:col-span-2">
            <!-- Komponent do usuwania konta -->
            <livewire:trainer.profile.delete-trainer-account />
        </div>
    </div>
</div> 