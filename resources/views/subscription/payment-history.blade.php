@extends('layouts.app')

@section('title', 'Historique des paiements - CodeLearn')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-gradient-to-r from-blue-100 to-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-history text-3xl text-blue-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Historique des paiements</h1>
            <p class="text-gray-600 mt-2">Consultez tous vos paiements et téléchargez vos factures</p>
        </div>

        @php
            $completedPayments = $payments->where('status', 'completed');
            $totalMga = $completedPayments->where('currency', 'MGA')->sum('amount');
            $totalUsd = $completedPayments->where('currency', 'USD')->sum('amount');
        @endphp

        <!-- Stats Summary -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Paiements réussis</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ $payments->where('status', 'completed')->count() }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-clock text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">En attente</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ $payments->where('status', 'pending')->count() }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-times-circle text-red-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Échoués</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ $payments->where('status', 'failed')->count() }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-coins text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total dépensé</p>
                        <div class="text-2xl font-bold text-gray-900">
                            <div>{{ number_format($totalMga, 0) }} MGA</div>
                            <div class="text-base text-gray-500">{{ number_format($totalUsd, 2) }} USD</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="font-bold text-gray-900">Tous vos paiements</h3>
                    <p class="text-sm text-gray-600">{{ $payments->total() }} paiement(s) trouvé(s)</p>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-4">
                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                        <select class="form-select" onchange="window.location.href = this.value">
                            <option value="{{ request()->fullUrlWithQuery(['status' => '']) }}">Tous</option>
                            <option value="{{ request()->fullUrlWithQuery(['status' => 'completed']) }}" 
                                    {{ request('status') === 'completed' ? 'selected' : '' }}>Complétés</option>
                            <option value="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}"
                                    {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="{{ request()->fullUrlWithQuery(['status' => 'failed']) }}"
                                    {{ request('status') === 'failed' ? 'selected' : '' }}>Échoués</option>
                        </select>
                    </div>
                    
                    <!-- Method Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Méthode</label>
                        <select class="form-select" onchange="window.location.href = this.value">
                            <option value="{{ request()->fullUrlWithQuery(['method' => '']) }}">Toutes</option>
                            <option value="{{ request()->fullUrlWithQuery(['method' => 'card']) }}"
                                    {{ request('method') === 'card' ? 'selected' : '' }}>Carte</option>
                            <option value="{{ request()->fullUrlWithQuery(['method' => 'mobile_money']) }}"
                                    {{ request('method') === 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                            <option value="{{ request()->fullUrlWithQuery(['method' => 'crypto']) }}"
                                    {{ request('method') === 'crypto' ? 'selected' : '' }}>Crypto</option>
                            <option value="{{ request()->fullUrlWithQuery(['method' => 'bank']) }}"
                                    {{ request('method') === 'bank' ? 'selected' : '' }}>Virement</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payments Table -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            @if($payments->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Date</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Référence</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Méthode</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Montant</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Statut</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($payments as $payment)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $payment->created_at->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $payment->created_at->format('H:i') }}</div>
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <div class="font-mono text-sm">{{ $payment->transaction_id }}</div>
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            @if($payment->payment_method === 'card')
                                                <i class="far fa-credit-card text-blue-500 mr-2"></i>
                                                <span>Carte bancaire</span>
                                            @elseif($payment->payment_method === 'mobile_money')
                                                <i class="fas fa-mobile-alt text-purple-500 mr-2"></i>
                                                <span>Mobile Money</span>
                                            @elseif($payment->payment_method === 'cryptocurrency')
                                                <i class="fab fa-bitcoin text-yellow-500 mr-2"></i>
                                                <span>Cryptomonnaie</span>
                                            @else
                                                <i class="fas fa-university text-green-500 mr-2"></i>
                                                <span>Virement bancaire</span>
                                            @endif
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900">
                                            {{ number_format($payment->amount, 0) }} {{ $payment->currency }}
                                        </div>
                                        @if($payment->crypto_amount)
                                            <div class="text-xs text-gray-500">
                                                {{ number_format($payment->crypto_amount, 6) }} 
                                                {{ strtoupper($payment->crypto_type ?? 'BTC') }}
                                            </div>
                                        @endif
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                            {{ $payment->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                               ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                               ($payment->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                            @if($payment->status === 'completed')
                                                <i class="fas fa-check-circle mr-1 text-xs"></i>
                                            @elseif($payment->status === 'pending')
                                                <i class="fas fa-clock mr-1 text-xs"></i>
                                            @elseif($payment->status === 'failed')
                                                <i class="fas fa-times-circle mr-1 text-xs"></i>
                                            @endif
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                        @if($payment->confirmed_at)
                                            <div class="text-xs text-gray-500 mt-1">
                                                Confirmé le {{ $payment->confirmed_at->format('d/m/Y') }}
                                            </div>
                                        @endif
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('subscription.invoice', $payment->id) }}" 
                                               class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:
