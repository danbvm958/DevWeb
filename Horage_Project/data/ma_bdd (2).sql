-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 09 mai 2025 à 10:48
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ma_bdd`
--

-- --------------------------------------------------------

--
-- Structure de la table `choix_options`
--

CREATE TABLE `choix_options` (
  `IdChoix` int(11) NOT NULL,
  `IdOption` int(11) NOT NULL,
  `Nom` varchar(100) NOT NULL,
  `Prix` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `choix_options`
--

INSERT INTO `choix_options` (`IdChoix`, `IdOption`, `Nom`, `Prix`) VALUES
(1, 1, 'Vol commercial', 1000.00),
(2, 1, 'Jet privé', 10000.00),
(3, 2, 'Hôtel gothique', 5000.00),
(4, 2, 'Château privatisé', 10000.00),
(5, 3, 'Visite de la vieille ville', 500.00),
(6, 3, 'Dîner thématique vampires', 1000.00),
(7, 4, 'Chambre du comte', 10000.00),
(8, 4, 'Suite royale avec majordome', 15000.00),
(9, 5, 'Séance de spiritisme', 2000.00),
(10, 5, 'Escape Game : Évasion du donjon', 2500.00),
(11, 6, 'Buffet médiéval', 1000.00),
(12, 6, 'Festin royal avec spectacle', 2000.00),
(13, 7, 'Randonnée nocturne', 800.00),
(14, 7, 'Observation des loups-garous', 1200.00),
(15, 8, 'Cabane isolée', 500.00),
(16, 8, 'Refuge forestier', 1000.00),
(17, 9, 'Bus spécialisé', 1500.00),
(18, 9, 'Hélicoptère privé', 15000.00),
(19, 10, 'Hôtel soviétique', 7000.00),
(20, 10, 'Appartement abandonné', 12000.00),
(21, 11, 'Visite des bunkers secrets', 2000.00),
(22, 11, 'Dîner aux chandelles dans un bunker', 2500.00),
(23, 13, 'Vol commercial', 1000.00),
(24, 13, 'Jet privé', 10000.00),
(25, 14, 'Hôtel gothique', 5000.00),
(26, 14, 'Château privatisé', 10000.00),
(27, 15, 'Visite de la vieille ville', 500.00),
(28, 15, 'Dîner thématique vampires', 1000.00),
(29, 16, 'Chambre du comte', 10000.00),
(30, 16, 'Suite royale avec majordome', 15000.00),
(31, 17, 'Séance de spiritisme', 2000.00),
(32, 17, 'Escape Game : Évasion du donjon', 2500.00),
(33, 18, 'Buffet médiéval', 1000.00),
(34, 18, 'Festin royal avec spectacle', 2000.00),
(35, 19, 'Randonnée nocturne', 800.00),
(36, 19, 'Observation des loups-garous', 1200.00),
(37, 20, 'Cabane isolée', 500.00),
(38, 20, 'Refuge forestier', 1000.00),
(39, 21, 'Bus spécialisé', 1500.00),
(40, 21, 'Hélicoptère privé', 15000.00),
(41, 22, 'Hôtel soviétique', 7000.00),
(42, 22, 'Appartement abandonné', 12000.00),
(43, 23, 'Visite des bunkers secrets', 2000.00),
(44, 23, 'Dîner aux chandelles dans un bunker', 2500.00),
(45, 24, 'Chambre dans la maison hantée', 18000.00),
(46, 24, 'Nuit dans l\'école abandonnée', 22000.00),
(47, 25, 'Chasse aux fantômes avec équipement', 3500.00),
(48, 25, 'Exploration nocturne de l\'hôpital', 4000.00),
(49, 25, 'Séance de spiritisme dans le parc d\'attractions', 3000.00),
(50, 26, 'Rations militaires soviétiques', 1500.00),
(51, 26, 'Banquet post-apocalyptique', 3000.00),
(52, 27, 'Visite du réacteur 4 (vue extérieure)', 5000.00),
(53, 27, 'Exploration de la forêt rouge', 3500.00),
(54, 28, 'Véhicule blindé', 2000.00),
(55, 28, 'Convoi militaire', 3000.00),
(56, 29, 'Shinkansen (train à grande vitesse)', 5000.00),
(57, 29, 'Voiture avec chauffeur', 8000.00),
(58, 30, 'Hôtel capsule', 7000.00),
(59, 30, 'Ryokan hanté', 12000.00),
(60, 31, 'Visite de lieux maudits de Tokyo', 4000.00),
(61, 31, 'Cérémonie de purification shinto', 3000.00),
(62, 32, 'Tente sécurisée en bordure de forêt', 15000.00),
(63, 32, 'Cabane au cœur de la forêt', 25000.00),
(64, 33, 'Randonnée avec guide spécialiste', 6000.00),
(65, 33, 'Nuit dans une zone réputée hantée', 8000.00),
(66, 33, 'Recherche des grottes glacées', 5000.00),
(67, 34, 'Repas traditionnel japonais', 3000.00),
(68, 34, 'Rations de survie', 1500.00),
(69, 35, 'Session de débriefing avec psychologue', 4000.00),
(70, 35, 'Visite d\'un sanctuaire apaisant', 2000.00),
(71, 36, 'Vol standard', 5000.00),
(72, 36, 'Vol premium avec espace détente', 8000.00),
(73, 37, 'Shinkansen (train à grande vitesse)', 5000.00),
(74, 37, 'Voiture avec chauffeur', 8000.00),
(75, 38, 'Hôtel capsule', 7000.00),
(76, 38, 'Ryokan hanté', 12000.00),
(77, 39, 'Visite de lieux maudits de Tokyo', 4000.00),
(78, 39, 'Cérémonie de purification shinto', 3000.00),
(79, 40, 'Tente sécurisée en bordure de forêt', 15000.00),
(80, 40, 'Cabane au cœur de la forêt', 25000.00),
(81, 41, 'Randonnée avec guide spécialiste', 6000.00),
(82, 41, 'Nuit dans une zone réputée hantée', 8000.00),
(83, 41, 'Recherche des grottes glacées', 5000.00),
(84, 42, 'Repas traditionnel japonais', 3000.00),
(85, 42, 'Rations de survie', 1500.00),
(86, 43, 'Session de débriefing avec psychologue', 4000.00),
(87, 43, 'Visite d\'un sanctuaire apaisant', 2000.00),
(88, 44, 'Vol standard', 5000.00),
(89, 44, 'Vol premium avec espace détente', 8000.00),
(90, 45, 'Shinkansen (train à grande vitesse)', 5000.00),
(91, 45, 'Voiture avec chauffeur', 8000.00),
(92, 46, 'Hôtel capsule', 7000.00),
(93, 46, 'Ryokan hanté', 12000.00),
(94, 47, 'Visite de lieux maudits de Tokyo', 4000.00),
(95, 47, 'Cérémonie de purification shinto', 3000.00),
(96, 48, 'Tente sécurisée en bordure de forêt', 15000.00),
(97, 48, 'Cabane au cœur de la forêt', 25000.00),
(98, 49, 'Randonnée avec guide spécialiste', 6000.00),
(99, 49, 'Nuit dans une zone réputée hantée', 8000.00),
(100, 49, 'Recherche des grottes glacées', 5000.00),
(101, 50, 'Repas traditionnel japonais', 3000.00),
(102, 50, 'Rations de survie', 1500.00),
(103, 51, 'Session de débriefing avec psychologue', 4000.00),
(104, 51, 'Visite d\'un sanctuaire apaisant', 2000.00),
(105, 52, 'Vol standard', 5000.00),
(106, 52, 'Vol premium avec espace détente', 8000.00),
(107, 53, 'Navette spéciale', 2000.00),
(108, 53, 'Ambulance vintage', 5000.00),
(109, 54, 'Motel des aliénés', 8000.00),
(110, 54, 'Chambre d\'infirmière préservée', 12000.00),
(111, 55, 'Visite du musée médical secret', 3500.00),
(112, 55, 'Projection de films d\'archive troublants', 2500.00),
(113, 56, 'Cellule d\'isolement', 25000.00),
(114, 56, 'Salle de traitement électroconvulsif', 30000.00),
(115, 57, 'Enquête dans la morgue secrète', 6000.00),
(116, 57, 'Nuit dans le service pédiatrique', 8000.00),
(117, 57, 'Session de spiritisme dans la cave', 4500.00),
(118, 58, 'Repas d\'époque de l\'asile', 3000.00),
(119, 58, 'Banquet dans la salle de dissection', 5000.00),
(120, 59, 'Thérapie de groupe post-traumatique', 4000.00),
(121, 59, 'Destruction des enregistrements compromettants', 2500.00),
(122, 60, 'Bus classique', 1500.00),
(123, 60, 'Fourgon cellulaire', 3000.00),
(124, 61, 'Transfert standard', 1000.00),
(125, 61, 'Limousine des années 30', 4000.00),
(126, 62, 'Cabine standard', 10000.00),
(127, 62, 'Suite du capitaine hantée', 20000.00),
(128, 63, 'Visite des zones interdites', 4000.00),
(129, 63, 'Dîner avec médium', 3500.00),
(130, 64, 'Cabine près de la salle des machines', 30000.00),
(131, 64, 'Chambre dans l\'ancienne infirmerie', 35000.00),
(132, 65, 'Nuit dans la piscine vide (scène de noyade)', 7000.00),
(133, 65, 'Exploration de la chaufferie (13 morts)', 6000.00),
(134, 65, 'Session de spiritisme sur le pont promenade', 5000.00),
(135, 66, 'Menu du dernier repas du capitaine', 4000.00),
(136, 66, 'Banquet dans la salle des 1ère classe', 6000.00),
(137, 67, 'Analyse des enregistrements paranormaux', 3000.00),
(138, 67, 'Cérémonie de purification', 2000.00),
(139, 68, 'Navette standard', 1000.00),
(140, 68, 'Hélicoptère de secours', 8000.00),
(141, 69, 'Navette privée', 5000.00),
(142, 69, 'Train express', 3000.00),
(143, 70, 'Hôtel moderne', 8000.00),
(144, 70, 'Auberge dans un ancien sanatorium', 12000.00),
(145, 71, 'Visite guidée nocturne', 7000.00),
(146, 71, 'Séance de spiritisme', 9000.00),
(147, 72, 'Repas gastronomique', 4000.00),
(148, 72, 'Rations militaires', 2000.00),
(149, 73, 'Exploration des bunkers de Berlin', 5000.00),
(150, 73, 'Détente dans un spa', 4000.00),
(151, 74, 'Gondole privée', 6000.00),
(152, 74, 'Bateau collectif', 3000.00),
(153, 75, 'Palais vénitien', 15000.00),
(154, 75, 'Hôtel hanté', 12000.00),
(155, 76, 'Chasse aux fantômes', 8000.00),
(156, 76, 'Séance de spiritisme', 7000.00),
(157, 77, 'Tente sécurisée sur l\'île', 10000.00),
(158, 77, 'Cabane en bord de mer', 13000.00),
(159, 78, 'Visite des cryptes vénitiennes', 5000.00),
(160, 78, 'Dîner mystère dans un palais', 7000.00),
(161, 79, 'Voiture de collection', 8000.00),
(162, 79, 'Navette hantée', 5000.00),
(163, 80, 'Hôtel moderne', 10000.00),
(164, 80, 'Auberge à l’ancienne', 7000.00),
(165, 81, 'Session de spiritisme', 7000.00),
(166, 81, 'Visite en réalité augmentée', 6000.00),
(167, 81, 'Enquête paranormale complète', 10000.00),
(168, 82, 'Repas servi dans l’ancienne cantine', 4000.00),
(169, 82, 'Pique-nique nocturne sur le toit', 5000.00),
(170, 83, 'Rencontre avec un médium', 6000.00),
(171, 83, 'Séance de relaxation après l’expérience', 4000.00),
(172, 84, 'Carrosse d\'époque', 7000.00),
(173, 84, 'Navette classique', 4000.00),
(174, 85, 'Château hanté', 15000.00),
(175, 85, 'Hôtel historique', 12000.00),
(176, 86, 'Exploration nocturne avec guide', 8000.00),
(177, 86, 'Escape game dans les souterrains', 7000.00),
(178, 86, 'Séance d’hypnose et voyage astral', 9000.00),
(179, 87, 'Repas victorien en costume', 6000.00),
(180, 87, 'Dégustation de plats médiévaux', 5000.00),
(181, 88, 'Tour privé du château', 6000.00),
(182, 88, 'Expérience immersive sur l’histoire écossaise', 5000.00),
(183, 89, 'Voiture de luxe', 10000.00),
(184, 89, 'Navette partagée', 4000.00),
(185, 90, 'Hôtel moderne avec vue sur Manhattan', 15000.00),
(186, 90, 'Petit motel rustique', 7000.00),
(187, 91, 'Veillée nocturne avec médium', 9000.00),
(188, 91, 'Installation de capteurs EMF', 6000.00),
(189, 91, 'Séance d\'hypnose collective', 8000.00),
(190, 92, 'Dîner inspiré des années 70', 5000.00),
(191, 92, 'Rations de survie pour ambiance immersive', 2500.00),
(192, 93, 'Balade dans le cimetière de Trinity Church', 5000.00),
(193, 93, 'Exploration de l\'ancien hôpital psychiatrique', 7000.00),
(194, 94, 'Calèche hantée', 6000.00),
(195, 94, 'Voiture de collection', 10000.00),
(196, 95, 'Hôtel boutique au décor gothique', 12000.00),
(197, 95, 'Chambre dans une ancienne prison', 8000.00),
(198, 96, 'Visite clandestine avec guide', 9000.00),
(199, 96, 'Exploration immersive avec torches', 7000.00),
(200, 96, 'Nuit dans une section interdite', 12000.00),
(201, 97, 'Dîner à thème dans les catacombes', 6000.00),
(202, 97, 'Buffet froid façon banquet macabre', 4500.00),
(203, 98, 'Visite de l’ancienne morgue de Paris', 7000.00),
(204, 98, 'Balade nocturne sur les traces de fantômes', 5000.00),
(205, 99, 'Voiture rétro des années 1920', 7000.00),
(206, 99, 'Bus spécial ambiance horreur', 4000.00),
(207, 100, 'Hôtel victorien hanté', 13000.00),
(208, 100, 'Chambre d\'hôte dans une vieille bâtisse', 9000.00),
(209, 101, 'Visite guidée nocturne', 7000.00),
(210, 101, 'Expérience en réalité augmentée', 9000.00),
(211, 101, 'Nuit dans une cellule abandonnée', 12000.00),
(212, 102, 'Dîner reconstitution des repas d’antan', 5000.00),
(213, 102, 'Ration militaire pour une immersion totale', 3000.00),
(214, 103, 'Exploration des tunnels sous la ville', 7000.00),
(215, 103, 'Séance de spiritisme avec un médium', 5000.00),
(216, 104, 'Ancien bus soviétique', 8000.00),
(217, 104, 'Jeep militaire', 12000.00),
(218, 105, 'Hôtel modernisé avec bunker', 15000.00),
(219, 105, 'Auberge de jeunesse en zone rurale', 7000.00),
(220, 106, 'Randonnée en combinaison anti-radiations', 9000.00),
(221, 106, 'Exploration libre avec compteur Geiger', 12000.00),
(222, 106, 'Nuit dans une école abandonnée', 11000.00),
(223, 107, 'Plat traditionnel ukrainien', 6000.00),
(224, 107, 'Rations militaires d’époque', 3500.00),
(225, 108, 'Visite du musée de Tchernobyl', 7000.00),
(226, 108, 'Rencontre avec un ancien liquidateur', 9000.00);

-- --------------------------------------------------------

--
-- Structure de la table `etapes`
--

CREATE TABLE `etapes` (
  `IdEtape` int(11) NOT NULL,
  `IdVoyage` int(11) NOT NULL,
  `Titre` varchar(100) NOT NULL,
  `DateArrive` date NOT NULL,
  `DateDepart` date NOT NULL,
  `Position` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `etapes`
--

INSERT INTO `etapes` (`IdEtape`, `IdVoyage`, `Titre`, `DateArrive`, `DateDepart`, `Position`) VALUES
(1, 1, 'Arrivée à Bucarest', '2025-10-30', '2025-10-30', 'Bucarest'),
(2, 1, 'Nuit au château de Dracula', '2025-10-30', '2025-10-31', 'Bran'),
(3, 1, 'Excursion dans les Carpates', '2025-10-31', '2025-11-01', 'Carpates'),
(4, 2, 'Arrivée à Kiev', '2025-11-15', '2025-11-15', 'Kiev'),
(5, 2, 'Exploration de Pripyat', '2025-11-16', '2025-11-16', 'Pripyat'),
(6, 3, 'Arrivée à Bucarest', '2025-10-30', '2025-10-30', 'Bucarest'),
(7, 3, 'Nuit au château de Dracula', '2025-10-30', '2025-10-31', 'Bran'),
(8, 3, 'Excursion dans les Carpates', '2025-10-31', '2025-11-01', 'Carpates'),
(9, 4, 'Arrivée à Kiev', '2025-11-15', '2025-11-15', 'Kiev'),
(10, 4, 'Exploration de Pripyat', '2025-11-16', '2025-11-16', 'Pripyat'),
(11, 4, 'Retour via la zone d\'exclusion', '2025-11-17', '2025-11-17', 'Zone d\'exclusion'),
(12, 5, 'Arrivée à Tokyo', '2026-01-10', '2026-01-10', 'Tokyo'),
(13, 5, 'Exploration d\'Aokigahara', '2026-01-11', '2026-01-14', 'Aokigahara'),
(14, 5, 'Retour à la civilisation', '2026-01-15', '2026-01-15', 'Tokyo'),
(15, 6, 'Arrivée à Tokyo', '2026-01-10', '2026-01-10', 'Tokyo'),
(16, 6, 'Exploration d\'Aokigahara', '2026-01-11', '2026-01-14', 'Aokigahara'),
(17, 6, 'Retour à la civilisation', '2026-01-15', '2026-01-15', 'Tokyo'),
(18, 7, 'Arrivée à Tokyo', '2026-01-10', '2026-01-10', 'Tokyo'),
(19, 7, 'Exploration d\'Aokigahara', '2026-01-11', '2026-01-14', 'Aokigahara'),
(20, 7, 'Retour à la civilisation', '2026-01-15', '2026-01-15', 'Tokyo'),
(21, 8, 'Arrivée à Staten Island', '2025-09-20', '2025-09-20', 'Staten Island'),
(22, 8, 'Immersion dans l\'asile', '2025-09-21', '2025-09-22', 'Willowbrook'),
(23, 8, 'Retour et débriefing', '2025-09-23', '2025-09-23', 'Staten Island'),
(24, 9, 'Embarquement à Long Beach', '2026-02-13', '2026-02-13', 'Long Beach'),
(25, 9, 'Navigation hantée', '2026-02-14', '2026-02-15', 'En mer'),
(26, 9, 'Débarquement et bilan', '2026-02-16', '2026-02-16', 'Long Beach'),
(27, 10, 'Arrivée à Berlin', '2026-03-15', '2026-03-15', 'Berlin'),
(28, 10, 'Exploration de Beelitz-Heilstätten', '2026-03-16', '2026-03-18', 'Beelitz'),
(29, 10, 'Retour à Berlin et visite des souterrains', '2026-03-19', '2026-03-20', 'Berlin'),
(30, 11, 'Arrivée à Venise', '2026-04-10', '2026-04-10', 'Venise'),
(31, 11, 'Exploration de l\'île de Poveglia', '2026-04-11', '2026-04-13', 'Poveglia'),
(32, 11, 'Retour à Venise et visite des cryptes', '2026-04-14', '2026-04-15', 'Venise'),
(33, 12, 'Arrivée à Louisville', '2026-05-20', '2026-05-20', 'Louisville'),
(34, 12, 'Exploration du Sanatorium', '2026-05-21', '2026-05-23', 'Louisville'),
(35, 12, 'Retour et débriefing', '2026-05-24', '2026-05-25', 'Louisville'),
(36, 13, 'Arrivée à Édimbourg', '2026-06-12', '2026-06-12', 'Édimbourg'),
(37, 13, 'Exploration de la ville souterraine', '2026-06-13', '2026-06-15', 'Édimbourg'),
(38, 13, 'Retour et visite du château d\'Édimbourg', '2026-06-16', '2026-06-17', 'Édimbourg'),
(39, 14, 'Arrivée à New York', '2026-09-10', '2026-09-10', 'New York'),
(40, 14, 'Séjour à Amityville', '2026-09-11', '2026-09-14', 'Amityville'),
(41, 14, 'Retour et visite des lieux hantés de New York', '2026-09-15', '2026-09-15', 'New York'),
(42, 15, 'Arrivée à Paris', '2026-10-05', '2026-10-05', 'Paris'),
(43, 15, 'Exploration des Catacombes', '2026-10-06', '2026-10-08', 'Paris'),
(44, 15, 'Visite du Paris mystérieux', '2026-10-09', '2026-10-10', 'Paris'),
(45, 16, 'Arrivée à Philadelphie', '2026-11-15', '2026-11-15', 'Philadelphie'),
(46, 16, 'Expérience immersive à Pennhurst', '2026-11-16', '2026-11-19', 'Pennhurst'),
(47, 16, 'Retour et visite des souterrains de Philadelphie', '2026-11-20', '2026-11-20', 'Philadelphie'),
(48, 17, 'Arrivée à Kiev', '2026-12-05', '2026-12-05', 'Kiev'),
(49, 17, 'Exploration de Pripyat', '2026-12-06', '2026-12-09', 'Pripyat'),
(50, 17, 'Retour et visite du musée de Tchernobyl', '2026-12-10', '2026-12-10', 'Kiev');

-- --------------------------------------------------------

--
-- Structure de la table `options_commande`
--

CREATE TABLE `options_commande` (
  `IdOptionCommande` int(11) NOT NULL,
  `IdCommande` int(11) NOT NULL,
  `IdEtape` int(11) NOT NULL,
  `IdOption` int(11) NOT NULL,
  `IdChoix` int(11) NOT NULL,
  `Prix` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `options_commande`
--

INSERT INTO `options_commande` (`IdOptionCommande`, `IdCommande`, `IdEtape`, `IdOption`, `IdChoix`, `Prix`) VALUES
(1, 0, 21, 53, 107, 117500.00),
(2, 0, 21, 54, 109, 117500.00),
(3, 0, 22, 56, 113, 117500.00),
(4, 0, 22, 58, 118, 117500.00),
(5, 0, 23, 60, 122, 117500.00),
(6, 2, 6, 13, 23, 67500.00),
(7, 2, 6, 14, 25, 67500.00),
(8, 2, 7, 16, 29, 67500.00),
(9, 2, 7, 18, 33, 67500.00),
(10, 2, 8, 20, 37, 67500.00);

-- --------------------------------------------------------

--
-- Structure de la table `options_etape`
--

CREATE TABLE `options_etape` (
  `IdOption` int(11) NOT NULL,
  `IdEtape` int(11) NOT NULL,
  `NomOption` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `options_etape`
--

INSERT INTO `options_etape` (`IdOption`, `IdEtape`, `NomOption`) VALUES
(1, 1, 'Transport'),
(2, 1, 'Hébergement'),
(3, 1, 'Activité'),
(4, 2, 'Hébergement'),
(5, 2, 'Activité'),
(6, 2, 'Dîner'),
(7, 3, 'Activité'),
(8, 3, 'Hébergement'),
(9, 4, 'Transport'),
(10, 4, 'Hébergement'),
(11, 4, 'Activité'),
(12, 5, 'Hébergement'),
(13, 6, 'Transport'),
(14, 6, 'Hébergement'),
(15, 6, 'Activité'),
(16, 7, 'Hébergement'),
(17, 7, 'Activité'),
(18, 7, 'Dîner'),
(19, 8, 'Activité'),
(20, 8, 'Hébergement'),
(21, 9, 'Transport'),
(22, 9, 'Hébergement'),
(23, 9, 'Activité'),
(24, 10, 'Hébergement'),
(25, 10, 'Activité'),
(26, 10, 'Dîner'),
(27, 11, 'Activité'),
(28, 11, 'Transport retour'),
(29, 12, 'Transport'),
(30, 12, 'Hébergement'),
(31, 12, 'Activité'),
(32, 13, 'Hébergement'),
(33, 13, 'Activité'),
(34, 13, 'Dîner'),
(35, 14, 'Activité'),
(36, 14, 'Transport retour'),
(37, 15, 'Transport'),
(38, 15, 'Hébergement'),
(39, 15, 'Activité'),
(40, 16, 'Hébergement'),
(41, 16, 'Activité'),
(42, 16, 'Dîner'),
(43, 17, 'Activité'),
(44, 17, 'Transport retour'),
(45, 18, 'Transport'),
(46, 18, 'Hébergement'),
(47, 18, 'Activité'),
(48, 19, 'Hébergement'),
(49, 19, 'Activité'),
(50, 19, 'Dîner'),
(51, 20, 'Activité'),
(52, 20, 'Transport retour'),
(53, 21, 'Transport'),
(54, 21, 'Hébergement'),
(55, 21, 'Activité'),
(56, 22, 'Hébergement'),
(57, 22, 'Activité'),
(58, 22, 'Dîner'),
(59, 23, 'Activité'),
(60, 23, 'Transport retour'),
(61, 24, 'Transport'),
(62, 24, 'Hébergement'),
(63, 24, 'Activité'),
(64, 25, 'Hébergement'),
(65, 25, 'Activité'),
(66, 25, 'Dîner'),
(67, 26, 'Activité'),
(68, 26, 'Transport retour'),
(69, 27, 'Transport'),
(70, 27, 'Hébergement'),
(71, 28, 'Activité'),
(72, 28, 'Dîner'),
(73, 29, 'Activité'),
(74, 30, 'Transport'),
(75, 30, 'Hébergement'),
(76, 31, 'Activité'),
(77, 31, 'Hébergement'),
(78, 32, 'Activité'),
(79, 33, 'Transport'),
(80, 33, 'Hébergement'),
(81, 34, 'Activité'),
(82, 34, 'Dîner'),
(83, 35, 'Activité'),
(84, 36, 'Transport'),
(85, 36, 'Hébergement'),
(86, 37, 'Activité'),
(87, 37, 'Dîner'),
(88, 38, 'Activité'),
(89, 39, 'Transport'),
(90, 39, 'Hébergement'),
(91, 40, 'Expérience paranormale'),
(92, 40, 'Repas'),
(93, 41, 'Visite'),
(94, 42, 'Transport'),
(95, 42, 'Hébergement'),
(96, 43, 'Expérience souterraine'),
(97, 43, 'Repas'),
(98, 44, 'Activité'),
(99, 45, 'Transport'),
(100, 45, 'Hébergement'),
(101, 46, 'Exploration'),
(102, 46, 'Repas'),
(103, 47, 'Activité'),
(104, 48, 'Transport'),
(105, 48, 'Hébergement'),
(106, 49, 'Expérience de survie'),
(107, 49, 'Repas'),
(108, 50, 'Activité');

-- --------------------------------------------------------

--
-- Structure de la table `panier`
--

CREATE TABLE `panier` (
  `IdPanier` int(11) NOT NULL,
  `IdUtilisateur` int(11) NOT NULL,
  `IdVoyage` int(11) NOT NULL,
  `DataAjout` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reduction`
--

CREATE TABLE `reduction` (
  `IdReduction` int(11) NOT NULL,
  `IdVoyage` int(11) NOT NULL,
  `TypeReduction` varchar(50) NOT NULL,
  `ConditionReduction` int(11) NOT NULL DEFAULT 2,
  `PrixReduit` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reduction`
--

INSERT INTO `reduction` (`IdReduction`, `IdVoyage`, `TypeReduction`, `ConditionReduction`, `PrixReduit`) VALUES
(1, 3, 'groupe', 4, 40000.00),
(2, 3, 'enfant', 2, 5000.00),
(3, 4, 'groupe', 4, 55000.00),
(4, 4, 'enfant', 2, 7000.00),
(5, 5, 'groupe', 4, 75000.00),
(6, 5, 'enfant', 2, 10000.00),
(7, 6, 'groupe', 4, 75000.00),
(8, 6, 'enfant', 2, 10000.00),
(9, 7, 'groupe', 4, 75000.00),
(10, 7, 'enfant', 2, 10000.00),
(11, 8, 'groupe', 5, 68000.00),
(12, 8, 'enfant', 3, 9000.00),
(13, 9, 'groupe', 1, 78000.00),
(14, 9, 'enfant', 2, 10000.00),
(15, 10, 'groupe', 4, 70000.00),
(16, 10, 'enfant', 5, 12000.00),
(17, 11, 'groupe', 2, 75000.00),
(18, 11, 'enfant', 3, 10000.00),
(19, 12, 'groupe', 3, 78000.00),
(20, 12, 'enfant', 2, 11000.00),
(21, 13, 'groupe', 4, 70000.00),
(22, 13, 'enfant', 2, 9000.00),
(23, 14, 'groupe', 3, 80000.00),
(24, 14, 'enfant', 5, 12000.00),
(25, 15, 'groupe', 2, 71000.00),
(26, 15, 'enfant', 0, 9500.00),
(27, 16, 'groupe', 0, 85000.00),
(28, 16, 'enfant', 0, 10000.00),
(29, 17, 'groupe', 0, 93000.00),
(30, 17, 'enfant', 0, 11000.00);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `NomUtilisateur` varchar(30) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `MotDePasse` varchar(255) NOT NULL,
  `Nom` varchar(50) NOT NULL,
  `Prenom` varchar(50) NOT NULL,
  `Anniversaire` date DEFAULT NULL,
  `Types` varchar(10) NOT NULL,
  `Id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`NomUtilisateur`, `Email`, `MotDePasse`, `Nom`, `Prenom`, `Anniversaire`, `Types`, `Id`) VALUES
('danbvm', 'dan@gmail.com', '$2y$10$Quy17FNHqgyCly43SgWhq.aPXOmSY6485UZcZPLF7N2EWF.zDmla.', 'Bavamian', 'Dan', '2005-09-09', 'basic', 2),
('hp', 'harry@gmail.com', '$2y$10$uIOAWm4aHPpSQB9pIX5DPevuQoLe89zcQaroPaI5sGRXvcz0svQ/O', 'potter', 'Harry', '1990-06-01', 'basic', 3);

-- --------------------------------------------------------

--
-- Structure de la table `voyages`
--

CREATE TABLE `voyages` (
  `IdVoyage` int(11) NOT NULL,
  `Titre` varchar(100) NOT NULL,
  `Description` text NOT NULL,
  `DateDebut` date NOT NULL,
  `DateFin` date NOT NULL,
  `PrixBase` decimal(10,2) NOT NULL,
  `PlacesDispo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `voyages`
--

INSERT INTO `voyages` (`IdVoyage`, `Titre`, `Description`, `DateDebut`, `DateFin`, `PrixBase`, `PlacesDispo`) VALUES
(1, 'Nuit de terreur au château de Bran', 'Plongez dans l\'atmosphère envoûtante du château de Dracula pour une nuit d\'épouvante inoubliable.', '2025-10-30', '2025-11-01', 50000.00, 10),
(2, 'Week-end dans la maison hantée de Pripyat', 'Explorez les vestiges de la ville fantôme de Pripyat, avec une nuit dans une maison réputée hantée près de la zone d\'exclusion de Tchernobyl.', '2025-11-15', '2025-11-17', 65000.00, 8),
(3, 'Nuit de terreur au château de Bran', 'Plongez dans l\'atmosphère envoûtante du château de Dracula pour une nuit d\'épouvante inoubliable.', '2025-10-30', '2025-11-01', 50000.00, 5),
(4, 'Week-end dans la maison hantée de Pripyat', 'Explorez les vestiges de la ville fantôme de Pripyat, avec une nuit dans une maison réputée hantée près de la zone d\'exclusion de Tchernobyl.', '2025-11-15', '2025-11-17', 65000.00, 5),
(5, 'Expédition dans la forêt des suicidés', 'Osez pénétrer dans la mystérieuse forêt d\'Aokigahara au Japon, lieu empreint de légendes terrifiantes et d\'énergie mystique.', '2026-01-10', '2026-01-15', 85000.00, 5),
(6, 'Expédition dans la forêt des suicidés', 'Osez pénétrer dans la mystérieuse forêt d\'Aokigahara au Japon, lieu empreint de légendes terrifiantes et d\'énergie mystique.', '2026-01-10', '2026-01-15', 85000.00, 6),
(7, 'Expédition dans la forêt des suicidés', 'Osez pénétrer dans la mystérieuse forêt d\'Aokigahara au Japon, lieu empreint de légendes terrifiantes et d\'énergie mystique.', '2026-01-10', '2026-01-15', 85000.00, 8),
(8, '72h dans l\'asile hanté de Willowbrook', 'Plongez dans l\'horreur absolue de cet ancien asile où des expériences médicales terrifiantes furent menées. Âmes sensibles s\'abstenir.', '2025-09-20', '2025-09-23', 78000.00, 6),
(9, 'Croisière fantôme sur le Queen Mary', 'Embarquez pour une traversée cauchemardesque sur ce paquebot hanté, théâtre de nombreux décès tragiques et phénomènes paranormaux.', '2026-02-13', '2026-02-16', 88000.00, 4),
(10, 'Exploration de l\'Hôpital Abandonné de Beelitz', 'Plongez dans l\'atmosphère angoissante de l\'hôpital militaire abandonné de Beelitz-Heilstätten en Allemagne, un lieu chargé d\'histoires et de phénomènes étranges.', '2026-03-15', '2026-03-20', 78000.00, 6),
(11, 'Nuit d\'épouvante sur l\'île de Poveglia', 'Vivez une expérience terrifiante sur l\'île maudite de Poveglia en Italie, autrefois utilisée comme asile et lieu de quarantaine.', '2026-04-10', '2026-04-15', 82000.00, 5),
(12, 'Immersion nocturne au Sanatorium de Waverly Hills', 'Explorez l\'un des lieux les plus hantés des États-Unis, l\'ancien sanatorium de Waverly Hills, où les esprits des patients défunts errent encore.', '2026-05-20', '2026-05-25', 86000.00, 6),
(13, 'Plongée dans la cité souterraine d\'Édimbourg', 'Découvrez l’effrayante ville cachée sous Édimbourg, un réseau de tunnels et de ruelles où le temps semble s’être arrêté depuis le XVIIe siècle.', '2026-06-12', '2026-06-17', 75000.00, 5),
(14, 'Séjour paranormal dans la maison d\'Amityville', 'Osez passer plusieurs nuits dans l’une des maisons hantées les plus célèbres au monde, connue pour ses phénomènes paranormaux inexpliqués.', '2026-09-10', '2026-09-15', 89000.00, 6),
(15, 'Descente dans les profondeurs des catacombes de Paris', 'Découvrez un Paris inconnu en explorant ses catacombes, un immense ossuaire souterrain où règne une atmosphère lugubre.', '2026-10-05', '2026-10-10', 76000.00, 4),
(16, 'Nuit terrifiante à l\'asile de Pennhurst', 'Plongez dans l’histoire troublante de cet ancien asile psychiatrique abandonné, connu pour ses phénomènes paranormaux.', '2026-11-15', '2026-11-20', 92000.00, 0),
(17, 'Survivre une nuit dans Pripyat, ville fantôme', 'Découvrez l’atmosphère post-apocalyptique de Pripyat, ville abandonnée après la catastrophe de Tchernobyl.', '2026-12-05', '2026-12-10', 99000.00, 0);

-- --------------------------------------------------------

--
-- Structure de la table `voyage_payee`
--

CREATE TABLE `voyage_payee` (
  `IdCommande` int(11) NOT NULL,
  `IdVoyage` int(11) NOT NULL,
  `IdUtilisateur` int(11) NOT NULL,
  `DatePaiement` date NOT NULL,
  `Prix` decimal(10,2) NOT NULL,
  `NbAdultes` int(11) NOT NULL,
  `NbEnfants` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `voyage_payee`
--

INSERT INTO `voyage_payee` (`IdCommande`, `IdVoyage`, `IdUtilisateur`, `DatePaiement`, `Prix`, `NbAdultes`, `NbEnfants`) VALUES
(1, 8, 2, '2025-05-09', 117500.00, 1, 0),
(2, 3, 2, '2025-05-09', 67500.00, 1, 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `choix_options`
--
ALTER TABLE `choix_options`
  ADD PRIMARY KEY (`IdChoix`),
  ADD KEY `IdOption` (`IdOption`);

--
-- Index pour la table `etapes`
--
ALTER TABLE `etapes`
  ADD PRIMARY KEY (`IdEtape`),
  ADD KEY `position` (`Position`),
  ADD KEY `IdVoyage` (`IdVoyage`);

--
-- Index pour la table `options_commande`
--
ALTER TABLE `options_commande`
  ADD PRIMARY KEY (`IdOptionCommande`),
  ADD KEY `IdCommande` (`IdCommande`),
  ADD KEY `IdOption` (`IdOption`),
  ADD KEY `IdChoix` (`IdChoix`);

--
-- Index pour la table `options_etape`
--
ALTER TABLE `options_etape`
  ADD PRIMARY KEY (`IdOption`),
  ADD KEY `IdEtape` (`IdEtape`);

--
-- Index pour la table `panier`
--
ALTER TABLE `panier`
  ADD PRIMARY KEY (`IdPanier`),
  ADD KEY `IdUtilisateur` (`IdUtilisateur`),
  ADD KEY `IdVoyage` (`IdVoyage`);

--
-- Index pour la table `reduction`
--
ALTER TABLE `reduction`
  ADD PRIMARY KEY (`IdReduction`),
  ADD KEY `IdVoyage` (`IdVoyage`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Utilisateur` (`NomUtilisateur`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Index pour la table `voyages`
--
ALTER TABLE `voyages`
  ADD PRIMARY KEY (`IdVoyage`);

--
-- Index pour la table `voyage_payee`
--
ALTER TABLE `voyage_payee`
  ADD PRIMARY KEY (`IdCommande`),
  ADD KEY `IdUtilisateur` (`IdUtilisateur`),
  ADD KEY `IdVoyage` (`IdVoyage`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `choix_options`
--
ALTER TABLE `choix_options`
  MODIFY `IdChoix` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=227;

--
-- AUTO_INCREMENT pour la table `etapes`
--
ALTER TABLE `etapes`
  MODIFY `IdEtape` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT pour la table `options_commande`
--
ALTER TABLE `options_commande`
  MODIFY `IdOptionCommande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `options_etape`
--
ALTER TABLE `options_etape`
  MODIFY `IdOption` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT pour la table `panier`
--
ALTER TABLE `panier`
  MODIFY `IdPanier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT pour la table `reduction`
--
ALTER TABLE `reduction`
  MODIFY `IdReduction` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `voyages`
--
ALTER TABLE `voyages`
  MODIFY `IdVoyage` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `voyage_payee`
--
ALTER TABLE `voyage_payee`
  MODIFY `IdCommande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `choix_options`
--
ALTER TABLE `choix_options`
  ADD CONSTRAINT `choix_options_ibfk_1` FOREIGN KEY (`IdOption`) REFERENCES `options_etape` (`IdOption`) ON DELETE CASCADE;

--
-- Contraintes pour la table `etapes`
--
ALTER TABLE `etapes`
  ADD CONSTRAINT `etapes_ibfk_1` FOREIGN KEY (`IdVoyage`) REFERENCES `voyages` (`IdVoyage`) ON DELETE CASCADE;

--
-- Contraintes pour la table `options_commande`
--
ALTER TABLE `options_commande`
  ADD CONSTRAINT `options_commande_ibfk_1` FOREIGN KEY (`IdCommande`) REFERENCES `voyage_payee` (`IdCommande`),
  ADD CONSTRAINT `options_commande_ibfk_2` FOREIGN KEY (`IdOption`) REFERENCES `options_etape` (`IdOption`),
  ADD CONSTRAINT `options_commande_ibfk_3` FOREIGN KEY (`IdChoix`) REFERENCES `choix_options` (`IdChoix`);

--
-- Contraintes pour la table `options_etape`
--
ALTER TABLE `options_etape`
  ADD CONSTRAINT `options_etape_ibfk_1` FOREIGN KEY (`IdEtape`) REFERENCES `etapes` (`IdEtape`) ON DELETE CASCADE;

--
-- Contraintes pour la table `panier`
--
ALTER TABLE `panier`
  ADD CONSTRAINT `panier_ibfk_1` FOREIGN KEY (`IdUtilisateur`) REFERENCES `utilisateur` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `panier_ibfk_2` FOREIGN KEY (`IdVoyage`) REFERENCES `voyages` (`IdVoyage`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reduction`
--
ALTER TABLE `reduction`
  ADD CONSTRAINT `reduction_ibfk_1` FOREIGN KEY (`IdVoyage`) REFERENCES `voyages` (`IdVoyage`) ON DELETE CASCADE;

--
-- Contraintes pour la table `voyage_payee`
--
ALTER TABLE `voyage_payee`
  ADD CONSTRAINT `voyage_payee_ibfk_1` FOREIGN KEY (`IdUtilisateur`) REFERENCES `utilisateur` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `voyage_payee_ibfk_2` FOREIGN KEY (`IdVoyage`) REFERENCES `voyages` (`IdVoyage`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
