@extends('layouts.app')

@section('content')
  <a id="home"></a>
  
  @include('partials._hero')
  
  
  
  <a id="features"></a>
  @include('partials._features')
  
  <a id="pricing"></a>
  
  <!-- Pass data to pricing partial -->
  @include('partials._pricing', [
      'initialType' => $initialType ?? 'local',
      'initialBundles' => $initialBundles ?? [],
      'allPlans' => $allPlans ?? []
  ])
  
  @include('partials._how-it-works')
  @include('partials._feature-overview')
  
  <a id="testimonials"></a>
  @include('partials._testimonials')
  
  <a id="faq"></a>
  @include('partials._faq')
  
  @include('partials._footer')
@endsection