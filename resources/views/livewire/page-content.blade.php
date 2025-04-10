<div>
    <div x-data="{ 
        show: true, 
        currentContent: @entangle('currentPage')
    }"
         x-init="
            $watch('currentContent', () => {
                show = false;
                setTimeout(() => {
                    show = true;
                }, 100);
            })
         ">
        <div x-show="show" 
             x-transition:enter="transition ease-out duration-250"
             x-transition:enter-start="opacity-0 transform scale-99"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-99">
            
            @if($currentPage === 'home')
                <livewire:home-page />
            @elseif($currentPage === 'about')
                <livewire:about-page />
            @elseif($currentPage === 'contact')
                <livewire:contact-page />
            @elseif($currentPage === 'search')
                <livewire:search-results-page />
            @elseif($currentPage === 'terms')
                <livewire:terms-page />
            @elseif($currentPage === 'posts')
                <livewire:posts-page />
            @elseif($currentPage === 'profile')
                <livewire:profile.profile />
            @elseif($currentPage === 'login')
                <livewire:auth.login />
            @elseif($currentPage === 'register')
                <livewire:auth.register />
            @else
                <livewire:home-page />
            @endif
        </div>
    </div>
</div>