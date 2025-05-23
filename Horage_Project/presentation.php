
    <?php 
        // On inclut le fichier de gestion des sessions
        require_once 'session.php';
        DemarrageSession();
    ?>
    <!DOCTYPE>
<html>
    <head>
        <!-- On définit le titre de la page -->
        <title>Presentation - Horage</title>
        <!-- On spécifie l'encodage des caractères -->
        <meta charset="UTF-8">
        <!-- On lie la feuille de style CSS -->
        <link rel="stylesheet" href="CSS/presentation.css">
        <!-- On configure le viewport pour le responsive design -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- On définit l'icône du site -->
        <link rel="shortcut icon" href="img_horage/logo-Photoroom.png" type="image/x-icon">
        <!-- On inclut les scripts JavaScript avec defer pour chargement différé -->
        <script src="js/ThemeSwitcher.js" defer></script>
        <script src="js/navHighlighter.js" defer></script>
    </head>
    <body>
    <?php 
        // On inclut le fichier de gestion des sessions
        require_once 'session.php';
        // On affiche le header du site
        AfficherHeader();
    ?>
        <main>
            <!-- Première section de présentation -->
            <section class="hero">
                <div class="block">
                    <!-- On affiche une image décorative -->
                    <img src="img_horage/sang.png" width="80px" class="sang">
                    <h2>Histoire</h2>
                    <p><br>Tout a commencé une nuit de pleine lune, lorsque des passionnés d'épouvante ont décidé de transformer leur fascination pour l'inexpliqué en une expérience inoubliable. Fondée en 2025, Horage est née d'une obsession pour les lieux hantés, les légendes oubliées et les frissons garantis. Des catacombes les plus profondes aux manoirs maudits, nous explorons l'invisible pour offrir aux âmes courageuses des voyages où le paranormal devient réalité. Osez entrer dans l'inconnu... mais saurez-vous en sortir ?</p>
                </div>

                <div class="block">
                    <img src="img_horage/sang.png" width="80px" class="sang">
                    <h2>Valeurs</h2>
                    <p><br>Chez Horage, nous croyons que le véritable frisson ne se trouve pas dans les films, mais dans les lieux où l'histoire murmure encore à travers les murs. Nous nous engageons à offrir des expériences authentiques, basées sur des récits documentés et des témoignages troublants. Sécurité et immersion sont nos priorités : chaque voyage est conçu pour vous plonger dans l'étrange tout en respectant l'intégrité des sites visités. Nous collaborons avec des historiens et enquêteurs pour garantir des aventures à la fois terrifiantes, et respectueuses du mystère.</p>
                </div>

                <div class="block">
                    <img src="img_horage/sang.png" width="80px" class="sang">
                    <h2>Equipe</h2>
                    <p><br>Derrière Horage, une équipe de passionnés de l'étrange et du surnaturel travaille dans l'ombre pour vous offrir des expériences inoubliables. Chady, Zied et Dan, experts en folklore et aventuriers intrépides, partageons tous un goût prononcé pour l'adrénaline et les mystères inexpliqués. Chaque membre de notre équipe a exploré des lieux hantés aux quatre coins du monde et met son expertise au service de votre voyage. Que vous soyez sceptique ou chasseur de fantômes aguerri, nous serons vos guides dans l'au-delà... enfin, si vous en revenez.</p>
                </div>
            </section>
            
            <!-- Deuxième section de présentation -->
            <section class="hero2">
                <div class="block">
                    <img src="img_horage/sang.png" width="80px" id="sang1" class="sang">
                    <h2>Collaborateurs</h2>
                    <p><br>Nous sommes fiers d'être partenaires de <span>L'Institut Européen des Phénomènes Inexpliqués</span> (IEPI), une organisation dédiée à l'étude scientifique du paranormal, de <span>L'Ordre des Explorateurs de l'Ombre</span>, un collectif d'aventuriers spécialisés dans les lieux abandonnés et chargés d'histoire, et de <span>La Fédération Internationale du Tourisme Mystique</span> (FITM), qui promeut des voyages axés sur le surnaturel et les légendes urbaines. Nos collaborateurs vont vous faire le plasir d'optimiser vos experiences</p>
                </div>
                
                <div class="block">
                    <img src="img_horage/sang.png" width="80px" id="sang2" class="sang">
                    <h2>Certifications</h2>
                    <p><br>Tous nos circuits sont conçus dans le respect du patrimoine et des croyances locales, et nous avons obtenu le label <span>"Voyage Immersif et Sûr"</span>, garantissant une approche encadrée et sécurisée... du moins, autant qu'on peut l'être face à l'inconnu. Nous somme donc une agence reconnue à 100% par l'Etat, votre sûreté est donc assurée tout en passant des moments inoubliables. Avec nous, il n'y a aucun problème, si vous nous faites confiance je vous assure que non seulement vous aurez la chair de poule, et ce, sans rentrer en 1000 morceaux.</p>
                </div>

                <div class="block">
                    <img src="img_horage/sang.png" width="80px" id="sang3" class="sang">
                    <h2>Pourquoi Horage ?</h2>
                    <p><br>Parce que les frissons ne s'improvisent pas, Horage est la référence des voyages dédiés au surnaturel et aux mystères les plus sombres. Nos parcours inédits vous emmènent dans des lieux hantés, oubliés ou interdits, soigneusement sélectionnés pour leur histoire troublante et leurs phénomènes inexpliqués. Chaque expédition est encadrée par des chasseurs de fantômes, historiens de l'occulte et enquêteurs spécialisés. Tout est organisé dans le respect du patrimoine et des protocoles en place, garantissant une aventure sécurisée sans compromis sur l'adrénaline</p>
                </div>
            </section>

        </main>

        <!-- Pied de page -->
        <footer>
            <h2>Copyright © Horage - Tous droits réservés</h2>
            <p>Le contenu de ce site, incluant, sans s'y limiter, les textes, images, vidéos, logos, graphiques et tout autre élément, est la propriété exclusive d'Horage ou de ses partenaires et est protégé par les lois en vigueur sur la propriété intellectuelle.</p>
        </footer>
    </body>
</html>