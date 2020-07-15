-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mar. 14 juil. 2020 à 18:26
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `adds`
--

INSERT INTO `adds` (`id`, `created_at`, `title`, `category`, `description`, `closed`, `creator_id`, `basket_size`, `basket_quantity`) VALUES
(1, '2020-07-14 12:34:44', '', 2, 'ubuiiio', 0, 8, 1, 5),
(3, '2020-07-14 14:51:31', 'hbikbhio', 8, 'jk jljlk m', 1, 11, 1, 1),
(4, '2020-07-14 15:05:57', 'Garder mon chien ', 8, 'Pour un we', 1, 11, 2, 5),
(5, '2020-07-14 18:21:34', 'courrier', 5, 'courrier', 1, 8, 1, 3);

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `baskets`
--

INSERT INTO `baskets` (`id`, `company_id`, `title`, `category`, `description`, `available`) VALUES
(1, 3, 'lapin', 12, 'hhhh', 0),
(2, 3, '', 11, 'Fruits du verger', 1),
(4, 3, 'Poulet', 12, 'Ailes de poulet', 1);

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
(8, 'Animal (garde/soin/promenade)', '1'),
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
  `role` int(11) NOT NULL,
  `profile` varchar(255) NOT NULL,
  `lat` float NOT NULL,
  `lng` float NOT NULL,
  `register_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `company`, `address`, `zip_code`, `city`, `phone`, `email`, `password`, `role`, `profile`, `lat`, `lng`, `register_date`) VALUES
(1, 'Dev\'', 'DS', '', '11 rue bel air', '29600', 'St Martin des Champs', '0623523032', 'natachadesmet@yahoo.fr', '$2y$10$npDtoLRqPGkFt5vMZb/ufegLEvk0CQ3oCcVWkFmrEjgFw1Er5KQk6', 0, 'test', 48.5696, -3.83997, '2020-07-05 14:55:57'),
(2, 'Yolande', 'jjk', '', ' 9 rue  bel air', '29600', 'st martin des champs', '64646', 'yo@yahooo.com', '$2y$10$8R470tcB9JDpowkCb9QuMu1v9OZg0E1FIIXtnvhQIeZtWWZ6s9iVy', 1, 'yo', 48.5696, -3.83997, '2020-07-05 14:55:57'),
(3, 'georges', 'Lapatate', 'chouchoux', '10 rue courte', '29600', 'Morlaix', '54654646', 'chouchoux@chou.com', '$2y$10$8hd/l/xn6FtfOF0r75InVOW9v.Dy.rf8AkZVxokDOmBJx6g8x8eAi', 2, 'entreprise légumes', 48.5764, -3.83018, '2020-07-05 14:55:57'),
(4, 'Albert', 'choux', 'Choux\'chêne', '6 rue courte', '29600', 'Morlaix', '5464646', 'chene@chene.com', '$2y$10$Chhpfof4Z.a.c9RC8YI7auW9lQivccleqx4Uu4mUoHiZAjnvqltzq', 2, 'producteur légumes et alcool', 48.5763, -3.82997, '2020-07-05 14:55:57'),
(5, 'nat', 'de smet', '', '11 rue bel air', '29600', 'ST MARTIN DES CHAMPS', '0623523032', 'natdesmet@yahoo.fr', '$2y$10$PBGtht3VYRtI7L0BFy8NLergyXC9o.K87YTPedxr9hSYCH5pdsKTe', 1, 'jhkbkb', 48.5696, -3.83997, '2020-07-05 14:55:57'),
(6, 'guill', 'hhjh', '', '7 rue bel air', '29600', 'ST MARTIN DES CHAMPS', '0623523032', 'test@com.com', '$2y$10$HLm5OlQwn3K.CRFm8GY/k.fxHVu35.A8SIlex7xjUTP5YxV37SSmO', 1, 'jbkbjk', 48.5696, -3.83997, '2020-07-05 14:55:57'),
(7, 'jo', 'hjjgjg', '', '5 rue bel air', '29600', 'ST MARTIN DES CHAMPS', '0623523032', 'jo@jo.com', '$2y$10$xQ2D5YhyaaFf1nIPK5lNQOl.ROrbqz1h1X.egB2LYWtIAn9I9SjzC', 1, 'kjhkjn', 48.5696, -3.83997, '2020-07-05 14:55:57'),
(8, 'Jean', 'FORTEROCHE', '', '43 rue de kerloscant', '29670', 'Taulé', '654614615', 'jeannot@blog.com', '$2y$10$Rdv8.QG75ssWCEXlUTjjmeSwqPQ2YAXnlPMNrWvXQcqjwlze62zrm', 1, 'hikhih', 48.6021, -3.90104, '2020-07-05 14:55:57'),
(9, 'hjbjkhk', 'bjkbkj', '', '11 rue bel air', '29600', 'ST MARTIN DES CHAMPS', '0623523032', 'nat@yahoo.fr', '$2y$10$RAcZURzcH/nKqA6MnWFZ5.pDYtJUGTCZO5BJfcwy1Yl8qZaZRcqdm', 1, 'hnlilk', 48.5696, -3.83997, '2020-07-05 15:34:05'),
(10, 'hjkbjk', 'hhhhhh', '', '11 rue bel air', '29600', 'ST MARTIN DES CHAMPS', '0623523032', 'na@yahoo.fr', '$2y$10$XI2jTR5EqhZCs9qbmLNfSe1W2s/uQVAZsxbbDiyETjrG8nN4SkGOK', 1, 'jhbk', 48.5696, -3.83997, '2020-07-05 15:34:56'),
(11, 'Guigui', 'Quillet', '', '10 rue courte', '29600', 'Morlaix', '65416+656', 'gui@gui.com', '$2y$10$y4ozh8kJ5L6g4DMFqRhtPujeq5tDExf.dsvXumCezaYeE5deC1MhO', 1, 'hbiuhbioi', 48.5764, -3.83018, '2020-07-14 12:50:45');

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `validations`
--

INSERT INTO `validations` (`id`, `user_id`, `add_id`, `basket_id`, `accepted_date`) VALUES
(1, 8, 3, 1, '2020-07-14 17:54:02'),
(2, 8, 4, 1, '2020-07-14 18:11:47'),
(3, 11, 5, 1, '2020-07-14 18:23:02');

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
