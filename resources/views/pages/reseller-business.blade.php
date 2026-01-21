@extends('layouts.app')
<style>
    .reseller-success-message {
        background: #e7f7ed;
        border: 1px solid #42b883;
        color: #2f855a;
        padding: 12px 16px;
        font-size: 14px;
        border-radius: 6px;
        margin-bottom: 20px;
        animation: fadeIn 0.4s ease;
    }

    .spinner {
        border: 2px solid #fff;
        border-top: 2px solid rgba(255, 255, 255, 0.4);
        border-radius: 50%;
        width: 16px;
        height: 16px;
        display: inline-block;
        animation: spin 0.6s linear infinite;
        margin-right: 6px;
        vertical-align: middle;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>

@section('title', 'Reseller – Business')
@section('meta_description',
    'Discover why Esimira is the best choice for travelers. Global coverage, instant
    activation, affordable plans, and 24/7 support.')
@section('meta_keywords',
    'why choose esimira, esim benefits, travel esim advantages, global data coverage, instant
    activation esim')

@section('content')
    <div
        class="flex flex-col justify-start items-center h-[66vh] w-full  bg-[url('../assets/images/terms-bg.png')] bg-cover bg-center relative">

        <section id="hero"
            class="hero-section max-w-[1140px] mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 lg:py-[196px] relative z-10">
            <div class="container hero-content">
                <div class="hero-content-reseller">
                    <h1 class="hero-title-reseller">Reseller <span class="highlight-reseller">Business</span></h1>
                </div>
                <p class="breadcrumbs">Home → Reseller Business</p>

            </div>
        </section>

    </div>


    </div>
    <section id="section-main">
        <div class="main-wrapper">

            <!-- Left Column: Content & Image -->
            <div class="content-column">
                <div class="text-block">
                    <h2>Ready to start your eSIM<br><span class="highlight-reseller">Business?</span></h2>
                    <p class="subtitle">Access wholesale rates, dedicated onboarding assistance, and your own partner
                        dashboard today.</p>
                </div>

                <div class="image-block">
                    <!-- Merged Image Representation for the overlapping/masked images -->
                    <div class="merged-image-container-reseller">
                        <img src="../assets/images/fb82ae8554bff1ab93d6e4d3493b9abde0e8a529.png" class="img-back"
                            alt="Office Background">
                        <img src="../assets/images/d86b42ee94f1a4982c0c6ab44fda2c47e1c91013.png" class="img-front"
                            alt="Handshake">
                        <!--merged image-->
                    </div>
                </div>
            </div>

            <!-- Right Column: Form -->
            <div class="form-column" id="formColumn">

                <div class="form-card">
                    <h3>Personal Details</h3>
                    @if (session('success'))
                        <div class="reseller-success-message">
                            {{ session('success') }}
                        </div>
                    @endif


                    <form class="details-form" method="POST" action="{{ route('reseller.enquiry.submit') }}">
                        @csrf

                        <div class="form-group-reseller">
                            <label>Name</label>
                            <div class="input-wrapper">
                                <input type="text" name="name" placeholder="Enter your name" required>
                            </div>
                        </div>

                        <div class="form-group-reseller">
                            <label>Email</label>
                            <div class="input-wrapper">
                                <input type="email" name="email" placeholder="Example@email.com" required>
                            </div>
                        </div>


                        <div class="form-group-reseller">
                            {{-- <label>Password</label>
                <div class="input-wrapper">
                    <input type="password" name="password" placeholder="At least 8 characters" required>
                    <img src="../assets/images/411_426.svg" class="icon-eye" alt="Show Password">
                </div> --}}
                        </div>


                        <div class="form-group-reseller">
                            <label>Phone number</label>
                            <div class="input-wrapper">
                                <input type="tel" name="phone" placeholder="Enter your number" required>
                            </div>
                        </div>

                        <div class="form-group-reseller">
                            <label>WhatsApp number</label>
                            <div class="input-wrapper">
                                <input type="tel" name="whatsapp" placeholder="Enter your WhatsApp number">
                            </div>
                        </div>

                        <div class="form-group-reseller">
                            <label>Do you have a website?</label>
                            <div class="input-wrapper select-wrapper">
                                <select name="has_website" required>
                                    <option value="No">No</option>
                                    <option value="Yes">Yes</option>
                                </select>
                                <img src="../assets/images/411_444.svg" class="icon-chevron" alt="Select">
                            </div>
                        </div>

                        <div class="form-group-reseller">
                            <label>What language do you speak?</label>
                            <div class="input-wrapper select-wrapper">
                                <select name="language" required>
                                    <option value="">Select your language</option>
                                    <option value="English">English</option>
                                    <option value="Spanish">Spanish</option>
                                </select>
                                <img src="../assets/images/411_451.svg" class="icon-chevron" alt="Select">
                            </div>
                        </div>
                        <br><br>
                        <button type="submit" id="btnSubmit" class="btn-continue">
                            Continue
                        </button>

                    </form>
                </div>
            </div>


        </div>
    </section>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // -------------------------------------------------------
            // 1️⃣ Button Loading on Submit
            // -------------------------------------------------------

            const form = document.querySelector('.details-form');
            const btn = document.getElementById('btnSubmit');

            if (form && btn) {
                form.addEventListener('submit', function() {

                    // Disable button
                    btn.disabled = true;

                    // Add spinner + text
                    btn.innerHTML = `
                <span class="spinner"></span> Submitting...
            `;

                    // Add optional loading class
                    btn.classList.add('loading');
                });
            }

            // -------------------------------------------------------
            // 2️⃣ Scroll to form when success message appears
            // -------------------------------------------------------

            const successMsg = document.querySelector('.reseller-success-message');

            if (successMsg) {
                const formColumn = document.getElementById('formColumn');

                if (formColumn) {
                    formColumn.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }

        });
    </script>







@endsection
