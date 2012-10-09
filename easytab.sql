-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Version du serveur: 5.5.20-log
-- Version de PHP: 5.3.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+02:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `easytab`
--
CREATE DATABASE `easytab` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `easytab`;

-- --------------------------------------------------------

--
-- Structure de la table `tablature`
--

CREATE TABLE IF NOT EXISTS `tablature` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `nom` text NOT NULL,
  `chemin` text NOT NULL,
  `titre` text NOT NULL,
  `artiste` text NOT NULL,
  `public` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Contenu de la table `tablature`
--


-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dateInscription` bigint(14) NOT NULL,
  `login` varchar(30) NOT NULL,
  `password` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id`, `dateInscription`, `login`, `password`) VALUES
(1, -5694963725000, 'Admin', '3174685bb0ac5dfed704b7b41a5e44b713b759a1'),
(2, 1341763667245, 'Fab', '03b8b8d1ba64d7498760a6b5e6cf197335d62ad7'),
(3, 1341884838345, 'quidam', '279dc1e9d24616e6e4b553320688323cf8febf08');


--
-- Structure de la table `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auteurId` int(11) NOT NULL,
  `texte` text NOT NULL,
  `tablatureId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `auteurId` (`auteurId`),
  KEY `tablatureId` (`tablatureId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `note`
--

CREATE TABLE IF NOT EXISTS `note` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `tablatureId` int(11) NOT NULL,
  `note` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tablatureId` (`tablatureId`),
  KEY `userId` (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`tablatureId`) REFERENCES `tablature` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`auteurId`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Contraintes pour la table `note`
--
ALTER TABLE `note`
  ADD CONSTRAINT `note_ibfk_2` FOREIGN KEY (`tablatureId`) REFERENCES `tablature` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `note_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Contraintes pour la table `tablature`
--
ALTER TABLE `tablature`
  ADD CONSTRAINT `tablature_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
