-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 09 avr. 2025 à 22:48
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bibliotheque_numerique`
--

-- --------------------------------------------------------

--
-- Structure de la table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `admins`
--

INSERT INTO `admins` (`id`, `name`, `password`, `role`) VALUES
(1, 'admin', '1234', 'admin'),
(2, 'test', '$2y$10$OCaIjIXPWxiDwP8NtAy3S.oGRk4C25MVQAaEhfgn6mG78XeznUy9G', 'admin');

-- --------------------------------------------------------

--
-- Structure de la table `admin_logs`
--

DROP TABLE IF EXISTS `admin_logs`;
CREATE TABLE IF NOT EXISTS `admin_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `admin_id` int NOT NULL,
  `actions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `documents`
--

DROP TABLE IF EXISTS `documents`;
CREATE TABLE IF NOT EXISTS `documents` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type_id` int NOT NULL,
  `document_path` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `publication_date` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `type_id` (`type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `document_fields`
--

DROP TABLE IF EXISTS `document_fields`;
CREATE TABLE IF NOT EXISTS `document_fields` (
  `id` int NOT NULL AUTO_INCREMENT,
  `document_id` int NOT NULL,
  `field_id` int NOT NULL,
  `field_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `document_id` (`document_id`),
  KEY `field_id` (`field_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `fields`
--

DROP TABLE IF EXISTS `fields`;
CREATE TABLE IF NOT EXISTS `fields` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type_id` (`type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `fields`
--

INSERT INTO `fields` (`id`, `type`, `type_id`, `name`) VALUES
(14, 'date', 9, 'date'),
(13, 'text', 9, 'option'),
(12, 'text', 9, 'specialiter'),
(11, 'text', 9, 'theme'),
(41, 'text', 34, 'machine'),
(33, 'number', 26, 'ffff'),
(40, 'text', 33, 'machine'),
(39, 'text', 32, 'machine'),
(38, 'text', 31, '52125'),
(37, 'text', 30, 'hhhh'),
(36, 'text', 29, '1111'),
(42, 'text', 35, '57554'),
(43, 'number', 36, 'zddsf');

-- --------------------------------------------------------

--
-- Structure de la table `types`
--

DROP TABLE IF EXISTS `types`;
CREATE TABLE IF NOT EXISTS `types` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `types`
--

INSERT INTO `types` (`id`, `name`) VALUES
(9, 'Mimoire'),
(25, 'gggg'),
(27, 'kkk machine'),
(32, 'kkk machine'),
(31, '5841'),
(29, 'kkk machine'),
(30, 'hhhh hhhh hhhh hhhh'),
(33, 'kkk machine'),
(34, 'kkk machine'),
(35, '457554'),
(36, 'zddsd');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
