<footer class="bg-gray-900 text-white mt-auto">
    @php
        $footerImages = [
            '/images/home/footer/footer-1.jpg',
            '/images/home/footer/footer-2.jpg',
            '/images/home/footer/footer-3.jpg',
            '/images/home/footer/footer-4.jpg',
            '/images/home/footer/footer-5.jpg',
        ];
    @endphp

    <style>
        @keyframes footer-scroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        .footer-marquee-track {
            width: max-content;
            animation: footer-scroll 28s linear infinite;
        }
    </style>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="text-center mb-8">
            <h3 class="text-2xl font-bold mb-3">Galerie du footer en boucle</h3>
            <p class="text-gray-400 text-sm">Remplacez uniquement les chemins d'images dans <code>$footerImages</code> pour mettre votre contenu.</p>
        </div>

        <div class="relative overflow-hidden mb-10">
            <div class="footer-marquee-track flex gap-4">
                @foreach(array_merge($footerImages, $footerImages) as $index => $image)
                    <div class="w-[220px] h-[130px] rounded-xl overflow-hidden border border-gray-800 bg-gray-800/40 flex-shrink-0">
                        <img
                            src="{{ $image }}"
                            alt="Image footer {{ ($index % count($footerImages)) + 1 }}"
                            class="w-full h-full object-cover"
                            loading="lazy"
                        >
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-gray-800 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm">
                    &copy; {{ date('Y') }} CodeLearn. Tous droits réservés.
                </p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="{{ route('home') }}" class="text-gray-400 hover:text-white text-sm transition-colors">Accueil</a>
                    <a href="{{ route('subscription.plans') }}" class="text-gray-400 hover:text-white text-sm transition-colors">Tarifs</a>
                    <a href="{{ route('contact') }}" class="text-gray-400 hover:text-white text-sm transition-colors">Contact</a>
                </div>
            </div>
        </div>
    </div>
</footer>
