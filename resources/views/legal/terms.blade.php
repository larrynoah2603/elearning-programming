@extends('layouts.app')

@section('title', 'Conditions d\'utilisation')

@section('content')
<div class="bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="prose prose-lg prose-gray dark:prose-invert max-w-none">
            <h1 class="text-4xl font-bold mb-8">Conditions d'utilisation</h1>
            
            <p class="text-gray-600 mb-8"><strong>Date de la dernière mise à jour :</strong> {{ date('d F Y') }}</p>

            <h2 class="text-2xl font-semibold mt-8 mb-4">1. Acceptation des conditions</h2>
            <p>
                En accédant et en utilisant la plateforme CodeLearn, vous acceptez d'être lié par ces 
                Conditions d'utilisation. Si vous n'acceptez pas ces conditions, veuillez ne pas utiliser la Plateforme.
            </p>

            <h2 class="text-2xl font-semibold mt-8 mb-4">2. Licence d'utilisation</h2>
            <p>
                CodeLearn vous accorde une licence limitée, non exclusive et révocable d'accéder et d'utiliser 
                la Plateforme pour votre usage personnel et éducatif uniquement.
            </p>

            <h2 class="text-2xl font-semibold mt-8 mb-4">3. Obligations des utilisateurs</h2>
            <p>Vous acceptez de :</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Fournir des informations exactes et à jour lors de l'inscription</li>
                <li>Protéger la confidentialité de votre mot de passe</li>
                <li>Être responsable de toutes les activités sous votre compte</li>
                <li>Respecter toutes les lois et réglementations applicables</li>
                <li>Ne pas modifier, copier ou reproduire le contenu sans permission</li>
                <li>Ne pas utiliser la Plateforme pour des activités illégales ou abusives</li>
                <li>Ne pas interférer avec ou perturber l'infrastructure de la Plateforme</li>
            </ul>

            <h2 class="text-2xl font-semibold mt-8 mb-4">4. Contenu utilisateur</h2>
            <p>
                Le contenu que vous soumettez (exercices, commentaires, projets) doit être original et ne doit pas 
                violer les droits de tiers. En soumettant du contenu, vous nous accordez le droit de l'utiliser, 
                de le reproduire et de l'afficher sur la Plateforme.
            </p>

            <h2 class="text-2xl font-semibold mt-8 mb-4">5. Propriété intellectuelle</h2>
            <p>
                Tout le contenu de la Plateforme (cours, vidéos, exercices, graphiques) est protégé par les droits 
                d'auteur et autres droits de propriété intellectuelle. Vous ne pouvez pas reproduire, distribuer ou 
                transmettre ce contenu sans autorisation écrite.
            </p>

            <h2 class="text-2xl font-semibold mt-8 mb-4">6. Frais et paiement</h2>
            <ul class="list-disc pl-6 mb-4">
                <li>Certains contenus et services nécessitent un paiement</li>
                <li>Les frais sont établis avant la soumission du paiement</li>
                <li>Les abonnements se renouvellent automatiquement sauf indication contraire</li>
                <li>Les remboursements sont soumis aux conditions spécifiées dans votre plan</li>
                <li>Vous êtes responsable des tous les frais et taxes applicables</li>
            </ul>

            <h2 class="text-2xl font-semibold mt-8 mb-4">7. Limitation de responsabilité</h2>
            <p>
                CodeLearn n'est pas responsable des dommages indirects, accidentels ou consécutifs résultant de 
                l'utilisation de la Plateforme. Notre responsabilité est limitée au montant que vous avez payé.
            </p>

            <h2 class="text-2xl font-semibold mt-8 mb-4">8. Clause de non-garantie</h2>
            <p>
                La Plateforme est fournie « telle quelle » sans garantie d'aucune sorte. Nous ne garantissons pas 
                que la Plateforme sera sans erreur, ininterrompue ou sécurisée.
            </p>

            <h2 class="text-2xl font-semibold mt-8 mb-4">9. Suspension et résiliation</h2>
            <p>
                Nous nous réservons le droit de suspendre ou de résilier votre compte si vous violez ces conditions 
                ou pour des raisons de sécurité. L'accès sera rétabli après la résolution du problème.
            </p>

            <h2 class="text-2xl font-semibold mt-8 mb-4">10. Modifications des conditions</h2>
            <p>
                Nous pouvons modifier ces conditions à tout moment. Les modifications importantes vous seront 
                notifiées. L'utilisation continue de la Plateforme constitue l'acceptation des nouvelles conditions.
            </p>

            <h2 class="text-2xl font-semibold mt-8 mb-4">11. Droit applicable</h2>
            <p>
                Ces conditions sont régies par les lois applicables au Madagascar. Tout litige sera résolu 
                dans les tribunaux compétents de Madagascar.
            </p>

            <h2 class="text-2xl font-semibold mt-8 mb-4">12. Nous contacter</h2>
            <p>
                Pour toute question sur ces Conditions d'utilisation, veuillez nous contacter :
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
