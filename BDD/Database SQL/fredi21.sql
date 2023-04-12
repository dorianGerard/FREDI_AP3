-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 22, 2023 at 05:45 PM
-- Server version: 10.10.3-MariaDB-1:10.10.3+maria~ubu2204
-- PHP Version: 8.1.2-1ubuntu2.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fredi21`
--

CREATE DATABASE fredi21 DEFAULT CHARSET=utf8 COLLATE utf8_general_ci ;
USE fredi21;

-- --------------------------------------------------------

--
-- Structure de la table `adherent`
--

CREATE TABLE `adherent` (
  `id_adherent` int(11) NOT NULL,
  `nr_licence` varchar(50) DEFAULT NULL,
  `adr1` varchar(50) DEFAULT NULL,
  `adr2` varchar(50) DEFAULT NULL,
  `adr3` varchar(50) DEFAULT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_club` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `adherent`
--

INSERT INTO `adherent` (`id_adherent`, `nr_licence`, `adr1`, `adr2`, `adr3`, `id_utilisateur`, `id_club`) VALUES
(10, 'L654', 'Place Stanislas, 54000 Nancy', NULL, NULL, 1, 2),
(11, 'L987', '1 Rue Paul Verlaine, 54500 Vandoeuvre-lès-Nancy', NULL, NULL, 2, 3),
(12, 'L665', '1 Rue des Arts, 54000 Nancy', NULL, NULL, 3, 5),
(13, 'L689', '5 Rue Gambetta, 57000 Metz', NULL, NULL, 4, 6),
(14, 'L682', '1 Rue du Pont Mouja, 57000 Metz', NULL, NULL, 5, 8),
(15, 'L892', '8 Rue Gustave Simon, 54000 Nancy', NULL, NULL, 6, 5),
(16, 'L836', '2 Rue de la Source, 54520 Laxou', NULL, NULL, 7, 4),
(17, 'L368', '22 Rue Sainte-Catherine, 54000 Nancy', NULL, NULL, 8, 7);

-- --------------------------------------------------------

--
-- Structure de la table `club`
--

CREATE TABLE `club` (
  `id_club` int(11) NOT NULL,
  `lib_club` varchar(50) DEFAULT NULL,
  `adr1` varchar(50) DEFAULT NULL,
  `adr2` varchar(50) DEFAULT NULL,
  `adr3` varchar(50) DEFAULT NULL,
  `id_ligue` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `club`
--

INSERT INTO `club` (`id_club`, `lib_club`, `adr1`, `adr2`, `adr3`, `id_ligue`) VALUES
(1, 'Club de Foot de Nancy', NULL, NULL, NULL, 1),
(2, 'Club de Foot de Metz', NULL, NULL, NULL, 1),
(3, 'Club de Handball de Lunéville', NULL, NULL, NULL, 2),
(4, 'Club de Handball de Epinal', NULL, NULL, NULL, 2),
(5, 'Club de Golf de Verdun', NULL, NULL, NULL, 3),
(6, 'Club de Golf de Longwy', NULL, NULL, NULL, 3),
(7, 'Club de Judo de ThionVille', NULL, NULL, NULL, 4),
(8, 'Club de Judo de bar-le-Duc', NULL, NULL, NULL, 4),
(9, 'Club de Rugby de Yutz', NULL, NULL, NULL, 5),
(10, 'Club de Rugby de Bitche', NULL, NULL, NULL, 5);

-- --------------------------------------------------------

--
-- Structure de la table `ligne`
--

CREATE TABLE `ligne` (
  `id_ligne` int(11) NOT NULL,
  `date_ligne` date DEFAULT NULL,
  `lib_trajet` varchar(50) DEFAULT NULL,
  `nb_km` decimal(11,0) DEFAULT NULL,
  `mt_km` decimal(15,2) DEFAULT NULL,
  `mt_peage` decimal(15,2) DEFAULT NULL,
  `mt_repas` decimal(15,2) DEFAULT NULL,
  `mt_hebergement` decimal(15,2) DEFAULT NULL,
  `mt_total` decimal(15,2) DEFAULT NULL,
  `id_motif` int(11) NOT NULL,
  `id_note` int(11) NOT NULL,
  `id_periode` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `ligne`
--

INSERT INTO `ligne` (`id_ligne`, `date_ligne`, `lib_trajet`, `nb_km`, `mt_km`, `mt_peage`, `mt_repas`, `mt_hebergement`, `mt_total`, `id_motif`, `id_note`, `id_periode`) VALUES
(34, '2022-12-21', 'Nancy - Metz', 98, 168.56, 78.00, 12.00, 3.00, 261.56, 14, 35, 2),
(35, '2022-12-28', 'Toulouse - Paris', 235, 404.20, 352.00, 35.00, 234.00, 1025.20, 14, 35, 4),
(45, '2023-03-10', 'Montauban - Toulouse', 152, 270.86, 15.00, 25.00, 150.00, 460.86, 14, 45, 3);

--
-- Déclencheurs `ligne`
--
DELIMITER $$
CREATE TRIGGER `after_delete_ligne` AFTER DELETE ON `ligne` FOR EACH ROW BEGIN

DECLARE NewTotalMT INT;
DECLARE OldTotalMT INT;
DECLARE curseur_mtLigne_fini INT;
DECLARE verifMT INT;

DECLARE mtLigne_Cursor CURSOR FOR SELECT mt_total FROM ligne WHERE id_note = OLD.id_note;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET curseur_mtLigne_fini = 1 ;

SET NewTotalMT = 0;

OPEN mtLigne_Cursor;
ligne_cursor: LOOP
    FETCH mtLigne_Cursor INTO OldTotalMT;

    if curseur_mtLigne_fini = 1 THEN
        UPDATE note SET mt_total = NewTotalMT WHERE id_note = OLD.id_note;
        SELECT mt_total into verifMT FROM note WHERE id_note = OLD.id_note;
        IF verifMT = 0 THEN
            DELETE FROM note WHERE id_note = OLD.id_note;
        END IF;
        LEAVE ligne_cursor;
    END IF;
        
    SET NewTotalMT = OldTotalMT + NewTotalMT;

END LOOP ligne_cursor;
CLOSE mtLigne_Cursor;

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_insert_ligne` AFTER INSERT ON `ligne` FOR EACH ROW BEGIN

DECLARE NewTotalMT INT;
DECLARE OldTotalMT INT;
DECLARE curseur_mtLigne_fini INT;

DECLARE mtLigne_Cursor CURSOR FOR SELECT mt_total FROM ligne WHERE id_note = NEW.id_note;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET curseur_mtLigne_fini = 1 ;

SET NewTotalMT = 0;

OPEN mtLigne_Cursor;
ligne_cursor: LOOP
    FETCH mtLigne_Cursor INTO OldTotalMT;

    if curseur_mtLigne_fini = 1 THEN
        UPDATE note SET mt_total = NewTotalMT WHERE id_note = NEW.id_note;
        LEAVE ligne_cursor;
    END IF;

    SET NewTotalMT = OldTotalMT + NewTotalMT;

END LOOP ligne_cursor;
CLOSE mtLigne_Cursor;

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_update_ligne` AFTER UPDATE ON `ligne` FOR EACH ROW BEGIN

DECLARE NewTotalMT INT;
DECLARE OldTotalMT INT;
DECLARE curseur_mtLigne_fini INT;

DECLARE mtLigne_Cursor CURSOR FOR SELECT mt_total FROM ligne WHERE id_note = NEW.id_note;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET curseur_mtLigne_fini = 1 ;

SET NewTotalMT = 0;

OPEN mtLigne_Cursor;
ligne_cursor: LOOP
    FETCH mtLigne_Cursor INTO OldTotalMT;

    if curseur_mtLigne_fini = 1 THEN
        UPDATE note SET mt_total = NewTotalMT WHERE id_note = NEW.id_note;
        LEAVE ligne_cursor;
    END IF;

    SET NewTotalMT = OldTotalMT + NewTotalMT;

END LOOP ligne_cursor;
CLOSE mtLigne_Cursor;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `ligue`
--

CREATE TABLE `ligue` (
  `id_ligue` int(11) NOT NULL,
  `lib_ligue` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `ligue`
--

INSERT INTO `ligue` (`id_ligue`, `lib_ligue`) VALUES
(1, 'Football'),
(2, 'Handball'),
(3, 'Golf'),
(4, 'Judo'),
(5, 'Rugby');

-- --------------------------------------------------------

--
-- Structure de la table `motif`
--

CREATE TABLE `motif` (
  `id_motif` int(11) NOT NULL,
  `lib_motif` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `motif`
--

INSERT INTO `motif` (`id_motif`, `lib_motif`) VALUES
(10, 'Competition'),
(11, 'Rassemblement'),
(12, 'Spectacle'),
(13, 'Achat materiel'),
(14, 'Enseignement'),
(15, 'Cours'),
(16, 'Participation foire'),
(17, 'Recrutement');

-- --------------------------------------------------------

--
-- Structure de la table `note`
--

CREATE TABLE `note` (
  `id_note` int(11) NOT NULL,
  `est_valide` tinyint(1) DEFAULT NULL,
  `mt_total` decimal(15,2) DEFAULT NULL,
  `dat_remise` date DEFAULT NULL,
  `id_periode` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `note`
--

INSERT INTO `note` (`id_note`, `est_valide`, `mt_total`, `dat_remise`, `id_periode`, `id_utilisateur`) VALUES
(35, 0, 1287.00, '2022-12-21', 4, 2),
(45, 1, 461.00, '2023-03-10', 3, 2);

-- --------------------------------------------------------

--
-- Structure de la table `periode`
--

CREATE TABLE `periode` (
  `id_periode` int(11) NOT NULL,
  `lib_periode` varchar(50) DEFAULT NULL,
  `est_active` tinyint(1) NOT NULL DEFAULT 0,
  `mt_km` decimal(8,3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `periode`
--

INSERT INTO `periode` (`id_periode`, `lib_periode`, `est_active`, `mt_km`) VALUES
(1, '2023', 0, 175.280),
(2, '2022', 0, 185.000),
(3, '2021', 0, 178.200),
(4, '2020', 1, 172.230),
(5, '2019', 0, 178.000),
(6, '2018', 0, 172.800),
(7, '2017', 0, 245.000),
(8, '2016', 0, 368.000),
(9, '2015', 0, 213.000),
(10, '2014', 0, 192.500);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_utilisateur` int(11) NOT NULL,
  `pseudo` varchar(50) DEFAULT NULL,
  `mdp` varchar(255) DEFAULT NULL,
  `mail` varchar(50) DEFAULT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `role` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `pseudo`, `mdp`, `mail`, `nom`, `prenom`, `role`) VALUES
(1, 'Darksasuke', '$2y$10$IoJFeapIfa/g7myDX/AP4OLqHckc43h0T8vwRVS5amQB0DyJvMkV.', 'Darksasuke@gmail.com', 'Richard', 'Cuterrie', 1),
(2, 'Poney123', '$2y$10$OFFfUMwLdYV5KkVzCp6Nl.HDPNnK0DwQpWTlP/r4aMwqDfz1K0WwG', 'Poney123@gmail.com', 'Henry', 'Car', 1),
(3, 'DocteurSol', '$2y$10$/AyjUIXZyHdJfYgFvFTax.DuPV7qOTfNR/EzC/705Rrg0JA5xUrtS', 'DocteurSol@gmail.com', 'Khaoutar', 'Tiflette', 1),
(4, 'SISRcNUL', '$2y$10$A5PhXZazYlFYjFi0UNaA7O/3nd5H/tj1fS6F40b7LwwhTrZMjhn7i', 'SISRcNUL@gmail.com', 'Jean', 'Bonbeur', 2),
(5, 'JaimelaM2L', '$2y$10$G03GfY8fdt1ejXz/jnW1C.btqtyzDa9xSNxiZ4Ch1G8G35i5dswA6', 'JaimelaM2L@gmail.com', 'Ibrhima', 'Carronie', 3),
(6, 'FouBrave', '$2y$10$Lz/psFVpY55K2.eE/DOOve4N79iaG3.JG62Bdeq3I7baDEIQCe3Yq', 'FouBrave@gmail.com', 'Angélica', 'Kahuète', 1),
(7, 'GoodRiku', '$2y$10$.nMFzej81tq58QMEOQI2DuFWoZqzN86Cg/qW/kly1vHh4FXeOwv.m', 'GoodRiku@gmail.com', 'Oscar', 'Got', 2),
(8, 'CookieVif', '$2y$10$5k8GZzA0drzIiqsT0IUQSer/tVQRxMiy1EuorH7sxZ0ohlzszhBKe', 'CookieVif@gmail.com', 'Vladimir', 'Aclette', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `adherent`
--
ALTER TABLE `adherent`
  ADD PRIMARY KEY (`id_adherent`),
  ADD KEY `fk_id_utilisateur2` (`id_utilisateur`),
  ADD KEY `fk_id_club` (`id_club`);

--
-- Index pour la table `club`
--
ALTER TABLE `club`
  ADD PRIMARY KEY (`id_club`),
  ADD KEY `fk_id_ligue` (`id_ligue`);

--
-- Index pour la table `ligne`
--
ALTER TABLE `ligne`
  ADD PRIMARY KEY (`id_ligne`),
  ADD KEY `fk_id_motif` (`id_motif`),
  ADD KEY `fk_id_note` (`id_note`),
  ADD KEY `id_periode` (`id_periode`);

--
-- Index pour la table `ligue`
--
ALTER TABLE `ligue`
  ADD PRIMARY KEY (`id_ligue`);

--
-- Index pour la table `motif`
--
ALTER TABLE `motif`
  ADD PRIMARY KEY (`id_motif`);

--
-- Index pour la table `note`
--
ALTER TABLE `note`
  ADD PRIMARY KEY (`id_note`),
  ADD KEY `fk_id_periode` (`id_periode`),
  ADD KEY `fk_id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `periode`
--
ALTER TABLE `periode`
  ADD PRIMARY KEY (`id_periode`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `adherent`
--
ALTER TABLE `adherent`
  MODIFY `id_adherent` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pour la table `club`
--
ALTER TABLE `club`
  MODIFY `id_club` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `ligne`
--
ALTER TABLE `ligne`
  MODIFY `id_ligne` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT pour la table `ligue`
--
ALTER TABLE `ligue`
  MODIFY `id_ligue` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `motif`
--
ALTER TABLE `motif`
  MODIFY `id_motif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `note`
--
ALTER TABLE `note`
  MODIFY `id_note` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT pour la table `periode`
--
ALTER TABLE `periode`
  MODIFY `id_periode` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `adherent`
--
ALTER TABLE `adherent`
  ADD CONSTRAINT `fk_id_club` FOREIGN KEY (`id_club`) REFERENCES `club` (`id_club`),
  ADD CONSTRAINT `fk_id_utilisateur2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `club`
--
ALTER TABLE `club`
  ADD CONSTRAINT `fk_id_ligue` FOREIGN KEY (`id_ligue`) REFERENCES `ligue` (`id_ligue`);

--
-- Contraintes pour la table `ligne`
--
ALTER TABLE `ligne`
  ADD CONSTRAINT `fk_id_motif` FOREIGN KEY (`id_motif`) REFERENCES `motif` (`id_motif`),
  ADD CONSTRAINT `fk_id_note` FOREIGN KEY (`id_note`) REFERENCES `note` (`id_note`),
  ADD CONSTRAINT `ligne_ibfk_1` FOREIGN KEY (`id_periode`) REFERENCES `periode` (`id_periode`);

--
-- Contraintes pour la table `note`
--
ALTER TABLE `note`
  ADD CONSTRAINT `fk_id_periode` FOREIGN KEY (`id_periode`) REFERENCES `periode` (`id_periode`),
  ADD CONSTRAINT `fk_id_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

