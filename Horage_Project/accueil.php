<?php
        // Nous incluons le fichier de session et demarrons la session
        require_once 'session.php';
        DemarrageSession();
    ?>
    <!DOCTYPE>
<html>
    <head>
        <!-- Je définis le titre de la page -->
        <title>Page d'accueil - Horage</title>
        <!-- On spécifie l'encodage des caractères -->
        <meta charset="UTF-8">
        <!-- Je lie la feuille de style CSS -->
        <link rel="stylesheet" href="CSS/accueil.css">
        <!-- On adapte la page pour les appareils mobiles -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Je définis l'icône de la page (favicon) -->
        <link rel="shortcut icon" href="img_horage/logo-Photoroom.png" type="image/x-icon">
        <!-- On charge les scripts JavaScript avec defer pour qu'ils s'exécutent après le chargement de la page -->
        <script src="js/ThemeSwitcher.js" defer></script>
        <script src="js/navHighlighter.js" defer></script>
        <script src="js/localStorageCleanup.js" defer></script>
    </head>
    <body>
    <?php
        // Affichons le header
        afficherHeader();
    ?>
        <main>
            <!-- Section hero principale avec texte et boutons -->
            <section class="hero">
                <img src="img_horage/sang.png" alt="sang" width="80px" id="sang">
                <div class="hero_txt">
                    <h2>Horage – Des voyages qui vous hanteront… à jamais</h2>
                    <p>Cette agence de voyage a pour but de vous offrir les voyages les plus sensationnels que vous pouvez imaginez. Maison hantées, urbex, sombres forêts, cette agence est l'endroit parfait pour les accro de sensations forte. Si vous avez envie d'un voyage pour monter votre bpm, vous êtes dans l'endroit parfait, vous êtes sur <span>Horage</span> </p>
                </div>
                <div class="hero_discover_all">
                    <div class="hero_discover">
                        <h2 class="accroche">aventure toi !</h2>
                        <p class="button_go"><a href="/horage_project/Reserve.php" class="a2">nos offres</a></p>
                    </div>
                    <div class="hero_discover2">
                        <h2 class="accroche">Reservez ici !</h2>
                        <p class="button_go"><a href="/horage_project/Recherche.php" class="a2">reservation</a></p>
                    </div>
                </div>
            </section>
            <br><br><br><br><br>
            <!-- Section galerie photo -->
            <h2 id="galerie">Galerie photo</h2>
            <section class="hero3">
                <div class="first_place">
                	<img src="https://media.routard.com/image/14/5/fb-lieux-effrayants.1496145.jpg" width="350" class="img_comment">
                	<img src="https://cdn.generationvoyage.fr/2014/10/endroits-les-plus-etranges-terrifiants-monde-14.jpg" width="350" class="img_comment">
                	<img src="https://uploads.lebonbon.fr/source/2023/september/2034802/etqwd8qxyaud0za_2_1200.jpg" width="350" class="img_comment">
                	<img src="https://www.sncf-connect.com/assets/styles/scale_max_width_961/public/media/2019-09/istock-181137089.jpg?itok=opWelUrU" width="350" class="img_comment">
                </div>
                <div class="second_place">
                	<img src="https://www.hostelworld.com/blog/wp-content/uploads/2017/10/@ordinescarso.jpg" width="350" class="img_comment">
                	<img src="https://static.hitek.fr/img/actualite/w_une-mysterieuse-ile-au-mexique-est-entierement-peuplee-de-poupees5.jpg" width="350" class="img_comment">
                	<img src="https://cdn.generationvoyage.fr/2014/10/endroits-les-plus-etranges-terrifiants-monde-13.jpg" width="350" class="img_comment">
                	<img src="https://www.petitfute.com/medias/mag/18294/originale/17032-eastern-state-penitentiary.jpg" width="350" class="img_comment">
                </div>
            </section>
            <!-- Section des commentaires clients -->
            <section class="hero2">
                <div class="comment">
                    <img src="img_horage/femme1.webp" width="50px" height="50px" class="img_comment">
                
                    <h2 class="commentaire">MarieGoblin51</h2>
                    <p>🌟🌟🌟🌟🌟</p>
                    
                    <p><br>Le voyage que j'ai passé avec mes enfants était incroyable, nous avons passé de bons moments, je recommande fortement de prendre son voyage ici. Merci beaucoup Horage ! Je pleure de joie !</p>
                </div>
                <div class="comment">
                    <img src="img_horage/homme1.webp" width="50px" height="50px" class="img_comment">
                    
                    <h2 class="commentaire">Kirby54</h2>
                    <p>🌟🌟🌟🌟★</p>
                    
                    <p><br>Etant un amateur de bêtes sauvages humanoide, j'ai pu trouver ce que je recherchait dans les voyages proposé par Horage ! Le voyage est très sécurisé et la peur est garantie. Je compte booker un prochain voyage le mois prochain.</p>
                </div>
                <div class="comment">
                    <img src="img_horage/homme2.webp" width="50px" height="50px" class="img_comment">
                    
                    <h2 class="commentaire">Sung Jin Woo</h2>
                    <p>🌟🌟🌟★★</p>
                
                    <p><br>Mon voyage de lune de miel s'est très bien passé. J'ai jamais passé une aventure aussi excitante de toute ma vie. Ceci dit attention, ils ne rigolent pas, ce n'est pas la place pour les poules mouillé ici !</p>
                </div>
            </section>
        </main>

        <!-- Pied de page avec copyright -->
        <footer>
            <h2>Copyright © Horage - Tous droits réservés</h2>
            <p>Le contenu de ce site, incluant, sans s'y limiter, les textes, images, vidéos, logos, graphiques et tout autre élément, est la propriété exclusive d'Horage ou de ses partenaires et est protégé par les lois en vigueur sur la propriété intellectuelle.</p>
        </footer>
    </body>
</html>
<?php
// On démarre la session PHP
session_start();
?>
