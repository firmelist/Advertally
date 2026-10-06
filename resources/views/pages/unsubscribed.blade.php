@extends('layouts.app')

@section('content')
    <section class="section bg-hero">
        <div class="container-narrow text-center">
            <h1 class="h-page">You have been unsubscribed.</h1>
            <p class="lead mt-5">You will no longer receive the AI Growth Intelligence Brief. You can resubscribe from the footer at any time.</p>
            <a href="{{ route('home') }}" class="btn-secondary btn-lg mt-9">Back to home</a>
        </div>
    </section>
@endsection
