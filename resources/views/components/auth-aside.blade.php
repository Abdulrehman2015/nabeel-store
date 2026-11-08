@props([
    'eyebrow' => store_name() . ' customer access',
    'title',
    'description',
    'note' => 'Trusted supply for growing businesses',
])

<aside class="auth-aside">
    <a href="{{ route('home') }}" class="auth-aside-brand">
        <img src="{{ setting('site_logo') ? asset('storage/' . setting('site_logo')) : asset('images/logo.png') }}" alt="{{ store_name() }}">
        <span>Customer portal</span>
    </a>

    <div class="auth-aside-copy">
        <span class="auth-eyebrow">{{ $eyebrow }}</span>
        <h1>{{ $title }}</h1>
        <p>{{ $description }}</p>

        <div class="auth-perks">
            <div class="auth-perk"><span class="auth-perk-icon">✓</span><span>Great prices on everyday essentials</span></div>
            <div class="auth-perk"><span class="auth-perk-icon">↗</span><span>Simple, reliable doorstep delivery</span></div>
            <div class="auth-perk"><span class="auth-perk-icon">⌁</span><span>Protected account verification</span></div>
        </div>
    </div>

    <img src="{{ asset('images/delivery-3d.png') }}" alt="Delivery partner with a trolley" class="auth-aside-art">
    <span class="auth-aside-note">{{ $note }}</span>
</aside>
