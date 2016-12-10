-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Ven 02 Décembre 2016 à 01:40
-- Version du serveur :  5.7.16-0ubuntu0.16.04.1
-- Version de PHP :  7.0.8-0ubuntu0.16.04.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données :  `cp_scrum`
--
CREATE DATABASE IF NOT EXISTS `cp_scrum` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `cp_scrum`;

-- --------------------------------------------------------

--
-- Structure de la table `burndown_chart`
--

CREATE TABLE `burndown_chart` (
  `BDC_id` int(11) NOT NULL,
  `BDC_chargePlanifie` int(11) NOT NULL,
  `SPR_id` int(11) NOT NULL,
  `PRO_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `developpeur`
--

CREATE TABLE `developpeur` (
  `DEV_id` int(11) NOT NULL,
  `DEV_prenom` varchar(255) NOT NULL,
  `DEV_nom` varchar(255) NOT NULL,
  `DEV_pseudo` varchar(20) NOT NULL,
  `DEV_mdp` varchar(255) NOT NULL,
  `DEV_mail` varchar(255) NOT NULL,
  `DEV_urlAvatar` text,
  `DEV_dateCreation` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `inter_dev_projet`
--

CREATE TABLE `inter_dev_projet` (
  `DEV_id` int(11) NOT NULL,
  `PRO_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `projet`
--

CREATE TABLE `projet` (
  `PRO_id` int(11) NOT NULL,
  `PRO_nom` varchar(255) NOT NULL,
  `PRO_client` varchar(255) NOT NULL,
  `PRO_description` text NOT NULL,
  `PRO_dateCreation` datetime NOT NULL,
  `DEV_idProductOwner` int(11) NOT NULL,
  `DEV_idScrumMaster` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `sprint`
--

CREATE TABLE `sprint` (
  `SPR_id` int(11) NOT NULL,
  `SPR_numero` int(11) NOT NULL,
  `SPR_dateDebut` date NOT NULL,
  `SPR_duree` int(11) NOT NULL,
  `PRO_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `tache`
--

CREATE TABLE `tache` (
  `TAC_id` int(11) NOT NULL,
  `TAC_numero` int(11) NOT NULL,
  `TAC_nom` varchar(255) NOT NULL,
  `TAC_description` text NOT NULL,
  `TAC_nbJours` int(11) NOT NULL,
  `TAC_dateDepart` date NOT NULL,
  `TAC_etat` enum('TO DO','ON GOING','TO TEST','DONE') DEFAULT NULL,
  `DEV_id` int(11) NOT NULL,
  `US_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `us`
--

CREATE TABLE `us` (
  `US_id` int(11) NOT NULL,
  `US_numero` int(11) NOT NULL,
  `US_nom` varchar(255) NOT NULL,
  `US_chiffrageAbstrait` int(11) NOT NULL,
  `US_priorite` int(11) DEFAULT NULL,
  `US_dateCreation` datetime NOT NULL,
  `US_dateDernierCommit` date DEFAULT NULL,
  `US_idDernierCommit` varchar(255) DEFAULT NULL,
  `US_auteurDernierCommit` varchar(255) DEFAULT NULL,
  `PRO_id` int(11) NOT NULL,
  `SPR_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `burndown_chart`
--
ALTER TABLE `burndown_chart`
  ADD PRIMARY KEY (`BDC_id`),
  ADD KEY `PRO_id` (`PRO_id`),
  ADD KEY `SPR_id` (`SPR_id`);

--
-- Index pour la table `developpeur`
--
ALTER TABLE `developpeur`
  ADD PRIMARY KEY (`DEV_id`),
  ADD UNIQUE KEY `UC_DEVELOPPEUR_PSEUDO` (`DEV_pseudo`),
  ADD UNIQUE KEY `UC_DEVELOPPEUR_MAIL` (`DEV_mail`);

--
-- Index pour la table `inter_dev_projet`
--
ALTER TABLE `inter_dev_projet`
  ADD PRIMARY KEY (`DEV_id`,`PRO_id`),
  ADD KEY `inter_dev_projet_ibfk_2` (`PRO_id`);

--
-- Index pour la table `projet`
--
ALTER TABLE `projet`
  ADD PRIMARY KEY (`PRO_id`),
  ADD KEY `projet_ibfk_1` (`DEV_idProductOwner`),
  ADD KEY `projet_ibfk_2` (`DEV_idScrumMaster`);

--
-- Index pour la table `sprint`
--
ALTER TABLE `sprint`
  ADD PRIMARY KEY (`SPR_id`),
  ADD KEY `sprint_ibfk_1` (`PRO_id`);

--
-- Index pour la table `tache`
--
ALTER TABLE `tache`
  ADD PRIMARY KEY (`TAC_id`),
  ADD KEY `tache_ibfk_1` (`DEV_id`),
  ADD KEY `tache_ibfk_2` (`US_id`);

--
-- Index pour la table `us`
--
ALTER TABLE `us`
  ADD PRIMARY KEY (`US_id`),
  ADD KEY `us_ibfk_1` (`PRO_id`),
  ADD KEY `us_ibfk_2` (`SPR_id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `burndown_chart`
--
ALTER TABLE `burndown_chart`
  MODIFY `BDC_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `developpeur`
--
ALTER TABLE `developpeur`
  MODIFY `DEV_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `projet`
--
ALTER TABLE `projet`
  MODIFY `PRO_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `sprint`
--
ALTER TABLE `sprint`
  MODIFY `SPR_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `tache`
--
ALTER TABLE `tache`
  MODIFY `TAC_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `us`
--
ALTER TABLE `us`
  MODIFY `US_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `burndown_chart`
--
ALTER TABLE `burndown_chart`
  ADD CONSTRAINT `burndown_chart_ibfk_1` FOREIGN KEY (`SPR_id`) REFERENCES `sprint` (`SPR_id`),
  ADD CONSTRAINT `burndown_chart_ibfk_2` FOREIGN KEY (`PRO_id`) REFERENCES `projet` (`PRO_id`);

--
-- Contraintes pour la table `inter_dev_projet`
--
ALTER TABLE `inter_dev_projet`
  ADD CONSTRAINT `inter_dev_projet_ibfk_1` FOREIGN KEY (`DEV_id`) REFERENCES `developpeur` (`DEV_id`),
  ADD CONSTRAINT `inter_dev_projet_ibfk_2` FOREIGN KEY (`PRO_id`) REFERENCES `projet` (`PRO_id`);

--
-- Contraintes pour la table `projet`
--
ALTER TABLE `projet`
  ADD CONSTRAINT `projet_ibfk_1` FOREIGN KEY (`DEV_idProductOwner`) REFERENCES `developpeur` (`DEV_id`),
  ADD CONSTRAINT `projet_ibfk_2` FOREIGN KEY (`DEV_idScrumMaster`) REFERENCES `developpeur` (`DEV_id`);

--
-- Contraintes pour la table `sprint`
--
ALTER TABLE `sprint`
  ADD CONSTRAINT `sprint_ibfk_1` FOREIGN KEY (`PRO_id`) REFERENCES `projet` (`PRO_id`);

--
-- Contraintes pour la table `tache`
--
ALTER TABLE `tache`
  ADD CONSTRAINT `tache_ibfk_1` FOREIGN KEY (`DEV_id`) REFERENCES `developpeur` (`DEV_id`),
  ADD CONSTRAINT `tache_ibfk_2` FOREIGN KEY (`US_id`) REFERENCES `us` (`US_id`);

--
-- Contraintes pour la table `us`
--
ALTER TABLE `us`
  ADD CONSTRAINT `us_ibfk_1` FOREIGN KEY (`PRO_id`) REFERENCES `projet` (`PRO_id`),
  ADD CONSTRAINT `us_ibfk_2` FOREIGN KEY (`SPR_id`) REFERENCES `sprint` (`SPR_id`);
