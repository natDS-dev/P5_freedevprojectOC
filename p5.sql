-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mer. 17 juin 2020 à 17:26
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
  `description` varchar(255) NOT NULL,
  `closed` tinyint(1) DEFAULT '0',
  `creator_id` int(11) NOT NULL,
  `basket_size` int(11) NOT NULL,
  `basket_quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `creator_id` (`creator_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `adds`
--

INSERT INTO `adds` (`id`, `created_at`, `title`, `description`, `closed`, `creator_id`, `basket_size`, `basket_quantity`) VALUES
(1, '2020-06-13 10:22:16', 'pelouse ', 'pelouse à tondre', 0, 1, 1, 2),
(3, '2020-06-14 15:34:27', 'Taille', 'taille haie', 0, 1, 1, 2),
(4, '2020-06-14 15:36:14', '', 'planter choux', 0, 1, 2, 1),
(5, '2020-06-14 15:54:49', 'nettoyage', 'nettoyer garage', 0, 1, 2, 11),
(6, '2020-06-14 16:36:20', 'camping', 'faire feu de camp', 0, 1, 3, 20),
(7, '2020-06-14 16:47:48', 'film', 'tourner film', 0, 4, 3, 24),
(8, '2020-06-14 16:48:27', 'film', 'tourner film', 0, 4, 3, 24);

-- --------------------------------------------------------

--
-- Structure de la table `baskets`
--

DROP TABLE IF EXISTS `baskets`;
CREATE TABLE IF NOT EXISTS `baskets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `available` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `baskets`
--

INSERT INTO `baskets` (`id`, `company_id`, `title`, `description`, `available`) VALUES
(1, 2, 'panier légumes', 'légumes de saison', 1),
(2, 5, 'Légumes variés', 'Légumes de saison 1kg', 1);

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `company`, `address`, `zip_code`, `city`, `phone`, `email`, `password`, `role`, `profile`) VALUES
(1, 'natacha', 'de smet', '', '11 rue bel air', '29600', 'ST MARTIN DES CHAMPS', '0623523032', 'natachadesmet@yahoo.fr', '$2y$10$/EDIqNpWLjxY3thRAW4xRO6HcClCs348qhwoCzKKUbGx.uubw6Xwq', 1, 'jbjknk'),
(2, '', '', 'Les choux Cie', '3 rue des patates', '29600', 'Chips city', '06.00.01.02.03', 'chouxcie@yahoo.com', 'jjj', 2, 'chef d\'entreprise'),
(4, 'robert', 'de niro', '', 'jhihoioi', '68219', 'vjbuk', '6581', 'jybg@huj.vf', '$2y$10$3JvMElm1k4CGLagU5fWnoOOrHJXkhFa0nvkKnYSpkAGFc2H8rz9k6', 1, 'jkjhliho'),
(5, '', '', 'chouchou', '665245', '451646', 'gbjjh', '684616', 'chou@chou.com', '$2y$10$NW8wdymudCVy9RK7uMNOB.WQmTL/Ss1T4pN99AgppqIEjFRTzUhI2', 2, 'jhuhuih i');

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
  ADD CONSTRAINT `adds_ibfk_1` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `baskets`
--
ALTER TABLE `baskets`
  ADD CONSTRAINT `baskets_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `users` (`id`);

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
