<?php

namespace Database\Seeders;

use App\Models\Exercise;
use Illuminate\Database\Seeder;

class ExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $exercises = [
            // Python - Simple
            [
                'title' => 'Hello World',
                'description' => 'Écrivez votre premier programme Python qui affiche "Hello World".',
                'slug' => 'hello-world-python',
                'difficulty' => 'simple',
                'access_level' => 'free',
                'programming_language' => 'python',
                'instructions' => "Écrivez un programme Python qui affiche le texte 'Hello World' à l'écran.",
                'starter_code' => '# Écrivez votre code ici\n',
                'solution_code' => 'print("Hello World")',
                'hints' => 'Utilisez la fonction print() pour afficher du texte.',
                'points' => 5,
                'estimated_time' => 5,
                'user_id' => 1,
                'order' => 1,
            ],
            [
                'title' => 'Calculatrice simple',
                'description' => 'Créez une calculatrice qui effectue les opérations de base.',
                'slug' => 'calculatrice-simple-python',
                'difficulty' => 'simple',
                'access_level' => 'free',
                'programming_language' => 'python',
                'instructions' => "Créez un programme qui demande deux nombres à l'utilisateur et affiche leur somme, différence, produit et quotient.",
                'starter_code' => '# Demandez les deux nombres\na = float(input("Premier nombre: "))\nb = float(input("Deuxième nombre: "))\n\n# Effectuez les calculs\n',
                'solution_code' => 'a = float(input("Premier nombre: "))\nb = float(input("Deuxième nombre: "))\n\nprint(f"Somme: {a + b}")\nprint(f"Différence: {a - b}")\nprint(f"Produit: {a * b}")\nprint(f"Quotient: {a / b}" if b != 0 else "Division par zéro impossible")',
                'hints' => "Utilisez float() pour convertir l'entrée utilisateur en nombre. Attention à la division par zéro !",
                'points' => 10,
                'estimated_time' => 15,
                'user_id' => 1,
                'order' => 2,
            ],
            [
                'title' => 'Devine le nombre',
                'description' => 'Créez un jeu où l\'utilisateur doit deviner un nombre aléatoire.',
                'slug' => 'devine-nombre-python',
                'difficulty' => 'simple',
                'access_level' => 'free',
                'programming_language' => 'python',
                'instructions' => "Créez un jeu où l'ordinateur choisit un nombre aléatoire entre 1 et 100, et l'utilisateur doit le deviner.",
                'starter_code' => 'import random\n\nnombre_secret = random.randint(1, 100)\ntentatives = 0\n\nprint("J\'ai choisi un nombre entre 1 et 100. À vous de le deviner !")\n\nwhile True:\n    # Votre code ici\n    pass',
                'solution_code' => 'import random\n\nnombre_secret = random.randint(1, 100)\ntentatives = 0\n\nprint("J\'ai choisi un nombre entre 1 et 100. À vous de le deviner !")\n\nwhile True:\n    essai = int(input("Votre proposition: "))\n    tentatives += 1\n    \n    if essai < nombre_secret:\n        print("Trop petit !")\n    elif essai > nombre_secret:\n        print("Trop grand !")\n    else:\n        print(f"Bravo ! Vous avez trouvé en {tentatives} tentatives.")\n        break',
                'hints' => "Utilisez la boucle while pour répéter jusqu'à ce que l'utilisateur trouve. Donnez des indices 'trop petit' ou 'trop grand'.",
                'points' => 15,
                'estimated_time' => 20,
                'user_id' => 1,
                'order' => 3,
            ],
            // Python - Complexe
            [
                'title' => 'Gestionnaire de contacts',
                'description' => 'Créez un programme complet pour gérer une liste de contacts.',
                'slug' => 'gestionnaire-contacts-python',
                'difficulty' => 'complexe',
                'access_level' => 'subscribed',
                'programming_language' => 'python',
                'instructions' => "Créez un gestionnaire de contacts avec les fonctionnalités suivants : ajouter un contact, supprimer un contact, rechercher un contact, afficher tous les contacts.",
                'starter_code' => 'contacts = {}\n\ndef ajouter_contact(nom, telephone, email):\n    # Votre code ici\n    pass\n\ndef supprimer_contact(nom):\n    # Votre code ici\n    pass\n\ndef rechercher_contact(nom):\n    # Votre code ici\n    pass\n\ndef afficher_contacts():\n    # Votre code ici\n    pass\n\n# Menu principal\nwhile True:\n    print("\\n1. Ajouter un contact")\n    print("2. Supprimer un contact")\n    print("3. Rechercher un contact")\n    print("4. Afficher tous les contacts")\n    print("5. Quitter")\n    \n    choix = input("Votre choix: ")\n    # Votre code ici',
                'solution_code' => 'contacts = {}\n\ndef ajouter_contact(nom, telephone, email):\n    contacts[nom] = {"telephone": telephone, "email": email}\n    print(f"Contact {nom} ajouté avec succès.")\n\ndef supprimer_contact(nom):\n    if nom in contacts:\n        del contacts[nom]\n        print(f"Contact {nom} supprimé.")\n    else:\n        print("Contact non trouvé.")\n\ndef rechercher_contact(nom):\n    if nom in contacts:\n        contact = contacts[nom]\n        print(f"Nom: {nom}")\n        print(f"Téléphone: {contact[\'telephone\']}")\n        print(f"Email: {contact[\'email\']}")\n    else:\n        print("Contact non trouvé.")\n\ndef afficher_contacts():\n    if not contacts:\n        print("Aucun contact enregistré.")\n    else:\n        for nom, infos in contacts.items():\n            print(f"\\n{nom}: {infos[\'telephone\']}, {infos[\'email\']}")\n\nwhile True:\n    print("\\n1. Ajouter un contact")\n    print("2. Supprimer un contact")\n    print("3. Rechercher un contact")\n    print("4. Afficher tous les contacts")\n    print("5. Quitter")\n    \n    choix = input("Votre choix: ")\n    \n    if choix == "1":\n        nom = input("Nom: ")\n        tel = input("Téléphone: ")\n        email = input("Email: ")\n        ajouter_contact(nom, tel, email)\n    elif choix == "2":\n        nom = input("Nom à supprimer: ")\n        supprimer_contact(nom)\n    elif choix == "3":\n        nom = input("Nom à rechercher: ")\n        rechercher_contact(nom)\n    elif choix == "4":\n        afficher_contacts()\n    elif choix == "5":\n        break',
                'hints' => "Utilisez un dictionnaire pour stocker les contacts. Chaque contact peut être un dictionnaire avec les informations.",
                'points' => 25,
                'estimated_time' => 45,
                'user_id' => 1,
                'order' => 4,
            ],
            // JavaScript - Simple
            [
                'title' => 'Manipulation du DOM',
                'description' => 'Apprenez à modifier le contenu d\'une page web avec JavaScript.',
                'slug' => 'manipulation-dom-javascript',
                'difficulty' => 'simple',
                'access_level' => 'free',
                'programming_language' => 'javascript',
                'instructions' => "Créez un script qui change le texte d'un élément lorsqu'on clique sur un bouton.",
                'starter_code' => '// Sélectionnez l\'élément et le bouton\nconst element = document.getElementById("monElement");\nconst bouton = document.getElementById("monBouton");\n\n// Ajoutez l\'écouteur d\'événement\n',
                'solution_code' => 'const element = document.getElementById("monElement");\nconst bouton = document.getElementById("monBouton");\n\nbouton.addEventListener("click", function() {\n    element.textContent = "Texte modifié !";\n    element.style.color = "red";\n});',
                'hints' => "Utilisez addEventListener pour écouter les clics et textContent pour modifier le texte.",
                'points' => 10,
                'estimated_time' => 15,
                'user_id' => 1,
                'order' => 5,
            ],
            // HTML/CSS - Simple
            [
                'title' => 'Créer une carte de profil',
                'description' => 'Créez une carte de profil stylisée avec HTML et CSS.',
                'slug' => 'carte-profil-html-css',
                'difficulty' => 'simple',
                'access_level' => 'free',
                'programming_language' => 'html_css',
                'instructions' => "Créez une carte de profil avec une image, un nom, une description et des liens sociaux.",
                'starter_code' => '<!-- HTML -->\n<div class="carte-profil">\n    <!-- Votre code ici -->\n</div>\n\n<style>\n.carte-profil {\n    /* Votre CSS ici */\n}\n</style>',
                'solution_code' => '<div class="carte-profil">\n    <img src="avatar.jpg" alt="Avatar" class="profil-image">\n    <h2>Jean Dupont</h2>\n    <p>Développeur web passionné</p>\n    <div class="reseaux-sociaux">\n        <a href="#"><i class="fab fa-twitter"></i></a>\n        <a href="#"><i class="fab fa-linkedin"></i></a>\n        <a href="#"><i class="fab fa-github"></i></a>\n    </div>\n</div>\n\n<style>\n.carte-profil {\n    width: 300px;\n    padding: 20px;\n    border-radius: 10px;\n    box-shadow: 0 4px 6px rgba(0,0,0,0.1);\n    text-align: center;\n    background: white;\n}\n.profil-image {\n    width: 100px;\n    height: 100px;\n    border-radius: 50%;\n    object-fit: cover;\n}\n.reseaux-sociaux a {\n    margin: 0 10px;\n    color: #333;\n    font-size: 20px;\n}\n</style>',
                'hints' => "Utilisez border-radius: 50% pour créer une image ronde et box-shadow pour l'ombre.",
                'points' => 10,
                'estimated_time' => 20,
                'user_id' => 1,
                'order' => 6,
            ],
        ];

        foreach ($exercises as $exercise) {
            Exercise::create($exercise);
        }
    }
}
