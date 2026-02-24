@extends('layouts.app')

@section('title', 'Facture ' . $invoice_number . ' - CodeLearn')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('subscription.payment-history') }}" class="inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-700">
                <i class="fas fa-arrow-left mr-2"></i> Retour à l'historique
            </a>
            <button type="button" onclick="window.print()" class="btn btn-outline text-sm">
                <i class="fas fa-print mr-2"></i> Imprimer
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden print:shadow-none print:border-0">
            <div class="p-8 border-b border-gray-200 bg-gradient-to-r from-primary-50 to-blue-50">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Facture</h1>
                        <p class="text-gray-600 mt-1">CodeLearn</p>
                    </div>
                    <div class="text-left sm:text-right">
                        <p class="text-sm text-gray-500">N° Facture</p>
                        <p class="font-mono font-bold text-gray-900">{{ $invoice_number }}</p>
                        <p class="text-sm text-gray-500 mt-2">Date d'émission</p>
                        <p class="font-medium text-gray-900">{{ $date }}</p>
                    </div>
                </div>
            </div>

            <div class="p-8">
                <div class="grid md:grid-cols-2 gap-8 mb-8">
                    <div>
                        <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500 mb-3">Facturé à</h2>
                        <p class="font-bold text-gray-900">{{ $user->name }}</p>
                        <p class="text-gray-700">{{ $user->email }}</p>
                    </div>

                    <div class="md:text-right">
                        <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500 mb-3">Détails paiement</h2>
                        <p class="text-gray-700"><span class="font-medium">Référence:</span> {{ $payment->transaction_id }}</p>
                        <p class="text-gray-700"><span class="font-medium">Méthode:</span> {{ $payment->payment_method_display }}</p>
                        <p class="text-gray-700"><span class="font-medium">Statut:</span> {{ $payment->status_display }}</p>
                        <p class="text-gray-700"><span class="font-medium">Date:</span> {{ $payment->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full border border-gray-200 rounded-lg overflow-hidden">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Description</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Formule</th>
                                <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-t border-gray-200">
                                <td class="px-4 py-4 text-gray-900">Abonnement Premium CodeLearn</td>
                                <td class="px-4 py-4 text-gray-700">
                                    {{ $subscription ? ucfirst($subscription->plan) : 'N/A' }}
                                </td>
                                <td class="px-4 py-4 text-right font-bold text-gray-900">
                                    {{ number_format((float) $payment->amount, 0, ',', ' ') }} {{ $payment->currency_display }}
                                </td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-gray-50 border-t border-gray-200">
                            <tr>
                                <td colspan="2" class="px-4 py-3 text-right font-semibold text-gray-700">Total</td>
                                <td class="px-4 py-3 text-right font-bold text-gray-900">
                                    {{ number_format((float) $payment->amount, 0, ',', ' ') }} {{ $payment->currency_display }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="mt-8 text-sm text-gray-600">
                    <p>Merci pour votre confiance.</p>
                    <p class="mt-1">Pour toute question concernant cette facture, contactez notre <a href="{{ route('support') }}" class="text-primary-600 hover:text-primary-700 font-medium">support</a>.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
@media print {
    header, nav, footer, .no-print {
        display: none !important;
    }
}
</style>
@endpush
