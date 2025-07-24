-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Jeu 24 Juillet 2025 à 10:53
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `absencetrack`
--

-- --------------------------------------------------------

--
-- Structure de la table `absence`
--

CREATE TABLE IF NOT EXISTS `absence` (
  `idAbsence` int(11) NOT NULL AUTO_INCREMENT,
  `dateAbsence` date DEFAULT NULL,
  `statut` enum('Présent','Absent','Absence Justifié','Retard') NOT NULL,
  `justification` text,
  `justificationValidee` tinyint(4) NOT NULL,
  `idEtudiant` int(11) DEFAULT NULL,
  `codeMatiere` varchar(10) DEFAULT NULL,
  `Matiere` varchar(255) NOT NULL,
  PRIMARY KEY (`idAbsence`),
  KEY `idEtudiant` (`idEtudiant`),
  KEY `codeMatiere` (`codeMatiere`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=140 ;

--
-- Contenu de la table `absence`
--

INSERT INTO `absence` (`idAbsence`, `dateAbsence`, `statut`, `justification`, `justificationValidee`, `idEtudiant`, `codeMatiere`, `Matiere`) VALUES
(1, '2025-05-16', 'Présent', NULL, 0, 3, NULL, ''),
(2, '2025-05-29', 'Absent', NULL, 0, 3, NULL, ''),
(3, '2025-05-16', 'Retard', NULL, 0, 7, NULL, ''),
(4, '2025-05-20', 'Retard', NULL, 0, 7, NULL, ''),
(5, '2025-05-16', '', NULL, 0, 3, NULL, ''),
(6, '2025-05-16', '', NULL, 0, 7, NULL, ''),
(7, '2025-05-20', '', NULL, 0, 10, NULL, ''),
(8, '2025-05-20', '', NULL, 0, 10, NULL, ''),
(9, '2025-05-20', '', NULL, 0, 10, NULL, ''),
(10, '2025-05-20', '', NULL, 0, 10, NULL, ''),
(11, '2025-05-26', 'Absent', NULL, 0, 3, NULL, ''),
(12, '2025-06-04', 'Retard', NULL, 0, 13, NULL, ''),
(13, '2025-07-15', 'Absent', NULL, 0, 3, NULL, ''),
(14, '2025-07-15', 'Absent', NULL, 0, 10, NULL, ''),
(15, '2025-07-15', 'Absent', NULL, 0, 16, NULL, ''),
(16, '2025-07-15', 'Présent', 'maladie', 1, 18, NULL, ''),
(17, '2025-07-15', 'Absent', NULL, 0, 19, NULL, ''),
(18, '2025-07-15', 'Absent', NULL, 0, 7, NULL, ''),
(19, '2025-07-15', 'Absent', NULL, 0, 11, NULL, ''),
(20, '2025-07-15', 'Absent', NULL, 0, 13, NULL, ''),
(21, '2025-07-15', 'Absent', NULL, 0, 15, NULL, ''),
(22, '2025-07-15', 'Absent', NULL, 0, 7, NULL, ''),
(23, '2025-07-15', 'Absent', NULL, 0, 11, NULL, ''),
(24, '2025-07-15', 'Absent', NULL, 0, 13, NULL, ''),
(25, '2025-07-15', 'Absent', NULL, 0, 15, NULL, ''),
(26, '2025-07-15', 'Absent', NULL, 0, 7, NULL, ''),
(27, '2025-07-15', 'Absent', NULL, 0, 11, NULL, ''),
(28, '2025-07-15', 'Absent', NULL, 0, 13, NULL, ''),
(29, '2025-07-15', 'Absent', NULL, 0, 15, NULL, ''),
(30, '2025-07-16', 'Présent', NULL, 0, 3, NULL, 'Math'),
(31, '2025-07-16', 'Absent', NULL, 0, 10, NULL, 'Math'),
(32, '2025-07-16', 'Absent', NULL, 0, 16, NULL, 'Math'),
(33, '2025-07-16', 'Retard', NULL, 0, 18, NULL, 'Math'),
(34, '2025-07-16', 'Absent', NULL, 0, 19, NULL, 'Math'),
(35, '2025-04-12', 'Présent', NULL, 0, 3, NULL, 'Anglais'),
(36, '2025-04-12', 'Absent', NULL, 0, 10, NULL, 'Anglais'),
(37, '2025-04-12', 'Retard', NULL, 0, 16, NULL, 'Anglais'),
(38, '2025-04-12', 'Présent', NULL, 0, 18, NULL, 'Anglais'),
(39, '2025-04-12', 'Présent', NULL, 0, 19, NULL, 'Anglais'),
(40, '2025-07-30', 'Présent', NULL, 0, 29, NULL, 'tg'),
(41, '2025-07-30', 'Absent', NULL, 0, 30, NULL, 'tg'),
(42, '2025-07-18', 'Présent', NULL, 0, 29, NULL, 'll'),
(43, '2025-07-18', 'Présent', NULL, 0, 30, NULL, 'll'),
(44, '2025-07-18', 'Présent', NULL, 0, 3, NULL, 'bnjnj'),
(45, '2025-07-18', 'Présent', NULL, 0, 10, NULL, 'bnjnj'),
(46, '2025-07-18', 'Présent', NULL, 0, 16, NULL, 'bnjnj'),
(47, '2025-07-18', 'Présent', NULL, 0, 18, NULL, 'bnjnj'),
(48, '2025-07-18', 'Présent', NULL, 0, 19, NULL, 'bnjnj'),
(49, '2025-07-18', 'Présent', NULL, 0, 21, NULL, 'bnjnj'),
(50, '2025-07-18', 'Absent', NULL, 0, 24, NULL, 'bnjnj'),
(51, '2025-07-18', 'Absent', NULL, 0, 25, NULL, 'bnjnj'),
(52, '2025-07-18', 'Absent', NULL, 0, 26, NULL, 'bnjnj'),
(53, '2025-07-18', 'Absent', NULL, 0, 27, NULL, 'bnjnj'),
(54, '2025-07-18', 'Retard', NULL, 0, 7, NULL, 'Math'),
(55, '2025-07-18', 'Absent', NULL, 0, 11, NULL, 'Math'),
(56, '2025-07-18', 'Absent', NULL, 0, 13, NULL, 'Math'),
(57, '2025-07-18', 'Absent', NULL, 0, 15, NULL, 'Math'),
(58, '2025-07-18', 'Absent', NULL, 0, 20, NULL, 'Math'),
(59, '2025-07-18', 'Absent', NULL, 0, 23, NULL, 'Math'),
(60, '2025-07-21', 'Présent', NULL, 0, 29, NULL, 'Anglais'),
(61, '2025-07-21', 'Absent', NULL, 0, 30, NULL, 'Anglais'),
(62, '2025-07-21', 'Absent', NULL, 0, 31, NULL, 'Anglais'),
(63, '2025-07-16', 'Présent', NULL, 0, 3, NULL, 'Math'),
(64, '2025-07-16', 'Absent', NULL, 0, 10, NULL, 'Math'),
(65, '2025-07-16', 'Absent', NULL, 0, 16, NULL, 'Math'),
(66, '2025-07-16', '', 'malade', 1, 18, NULL, 'Math'),
(67, '2025-07-16', 'Absent', NULL, 0, 19, NULL, 'Math'),
(68, '2025-07-16', 'Absent', NULL, 0, 21, NULL, 'Math'),
(69, '2025-07-16', 'Absent', NULL, 0, 24, NULL, 'Math'),
(70, '2025-07-16', 'Absent', NULL, 0, 25, NULL, 'Math'),
(71, '2025-07-16', 'Absent', NULL, 0, 26, NULL, 'Math'),
(72, '2025-07-16', 'Absent', NULL, 0, 27, NULL, 'Math'),
(73, '2025-07-21', 'Présent', NULL, 0, 3, NULL, 'Math'),
(74, '2025-07-21', 'Absent', NULL, 0, 10, NULL, 'Math'),
(75, '2025-07-21', 'Absent', NULL, 0, 16, NULL, 'Math'),
(76, '2025-07-21', '', 'gvbhjn', 1, 18, NULL, 'Math'),
(77, '2025-07-21', 'Absent', NULL, 0, 19, NULL, 'Math'),
(78, '2025-07-21', 'Absent', NULL, 0, 21, NULL, 'Math'),
(79, '2025-07-21', 'Absent', NULL, 0, 24, NULL, 'Math'),
(80, '2025-07-21', 'Absent', NULL, 0, 25, NULL, 'Math'),
(81, '2025-07-21', 'Absent', NULL, 0, 26, NULL, 'Math'),
(82, '2025-07-21', 'Absent', NULL, 0, 27, NULL, 'Math'),
(83, '2025-07-21', 'Présent', NULL, 0, 3, NULL, 'Anglais'),
(84, '2025-07-21', 'Présent', NULL, 0, 10, NULL, 'Anglais'),
(85, '2025-07-21', 'Absent', NULL, 0, 16, NULL, 'Anglais'),
(86, '2025-07-21', '', 'tuyu', 1, 18, NULL, 'Anglais'),
(87, '2025-07-21', 'Absent', NULL, 0, 19, NULL, 'Anglais'),
(88, '2025-07-21', 'Présent', NULL, 0, 21, NULL, 'Anglais'),
(89, '2025-07-21', 'Présent', NULL, 0, 24, NULL, 'Anglais'),
(90, '2025-07-21', 'Présent', NULL, 0, 25, NULL, 'Anglais'),
(91, '2025-07-21', 'Présent', NULL, 0, 26, NULL, 'Anglais'),
(92, '2025-07-21', 'Présent', NULL, 0, 27, NULL, 'Anglais'),
(93, '2025-07-03', 'Présent', NULL, 0, 3, NULL, 'Math'),
(94, '2025-07-03', 'Absent', NULL, 0, 10, NULL, 'Math'),
(95, '2025-07-03', 'Absent', NULL, 0, 16, NULL, 'Math'),
(96, '2025-07-03', 'Absence Justifié', 'malade', 1, 18, NULL, 'Math'),
(97, '2025-07-03', 'Absent', NULL, 0, 19, NULL, 'Math'),
(98, '2025-07-03', 'Absent', NULL, 0, 21, NULL, 'Math'),
(99, '2025-07-03', 'Absent', NULL, 0, 24, NULL, 'Math'),
(100, '2025-07-03', 'Absent', NULL, 0, 25, NULL, 'Math'),
(101, '2025-07-03', 'Présent', NULL, 0, 26, NULL, 'Math'),
(102, '2025-07-03', 'Présent', NULL, 0, 27, NULL, 'Math'),
(103, '2025-07-24', 'Présent', NULL, 0, 3, NULL, 'cgvhbn'),
(104, '2025-07-24', 'Absent', NULL, 0, 10, NULL, 'cgvhbn'),
(105, '2025-07-24', 'Absent', NULL, 0, 16, NULL, 'cgvhbn'),
(106, '2025-07-24', 'Absence Justifié', 'maladie', 1, 18, NULL, 'cgvhbn'),
(107, '2025-07-24', 'Absent', NULL, 0, 19, NULL, 'cgvhbn'),
(108, '2025-07-24', 'Absent', NULL, 0, 21, NULL, 'cgvhbn'),
(109, '2025-07-24', 'Absent', NULL, 0, 24, NULL, 'cgvhbn'),
(110, '2025-07-24', 'Présent', NULL, 0, 25, NULL, 'cgvhbn'),
(111, '2025-07-24', 'Présent', NULL, 0, 26, NULL, 'cgvhbn'),
(112, '2025-07-24', 'Présent', NULL, 0, 27, NULL, 'cgvhbn'),
(113, '2025-07-21', 'Présent', NULL, 0, 7, NULL, 'Anglais'),
(114, '2025-07-21', 'Présent', NULL, 0, 11, NULL, 'Anglais'),
(115, '2025-07-21', 'Présent', NULL, 0, 13, NULL, 'Anglais'),
(116, '2025-07-21', 'Présent', NULL, 0, 15, NULL, 'Anglais'),
(117, '2025-07-21', 'Présent', NULL, 0, 20, NULL, 'Anglais'),
(118, '2025-07-21', 'Présent', NULL, 0, 23, NULL, 'Anglais'),
(119, '2025-07-21', 'Absent', NULL, 0, 32, NULL, 'Anglais'),
(120, '2025-07-21', 'Présent', NULL, 0, 3, NULL, 'Anglais'),
(121, '2025-07-21', 'Présent', NULL, 0, 10, NULL, 'Anglais'),
(122, '2025-07-21', 'Absent', NULL, 0, 16, NULL, 'Anglais'),
(123, '2025-07-21', 'Absence Justifié', 'yuu', 1, 18, NULL, 'Anglais'),
(124, '2025-07-21', 'Absent', NULL, 0, 19, NULL, 'Anglais'),
(125, '2025-07-21', 'Absent', NULL, 0, 21, NULL, 'Anglais'),
(126, '2025-07-21', 'Absent', NULL, 0, 24, NULL, 'Anglais'),
(127, '2025-07-21', 'Absent', NULL, 0, 25, NULL, 'Anglais'),
(128, '2025-07-21', 'Absent', NULL, 0, 26, NULL, 'Anglais'),
(129, '2025-07-21', 'Absent', NULL, 0, 27, NULL, 'Anglais'),
(130, '2025-07-22', 'Présent', NULL, 0, 3, NULL, 'Anglais'),
(131, '2025-07-22', 'Absent', NULL, 0, 10, NULL, 'Anglais'),
(132, '2025-07-22', 'Absent', NULL, 0, 16, NULL, 'Anglais'),
(133, '2025-07-22', '', 'Maladie', 0, 18, NULL, 'Anglais'),
(134, '2025-07-22', 'Absent', NULL, 0, 19, NULL, 'Anglais'),
(135, '2025-07-22', 'Absent', NULL, 0, 21, NULL, 'Anglais'),
(136, '2025-07-22', 'Présent', NULL, 0, 24, NULL, 'Anglais'),
(137, '2025-07-22', 'Présent', NULL, 0, 25, NULL, 'Anglais'),
(138, '2025-07-22', 'Retard', NULL, 0, 26, NULL, 'Anglais'),
(139, '2025-07-22', 'Présent', NULL, 0, 27, NULL, 'Anglais');

-- --------------------------------------------------------

--
-- Structure de la table `classe`
--

CREATE TABLE IF NOT EXISTS `classe` (
  `idClasse` int(11) NOT NULL AUTO_INCREMENT,
  `nomClasse` varchar(50) NOT NULL,
  PRIMARY KEY (`idClasse`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `classe`
--

INSERT INTO `classe` (`idClasse`, `nomClasse`) VALUES
(1, 'Prépa2'),
(2, 'Prépa3'),
(3, 'polytech'),
(4, 'esilc'),
(5, 'poly');

-- --------------------------------------------------------

--
-- Structure de la table `enseignant`
--

CREATE TABLE IF NOT EXISTS `enseignant` (
  `idEnseignant` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `idUtilisateur` int(11) DEFAULT NULL,
  PRIMARY KEY (`idEnseignant`),
  UNIQUE KEY `email` (`email`),
  KEY `idUtilisateur` (`idUtilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `etudiant`
--

CREATE TABLE IF NOT EXISTS `etudiant` (
  `idEtudiant` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `emailparent` varchar(255) NOT NULL,
  `idClasse` int(11) DEFAULT NULL,
  `idUtilisateur` int(11) DEFAULT NULL,
  `loginParent` varchar(100) DEFAULT NULL,
  `login` varchar(255) NOT NULL,
  PRIMARY KEY (`idEtudiant`),
  UNIQUE KEY `email` (`email`),
  KEY `idClasse` (`idClasse`),
  KEY `idUtilisateur` (`idUtilisateur`),
  KEY `fk_loginParent` (`loginParent`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=33 ;

--
-- Contenu de la table `etudiant`
--

INSERT INTO `etudiant` (`idEtudiant`, `nom`, `prenom`, `email`, `emailparent`, `idClasse`, `idUtilisateur`, `loginParent`, `login`) VALUES
(3, 'jane', 'rzkzk', 'tachemomakuissujane@gmail.com', '', 1, NULL, NULL, ''),
(7, 'Tankeu', 'Ronald', 'Ronald@gmail.com', '', 2, NULL, NULL, ''),
(10, 'janefr', 'Francis', 'mmafouotayo@gmail.com', 'mmafouotayo@gmail.com', 1, NULL, NULL, ''),
(11, 'mamoi', 'JAENNEy', 'tachemomakuissu@gmail.com', 'tachemomakjane@gmail.com', 2, NULL, NULL, ''),
(13, 'jane', 'jane', 'janem@gmail.com', '', 2, NULL, 'mamy', ''),
(15, 'Tach', 'oceanne', 'tachemojane@gmail.com', '', 2, NULL, 'maman', ''),
(16, 'lele', 'Adrien', 'adrien@gmail.com', '', 1, NULL, 'maman', ''),
(18, 'lele', 'Adrien', 'adrien123@gmail.com', '', 1, 19, 'maman', ''),
(19, 'lele', 'Adrien', 'adrien123g@gmail.com', '', 1, 21, 'maman', ''),
(20, 'Jany', 'JAENNE', 'tachemomakuis@gmail.com', '', 2, NULL, 'janemama', ''),
(21, 'nin', 'nini', 'nini@gmail.com', '', 1, NULL, 'janemama', ''),
(23, 'nini', 'nini', 'ninijr@gmail.com', '', 2, NULL, 'janemama', ''),
(24, 'hen', 'hen', 'hen@gmail.com', '', 1, NULL, 'janemama', 'hen23'),
(25, 'Jant', 'JAENNE', NULL, '', 1, NULL, 'Boris1', 'Boris2'),
(26, 'Jant', 'JAENNE', NULL, '', 1, NULL, 'Boris1', 'Boris2'),
(27, 'Jant', 'JAENNE', NULL, '', 1, NULL, 'Boris1', 'Boris2'),
(29, 'janetrue', 'ni', 'niniy@gmail.com', '', 3, NULL, 'Stephane', 'maly'),
(30, 'janetrue', 'ni', 'niniyt@gmail.com', '', 3, NULL, 'Stephane', 'malygvhhb'),
(31, 'dodo', 'dodo', 'tac@gmail.com', '', 3, NULL, 'dodo', 'dodo12'),
(32, 'janty', 'katy', 'tacujane@gmail.com', '', 2, NULL, 'janemama', 'janty');

-- --------------------------------------------------------

--
-- Structure de la table `matiere`
--

CREATE TABLE IF NOT EXISTS `matiere` (
  `codeMatiere` varchar(10) NOT NULL,
  `libelle` varchar(100) DEFAULT NULL,
  `volumeHoraire` int(11) DEFAULT NULL,
  `idEnseignant` int(11) DEFAULT NULL,
  PRIMARY KEY (`codeMatiere`),
  KEY `idEnseignant` (`idEnseignant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE IF NOT EXISTS `utilisateur` (
  `idUtilisateur` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(100) NOT NULL,
  `emailadmin` varchar(255) NOT NULL,
  `motDePasse` varchar(255) NOT NULL,
  `role` enum('Admin','Enseignant','Etudiant','Parent') NOT NULL,
  PRIMARY KEY (`idUtilisateur`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=43 ;

--
-- Contenu de la table `utilisateur`
--

INSERT INTO `utilisateur` (`idUtilisateur`, `login`, `emailadmin`, `motDePasse`, `role`) VALUES
(1, 'admin', '', '$2y$10$OuK7Em.rOuSOECSoI9XhYubs/IJt2.X6iMHX7y.0HA3dkl3/UQHIW', 'Admin'),
(2, 'Tankeu', '', '$2y$10$e6eyAlr8vKnGVuXSthwI8eRigG8wQW1ZR6CcSdiFSZsqVzZtnXEI.', 'Enseignant'),
(3, 'Jane', '', '$2y$10$pIwOXJKaxPG1GV1JXcs9dewiAQtZhovSDI10S0M1S2m8Kj28AJlTq', 'Etudiant'),
(5, 'TATA', '', '$2y$10$SMKjCVtF58k4iL9dWvyvj.7Z.Pm4DsuG101fHqjilTu.ARZ2e6iJS', 'Admin'),
(8, 'mama', '', 'jane', 'Admin'),
(9, 'janefr', 'tachemomakuissujane@gmail.com', '$2y$10$C8U2VTlXUybLM.9PAyofOuor8R17.qnulNaOfyLRC1.NN20mjy3E.', 'Etudiant'),
(10, 'Mradmin', 'tachemomakuissujane@gmail.com', '$2y$10$/DCYy7nqd4lKyxrvfbLDYOE6UjN9EzHgRZD0o8uxZ1S5ZUEhkdedy', 'Admin'),
(11, 'makuissu', 'tachemomakuissujane@gmail.com', '$2y$10$fzLu5ETDIapm6Z7.4s80b.L7y.fX1vPr9.izZMMzVsihJRF2Xk2X2', 'Etudiant'),
(12, 'francis', '', '$2y$10$lUNZWT/6X0o3mQ0IWMoFkOtnhjpiKH4OX3z5ePfUUG0Bpa0WOVf8a', 'Enseignant'),
(13, 'maman', '', '$2y$10$TAGaDJFe1Ub7hLqSad/SMOqCxdr5ngiEgboujYtx8AocgdCxKQIK.', 'Parent'),
(15, 'amadmin', '', '$2y$10$/sUxY0CP3S5rACAiEGazWOMdHQ9f70Z2LwinauyFOo34iFVPYq2OS', 'Admin'),
(16, 'mamy', '', '$2y$10$LVNiTavSZT/OzUe.Jq3zAucyfwR0p6PJ.ByuGocog915Tj4.K9rGy', 'Parent'),
(17, 'LeleAD', '', '$2y$10$P5A7W1DfReosOunBROA8k.BZf5v0t3r96wu1XfjFoVATl4D1r93B6', 'Etudiant'),
(19, 'LeleAD12', '', '$2y$10$C4Ys.JIUU2RAPLv8HkAAVOloIORO/9mKRdhoIJUdzvsJKgC71dodi', 'Etudiant'),
(21, 'LeleAD12y', '', '$2y$10$Oe/NcRF/sm8KqNwShsRWnepN0KVgSDGoaLvsoqs7CiJYEaTUHMGS6', 'Etudiant'),
(25, 'LeleAD12yio', '', '$2y$10$N5/ypDOe0dpqkdV6/zloAuF6CZZHx2J8iLROA3COXQeQaa5Zhrdf.', 'Admin'),
(26, 'Boris', '', '$2y$10$FkImiLR3XxI4ElqNa7.f8uOA2L4BvRtPTXzP2adxBj01ECii8kYAy', 'Admin'),
(27, 'janemama', '', '$2y$10$Lli/COUoiq6Xy24cBEtgbuT5X2SABHUdQPmcsKQOkZU4Zoddo70Ty', 'Parent'),
(29, 'lili', '', '$2y$10$yNmpNeCniaYh9CiiqedJvOLXC5zYOalcDa0qE/WF9sxIRRoC6MDCq', 'Etudiant'),
(30, 'hen23', '', '$2y$10$ONUzvpdXazMlma2mSieklO377Vpe1UaqxBmg0jkJsuE7VsEl8D./u', 'Etudiant'),
(31, 'Boris1', '', '$2y$10$YNyPYRqGLKVmk6MuJhEG3ek42w/BhQi8L.28OV7U7Ns6yAKspxI1K', 'Parent'),
(32, 'Boris3', 'borisndjile3@gmail.com', '$2y$10$18traU5Ps819buiciyt.8ejC8CNNFoEIkzJfm2PK4saTEcZ0RjU3q', 'Parent'),
(33, 'Stephane', 'stephaneatabong45@gmail.com', '$2y$10$zH9RBufxdTcp/pOI6/w4DO8HrgPFyYMtmCCZPZoNBos5tuEptpXxK', 'Parent'),
(34, 'Bori', 'jakxs@gmail.com', '$2y$10$wLZglYxcejnrH.znrMDMceFvJbCX/v92TWEe6/DUJZiPbWK8AjURW', 'Parent'),
(35, 'moi', 'moi@gmail.com', '$2y$10$0lt37nQ5GrKXL0NG/Y1oAu2zW435zuM19B9OnbniyH/iBksIFwzOa', 'Enseignant'),
(36, 'mamay', 'mamay@gmail.com', '$2y$10$v4pgtsx9lOf80Led04eUBeanxK7L1QF4rBQ/5lV2ZvHR7XUZJ1Kbe', 'Parent'),
(37, 'maly', 'maly@gmail.com', '$2y$10$rffddXeq1c9bxy8sG8AVJuR9d/qdqm27ZftfG1ogwfXUxZXNB0q.K', 'Enseignant'),
(39, 'malygvhhb', '', '$2y$10$DrR5G8UMZIkyMCk2R8Sz/.GTRJq2Q.igLfjGNdyR1/ZF0eGV52PPm', 'Etudiant'),
(40, 'dodo', 'dodo@gmail.com', '$2y$10$sxvkZTUy8ac3UZEAaGhZ4Oa2jZWp5uvDdWuJsa0NKsuLNqXgDt7Ma', 'Parent'),
(41, 'dodo12', '', '$2y$10$CWlq/9gkE54nDqAraB/mTeksBGLs6eVFE6DRtiX02qv/lohUZGj8W', 'Etudiant'),
(42, 'janty', '', '$2y$10$Pzm25CkyXVBaQroM6MTMV.xWzHjO0/tSiYlCDLpAKD28K0qNiex.6', 'Etudiant');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `absence`
--
ALTER TABLE `absence`
  ADD CONSTRAINT `absence_ibfk_1` FOREIGN KEY (`idEtudiant`) REFERENCES `etudiant` (`idEtudiant`),
  ADD CONSTRAINT `absence_ibfk_2` FOREIGN KEY (`codeMatiere`) REFERENCES `matiere` (`codeMatiere`);

--
-- Contraintes pour la table `enseignant`
--
ALTER TABLE `enseignant`
  ADD CONSTRAINT `enseignant_ibfk_1` FOREIGN KEY (`idUtilisateur`) REFERENCES `utilisateur` (`idUtilisateur`);

--
-- Contraintes pour la table `etudiant`
--
ALTER TABLE `etudiant`
  ADD CONSTRAINT `etudiant_ibfk_1` FOREIGN KEY (`idClasse`) REFERENCES `classe` (`idClasse`),
  ADD CONSTRAINT `etudiant_ibfk_2` FOREIGN KEY (`idUtilisateur`) REFERENCES `utilisateur` (`idUtilisateur`),
  ADD CONSTRAINT `fk_loginParent` FOREIGN KEY (`loginParent`) REFERENCES `utilisateur` (`login`);

--
-- Contraintes pour la table `matiere`
--
ALTER TABLE `matiere`
  ADD CONSTRAINT `matiere_ibfk_1` FOREIGN KEY (`idEnseignant`) REFERENCES `enseignant` (`idEnseignant`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
