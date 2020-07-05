-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  Dim 05 juil. 2020 à 18:29
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `adds`
--

INSERT INTO `adds` (`id`, `created_at`, `title`, `description`, `closed`, `creator_id`, `basket_size`, `basket_quantity`) VALUES
(1, '2020-06-20 09:39:00', 'Cabane dans les arbres ', 'Bonjour j\'aurais besoin d\'aide pour créer une cabane dans un arbre de mon jardin afin que mes petits enfants puissent y jouer. J\'ai le matériel nécessaire à disposition. Je pense que cela représentera une journée de travail. Merci   ', 0, 1, 2, 2),
(2, '2020-06-20 11:35:05', 'brico', 'brico', 1, 2, 1, 3),
(3, '2020-06-20 11:50:40', 'couture', 'couture pantalon', 1, 1, 1, 3),
(6, '2020-07-05 10:42:09', 'lavage', 'jkh ijo', 0, 1, 3, 1),
(7, '2020-07-05 19:20:51', 'pour de la couture de sac', 'couture de sac', 0, 8, 2, 2);

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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `baskets`
--

INSERT INTO `baskets` (`id`, `company_id`, `title`, `description`, `available`) VALUES
(1, 3, 'choux', 'choux variés', 1),
(9, 3, 'Légumes variés', 'Légumes de saison ', 1),
(10, 3, 'hjgjkbblk', 'kjhlknmnmrr', 1),
(11, 3, 'lllllllllllllljjjj', 'jhkbllkoiho', 1),
(12, 3, 'rdrdrrdrdrdrdrd', 'odododddododooddo', 1),
(13, 3, 'pypypypypyp', 'kfkfkfkfkkfkfk', 1);

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `company`, `address`, `zip_code`, `city`, `phone`, `email`, `password`, `role`, `profile`, `lat`, `lng`, `register_date`) VALUES
(1, 'Natacha', 'De Smet', '', '8 rue orsel', '69600', 'oullins', '0623523032', 'natachadesmet@yahoo.fr', '$2y$10$npDtoLRqPGkFt5vMZb/ufegLEvk0CQ3oCcVWkFmrEjgFw1Er5KQk6', 1, 'test', 45.7163, 4.81046, '2020-07-05 14:55:57'),
(2, 'Yolande', 'jjk', '', ' 9 rue  bel air', '29600', 'st martin des champs', '64646', 'yo@yahooo.com', '$2y$10$8R470tcB9JDpowkCb9QuMu1v9OZg0E1FIIXtnvhQIeZtWWZ6s9iVy', 1, 'yo', 48.5696, -3.83997, '2020-07-05 14:55:57'),
(3, 'georges', 'Lapatate', 'chouchoux', '10 rue courte', '29600', 'Morlaix', '54654646', 'chouchoux@chou.com', '$2y$10$8hd/l/xn6FtfOF0r75InVOW9v.Dy.rf8AkZVxokDOmBJx6g8x8eAi', 2, 'entreprise légumes', 48.5764, -3.83018, '2020-07-05 14:55:57'),
(4, 'Albert', 'choux', 'Choux\'chêne', '6 rue courte', '29600', 'Morlaix', '5464646', 'chene@chene.com', '$2y$10$Chhpfof4Z.a.c9RC8YI7auW9lQivccleqx4Uu4mUoHiZAjnvqltzq', 2, 'producteur légumes et alcool', 48.5763, -3.82997, '2020-07-05 14:55:57'),
(5, 'nat', 'de smet', '', '11 rue bel air', '29600', 'ST MARTIN DES CHAMPS', '0623523032', 'natdesmet@yahoo.fr', '$2y$10$PBGtht3VYRtI7L0BFy8NLergyXC9o.K87YTPedxr9hSYCH5pdsKTe', 1, 'jhkbkb', 48.5696, -3.83997, '2020-07-05 14:55:57'),
(6, 'guill', 'hhjh', '', '7 rue bel air', '29600', 'ST MARTIN DES CHAMPS', '0623523032', 'test@com.com', '$2y$10$HLm5OlQwn3K.CRFm8GY/k.fxHVu35.A8SIlex7xjUTP5YxV37SSmO', 1, 'jbkbjk', 48.5696, -3.83997, '2020-07-05 14:55:57'),
(7, 'jo', 'hjjgjg', '', '5 rue bel air', '29600', 'ST MARTIN DES CHAMPS', '0623523032', 'jo@jo.com', '$2y$10$xQ2D5YhyaaFf1nIPK5lNQOl.ROrbqz1h1X.egB2LYWtIAn9I9SjzC', 1, 'kjhkjn', 48.5696, -3.83997, '2020-07-05 14:55:57'),
(8, 'Jean', 'FORTEROCHE', '', '43 rue de kerloscant', '29670', 'Taulé', '654614615', 'jeannot@blog.com', '$2y$10$Rdv8.QG75ssWCEXlUTjjmeSwqPQ2YAXnlPMNrWvXQcqjwlze62zrm', 1, 'hikhih', 48.6021, -3.90104, '2020-07-05 14:55:57'),
(9, 'hjbjkhk', 'bjkbkj', '', '11 rue bel air', '29600', 'ST MARTIN DES CHAMPS', '0623523032', 'nat@yahoo.fr', '$2y$10$RAcZURzcH/nKqA6MnWFZ5.pDYtJUGTCZO5BJfcwy1Yl8qZaZRcqdm', 1, 'hnlilk', 48.5696, -3.83997, '2020-07-05 15:34:05'),
(10, 'hjkbjk', 'hhhhhh', '', '11 rue bel air', '29600', 'ST MARTIN DES CHAMPS', '0623523032', 'na@yahoo.fr', '$2y$10$XI2jTR5EqhZCs9qbmLNfSe1W2s/uQVAZsxbbDiyETjrG8nN4SkGOK', 1, 'jhbk', 48.5696, -3.83997, '2020-07-05 15:34:56');

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `validations`
--

INSERT INTO `validations` (`id`, `user_id`, `add_id`, `basket_id`, `accepted_date`) VALUES
(1, 1, 2, 9, '2020-07-02 20:30:31'),
(2, 2, 3, 1, '2020-07-02 20:32:49');

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
