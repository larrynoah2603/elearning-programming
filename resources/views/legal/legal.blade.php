@extends('layouts.app')

@section('title', 'Mentions légales')

@section('content')
<div class="bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="prose prose-lg prose-gray dark:prose-invert max-w-none">
            <h1 class="text-4xl font-bold mb-8">Mentions légales</h1>
            
            <p class="text-gray-600 mb-8"><strong>Date de la dernière mise à jour :</strong> {{ date('d F Y') }}</p>

            <h2 class="text-2xl font-semibold mt-8 mb-4">1. Identification de l'exploitant</h2>
            <p><strong>Nom du service :</strong> CodeLearn</p>
            <p><strong>Type :</strong> Plateforme e-learning de programmation</p>
            <p><strong>Adresse :</strong> LOT II i 68 y Bis Amboniloha, Madagascar</p>
            <p><strong>Email :</strong> larrynoah@gmail.com</p>
            <p><strong>Téléphone :</strong> +33 02 00 310</p>

            <h2 class="text-2xl font-semibold mt-8 mb-4">2. Responsable de la publication</h2>
            <p>
                Le responsable de la publication est le propriétaire et l'exploitant de la Plateforme CodeLearn.
            </p>

            <h2 class="text-2xl font-semibold mt-8 mb-4">3. Hébergement</h2>
            <p><strong>Hébergeur :</strong> Services d'hébergement web</p>
            <p>
                Cette Plateforme est hébergée sur les serveurs d'hébergement professionnel. 
                L'identification complète de l'hébergeur est disponible sur demande.
            </p>

            <h2 class="text-2xl font-semibold mt-8 mb-4">4. Propriété intellectuelle</h2>
            <p>
                L'ensemble du site, y compris le texte, les images, les vidéos, les logos et les graphiques, 
                est la propriété exclusive de CodeLearn ou de ses fournisseurs de contenu. 
                Toute reproduction, même partielle, est interdite sans l'autorisation préalable écrite.
            </p>

            <h2 class="text-2xl font-semibold mt-8 mb-4">5. Droit d'auteur</h2>
            <p>
                © {{ date('Y') }} CodeLearn. Tous droits réservés. Le contenu de ce site est protégé par les 
                lois de propriété intellectuelle. L'utilisation non autorisée du contenu constitue une violation 
                de ces droits et peut entraîner des poursuites judiciaires.
            </p>

            <h2 class="text-2xl font-semibold mt-8 mb-4">6. Limitation de responsabilité</h2>
            <p>
                CodeLearn s'efforce de fournir des informations exactes et à jour, mais ne garantit pas 
                l'exactitude, l'exhaustivité ou la pertinence de son contenu. CodeLearn ne peut pas être 
                responsable des dommages directs ou indirects résultant de l'utilisation de la Plateforme.
            </p>

            <h2 class="text-2xl font-semibold mt-8 mb-4">7. Conformité légale</h2>
            <p>
                Cette Plateforme est conforme aux lois et réglementations applicables à Madagascar 
                concernant la protection des données, les droits d'auteur et le commerce électronique.
            </p>

            <h2 class="text-2xl font-semibold mt-8 mb-4">8. Cookies et suivi</h2>
            <p>
                Cette Plateforme utilise des cookies et d'autres technologies de suivi. Vous pouvez contrôler 
                ces paramètres via votre navigateur. Consulter notre Politique de confidentialité pour plus de détails.
            </p>

            <h2 class="text-2xl font-semibold mt-8 mb-4">9. Accessibilité</h2>
            <p>
                CodeLearn s'engage à fournir un contenu accessible à tous les utilisateurs. 
                Si vous rencontrez des problèmes d'accessibilité, veuillez nous contacter.
            </p>

            <h2 class="text-2xl font-semibold mt-8 mb-4">10. Liens externes</h2>
            <p>
                CodeLearn peut contenir des liens vers des sites externes. Nous ne sommes pas responsables 
                du contenu de ces sites externes. La visite de sites externes est à votre propre risque.
            </p>

            <h2 class="text-2xl font-semibold mt-8 mb-4">11. Modification des mentions légales</h2>
            <p>
                CodeLearn se réserve le droit de modifier ces mentions légales à tout moment sans préavis. 
                Les modifications entrent en vigueur immédiatement après leur publication sur le site.
            </p>

            <h2 class="text-2xl font-semibold mt-8 mb-4">12. Signalement de violations</h2>
            <p>
                Si vous découvrez une violation de propriété intellectuelle, une activité frauduleuse ou 
                d'autres violations de ces mentions légales, veuillez nous contacter immédiatement aux 
                coordonnées ci-dessous.
            </p>

            <h2 class="text-2xl font-semibold mt-8 mb-4">13. Coordonnées de contact</h2>
            <div class="bg-gray-50 p-6 rounded-lg mt-4">
                <p><strong>CodeLearn</strong></p>
                <p>Email : larrynoah@gmail.com</p>
                <p>Téléphone : +33 02 00 310</p>
                <p>Adresse : LOT II i 68 y Bis Amboniloha, Madagascar</p>
            </div>

            <h2 class="text-2xl font-semibold mt-8 mb-4">14. Droit applicable</h2>
            <p>
                Ces mentions légales sont régies par les lois de Madagascar. 
                Tout litige découlant de ces mentions légales sera soumis à la juridiction des tribunaux compétents.
            </p>
        </div>
    </div>
</div>
@endsection
