-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Client: localhost

-- Généré le: Lun 09 Juillet 2012 à 13:15
-- Version du serveur: 5.5.20-log
-- Version de PHP: 5.3.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+02:00";







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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Contenu de la table `tablature`
--


-- --------------------------------------------------------

--
-- Structure de la table `user`
--



CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dateInscription` int(11) NOT NULL,
  `login` varchar(30) NOT NULL,
  `password` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id`, `dateInscription`, `login`, `password`) VALUES
(1, 1310656239, 'Admin', '3174685bb0ac5dfed704b7b41a5e44b713b759a1'),
(2, 1341846639, 'Fab', '03b8b8d1ba64d7498760a6b5e6cf197335d62ad7'),
(3, 1341846739, 'quidam', '279dc1e9d24616e6e4b553320688323cf8febf08');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `tablature`
--
ALTER TABLE `tablature`
  ADD CONSTRAINT `tablature_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;




