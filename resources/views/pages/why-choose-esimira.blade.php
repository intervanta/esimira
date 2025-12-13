@extends('layouts.app')

@section('title', 'Why Choose Esimira – Global eSIM Data Plans')
@section('meta_description', 'Discover why Esimira is the best choice for travelers. Global coverage, instant activation, affordable plans, and 24/7 support.')
@section('meta_keywords', 'why choose esimira, esim benefits, travel esim advantages, global data coverage, instant activation esim')

@section('content')

<div class="flex flex-col justify-start items-center h-[68vh]  w-full bg-[url('../assets/images/terms-bg.png')] bg-cover bg-center relative">
    <section id="hero" class="hero-section max-w-[1140px] mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 lg:py-[196px] relative z-10">
        <div class="container hero-content">
            <h1>Why Choose Esimira</h1>
            <p class="breadcrumbs">Home → Why Choose Esimira</p>
        </div>
    </section>
</div>
 @include('partials._search')
<section id="benefits" class="checkout-section py-16">
    <div class="w-full max-w-[1140px] mx-auto px-4 sm:px-6 lg:px-8 gap-24">
        <h2 class="section-title">Why Travelers <span class="highlight">Choose Esimira</span></h2>
        <div class="text-content">
            <p>Esimira revolutionizes the way travelers stay connected abroad. Our eSIM technology eliminates the need for physical SIM cards, offering instant connectivity in over 200 countries worldwide. With flexible data plans tailored to your travel needs, you can enjoy seamless internet access without the hassle of searching for local SIM vendors or paying exorbitant roaming fees.</p>
            <p>Our platform is designed with modern travelers in mind - whether you're on a short business trip, extended vacation, or backpacking adventure. We provide reliable high-speed data that keeps you connected to what matters most: navigation, communication with loved ones, sharing experiences on social media, or staying productive while on the move.</p>
        </div>
    </div>
</section>

<section id="advantages" class="checkout-section py-16">
    <div class="w-full max-w-[1140px] mx-auto px-4 sm:px-6 lg:px-8 gap-24">
        <h2 class="section-title">Key <span class="highlight">Advantages</span></h2>
        <div class="policies-content">
            <div class="text-column">
                <div class="advantage-item mb-8">
                    <h3 class="text-xl font-bold mb-2">Global Coverage</h3>
                    <p>Access data in 200+ countries with our extensive network of local carriers. Whether you're visiting popular destinations or remote locations, we've got you covered with reliable connectivity.</p>
                </div>
                <div class="advantage-item mb-8">
                    <h3 class="text-xl font-bold mb-2">Instant Activation</h3>
                    <p>Get connected immediately after purchase. No waiting in lines, no physical SIM cards to insert - just scan the QR code and you're online in minutes.</p>
                </div>
                <div class="advantage-item mb-8">
                    <h3 class="text-xl font-bold mb-2">Flexible Plans</h3>
                    <p>Choose from daily, weekly, or monthly plans that match your travel duration. Our pay-as-you-go model ensures you only pay for what you need.</p>
                </div>
                <div class="advantage-item">
                    <h3 class="text-xl font-bold mb-2">Cost Effective</h3>
                    <p>Save up to 90% compared to traditional roaming charges. Our local pricing model gives you access to affordable data rates in every country.</p>
                </div>
            </div>
            <div class="image-column">
                <div style="position: relative; width: 372.29px; height: 250px;">
                    <img src="../assets/images/why-choose-advantages.png" alt="Esimira advantages illustration">
                </div>
            </div>
        </div>
    </div>
</section>

<section id="features" class="checkout-section py-16">
    <div class="w-full max-w-[1140px] mx-auto px-4 sm:px-6 lg:px-8 gap-24">
        <h2 class="section-title">Premium <span class="highlight">Features</span></h2>
        <div class="text-content">
            <p>Esimira goes beyond basic connectivity with features designed specifically for travelers:</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
                <div class="feature-card p-6 rounded-lg shadow-md">
                    <div class="flex items-center mb-4">
                        <div class="bg-blue-100 p-3 rounded-full mr-4">
                            <i class="fas fa-bolt text-blue-600"></i>
                        </div>
                        <h3 class="text-lg font-bold">Easy Switching</h3>
                    </div>
                    <p>Switch between multiple eSIM profiles without removing your primary SIM. Keep your home number active while using local data abroad.</p>
                </div>
                
                <div class="feature-card p-6 rounded-lg shadow-md">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-100 p-3 rounded-full mr-4">
                            <i class="fas fa-shield-alt text-green-600"></i>
                        </div>
                        <h3 class="text-lg font-bold">Secure Connection</h3>
                    </div>
                    <p>Enjoy encrypted connections on trusted local networks. Your data remains secure while browsing on our partner carriers.</p>
                </div>
                
                <div class="feature-card p-6 rounded-lg shadow-md">
                    <div class="flex items-center mb-4">
                        <div class="bg-purple-100 p-3 rounded-full mr-4">
                            <i class="fas fa-mobile-alt text-purple-600"></i>
                        </div>
                        <h3 class="text-lg font-bold">Dual SIM Capability</h3>
                    </div>
                    <p>Modern smartphones support eSIM technology alongside physical SIMs. Use both simultaneously for calls and data.</p>
                </div>
                
                <div class="feature-card p-6 rounded-lg shadow-md">
                    <div class="flex items-center mb-4">
                        <div class="bg-orange-100 p-3 rounded-full mr-4">
                            <i class="fas fa-headset text-orange-600"></i>
                        </div>
                        <h3 class="text-lg font-bold">24/7 Support</h3>
                    </div>
                    <p>Our customer support team is available around the clock to assist with activation issues or connectivity questions.</p>
                </div>
            </div>
            
            <p class="mt-8">With Esimira, you're not just buying data - you're investing in peace of mind during your travels. Our technology ensures you stay connected from the moment you land until you return home, making your journey smoother and more enjoyable.</p>
        </div>
    </div>
</section>


@include('partials._faq')
@include('partials._footer')
@endsection