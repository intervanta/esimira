@extends('layouts.app')

@section('title', 'About Esimira – Global eSIM Solutions for Travelers')
@section('meta_description', 'Learn about Esimira, a global digital eSIM service offering fast, secure, and reliable connectivity for travelers worldwide.')
@section('meta_keywords', 'about esimira, esimira company, travel esim, global esim provider')

@section('content')

<div class="flex flex-col justify-start items-center h-[68vh] w-full  bg-[url('../assets/images/terms-bg.png')] bg-cover bg-center relative">

<section id="hero" class="hero-section max-w-[1140px] mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 lg:py-[196px] relative z-10">
  <div class="container hero-content">
    <h1>About Us</h1>
    <p class="breadcrumbs">Home → About Us</p>
  </div>
</section>

</div>

{{-- SECTION 1 — WHO WE ARE --}}
<section id="user-agreement" class="checkout-section py-16">
   <div class="w-full max-w-[1140px] mx-auto px-4 sm:px-6 lg:px-8 gap-24">

    <h2 class="section-title">Who <span class="highlight">We Are</span></h2>

    <div class="text-content">
      <p>
        Esimira is a global digital eSIM service designed for modern travelers, remote workers, and anyone who needs seamless mobile connectivity worldwide. 
        Our mission is simple — to make mobile data easy, accessible, and instant without relying on physical SIM cards or lengthy activation processes.
      </p>

      <p>
        With Esimira, you can stay connected in 150+ countries using secure, fast, and reliable digital eSIM technology. Whether you’re exploring new destinations, working internationally, or traveling frequently, Esimira ensures uninterrupted connectivity wherever your journey takes you.
      </p>

      <p>
        We are committed to creating a smooth, digital-first experience with instant activation, affordable plans, transparent pricing, and a completely hassle-free setup. No waiting, no contracts, no physical SIM delivery — just pure connectivity.
      </p>
    </div>

  </div>
</section>

{{-- SECTION 2 — OUR VISION & MISSION --}}
<section id="policies" class="checkout-section py-16">
<div class="w-full max-w-[1140px] mx-auto px-4 sm:px-6 lg:px-8 gap-24">

    <h2 class="section-title">Our Vision & <span class="highlight">Mission</span></h2>

    <div class="policies-content">
      <div class="text-column">
        <p>
          Our vision is to transform how people connect globally by eliminating the limits of traditional SIM cards and roaming charges. 
          We believe that travel should be stress-free, and staying connected should not come with hidden costs or complicated processes.
        </p>

        <p>
          Esimira’s mission is to provide world-class digital connectivity through a platform that is simple, transparent, and truly global. 
          We aim to empower travelers with the freedom to use mobile data anywhere in the world instantly.
        </p>
      </div>

      <div class="image-column">
        <div style="position: relative; width: 372.29px; height: 250px;">
          <img src="../assets/images/policies.png" alt="About Esimira illustration">
        </div>
      </div>
    </div>

  </div>
</section>

{{-- SECTION 3 — OUR VALUES --}}
<section id="legal-info" class="checkout-section py-16">
<div class="w-full max-w-[1140px] mx-auto px-4 sm:px-6 lg:px-8 gap-24">

    <h2 class="section-title">Our <span class="highlight">Values</span></h2>

    <div class="text-content">

      <p>
        <strong>❖ Innovation First:</strong>  
        We embrace cutting-edge eSIM technology to deliver the fastest and most efficient mobile connectivity solutions.
      </p>

      <p>
        <strong>❖ Global Freedom:</strong>  
        We remove barriers to communication by enabling travelers to enjoy seamless data connectivity across borders.
      </p>

      <p>
        <strong>❖ Transparency & Trust:</strong>  
        No hidden fees, no complicated contracts — just straightforward plans that work.
      </p>

      <p>
        <strong>❖ Customer-Centric Approach:</strong>  
        From instant delivery to 24/7 support, every part of Esimira is built with user convenience in mind.
      </p>

      <p>
        Esimira continues to evolve as we expand our global coverage, refine our technology, and create smarter ways for people to stay connected. 
        Wherever you go — Esimira goes with you.
      </p>

    </div>

  </div>
</section>
  @include('partials._footer')
@endsection
