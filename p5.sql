-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  Dim 21 juin 2020 à 19:00
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `adds`
--

INSERT INTO `adds` (`id`, `created_at`, `title`, `description`, `closed`, `creator_id`, `basket_size`, `basket_quantity`) VALUES
(1, '2020-06-20 09:39:00', 'Cabane dans les arbres ', 'Bonjour j\'aurais besoin d\'aide pour créer une cabane dans un arbre de mon jardin afin que mes petits enfants puissent y jouer. J\'ai le matériel nécessaire à disposition. Je pense que cela représentera une journée de travail. Merci   ', 0, 1, 2, 2),
(2, '2020-06-20 11:35:05', 'brico', 'brico', 0, 2, 1, 3),
(3, '2020-06-20 11:50:40', 'couture', 'couture pantalon', 0, 1, 1, 3);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `company`, `address`, `zip_code`, `city`, `phone`, `email`, `password`, `role`, `profile`, `lat`, `lng`) VALUES
(1, 'Nat', 'DS', '', '11 rue bel air', '29600', 'ST MARTIN DES CHAMPS', '0623523032', 'natachadesmet@yahoo.fr', '$2y$10$ka0BKEnd0T424kYSpbGRHOTpSq9FEVW9BnI.pyt4yLzfVwGilKuAW', 1, 'test', 48.5696, -3.83997),
(2, 'yo', 'jjk', '', ' 9 rue  bel air', '29600', 'st martin des champs', '64646', 'yo@yahooo.com', '$2y$10$E.LyDzV8vrxRkjLtaGtEg.OwvVoewXY1mMt0kaRTdBFVL.AFo1fLG', 1, 'yo', 48.5696, -3.83997);

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
