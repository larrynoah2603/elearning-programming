@extends('layouts.app')

@section('title', 'Politique de confidentialité')

@section('content')
<div class="bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="prose prose-lg prose-gray dark:prose-invert max-w-none">
            <h1 class="text-4xl font-bold mb-8">Politique de confidentialité</h1>
            
            <p class="text-gray-600 mb-8"><strong>Date de la dernière mise à jour :</strong> {{ date('d F Y') }}</p>

            <h2 class="text-2xl font-semibold mt-8 mb-4">1. Introduction</h2>
            <p>
                CodeLearn (« nous », « notre » ou « la Plateforme ») s'engage à protéger votre vie privée. 
                Cette Politique de confidentialité explique comment nous collectons, utilisons, divulguons et sauvegardons vos informations.
            </p>

            <h2 class="text-2xl font-semibold mt-8 mb-4">2. Informations que nous collectons</h2>
            <h3 class="text-xl font-semibold mt-6 mb-3">2.1 Informations que vous nous fournissez</h3>
            <ul class="list-disc pl-6 mb-4">
                <li>Informations de compte (nom, email, mot de passe)</li>
                <li>Informations de profil (photo de profil, bio, intérêts)</li>
                <li>Informations de paiement (numéro de carte, adresse de facturation)</li>
                <li>Contenu que vous créez (exercices soumis, commentaires, messages du forum)</li>
                <li>Informations de contact (téléphone, adresse)</li>
            </ul>

            <h3 class="text-xl font-semibold mt-6 mb-3">2.2 Informations collectées automatiquement</h3>
            <ul class="list-disc pl-6 mb-4">
                <li>Données de navigation (pages visitées, temps passé)</li>
                <li>Adresse IP et informations du navigateur</li>
                <li>Cookies et technologies de suivi similaires</li>
                <li>Données de progression dans les cours</li>
                <li>Informations d'appareil (type, système d'exploitation)</li>
            </ul>

            <h2 class="text-2xl font-semibold mt-8 mb-4">3. Comment nous utilisons vos informations</h2>
            <p>Nous utilisons vos informations pour :</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Fournir et améliorer nos services</li>
                <li>Personnaliser votre expérience d'apprentissage</li>
                <li>Traiter les paiements et les transactions</li>
                <li>Envoyer des communications (emails notifiés, mises à jour)</li>
                <li>Assurer la sécurité et prévenir la fraude</li>
                <li>Analyser les tendances et l'utilisation de la plateforme</li>
                <li>Respecter les obligations légales</li>
            </ul>

            <h2 class="text-2xl font-semibold mt-8 mb-4">4. Partage de vos informations</h2>
            <p>
                Nous ne vendons pas vos informations personnelles. Cependant, nous pouvons les partager avec :
            </p>
            <ul class="list-disc pl-6 mb-4">
                <li>Les prestataires de services (hébergement, paiement, email)</li>
                <li>Les partenaires pédagogiques (avec votre consentement)</li>
                <li>Les autorités légales (si légalement tenues)</li>
                <li>En cas de fusion ou acquisition</li>
            </ul>

            <h2 class="text-2xl font-semibold mt-8 mb-4">5. Sécurité de vos données</h2>
            <p>
                Nous mettons en place des mesures de sécurité appropriées pour protéger vos informations contre 
                l'accès non autorisé, la modification ou la divulgation. Cependant, aucune transmission sur Internet 
                n'est 100% sécurisée.
            </p>

            <h2 class="text-2xl font-semibold mt-8 mb-4">6. Cookies</h2>
            <p>
                Nous utilisons des cookies pour améliorer votre expérience. Vous pouvez contrôler les cookies 
                via les paramètres de votre navigateur. L'accès à certaines fonctionnalités peut être réduit 
                si vous désactivez les cookies.
            </p>

            <h2 class="text-2xl font-semibold mt-8 mb-4">7. Droits des utilisateurs</h2>
            <p>Vous avez le droit de :</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Accéder à vos données personnelles</li>
                <li>Corriger vos informations inexactes</li>
                <li>Supprimer votre compte et vos données</li>
                <li>Obtenir une copie de vos données</li>
                <li>Révoquer votre consentement</li>
            </ul>
            <p>
                Pour exercer ces droits, veuillez nous contacter à <strong>{{ config('mail.from.address') }}</strong>.
            </p>

            <h2 class="text-2xl font-semibold mt-8 mb-4">8. Durée de conservation</h2>
            <p>
                Nous conservons vos données personnelles aussi longtemps que nécessaire pour fournir nos services. 
                Vous pouvez demander la suppression de vos données à tout moment.
            </p>

            <h2 class="text-2xl font-semibold mt-8 mb-4">9. Modifications de cette politique</h2>
            <p>
                Nous pouvons mettre à jour cette Politique de confidentialité. Les modifications importantes 
                vous seront notifiées par email ou via la Plateforme.
            </p>

            <h2 class="text-2xl font-semibold mt-8 mb-4">10. Nous contacter</h2>
            <p>
                En cas de questions concernant cette Politique de confidentialité, veuillez nous contacter :
            </p>
            <ul class="list-disc pl-6 mb-4">
                <li><strong>Email :</strong> larrynoah@gmail.com</li>
                <li><strong>Téléphone :</strong> +33 02 00 310</li>
                <li><strong>Adresse :</strong> LOT II i 68 y Bis Amboniloha, Madagascar</li>
            </ul>
        </div>
    </div>
</div>
@endsection
