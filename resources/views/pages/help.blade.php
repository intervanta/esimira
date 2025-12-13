@extends('layouts.app')

@section('title', 'Help Center – Esimira Global eSIM Support')
@section('meta_description', 'Get help with Esimira eSIM activation, installation, troubleshooting, MiraVault credits, refunds, device compatibility, and more.')
@section('meta_keywords', 'esim help, esimira support, esim troubleshooting, install esim, esim refund help')

@section('content')

<div class="flex flex-col justify-start items-center h-[68vh] w-full bg-[url('../assets/images/terms-bg.png')] bg-cover bg-center relative">

<section id="hero" class="hero-section max-w-[1140px] mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 lg:py-[196px] relative z-10">
  <div class="container hero-content">
    <h1>Help Center</h1>
    <p class="breadcrumbs">Home → Help Center</p>
  </div>
</section>

</div>

{{-- SECTION 1 — HELP TILES (GLOBAL MODERN DESIGN) --}}
<section id="help-tiles" class="checkout-section py-16">
  <div class="w-full max-w-[1140px] mx-auto px-4 sm:px-6 lg:px-8">

    <h2 class="section-title text-center">How can we <span class="highlight">help you today?</span></h2>

    <p class="text-center text-gray-600 max-w-2xl mx-auto mt-4 mb-12">
      Explore our support topics to quickly find answers, guides, and the help you need.
      From installation to refunds — everything is covered.
    </p>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

      <!-- Tile -->
      <div class="p-7 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-lg transition cursor-pointer">
        <img src="/assets/icons/about.svg" class="h-10 mb-4" alt="">
        <h3 class="font-semibold text-xl mb-2">About Esimira</h3>
        <p class="text-gray-600 text-sm leading-relaxed">
          Discover our mission, global coverage, and how Esimira makes travel connectivity simple, fast, and borderless.
        </p>
      </div>

      <!-- Tile -->
      <div class="p-7 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-lg transition cursor-pointer">
        <img src="/assets/icons/wallet.svg" class="h-10 mb-4" alt="">
        <h3 class="font-semibold text-xl mb-2">MiraVault Wallet</h3>
        <p class="text-gray-600 text-sm leading-relaxed">
          Learn how MiraVault stores your credits, rewards, referral points, and top-up history securely.
        </p>
      </div>

      <!-- Tile -->
      <div class="p-7 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-lg transition cursor-pointer">
        <img src="/assets/icons/how.svg" class="h-10 mb-4" alt="">
        <h3 class="font-semibold text-xl mb-2">How Esimira Works</h3>
        <p class="text-gray-600 text-sm leading-relaxed">
          Step-by-step explanation of buying an eSIM, activating it, and using it while traveling worldwide.
        </p>
      </div>

      <!-- Tile -->
      <div class="p-7 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-lg transition cursor-pointer">
        <img src="/assets/icons/device.svg" class="h-10 mb-4" alt="">
        <h3 class="font-semibold text-xl mb-2">Device Compatibility</h3>
        <p class="text-gray-600 text-sm leading-relaxed">
          Check whether your smartphone supports eSIM. View full compatibility lists for major brands.
        </p>
      </div>

      <!-- Tile -->
      <div class="p-7 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-lg transition cursor-pointer">
        <img src="/assets/icons/installation.svg" class="h-10 mb-4" alt="">
        <h3 class="font-semibold text-xl mb-2">Installation Guides</h3>
        <p class="text-gray-600 text-sm leading-relaxed">
          Get installation instructions for iOS, Samsung, Pixel, Huawei, and manual SM-DP+ activation.
        </p>
      </div>

      <!-- Tile -->
      <div class="p-7 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-lg transition cursor-pointer">
        <img src="/assets/icons/troubleshoot.svg" class="h-10 mb-4" alt="">
        <h3 class="font-semibold text-xl mb-2">Troubleshooting</h3>
        <p class="text-gray-600 text-sm leading-relaxed">
          Fix issues like slow data, activation failure, QR not scanning, or incompatible device alerts.
        </p>
      </div>

      <!-- Tile -->
      <div class="p-7 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-lg transition cursor-pointer">
        <img src="/assets/icons/faq.svg" class="h-10 mb-4" alt="">
        <h3 class="font-semibold text-xl mb-2">FAQs</h3>
        <p class="text-gray-600 text-sm leading-relaxed">
          Browse answers to commonly asked questions about plans, activation, usage, validity, and more.
        </p>
      </div>

      <!-- Tile -->
      <div class="p-7 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-lg transition cursor-pointer">
        <img src="/assets/icons/refund.svg" class="h-10 mb-4" alt="">
        <h3 class="font-semibold text-xl mb-2">Refunds & Billing</h3>
        <p class="text-gray-600 text-sm leading-relaxed">
          Learn refund eligibility rules, payment problems, unused eSIM refunds, and billing assistance.
        </p>
      </div>

      <!-- Tile -->
      <div class="p-7 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-lg transition cursor-pointer">
        <img src="/assets/icons/account.svg" class="h-10 mb-4" alt="">
        <h3 class="font-semibold text-xl mb-2">Account & Security</h3>
        <p class="text-gray-600 text-sm leading-relaxed">
          Manage your Esimira account, login problems, password reset, and device security.
        </p>
      </div>

      <!-- Tile -->
      <div class="p-7 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-lg transition cursor-pointer">
        <img src="/assets/icons/network.svg" class="h-10 mb-4" alt="">
        <h3 class="font-semibold text-xl mb-2">Network & Coverage</h3>
        <p class="text-gray-600 text-sm leading-relaxed">
          Understand network partners, roaming support, 4G/LTE availability, and destination coverage.
        </p>
      </div>

      <!-- Tile -->
      <div class="p-7 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-lg transition cursor-pointer">
        <img src="/assets/icons/plans.svg" class="h-10 mb-4" alt="">
        <h3 class="font-semibold text-xl mb-2">Plans & Validity</h3>
        <p class="text-gray-600 text-sm leading-relaxed">
          Compare data packages, usage validity, top-ups, and multi-country plans.
        </p>
      </div>

      <!-- Tile -->
      <div class="p-7 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-lg transition cursor-pointer">
        <img src="/assets/icons/support.svg" class="h-10 mb-4" alt="">
        <h3 class="font-semibold text-xl mb-2">Contact Support</h3>
        <p class="text-gray-600 text-sm leading-relaxed">
          Need help? Reach us anytime at <strong>support@esimira.com</strong> — we’re available 24/7.
        </p>
      </div>

    </div>
  </div>
</section>

{{-- SECTION 2 — QUICK HELP CONTENT --}}
<section id="quick-help" class="checkout-section py-16">
  <div class="w-full max-w-[1140px] mx-auto px-4 sm:px-6 lg:px-8">

    <h2 class="section-title">Quick <span class="highlight">Help Topics</span></h2>

    <div class="text-content">

      <p><strong>✔ Installing your eSIM</strong><br>
        After purchase, you receive a QR code and manual activation details.  
        Scan the QR or install manually under your device network settings.
      </p>

      <p><strong>✔ When your plan starts</strong><br>
        Your plan activates only when the eSIM profile is installed on your device — not at purchase.
      </p>

      <p><strong>✔ eSIM not activating?</strong><br>
        Ensure roaming is ON, restart your device, and try manual SM-DP+ activation.
      </p>

      <p><strong>✔ Refund eligibility</strong><br>
        Refunds are possible only if the eSIM is unused, uninstalled, and verified by support.
      </p>

      <p><strong>✔ MiraVault help</strong><br>
        Use MiraVault to manage credits, rewards, and referrals securely in one place.
      </p>

      <p><strong>✔ Need more help?</strong><br>
        We are here 24/7 — support@esimira.com
      </p>

    </div>
  </div>
</section>

@include('partials._faq')
@include('partials._footer')
@endsection
