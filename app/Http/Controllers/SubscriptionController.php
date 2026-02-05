<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\Payment;
use App\Services\OrangeSmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    // Opérateurs Mobile Money à Madagascar
    private $mobileMoneyOperators = [
        'orange' => [
            'name' => 'Orange Money',
            'commission' => 1.5,
            'number_prefix' => '+26132, +26133, +26134',
        ],
        'airtel' => [
            'name' => 'Airtel Money',
            'commission' => 1.5,
            'number_prefix' => '+26138, +26139',
        ],
        'mvola' => [
            'name' => 'Mvola',
            'commission' => 1.0,
            'number_prefix' => '+26134',
        ],
        'telmoney' => [
            'name' => 'Telma Mobile Money',
            'commission' => 1.2,
            'number_prefix' => '+26133',
        ],
    ];

    // Cryptomonnaies supportées
    private $cryptocurrencies = [
        'usdt' => [
            'name' => 'USDT (Tether)',
            'network' => ['TRC20', 'ERC20', 'BEP20'],
            'minimum' => 10,
        ],
        'btc' => [
            'name' => 'Bitcoin (BTC)',
            'network' => ['Bitcoin'],
            'minimum' => 0.0002,
        ],
        'eth' => [
            'name' => 'Ethereum (ETH)',
            'network' => ['ERC20'],
            'minimum' => 0.005,
        ],
        'xrp' => [
            'name' => 'Ripple (XRP)',
            'network' => ['XRP'],
            'minimum' => 20,
        ],
    ];

    // Banques locales à Madagascar
    private $banks = [
        'boa' => [
            'name' => 'Bank of Africa',
            'swift' => 'AFRIKMGA',
            'currency' => 'MGA',
        ],
        'bfv' => [
            'name' => 'Banque Malagasy de l\'Océan Indien (BFV-SG)',
            'swift' => 'BFOIMGMG',
            'currency' => 'MGA',
        ],
        'bmoi' => [
            'name' => 'Banque de l\'Océan Indien',
            'swift' => 'BINIMGMG',
            'currency' => 'MGA',
        ],
        'access' => [
            'name' => 'Access Bank Madagascar',
            'swift' => 'ABMGMGMG',
            'currency' => 'MGA',
        ],
        'bnymadagascar' => [
            'name' => 'BNY Madagascar',
            'swift' => 'BNYAMGMG',
            'currency' => 'MGA',
        ],
    ];

    /**
     * Display subscription plans.
     */
    public function plans()
    {
        $plans = [
            [
                'id' => 'monthly',
                'name' => 'Mensuel',
                'price_mga' => 45000,
                'price_usd' => 10.00,
                'period' => 'mois',
                'features' => [
                    'Accès à tous les exercices',
                    'Accès à toutes les leçons PDF',
                    'Accès à toutes les vidéos',
                    'Support par email',
                    'Certificats de réussite',
                ],
                'highlighted' => false,
            ],
            [
                'id' => 'quarterly',
                'name' => 'Trimestriel',
                'price_mga' => 110000,
                'price_usd' => 25.00,
                'period' => 'trimestre',
                'features' => [
                    'Accès à tous les exercices',
                    'Accès à toutes les leçons PDF',
                    'Accès à toutes les vidéos',
                    'Support prioritaire',
                    'Certificats de réussite',
                    'Économisez 17%',
                ],
                'highlighted' => true,
            ],
            [
                'id' => 'yearly',
                'name' => 'Annuel',
                'price_mga' => 350000,
                'price_usd' => 80.00,
                'period' => 'an',
                'features' => [
                    'Accès à tous les exercices',
                    'Accès à toutes les leçons PDF',
                    'Accès à toutes les vidéos',
                    'Support prioritaire',
                    'Certificats de réussite',
                    'Sessions de mentorat mensuelles',
                    'Économisez 33%',
                ],
                'highlighted' => false,
            ],
        ];

        return view('subscription.plans', compact('plans'));
    }

    /**
     * Display subscription expired page.
     */
    public function expired()
    {
        return view('subscription.expired');
    }

    /**
     * Display checkout page with payment method selection.
     */
    public function checkout($plan)
    {
        $plans = [
            'monthly' => [
                'days' => 30, 
                'price' => 45000,
                'price_usd' => 10.00,
                'name' => 'Mensuel'
            ],
            'quarterly' => [
                'days' => 90, 
                'price' => 110000,
                'price_usd' => 25.00,
                'name' => 'Trimestriel'
            ],
            'yearly' => [
                'days' => 365, 
                'price' => 350000,
                'price_usd' => 80.00,
                'name' => 'Annuel'
            ],
        ];

        if (!isset($plans[$plan])) {
            return redirect()->route('subscription.plans')
                ->with('error', 'Plan d\'abonnement invalide.');
        }

        $planDetails = $plans[$plan];

        return view('subscription.checkout', [
            'plan' => $plan,
            'planDetails' => $planDetails,
            'mobileMoneyOperators' => $this->mobileMoneyOperators,
            'cryptocurrencies' => $this->cryptocurrencies,
            'banks' => $this->banks,
        ]);
    }

    /**
     * Display payment method selection page.
     */
    public function paymentMethod(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:monthly,quarterly,yearly',
        ]);
        
        return redirect()->route('subscription.checkout', ['plan' => $request->plan]);
    }

    /**
     * Display card payment page.
     */
    public function card($plan)
    {
        $planDetails = $this->getPlanDetails($plan);
        return view('subscription.card', compact('plan', 'planDetails'));
    }

    /**
     * Display mobile money payment page.
     */
    public function mobileMoney($plan)
    {
        $planDetails = $this->getPlanDetails($plan);
        return view('subscription.mobile-money', [
            'plan' => $plan,
            'planDetails' => $planDetails,
            'mobileMoneyOperators' => $this->mobileMoneyOperators,
        ]);
    }

    /**
     * Display cryptocurrency payment page.
     */
    public function crypto($plan)
    {
        $planDetails = $this->getPlanDetails($plan);
        $cryptoRates = $this->getCryptoExchangeRates();
        
        return view('subscription.crypto', [
            'plan' => $plan,
            'planDetails' => $planDetails,
            'cryptocurrencies' => $this->cryptocurrencies,
            'cryptoRates' => $cryptoRates,
        ]);
    }

    /**
     * Display bank transfer payment page.
     */
    public function bank($plan)
    {
        $planDetails = $this->getPlanDetails($plan);
        return view('subscription.bank', [
            'plan' => $plan,
            'planDetails' => $planDetails,
            'banks' => $this->banks,
        ]);
    }

    /**
     * Display crypto waiting page.
     */
    public function cryptoWaiting($id)
    {
        $payment = Payment::findOrFail($id);
        $planDetails = $this->getPlanDetails($payment->metadata['plan'] ?? 'monthly');
        
        return view('subscription.crypto-waiting', [
            'payment' => $payment,
            'planDetails' => $planDetails,
        ]);
    }

    /**
     * Display bank waiting page.
     */
    public function bankWaiting($id)
    {
        $payment = Payment::findOrFail($id);
        $planDetails = $this->getPlanDetails($payment->metadata['plan'] ?? 'monthly');
        
        return view('subscription.bank-waiting', [
            'payment' => $payment,
            'planDetails' => $planDetails,
        ]);
    }

    /**
     * Display mobile money waiting page.
     */
    public function mobileMoneyWaiting($id)
    {
        $payment = Payment::findOrFail($id);
        $planDetails = $this->getPlanDetails($payment->metadata['plan'] ?? 'monthly');
        
        return view('subscription.mobile-money-waiting', [
            'payment' => $payment,
            'planDetails' => $planDetails,
        ]);
    }

    /**
     * Process Mobile Money payment.
     */
    public function processMobileMoney(Request $request)
    {
        try {
            $validated = $request->validate([
                'plan' => 'required|in:monthly,quarterly,yearly',
                'operator' => 'required|in:orange,airtel,mvola,telmoney',
                'phone_number' => 'required|string|regex:/^[0-9+\-\s]{10,15}$/',
                'terms' => 'required|accepted',
            ], [
                'plan.in' => 'Le plan sélectionné n\'est pas valide.',
                'operator.in' => 'L\'opérateur sélectionné n\'est pas supporté.',
                'phone_number.regex' => 'Le numéro de téléphone n\'est pas valide.',
                'terms.accepted' => 'Vous devez accepter les conditions générales.',
            ]);

            $user = Auth::user();
            $planDetails = $this->getPlanDetails($validated['plan']);
            $operator = $this->mobileMoneyOperators[$validated['operator']];

            // Nettoyer le numéro de téléphone
            $phone = preg_replace('/[^0-9+]/', '', $validated['phone_number']);

            // Générer une référence de transaction
            $transactionId = 'MM' . strtoupper(Str::random(8)) . date('Ymd');

            // Créer un paiement en attente
            $payment = Payment::create([
                'user_id' => $user->id,
                'transaction_id' => $transactionId,
                'amount' => $planDetails['price'],
                'currency' => 'MGA',
                'payment_method' => 'mobile_money',
                'operator' => $operator['name'],
                'phone_number' => $phone,
                'status' => 'pending',
                'metadata' => [
                    'plan' => $validated['plan'],
                    'operator_key' => $validated['operator'],
                    'commission' => $operator['commission'],
                    'phone_original' => $validated['phone_number'],
                    'validation_code' => $this->generateValidationCode(),
                ],
            ]);

            $validationCode = $payment->metadata['validation_code'];
            $smsSent = app(OrangeSmsService::class)->sendPaymentValidationCode(
                $phone,
                $validationCode,
                number_format((float) $payment->amount, 0, '', ' ')
            );

            return redirect()->route('subscription.mobile-money-waiting', $payment->id)
                ->with('success', $smsSent
                    ? 'Demande de paiement envoyée. Un SMS Orange Money avec code de validation a été envoyé.'
                    : 'Demande de paiement envoyée. Entrez le code affiché ci-dessous pour valider le paiement.')
                ->with('fallback_validation_code', $smsSent ? null : $validationCode);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('Validation error in processMobileMoney', [
                'errors' => $e->errors(),
                'input' => $request->except(['_token', 'password']),
                'user_id' => Auth::id(),
            ]);
            
            return back()
                ->withErrors($e->validator)
                ->withInput();
                
        } catch (\Exception $e) {
            \Log::error('Mobile Money payment error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
            ]);
            
            return back()->with('error', 'Une erreur est survenue lors du traitement. Veuillez réessayer.');
        }
    }

    /**
     * Process Cryptocurrency payment.
     */
    public function processCrypto(Request $request)
    {
        try {
            $validated = $request->validate([
                'plan' => 'required|in:monthly,quarterly,yearly',
                'cryptocurrency' => 'required|in:usdt,btc,eth,xrp',
                'network' => 'required|string|in:TRC20,ERC20,BEP20,Bitcoin,XRP',
                'wallet_address' => 'required|string|min:26|max:50',
                'email' => 'required|email',
                'terms' => 'required|accepted',
            ], [
                'cryptocurrency.in' => 'Cryptomonnaie non supportée.',
                'network.in' => 'Réseau non supporté.',
                'wallet_address.min' => 'Adresse de portefeuille invalide.',
                'terms.accepted' => 'Vous devez accepter les conditions générales.',
            ]);

            $user = Auth::user();
            $planDetails = $this->getPlanDetails($validated['plan']);
            $crypto = $this->cryptocurrencies[$validated['cryptocurrency']];

            // Calculer le montant en cryptomonnaie
            $exchangeRate = $this->getCryptoExchangeRate($validated['cryptocurrency']);
            $cryptoAmount = $planDetails['price_usd'] / $exchangeRate;

            // Vérifier le montant minimum
            if ($cryptoAmount < $crypto['minimum']) {
                return back()->with('error', 'Montant trop faible pour cette cryptomonnaie.');
            }

            // Générer une adresse de réception unique
            $receivingAddress = $this->generateCryptoAddress($validated['cryptocurrency'], $user->id);

            // Enregistrer la demande de paiement en attente
            $payment = Payment::create([
                'user_id' => $user->id,
                'transaction_id' => 'CRYPTO_' . strtoupper(Str::random(10)),
                'amount' => $planDetails['price_usd'],
                'crypto_amount' => $cryptoAmount,
                'currency' => 'USD',
                'payment_method' => 'cryptocurrency',
                'crypto_type' => $validated['cryptocurrency'],
                'network' => $validated['network'],
                'receiving_address' => $receivingAddress,
                'status' => 'pending',
                'expires_at' => now()->addHours(2),
                'metadata' => [
                    'plan' => $validated['plan'],
                    'user_wallet' => $validated['wallet_address'],
                    'user_email' => $validated['email'],
                ],
            ]);

            session()->put('crypto_payment_id', $payment->id);

            return redirect()->route('subscription.crypto-waiting', $payment->id)
                ->with('success', 'Adresse de paiement générée. Veuillez envoyer le montant exact.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('Validation error in processCrypto', [
                'errors' => $e->errors(),
                'input' => $request->except(['_token', 'password']),
                'user_id' => Auth::id(),
            ]);
            
            return back()
                ->withErrors($e->validator)
                ->withInput();
                
        } catch (\Exception $e) {
            \Log::error('Crypto payment error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
            ]);
            
            return back()->with('error', 'Une erreur est survenue lors du traitement. Veuillez réessayer.');
        }
    }

    /**
     * Process Bank Transfer payment.
     */
    public function processBank(Request $request)
    {
        try {
            $validated = $request->validate([
                'plan' => 'required|in:monthly,quarterly,yearly',
                'bank_name' => 'required|string|min:3|max:100',
                'transfer_date' => 'required|date|after_or_equal:today',
                'email' => 'required|email',
                'additional_info' => 'nullable|string|max:500',
                'terms' => 'required|accepted',
            ], [
                'plan.in' => 'Plan invalide.',
                'transfer_date.after_or_equal' => 'La date de virement ne peut pas être dans le passé.',
                'terms.accepted' => 'Vous devez accepter les conditions générales.',
            ]);

            $user = Auth::user();
            $planDetails = $this->getPlanDetails($validated['plan']);

            // Générer un numéro de référence
            $referenceNumber = 'BANK' . date('Ymd') . str_pad($user->id, 6, '0', STR_PAD_LEFT);

            // Enregistrer le paiement en attente
            $payment = Payment::create([
                'user_id' => $user->id,
                'transaction_id' => $referenceNumber,
                'amount' => $planDetails['price'],
                'currency' => 'MGA',
                'payment_method' => 'bank_transfer',
                'bank_name' => $validated['bank_name'],
                'status' => 'pending',
                'metadata' => [
                    'plan' => $validated['plan'],
                    'transfer_date' => $validated['transfer_date'],
                    'user_email' => $validated['email'],
                    'additional_info' => $validated['additional_info'] ?? '',
                    'reference' => $referenceNumber,
                ],
            ]);

            return redirect()->route('subscription.bank-waiting', $payment->id)
                ->with('success', 'Demande de virement enregistrée. Veuillez effectuer le virement avec la référence fournie.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('Validation error in processBank', [
                'errors' => $e->errors(),
                'input' => $request->except(['_token', 'password']),
                'user_id' => Auth::id(),
            ]);
            
            return back()
                ->withErrors($e->validator)
                ->withInput();
                
        } catch (\Exception $e) {
            \Log::error('Bank transfer error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
            ]);
            
            return back()->with('error', 'Une erreur est survenue lors du traitement. Veuillez réessayer.');
        }
    }

    /**
     * Process Card payment.
     */
    public function processCard(Request $request)
    {
        try {
            $validated = $request->validate([
                'plan' => 'required|in:monthly,quarterly,yearly',
                'card_number' => 'required|string|regex:/^[0-9\s]{16,19}$/',
                'cardholder_name' => 'required|string|max:100',
                'expiry_date' => 'required|string|regex:/^(0[1-9]|1[0-2])\/[0-9]{2}$/',
                'cvv' => 'required|string|regex:/^[0-9]{3,4}$/',
                'email' => 'required|email',
                'terms' => 'required|accepted',
            ], [
                'card_number.regex' => 'Numéro de carte invalide.',
                'expiry_date.regex' => 'Date d\'expiration invalide (format MM/AA).',
                'cvv.regex' => 'CVV invalide (3 ou 4 chiffres).',
                'terms.accepted' => 'Vous devez accepter les conditions générales.',
            ]);

            $user = Auth::user();
            $planDetails = $this->getPlanDetails($validated['plan']);

            // Valider la date d'expiration
            [$month, $year] = explode('/', $validated['expiry_date']);
            $expiry = \Carbon\Carbon::createFromDate('20' . $year, $month, 1)->endOfMonth();
            
            if ($expiry->isPast()) {
                return back()->with('error', 'La carte a expiré.');
            }

            // Simuler le paiement
            $transactionId = 'CARD' . strtoupper(Str::random(8)) . date('His');

            $payment = Payment::create([
                'user_id' => $user->id,
                'transaction_id' => $transactionId,
                'amount' => $planDetails['price_usd'],
                'currency' => 'USD',
                'payment_method' => 'card',
                'status' => 'completed',
                'confirmed_at' => now(),
                'card_last4' => substr(preg_replace('/\s+/', '', $validated['card_number']), -4),
                'metadata' => [
                    'plan' => $validated['plan'],
                    'cardholder_name' => $validated['cardholder_name'],
                    'expiry_date' => $validated['expiry_date'],
                    'user_email' => $validated['email'],
                ],
            ]);

            // Activer l'abonnement
            $this->activateSubscription($user, $validated['plan'], $payment->id);

            return redirect()->route('dashboard')
                ->with('success', 'Paiement par carte réussi ! Votre abonnement est maintenant actif.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('Validation error in processCard', [
                'errors' => $e->errors(),
                'input' => $request->except(['_token', 'password', 'card_number', 'cvv']),
                'user_id' => Auth::id(),
            ]);
            
            return back()
                ->withErrors($e->validator)
                ->withInput();
                
        } catch (\Exception $e) {
            \Log::error('Card payment error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
            ]);
            
            return back()->with('error', 'Une erreur est survenue lors du traitement. Veuillez réessayer.');
        }
    }

    /**
     * Verify cryptocurrency payment via webhook.
     */
    public function verifyCryptoWebhook(Request $request)
    {
        try {
            $validated = $request->validate([
                'payment_id' => 'required|exists:payments,id',
                'transaction_hash' => 'required|string|min:10',
            ]);

            $payment = Payment::find($validated['payment_id']);
            
            if (!$payment || $payment->payment_method !== 'cryptocurrency') {
                return response()->json(['status' => 'failed', 'message' => 'Paiement non trouvé'], 404);
            }

            if ($payment->status === 'completed') {
                return response()->json(['status' => 'already_completed'], 200);
            }

            // Vérifier la transaction (simulation)
            $payment->update([
                'status' => 'completed',
                'transaction_hash' => $validated['transaction_hash'],
                'confirmed_at' => now(),
            ]);

            // Activer l'abonnement
            $user = $payment->user;
            $metadata = is_array($payment->metadata) ? $payment->metadata : [];
            $this->activateSubscription($user, $metadata['plan'] ?? 'monthly', $payment->id);

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            \Log::error('Crypto webhook error: ' . $e->getMessage());
            return response()->json(['status' => 'failed', 'message' => 'Erreur interne'], 500);
        }
    }

    /**
     * Verify bank transfer manually (admin).
     */
    public function confirmBank(Request $request)
    {
        try {
            $validated = $request->validate([
                'payment_id' => 'required|exists:payments,id',
                'reference' => 'required|string|max:50',
                'confirmation_date' => 'required|date',
            ]);

            $payment = Payment::findOrFail($validated['payment_id']);
            
            if ($payment->status === 'completed') {
                return back()->with('warning', 'Ce paiement a déjà été confirmé.');
            }

            $payment->update([
                'status' => 'completed',
                'verified_at' => now(),
                'verified_by' => Auth::id(),
                'reference' => $validated['reference'],
                'metadata' => array_merge(
                    is_array($payment->metadata) ? $payment->metadata : [],
                    [
                        'bank_reference' => $validated['reference'],
                        'confirmation_date' => $validated['confirmation_date'],
                        'verified_by_user' => Auth::user()->name,
                    ]
                ),
            ]);

            $user = $payment->user;
            $metadata = is_array($payment->metadata) ? $payment->metadata : [];
            $this->activateSubscription($user, $metadata['plan'] ?? 'monthly', $payment->id);

            return back()->with('success', 'Paiement bancaire vérifié et abonnement activé.');

        } catch (\Exception $e) {
            \Log::error('Confirm bank error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la confirmation du virement.');
        }
    }

    /**
     * Confirm Mobile Money payment using validation code.
     */
    public function confirmMobileMoneyCode(Request $request, $id)
    {
        $validated = $request->validate([
            'validation_code' => 'required|digits:6',
        ]);

        $payment = Payment::findOrFail($id);

        if ($payment->user_id !== Auth::id()) {
            abort(403);
        }

        if ($payment->status === 'completed') {
            return back()->with('success', 'Ce paiement est déjà confirmé.');
        }

        $metadata = $payment->metadata ?? [];
        $expectedCode = $metadata['validation_code'] ?? null;

        if (!$expectedCode || !$this->verifyMobileMoneyPayment($validated['validation_code']) || $validated['validation_code'] !== $expectedCode) {
            return back()->with('error', 'Code de validation invalide. Veuillez vérifier le SMS Orange Money.');
        }

        $payment->update([
            'status' => 'completed',
            'confirmed_at' => now(),
            'metadata' => array_merge($metadata, [
                'validated_at' => now()->format('Y-m-d H:i:s'),
            ]),
        ]);

        $this->activateSubscription($payment->user, $metadata['plan'] ?? 'monthly', $payment->id);

        return redirect()->route('dashboard')
            ->with('success', 'Paiement validé. Votre compte est maintenant Premium.');
    }

    /**
     * Verify payment status (for waiting pages).
     */
    public function verifyPayment($id)
    {
        try {
            $payment = Payment::findOrFail($id);
            
            // Vérifier l'accès
            if ($payment->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
                abort(403);
            }
            
            return response()->json([
                'status' => $payment->status,
                'message' => $this->getStatusMessage($payment->status),
                'transaction_id' => $payment->transaction_id,
                'updated_at' => $payment->updated_at->format('Y-m-d H:i:s'),
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Paiement non trouvé'
            ], 404);
        }
    }

    /**
     * Cancel subscription page.
     */
    public function cancel()
    {
        $user = Auth::user();
        
        if (!$user->isSubscribed()) {
            return redirect()->route('subscription.plans')
                ->with('info', 'Vous n\'avez pas d\'abonnement actif.');
        }
        
        $subscription = $user->activeSubscription();
        
        if (!$subscription) {
            $user->update(['is_subscribed' => false]);
            return redirect()->route('subscription.plans');
        }
        
        return view('subscription.cancel', compact('user', 'subscription'));
    }

    /**
     * Confirm subscription cancellation.
     */
    public function confirmCancel(Request $request)
    {
        try {
            $validated = $request->validate([
                'reason' => 'required|in:price,features,usage,other',
                'reason_other' => 'required_if:reason,other|nullable|string|max:500',
                'feedback' => 'nullable|string|max:1000',
                'confirmation' => 'required|accepted',
            ]);

            $user = Auth::user();
            
            // Désactiver l'abonnement
            $user->update([
                'is_subscribed' => false,
            ]);
            
            // Mettre à jour l'abonnement actif
            $subscription = Subscription::where('user_id', $user->id)
                ->where('status', 'active')
                ->latest()
                ->first();
                
            if ($subscription) {
                $subscription->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                    'cancellation_reason' => $validated['reason'] === 'other' 
                        ? $validated['reason_other'] 
                        : $validated['reason'],
                    'metadata' => [
                        'feedback' => $validated['feedback'] ?? null,
                        'cancelled_at' => now()->format('Y-m-d H:i:s'),
                    ],
                ]);
            }

            return redirect()->route('dashboard')
                ->with('success', 'Votre abonnement a été annulé. Vous conservez l\'accès jusqu\'à la fin de la période payée.');

        } catch (\Exception $e) {
            \Log::error('Cancel subscription error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'annulation de l\'abonnement.');
        }
    }

    /**
     * Display subscription management page.
     */
    public function manage()
    {
        $user = Auth::user();
        
        if (!$user->isSubscribed()) {
            return redirect()->route('subscription.plans')
                ->with('info', 'Vous n\'avez pas d\'abonnement actif. Découvrez nos offres !');
        }

        $subscription = Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->latest()
            ->first();

        if (!$subscription) {
            $user->update(['is_subscribed' => false]);
            return redirect()->route('subscription.plans');
        }

        $payments = Payment::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('subscription.manage', compact('subscription', 'payments', 'user'));
    }

    /**
     * Extend subscription.
     */
    public function extend(Request $request)
    {
        try {
            $validated = $request->validate([
                'days' => 'required|integer|min:1|max:365',
                'payment_id' => 'nullable|exists:payments,id',
            ]);

            $user = Auth::user();
            
            $subscription = Subscription::where('user_id', $user->id)
                ->where('status', 'active')
                ->latest()
                ->first();
                
            if (!$subscription) {
                return back()->with('error', 'Aucun abonnement actif trouvé.');
            }

            $newEndDate = $subscription->end_date->addDays($validated['days']);
            $subscription->update(['end_date' => $newEndDate]);
            
            $user->update([
                'subscription_expires_at' => $newEndDate,
            ]);

            return back()->with('success', "Votre abonnement a été prolongé jusqu'au {$newEndDate->format('d/m/Y')}.");

        } catch (\Exception $e) {
            \Log::error('Extend subscription error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la prolongation de l\'abonnement.');
        }
    }

    /**
     * Reactivate subscription.
     */
    public function reactivate()
    {
        try {
            $user = Auth::user();
            
            $subscription = Subscription::where('user_id', $user->id)
                ->where('status', 'cancelled')
                ->latest()
                ->first();
                
            if (!$subscription) {
                return redirect()->route('subscription.plans')
                    ->with('info', 'Aucun abonnement annulé récent trouvé.');
            }

            $daysRemaining = max(0, $subscription->cancelled_at->diffInDays($subscription->end_date, false));
            
            if ($daysRemaining <= 0) {
                return redirect()->route('subscription.plans')
                    ->with('info', 'La période d\'abonnement annulé est expirée.');
            }

            $subscription->update([
                'status' => 'active',
                'reactivated_at' => now(),
            ]);
            
            $user->update([
                'is_subscribed' => true,
                'subscription_expires_at' => $subscription->end_date,
            ]);

            return redirect()->route('dashboard')
                ->with('success', 'Votre abonnement a été réactivé !');

        } catch (\Exception $e) {
            \Log::error('Reactivate subscription error: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la réactivation de l\'abonnement.');
        }
    }

    /**
     * Payment history for user.
     */
    public function paymentHistory()
    {
        $user = Auth::user();
        $payments = Payment::where('user_id', $user->id)
            ->latest()
            ->paginate(15);

        return view('subscription.payment-history', compact('payments'));
    }

    /**
     * Download invoice.
     */
    public function invoice($id)
    {
        $payment = Payment::findOrFail($id);
        $user = Auth::user();
        
        if ($payment->user_id !== $user->id && !$user->isAdmin()) {
            abort(403);
        }
        
        $invoiceData = [
            'payment' => $payment,
            'user' => $user,
            'date' => now()->format('d/m/Y'),
            'invoice_number' => 'INV-' . $payment->transaction_id,
            'subscription' => $payment->subscription,
        ];
        
        return view('subscription.invoice', $invoiceData);
    }

    /**
     * Helper: Get plan details.
     */
    private function getPlanDetails(string $plan): array
    {
        $plans = [
            'monthly' => [
                'days' => 30, 
                'price' => 45000,
                'price_usd' => 10.00,
                'name' => 'Mensuel'
            ],
            'quarterly' => [
                'days' => 90, 
                'price' => 110000,
                'price_usd' => 25.00,
                'name' => 'Trimestriel'
            ],
            'yearly' => [
                'days' => 365, 
                'price' => 350000,
                'price_usd' => 80.00,
                'name' => 'Annuel'
            ],
        ];

        return $plans[$plan] ?? $plans['monthly'];
    }

    /**
     * Helper: Activate subscription.
     */
    private function activateSubscription($user, $plan, $paymentId): void
    {
        $planDetails = $this->getPlanDetails($plan);
        
        $user->update([
            'is_subscribed' => true,
            'subscription_expires_at' => now()->addDays($planDetails['days']),
        ]);
        
        Subscription::updateOrCreate(
            [
                'user_id' => $user->id,
                'status' => 'active',
            ],
            [
                'plan' => $plan,
                'payment_id' => $paymentId,
                'start_date' => now(),
                'end_date' => now()->addDays($planDetails['days']),
                'status' => 'active',
            ]
        );

        $payment = Payment::find($paymentId);
        if ($payment && $payment->phone_number) {
            try {
                app(OrangeSmsService::class)->sendPremiumActivation(
                    $payment->phone_number,
                    $planDetails['name'],
                    $user->subscription_expires_at
                );
            } catch (\Exception $e) {
                \Log::warning('Orange SMS error: ' . $e->getMessage(), [
                    'payment_id' => $paymentId,
                    'user_id' => $user->id,
                ]);
            }
        }
    }

    /**
     * Helper: Generate Mobile Money validation code.
     */
    private function generateValidationCode(): string
    {
        return (string) random_int(100000, 999999);
    }

    /**
     * Helper: Verify Mobile Money payment (simulated).
     */
    private function verifyMobileMoneyPayment(string $confirmationCode): bool
    {
        // Simulation
        return strlen($confirmationCode) === 6 && is_numeric($confirmationCode);
    }

    /**
     * Helper: Get cryptocurrency exchange rate.
     */
    private function getCryptoExchangeRate($crypto): float
    {
        $rates = [
            'usdt' => 1.0,
            'btc' => 45000.0,
            'eth' => 3000.0,
            'xrp' => 0.5,
        ];

        return $rates[$crypto] ?? 1.0;
    }

    /**
     * Helper: Get all crypto exchange rates.
     */
    private function getCryptoExchangeRates(): array
    {
        return [
            'btc' => 45000.0,
            'eth' => 3000.0,
            'usdt' => 1.0,
            'xrp' => 0.5,
        ];
    }

    /**
     * Helper: Generate crypto receiving address (simulated).
     */
    private function generateCryptoAddress($crypto, $userId): string
    {
        $prefixes = [
            'usdt' => 'T',
            'btc' => '1',
            'eth' => '0x',
            'xrp' => 'r',
        ];

        $prefix = $prefixes[$crypto] ?? '';
        return $prefix . strtoupper(Str::random(20)) . $userId;
    }

    /**
     * Helper: Get status message.
     */
    private function getStatusMessage($status): string
    {
        $messages = [
            'pending' => 'Paiement en attente',
            'completed' => 'Paiement confirmé',
            'failed' => 'Paiement échoué',
            'expired' => 'Paiement expiré',
        ];

        return $messages[$status] ?? 'Statut inconnu';
    }
}
