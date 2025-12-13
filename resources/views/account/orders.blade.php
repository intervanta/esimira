@extends('layouts.app')
<style>
    .sidebar a.active {
        background: #ef7f50;
        color: #fff;
        border-radius: 10px;
    }

    .sidebar a.active img {
        filter: brightness(0) invert(1);
    }
</style>
@section('content')
<!-- Dashboard Background Wrapper (REPLACES OLD BANNER SPACING) -->
<section class="w-full bg-[url('{{ asset('assets/images/plan_home_banner.png') }}')] bg-cover bg-center bg-no-repeat pt-28 pb-20">

    <div class="w-full max-w-[1300px] mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col lg:flex-row gap-6 lg:gap-8">

            <!-- Sidebar -->
            <aside class="w-full lg:w-96 xl:w-[16rem] flex-shrink-0">
                @include('layouts.sidebar')
            </aside>

            <!-- Main Dashboard Box -->
            <main class="flex-1 bg-white rounded-2xl shadow-lg p-6 sm:p-8 lg:p-10">
                
                {{-- <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-8">
                    Hi <strong>{{ auth()->user()->name ?? 'Tommy' }}</strong> 👋
                </h2> --}}

                <div class="content-box space-y-12">
                    
                     @include('account.sections.orders')
               
                </div>

            </main>

        </div>

    </div>
</section>
@include('partials._search')
@include('partials._faq')
@include('partials._footer')

@endsection


{{-- @push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const links = document.querySelectorAll('#sidebarMenu a');

    links.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();

            // Active link highlight
            links.forEach(a => a.classList.remove('active'));
            this.classList.add('active');

            // Hide all content sections
            document.querySelectorAll('.content-box > div').forEach(div => {
                div.classList.add('hidden');
            });

            // Show selected target
            const target = this.getAttribute('data-target');
            const targetEl = document.getElementById(target);
            if (targetEl) {
                targetEl.classList.remove('hidden');

                // Smooth scroll fix
                setTimeout(() => {
                    targetEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    window.scrollBy(0, -120);
                }, 100);
            }

            // Change icon
            const img = this.querySelector('img');
            if (img && this.dataset.iconWhite && this.dataset.iconBlack) {
                img.src = this.classList.contains('active')
                    ? this.dataset.iconWhite
                    : this.dataset.iconBlack;
            }
        });
    });

    // Auto-load first section
    const activeLink =
        document.querySelector('#sidebarMenu a.active') ||
        document.querySelector('#sidebarMenu a[data-target="account-info"]') ||
        links[0];

    activeLink?.click();
});
</script>
@endpush --}}
