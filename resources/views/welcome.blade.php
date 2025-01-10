@extends('layouts.app')

@section('content')
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<div class="bg-gray-100 h-sceen flex items-center justify-center ">
    <div class="text-center">
        <lottie-player 
            src="https://assets3.lottiefiles.com/packages/lf20_ikvz7qhc.json" 
            background="transparent" 
            speed="1" 
            style="width: 400px; height: 400px; margin:auto;" 
            autoplay loop playMode="normal" >
        </lottie-player>
        <h1 class="text-3xl font-bold text-gray-800 ">Bienvenue sur le système de gestion de <span class="wave-animation">Promostone -Industry</span></h1>
        <p class="text-gray-600 mt-4">
            Cette application a été spécialement conçue pour l'équipe de <span class="font-bold">Promostone Industry</span>, afin de simplifier et d'optimiser vos tâches quotidiennes.
        </p>
        <p class="text-gray-600 mt-2">
            Explorez les outils et fonctionnalités mis à votre disposition pour gérer efficacement vos activités et améliorer la productivité de votre équipe.
        </p>
        @if(auth()->user()->can('view payment statuses'))
            <a href="{{ route('paymentStatus.index') }}" 
               class="mt-6 inline-block bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600">
               Aller au tableau des statuts de paiement
            </a>
        @elseif(auth()->user()->can('create sales'))
            <a href="{{ route('sales.create') }}" 
               class="mt-6 inline-block bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600">
               Créer une vente
            </a>
        @elseif(auth()->user()->can('view clients'))
            <a href="{{ route('clients.index') }}" 
               class="mt-6 inline-block bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600">
               Aller à la liste des clients
            </a>
        @else
            <p class="mt-6 text-gray-500">Vous n'avez pas accès aux pages disponibles pour le moment.</p>
        @endif
    </div>
</div>
<style>
    .wave-animation {
        display: inline-block;
        font-weight: bold;
        color: #1e40af;
        white-space: nowrap;
    }

    .wave-animation span {
        display: inline-block;
        animation: wave 2.5s infinite ease-in-out;
        position: relative;
    }

     /* Animating each character differently */
     .wave-animation span:nth-child(1) { animation-delay: 0.2s; }
    .wave-animation span:nth-child(2) { animation-delay: 0.4s; }
    .wave-animation span:nth-child(3) { animation-delay: 0.6s; }
    .wave-animation span:nth-child(4) { animation-delay: 0.8s; }
    .wave-animation span:nth-child(5) { animation-delay: 1s; }
    .wave-animation span:nth-child(6) { animation-delay: 1.2s; }
    .wave-animation span:nth-child(7) { animation-delay: 1.4s; }
    .wave-animation span:nth-child(8) { animation-delay: 1.6s; }
    .wave-animation span:nth-child(9) { animation-delay: 1.8s; }
    .wave-animation span:nth-child(10) { animation-delay: 2s; }
    .wave-animation span:nth-child(11) { animation-delay: 2.2s; }
    .wave-animation span:nth-child(12) { animation-delay: 2.4s; }
    .wave-animation span:nth-child(13) { animation-delay: 2.6s; }
    .wave-animation span:nth-child(14) { animation-delay: 2.8s; }
    .wave-animation span:nth-child(15) { animation-delay: 3s; }
    .wave-animation span:nth-child(16) { animation-delay: 3.2s; }

    @keyframes wave {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-5px); /* Smaller movement */
        }
    }
</style>

<script>
    // Add animation dynamically to each character
    document.addEventListener('DOMContentLoaded', function () {
        const text = document.querySelector('.wave-animation');
        const characters = text.textContent.split('');
        text.textContent = '';
        characters.forEach(char => {
            const span = document.createElement('span');
            span.textContent = char;
            text.appendChild(span);
        });
    });
</script>
{{-- <script src="https://cdn.tailwindcss.com"></script> --}}
@endsection
