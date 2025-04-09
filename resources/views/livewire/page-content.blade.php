<div>
    <!-- Dynamic page content based on currentPage -->
    @if($currentPage === 'home')
        <livewire:home-page wire:key="home-page" />
    @elseif($currentPage === 'posts')
        <livewire:posts-page wire:key="posts-page" />
    @elseif($currentPage === 'about')
        <livewire:about-page wire:key="about-page" />
    @elseif($currentPage === 'contact')
        <livewire:contact-page wire:key="contact-page" />
    @elseif($currentPage === 'terms')
        <livewire:terms-page wire:key="terms-page" />
    @elseif($currentPage === 'login')
        <livewire:auth.login wire:key="login-page" />
    @elseif($currentPage === 'register')
        <livewire:auth.register wire:key="register-page" />
    @elseif($currentPage === 'profile')
        <livewire:profile.profile wire:key="profile-page" />
    @elseif($currentPage === 'forgot-password')
        <livewire:auth.forgot-password wire:key="forgot-password-page" />
    @elseif($currentPage === 'search')
        <livewire:search-results-page wire:key="search-results-page" />
    @endif
</div>