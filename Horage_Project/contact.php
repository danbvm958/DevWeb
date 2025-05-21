<?php
require_once 'session.php';
DemarrageSession();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <link rel="stylesheet" href="CSS/contact.css">
    <link rel="shortcut icon" href="img_horage/logo-Photoroom.png" type="image/x-icon">
    <script src="js/ThemeSwitcher.js" defer></script>
    <script src="js/navHighlighter.js" defer></script>
</head>
<body>
<html>
    <body>
        <?php
            afficherHeader();
        ?>
        <main>
            <div class="intro">
                <h2>Contactez nous sans attendre pour plus d'informations !</h2>
                <p>Vous souhaitez échanger directement avec Chady, Dan ou Zied, les créateurs de Horage, votre agence de voyage en ligne aux frissons garantis ? Nous sommes toujours ravis de discuter avec vous, que ce soit pour des questions, des idées de voyages terrifiants ou simplement partager votre passion de l'horreur ! Retrouvez toutes nos coordonnées ci-dessous – à vos claviers, prêts… tremblez !</p>
            </div>
            <br><br><br><br><br><br><br><br><br>
            <section class="contacts">
                <div class="contact-item">
                    <a href="https://www.linkedin.com/in/chady-zaarour-286875347/"><img src="img_horage/Chady.png" width="300" class="personne1"></a>
                    <div class="contact-text">
                        <p class="description">Chady a joué un rôle central dans le développement du site Horage, en se concentrant principalement sur l’aspect visuel et l’expérience utilisateur. Il a été l’architecte de la charte graphique, définissant l’identité visuelle du site afin de créer une ambiance immersive mêlant mystère et horreur. Il a conçu la page d’accueil ainsi que la page de présentation, veillant à ce qu'elles soient à la fois attractives, cohérentes et intuitives pour les visiteurs. Chady a également rédigé et intégré une grande partie du contenu lié aux destinations proposées, mettant en avant des lieux insolites et inquiétants qui incarnent parfaitement l'esprit du projet. Au-delà de l’esthétique, il a pris en charge des aspects plus techniques, notamment l’implémentation du système de sauvegarde des préférences utilisateur via les cookies, permettant à chaque visiteur de conserver ses choix, comme le thème visuel. Il a aussi été responsable de la vérification côté client des formulaires, s’assurant que les champs soient correctement remplis avant leur envoi, renforçant ainsi l’aspect professionnel et sécurisé du site. Lors de la deuxième phase de développement, malgré un incident matériel important — une panne soudaine de son PC —, Chady a fait preuve de persévérance et a poursuivi son travail avec détermination, contribuant au réajustement de la charte graphique tout en collaborant efficacement avec le reste de l’équipe. Son engagement constant et sa polyvalence entre design, contenu et logique frontend ont été des atouts majeurs dans la réussite du projet.</p>
                    </div>
                </div>
                <br><br><br><br><br><br><br><br><br>
                <div class="contact-item">
                    <a href="https://www.linkedin.com/in/dan-bavamian95/"><img src="img_horage/Dan.png" width="300" class="personne2"></a>
                    <div class="contact-text">
                        <p class="description">Dan a joué un rôle essentiel dans le développement du projet Horage, notamment en se concentrant sur le back-end et la gestion des données. Il a pris en charge la création de la page de profil et de la page de connexion, garantissant que les utilisateurs pouvaient s'inscrire, se connecter et gérer leurs informations personnelles de manière fluide. L'un de ses principaux défis a été la gestion des données utilisateurs via JSON, ce qui a permis de stocker les informations de manière flexible et accessible. En plus de cela, Dan a développé la fonctionnalité du panier d'achat, une partie cruciale du site, permettant de stocker les voyages non payés, d'ajouter des articles au panier lors du récapitulatif et de les retirer une fois le paiement effectué. Cette gestion des achats a assuré une expérience utilisateur fluide et sans accroc.

Un autre aspect majeur du travail de Dan a été la migration du projet vers une base de données SQL. Cela a permis d'améliorer la gestion et l'intégration des données sur le site, tout en facilitant les requêtes et le traitement d'un plus grand volume d'informations. Cette migration a renforcé la stabilité du site et sa capacité à évoluer, surtout à mesure que le projet prend de l'ampleur. Grâce à ces contributions, Dan a grandement contribué à la fiabilité et à l'efficacité du back-end du projet, assurant une meilleure performance et une gestion optimale des données. Son travail a été crucial pour l'amélioration continue de l'expérience utilisateur et pour la solidité de l'infrastructure du site.</p>
                    </div>
                </div>
                <br><br><br><br><br><br><br><br><br>
                <div class="contact-item">
                    <a href="https://www.linkedin.com/in/abdelmoulaz/"><img src="https://media.licdn.com/dms/image/v2/D4E35AQGnuQcD4NsCGQ/profile-framedphoto-shrink_200_200/B4EZZyc4J1HoAY-/0/1745676895147?e=1748257200&v=beta&t=iqWCwTISjaZ_Mf09T9-_6UtP82RKzn_gFlFNrlKss74" width="300" class="personne3"></a>
                    <div class="contact-text">
                        <p class="description">Zied a joué un rôle déterminant dans le développement de la plateforme Horage, notamment en se concentrant sur la gestion des fonctionnalités liées à la recherche, l’administration et le traitement des paiements. Il a pris en charge la page de recherche, permettant aux utilisateurs de trouver facilement des lieux intéressants et mystérieux, tout en optimisant l'interface pour que l'expérience soit fluide et intuitive. Un autre aspect clé du travail de Zied a été la gestion complète de la page administrateur, où il a mis en place des fonctionnalités permettant aux administrateurs de gérer les utilisateurs et de modifier les informations liées à leurs statuts, tels que la possibilité de désigner un utilisateur comme VIP ou de le bloquer. Cela a été réalisé via des appels AJAX, permettant de modifier ces informations directement dans le fichier JSON, offrant ainsi une grande flexibilité dans la gestion des utilisateurs.

Zied a également pris en charge les fonctionnalités de paiement, un aspect fondamental pour la plateforme Horage. Il a mis en place un système complet pour gérer les paiements des utilisateurs, permettant une gestion fluide des transactions tout en garantissant la sécurité des informations. En plus de cela, il a ajouté des options de tri sur la page de recherche, facilitant l'exploration des différents voyages en fonction des préférences des utilisateurs. Bien que le tri par nombre d'étapes ait posé quelques difficultés techniques, Zied a travaillé sans relâche pour améliorer cette fonctionnalité, assurant ainsi une meilleure expérience utilisateur. </p>
                    </div>
                </div>
            </section>
        </main>
        <br><br><br><br><br><br><br><br><br>
        <footer>
            <h2>Copyright © Horage - Tous droits réservés</h2>
            <p>Le contenu de ce site, incluant, sans s'y limiter, les textes, images, vidéos, logos, graphiques et tout autre élément, est la propriété exclusive d'Horage ou de ses partenaires et est protégé par les lois en vigueur sur la propriété intellectuelle.</p>
        </footer>
</body>
</html>