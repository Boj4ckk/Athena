-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 24 déc. 2023 à 23:50
-- Version du serveur : 5.7.36
-- Version de PHP : 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `athena`
--

DELIMITER $$
--
-- Procédures
--
DROP PROCEDURE IF EXISTS `event_by_date`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `event_by_date` (OUT `event_id` INT, IN `d` DATE)  BEGIN
    SELECT id_event INTO event_id FROM evenements WHERE date_event = d;
END$$

--
-- Fonctions
--
DROP FUNCTION IF EXISTS `event_by_date`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `event_by_date` (`d` DATE) RETURNS INT(11) BEGIN
DECLARE id_e int(11);
select id_event into id_e from evenements where date_event = d;
RETURN id_e;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `achete`
--

DROP TABLE IF EXISTS `achete`;
CREATE TABLE IF NOT EXISTS `achete` (
  `id_client` int(11) NOT NULL,
  `id_produit` int(11) NOT NULL,
  `quantite_acheter` int(11) DEFAULT NULL,
  `date_transaction` date DEFAULT NULL,
  PRIMARY KEY (`id_client`,`id_produit`),
  KEY `id_produit` (`id_produit`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `achete`
--

INSERT INTO `achete` (`id_client`, `id_produit`, `quantite_acheter`, `date_transaction`) VALUES
(12, 1, 3, '2023-01-15'),
(12, 2, 1, '2023-01-15'),
(12, 4, 3, '2023-02-18'),
(12, 3, 3, '2023-04-01'),
(14, 1, 2, '2023-12-24'),
(14, 2, 1, '2023-12-24'),
(14, 5, 1, '2023-12-24'),
(14, 4, 2, '2023-12-25'),
(15, 2, 2, '2023-12-25');

-- --------------------------------------------------------

--
-- Structure de la table `aime_produit`
--

DROP TABLE IF EXISTS `aime_produit`;
CREATE TABLE IF NOT EXISTS `aime_produit` (
  `id_client` int(11) NOT NULL,
  `id_produit` int(11) NOT NULL,
  PRIMARY KEY (`id_client`,`id_produit`),
  KEY `id_produit` (`id_produit`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `aime_produit`
--

INSERT INTO `aime_produit` (`id_client`, `id_produit`) VALUES
(1, 1),
(1, 3),
(1, 6),
(2, 2),
(3, 1),
(3, 2),
(4, 2),
(5, 6),
(11, 1),
(11, 2),
(11, 3),
(11, 6),
(12, 1),
(12, 2),
(12, 3),
(12, 5),
(14, 1),
(14, 2),
(14, 3),
(14, 5),
(14, 6),
(15, 2);

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `id_client` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo_client` varchar(100) DEFAULT NULL,
  `email_client` varchar(100) DEFAULT NULL,
  `wallet` float DEFAULT NULL,
  `mdp_client` varchar(100) DEFAULT NULL,
  `role` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_client`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`id_client`, `pseudo_client`, `email_client`, `wallet`, `mdp_client`, `role`) VALUES
(15, 'Yazid', 'yazyaz@gmail.com', 0.004, '$2y$10$K4co4LyQWbkOYwBPUnBuCOjOTUObCKnWh0dDtA8a8Sq0kjIRyBUwO', 1),
(16, 'Tataplacebestapp', 'pasmoin2svp@gmail.com', 0.01, '$2y$10$KGNnq221bSu8rXRYesnFnuq53Vc.P9q/onw3/yJjow4kycQPgfi1e', 1),
(17, 'Pasmoin2pointsvp', 'pls@gmail.com', 0.01, '$2y$10$wTq2NEGCatFsrAorVWbHdeFnVdyGT4sDinV/Ix2K1.hjD7c3FD78i', 0),
(13, 'bobo', 'bobo@gnorgrg', 0.01, '$2y$10$BUMFUzoEDB2o./QT.Az07uQiZix99JE.HUKcwGr1v7en.sLuIWq2O', 1);

-- --------------------------------------------------------

--
-- Structure de la table `evenements`
--

DROP TABLE IF EXISTS `evenements`;
CREATE TABLE IF NOT EXISTS `evenements` (
  `id_event` int(11) NOT NULL AUTO_INCREMENT,
  `nom_event` varchar(100) DEFAULT NULL,
  `ville_event` varchar(100) DEFAULT NULL,
  `pays_event` varchar(100) DEFAULT NULL,
  `date_event` date DEFAULT NULL,
  `photo_event` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_event`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `evenements`
--

INSERT INTO `evenements` (`id_event`, `nom_event`, `ville_event`, `pays_event`, `date_event`, `photo_event`) VALUES
(1, 'Mandragora', 'Paris', 'France', '2023-02-06', 'assets/images/Photo_events/Mandragora.png'),
(2, 'Apashe', 'Berlin', 'Germany', '2023-05-12', 'assets\\images\\Photo_events\\Apashe.png'),
(3, 'Vladmir Cauchemar', 'Lille', 'France', '2023-05-12', 'assets\\images\\Photo_events\\Vladmiri_cauchmar.png'),
(4, 'Ahmed Spins', 'Lyon', 'France', '2023-03-01', 'assets\\images\\Photo_events\\Ahmed_spins.png');

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

DROP TABLE IF EXISTS `produit`;
CREATE TABLE IF NOT EXISTS `produit` (
  `id_produit` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) DEFAULT NULL,
  `prix` float DEFAULT NULL,
  `quantité_disponible` int(11) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id_produit`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id_produit`, `nom`, `prix`, `quantité_disponible`, `image`, `description`) VALUES
(1, 'Between', 0.002, 16, 'assets/images/Photo_Pillule/Between.png', 'Conçue pour équilibrer parfaitement votre énergie et votre relaxation, Between vous aide à trouver cet équilibre idéal. Profitez d\'une expérience de festival immersive, où chaque note de musique prend une nouvelle dimension.'),
(2, 'Bipolar', 0.003, 26, 'assets/images/Photo_Pillule/Bipolar.png', 'Expérimentez les extrêmes de la musique et de l\'énergie avec Bipolar. Cette pilule dynamise vos sens, vous plongeant dans une aventure rythmique intense, parfaite pour les moments forts de votre festival favori.'),
(3, 'EnerSy', 0.0023, 15, 'assets/images/Photo_Pillule/EnerSy.png', 'Boostez votre énergie avec Enersy. Conçue pour vous tenir éveillé et actif, elle est idéale pour danser toute la nuit. Chaque battement de musique résonne en harmonie avec votre pouls, pour une expérience énergisante inoubliable.'),
(4, 'Sablier', 0.0025, 15, 'assets/images/Photo_Pillule/Sablier.png', 'Sablier est votre passerelle vers une perception prolongée du temps. Chaque moment du festival s\'étire agréablement, vous permettant de savourer pleinement l\'expérience musicale et de créer des souvenirs durables.'),
(5, 'Tradol', 0.003, 15, 'assets/images/Photo_Pillule/Tradol.png', 'Tradol ouvre les portes de la perception, intensifiant chaque son et couleur de votre expérience festivalière. Elle synchronise vos sens avec l\'ambiance autour de vous, pour une immersion totale dans l\'univers musical.'),
(6, 'Zen', 0.002, 15, 'assets/images/Photo_Pillule/Zen.png', 'Découvrez la sérénité au milieu de l\'effervescence du festival avec Zen. Cette pilule apaise l\'esprit tout en aiguisant les sens, vous permettant de vous immerger pleinement dans chaque note et mélodie, en toute tranquillité.');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
