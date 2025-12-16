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
                    
                     @include('account.sections.help')
               
                </div>

            </main>

        </div>

    </div>
</section>
@include('partials._search')
@include('partials._faq')
@include('partials._footer')

@endsection



