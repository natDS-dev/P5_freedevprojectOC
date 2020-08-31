-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  lun. 31 août 2020 à 07:18
-- Version du serveur :  5.7.26
-- Version de PHP :  7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `p5`
--

-- --------------------------------------------------------

--
-- Structure de la table `adds`
--

DROP TABLE IF EXISTS `adds`;
CREATE TABLE IF NOT EXISTS `adds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `title` varchar(255) NOT NULL,
  `category` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `closed` tinyint(1) DEFAULT '0',
  `creator_id` int(11) NOT NULL,
  `basket_size` int(11) NOT NULL,
  `basket_quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `creator_id` (`creator_id`),
  KEY `category` (`category`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `adds`
--

INSERT INTO `adds` (`id`, `created_at`, `title`, `category`, `description`, `closed`, `creator_id`, `basket_size`, `basket_quantity`) VALUES
(1, '2020-08-30 11:31:34', 'Taille de haie', 1, 'Bonjour, j\'aurais besoin d\'une personne pour tailler la haie de laurier entourant mon jardin. Taille haie à disposition.Merci', 0, 2, 2, 2),
(2, '2020-08-30 21:25:55', 'Montage barrière', 2, 'Bonjour, j\'aurais besoin de l\'aide d\'une personne pour monter une barrière dans mon jardin.Merci', 0, 4, 3, 2);

-- --------------------------------------------------------

--
-- Structure de la table `baskets`
--

DROP TABLE IF EXISTS `baskets`;
CREATE TABLE IF NOT EXISTS `baskets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `available` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`),
  KEY `category` (`category`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `baskets`
--

INSERT INTO `baskets` (`id`, `company_id`, `title`, `category`, `description`, `available`) VALUES
(1, 3, 'Fruits de saison', 11, 'Panier taille S :  6 pommes -  100g de framboises / panier taille M : 10 pommes - 150g de framboises / panier taille L : 15 pommes - 200g framboises', 1);

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `select_value` varchar(255) NOT NULL,
  `role` varchar(16) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `select_value`, `role`) VALUES
(1, 'Jardinage / Entretien espace extérieur', '1'),
(2, 'Bricolage / Réparation', '1'),
(3, 'Entretien domicile (ménage/nettoyage vitre etc.)', '1'),
(4, 'Garde exceptionnelle d\'enfant(s)', '1'),
(5, 'Aide administrative (démarches/courrier etc.)', '1'),
(6, 'Cours / Soutien scolaire / Aide informatique etc.', '1'),
(7, 'Déplacement / Co-voiturage', '1'),
(8, 'Animal (garde/soin/promenade)\'', '1'),
(9, 'Autres', '1'),
(10, 'Légumes', '2'),
(11, 'Fruits', '2'),
(12, 'Viandes', '2');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `company` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `zip_code` varchar(16) NOT NULL,
  `city` varchar(255) NOT NULL,
  `phone` varchar(16) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `recovery_token` varchar(255) NOT NULL,
  `role` int(11) NOT NULL,
  `profile` varchar(255) NOT NULL,
  `lat` float NOT NULL,
  `lng` float NOT NULL,
  `register_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `company`, `address`, `zip_code`, `city`, `phone`, `email`, `password`, `recovery_token`, `role`, `profile`, `lat`, `lng`, `register_date`, `banned`) VALUES
(1, 'Natacha', 'De smet', '', '11 rue bel air', '29600', 'St martin des champs', '0623523032', 'natachadesmet@yahoo.fr', '$2y$10$n4Mh.GotRp/.sxIhKnacFOZOIzhvFtJ99LcUwKUdoQLklqMh0q2g2', '', 0, 'admin', 48.5696, -3.83997, '2020-08-27 13:16:23', 0),
(2, 'Jean', 'Forteroche', '', '10 rue courte', '29600', 'Morlaix', '22211553', 'jeannotbzh@yopmail.com', '$2y$10$gkCfjfM6Q5Uo5tO74.w22OMi.mrL07YQkUylCQa6T3nCaMzSeex9W', '', 1, 'Écrivain ', 48.5764, -3.83018, '2020-08-28 05:09:31', 0),
(3, 'Michou', 'Romanesco', 'Choux & Co', '12 rampe saint augustin', '29600', 'Morlaix', '55444223', 'chouxbzh@yopmail.com', '$2y$10$X4Defxz0ulwsgkUq0saZUuxjh6aY2dM3vOt./A7RJcBMHFvhvdXTa', '', 2, 'Producteur de choux et autres légumes', 48.5725, -3.83637, '2020-08-28 05:15:44', 0),
(4, 'Guigui', 'LEQUI', '', '5 Rue de Kerfraval', '29600', 'Morlaix', '5522888', 'guiguibzh@yopmail.com', '$2y$10$AqNCdzvtZrBBBwa2Jc2pguWxlTPMCmOFabV.UH9DmPpNyVy/PXl8S', '', 1, 'Particulier', 48.5842, -3.81614, '2020-08-30 19:24:28', 0);

-- --------------------------------------------------------

--
-- Structure de la table `validations`
--

DROP TABLE IF EXISTS `validations`;
CREATE TABLE IF NOT EXISTS `validations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `add_id` int(11) NOT NULL,
  `basket_id` int(11) NOT NULL,
  `accepted_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `add_id` (`add_id`),
  KEY `basket_id` (`basket_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `adds`
--
ALTER TABLE `adds`
  ADD CONSTRAINT `adds_ibfk_1` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `adds_ibfk_2` FOREIGN KEY (`category`) REFERENCES `categories` (`id`);

--
-- Contraintes pour la table `baskets`
--
ALTER TABLE `baskets`
  ADD CONSTRAINT `baskets_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `baskets_ibfk_2` FOREIGN KEY (`category`) REFERENCES `categories` (`id`);

--
-- Contraintes pour la table `validations`
--
ALTER TABLE `validations`
  ADD CONSTRAINT `validations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `validations_ibfk_2` FOREIGN KEY (`add_id`) REFERENCES `adds` (`id`),
  ADD CONSTRAINT `validations_ibfk_3` FOREIGN KEY (`basket_id`) REFERENCES `baskets` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
