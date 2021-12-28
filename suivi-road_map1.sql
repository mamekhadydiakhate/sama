-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : ven. 29 oct. 2021 à 14:58
-- Version du serveur :  10.4.16-MariaDB
-- Version de PHP : 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";



/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `suivi-road_map1`
--

-- --------------------------------------------------------

--
-- Structure de la table `activite`
--

CREATE TABLE `activite` (
  `id` int(11) NOT NULL,
  `structure_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `tranche_horaire_id` int(11) DEFAULT NULL,
  `historique_id` int(11) DEFAULT NULL,
  `libelle` longtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `activite`
--
INSERT INTO `activite` (`id`, `structure_id`, `user_id`, `tranche_horaire_id`, `historique_id`, `libelle`) VALUES
(1, NULL, NULL, NULL, NULL, 'Concert DCIRE'),
(2, NULL, NULL, NULL, NULL, 'l\'ouverture '),
(3, NULL, NULL, NULL, NULL, 'l\'ouverture '),
(4, NULL, NULL, NULL, NULL, 'l\'ouverture '),
(5, 1, NULL, 2, NULL, 'l\'ouverture '),
(6, 1, 9, 2, NULL, 'l\'ouverture ');

-- --------------------------------------------------------

--
-- Structure de la table `admin_pp`
--

CREATE TABLE `admin_pp` (
  `id` int(11) NOT NULL,
  `username` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username_canonical` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_canonical` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `confirmation_token` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `login_tentative` int(11) DEFAULT NULL,
  `login_tentative_at` datetime DEFAULT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `matricule` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `autorite`
--

CREATE TABLE `autorite` (
  `id` int(11) NOT NULL,
  `evenement_id` int(11) DEFAULT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fonction` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `autorite`
--

INSERT INTO `autorite` (`id`, `evenement_id`, `nom`, `prenom`, `fonction`) VALUES
(1, 1, 'Sylla', 'Moustapha', 'Ministre'),
(2, 2, 'Dramé', 'Sekou', 'DG SONATEL'),
(6, NULL, 'Sylla', 'Moustapha', 'Ministre');

-- --------------------------------------------------------

--
-- Structure de la table `commentaire`
--

CREATE TABLE `commentaire` (
  `id` int(11) NOT NULL,
  `evenement_id` int(11) NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `difficulte`
--

CREATE TABLE `difficulte` (
  `id` int(11) NOT NULL,
  `activite_id` int(11) DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `cause` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20211015100540', '2021-10-15 10:05:45', 2930),
('DoctrineMigrations\\Version20211020072841', '2021-10-20 07:28:59', 261),
('DoctrineMigrations\\Version20211021090728', '2021-10-21 09:07:49', 51),
('DoctrineMigrations\\Version20211021091010', '2021-10-21 09:10:26', 150),
('DoctrineMigrations\\Version20211021121105', '2021-10-21 12:11:10', 493);

-- --------------------------------------------------------

--
-- Structure de la table `evenement`
--

CREATE TABLE `evenement` (
  `id` int(11) NOT NULL,
  `structure_id` int(11) DEFAULT NULL,
  `periodicite_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `thematique` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` datetime NOT NULL,
  `lieu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `structure_org` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `etat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `structure_concernee` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `evenement`
--

INSERT INTO `evenement` (`id`, `structure_id`, `periodicite_id`, `user_id`, `thematique`, `nom`, `date`, `lieu`, `structure_org`, `etat`, `structure_concernee`) VALUES
(1, 1, 1, 1, 'rencontre avec le president', 'rencontre', '2021-11-22 00:00:00', 'Rufisque', 'DCIRE', 'actif', 'CORP'),
(2, 2, 1, 3, 'renforcement des capacites', 'renforcement', '2021-11-22 00:00:00', 'dakar ville', 'DCIRE', 'actif', 'REX'),
(3, 3, 1, 3, 'sceance de dédiace', 'renforcement', '2021-11-22 00:00:00', 'dakar ville', 'DCIRE', 'actif', 'REX');

-- --------------------------------------------------------

--
-- Structure de la table `evenement_activite`
--

CREATE TABLE `evenement_activite` (
  `evenement_id` int(11) NOT NULL,
  `activite_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `extraction`
--

CREATE TABLE `extraction` (
  `id` int(11) NOT NULL,
  `type_periodicite_id` int(11) DEFAULT NULL,
  `etat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_extraction` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `historique`
--

CREATE TABLE `historique` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `details` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `addresse_ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `historique_evenement`
--

CREATE TABLE `historique_evenement` (
  `id` int(11) NOT NULL,
  `evenement_id` int(11) DEFAULT NULL,
  `id_utilisateur` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `interim`
--

CREATE TABLE `interim` (
  `id` int(11) NOT NULL,
  `username` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username_canonical` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_canonical` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `confirmation_token` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `login_tentative` int(11) DEFAULT NULL,
  `login_tentative_at` datetime DEFAULT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `matricule` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `personne_remplacee` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_utilisateur` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `periodicite`
--

CREATE TABLE `periodicite` (
  `id` int(11) NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `periodicite`
--

INSERT INTO `periodicite` (`id`, `date_debut`, `date_fin`) VALUES
(1, '2022-10-12 00:00:00', '2022-10-25'),
(2, '2021-10-12 00:00:00', '2022-10-20');

-- --------------------------------------------------------

--
-- Structure de la table `personne`
--

CREATE TABLE `personne` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `point_de_coordination`
--

CREATE TABLE `point_de_coordination` (
  `id` int(11) NOT NULL,
  `activite_id` int(11) DEFAULT NULL,
  `libelle` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `structure_impactee` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `create_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `profil`
--

CREATE TABLE `profil` (
  `id` int(11) NOT NULL,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `profil`
--

INSERT INTO `profil` (`id`, `libelle`) VALUES
(1, 'CORP'),
(2, 'ODC'),
(3, 'REX'),
(4, 'PP'),
(5, 'FS'),
(6, 'RSEP');

-- --------------------------------------------------------

--
-- Structure de la table `reset_password_request`
--

CREATE TABLE `reset_password_request` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `selector` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hashed_token` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `requested_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `expires_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `structure`
--

CREATE TABLE `structure` (
  `id` int(11) NOT NULL,
  `extraction_id` int(11) DEFAULT NULL,
  `type_structure_id` int(11) DEFAULT NULL,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `structure`
--

INSERT INTO `structure` (`id`, `extraction_id`, `type_structure_id`, `libelle`) VALUES
(1, NULL, NULL, 'CORP'),
(2, NULL, NULL, 'REX'),
(3, NULL, NULL, 'PP'),
(4, NULL, NULL, 'RSEP'),
(5, NULL, NULL, 'ODC'),
(6, NULL, NULL, 'FS');

-- --------------------------------------------------------

--
-- Structure de la table `tranche_horaire`
--

CREATE TABLE `tranche_horaire` (
  `id` int(11) NOT NULL,
  `evenement_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `tranche_horaire`
--

INSERT INTO `tranche_horaire` (`id`, `evenement_id`) VALUES
(2, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `type_periodicite`
--

CREATE TABLE `type_periodicite` (
  `id` int(11) NOT NULL,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `type_service`
--

CREATE TABLE `type_service` (
  `id` int(11) NOT NULL,
  `structure_id` int(11) DEFAULT NULL,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `type_service`
--

INSERT INTO `type_service` (`id`, `structure_id`, `libelle`) VALUES
(1, 2, 'RP'),
(2, 2, 'SP'),
(3, 1, 'INST'),
(4, 1, 'CDMS'),
(5, 4, 'DD'),
(6, 4, 'ENS');

-- --------------------------------------------------------

--
-- Structure de la table `type_structure`
--

CREATE TABLE `type_structure` (
  `id` int(11) NOT NULL,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL,
  `profil_id` int(11) DEFAULT NULL,
  `workflow_id` int(11) DEFAULT NULL,
  `admin_pp_id` int(11) DEFAULT NULL,
  `username` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username_canonical` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_canonical` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `confirmation_token` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `login_tentative` int(11) DEFAULT NULL,
  `login_tentative_at` datetime DEFAULT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `matricule` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_service_id` int(11) DEFAULT NULL,
  `service` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `profil_id`, `workflow_id`, `admin_pp_id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `confirmation_token`, `password_requested_at`, `roles`, `login_tentative`, `login_tentative_at`, `nom`, `prenom`, `matricule`, `type_service_id`, `service`) VALUES
(1, 1, NULL, NULL, 'NIANG028239', 'NIANG028239', 'Mareme.NIANG@orange-sonatel.com', 'Mareme.NIANG@orange-sonatel.com', 1, NULL, '', NULL, NULL, NULL, 'a:0:{}', NULL, NULL, NULL, NULL, '28239', NULL, NULL),
(2, 1, NULL, NULL, 'SOUMARE7365', 'SOUMARE7365', 'noumbe.soumare@orange-sonatel.com', 'noumbe.soumare@orange-sonatel.com', 0, NULL, '', NULL, NULL, NULL, 'a:0:{}', NULL, NULL, NULL, NULL, '7365', NULL, NULL),
(3, 2, NULL, NULL, 'diouf7598', 'diouf7598', 'daouda.diouf@orange-sonatel.com', 'daouda.diouf@orange-sonatel.com', 0, NULL, '', NULL, NULL, NULL, 'a:0:{}', NULL, NULL, NULL, NULL, '7598', NULL, NULL),
(4, 3, NULL, NULL, 'DIOP7462', 'DIOP7462', 'mmdiop@orange-sonatel.com', 'mmdiop@orange-sonatel.com', 0, NULL, '', NULL, NULL, NULL, 'a:0:{}', NULL, NULL, '', '', '7462', NULL, NULL),
(5, 3, NULL, NULL, 'ndaw7188', 'ndaw7188', 'Mouhameth.Ndaw@orange-sonatel.com', 'Mouhameth.Ndaw@orange-sonatel.com', 0, NULL, '', NULL, NULL, NULL, 'a:0:{}', NULL, NULL, NULL, NULL, '7188', NULL, NULL),
(6, 3, NULL, NULL, 'DIALLO60202', 'DIALLO60202', 'cheikh.diallo@orange-sonatel.com', 'cheikh.diallo@orange-sonatel.com', 0, NULL, '', NULL, NULL, NULL, 'a:0:{}', NULL, NULL, '', NULL, '60202', NULL, NULL),
(7, 4, NULL, NULL, 'DIEYE028284', 'DIEYE028284', 'moussa.dieye@orange-sonatel.com', 'moussa.dieye@orange-sonatel.com', 0, NULL, '', NULL, NULL, NULL, 'a:0:{}', NULL, NULL, NULL, NULL, '028284', NULL, NULL),
(8, 4, NULL, NULL, 'badiat9250', 'badiat9250', 'chantal.badiat@orange-sonatel.com', 'chantal.badiat@orange-sonatel.com', 0, NULL, '', NULL, NULL, NULL, 'a:0:{}', NULL, NULL, NULL, NULL, '9250', NULL, NULL),
(9, 5, NULL, NULL, 'khadydiakhate142', 'khadydiakhate142', 'ddiatou1@gmail.com', 'ddiatou1@gmail.com', 1, NULL, 'O3CWh5orXif2MQZj1hxdSzR9T8KP/zCwgRl3E+hCCnjY4mTAECMZZ+/OtYf1KkKzmdexhKBmo9aNHY4T4gdKew==', '2021-10-29 12:39:28', NULL, NULL, 'a:0:{}', 0, '2021-10-20 09:18:21', 'Diakhate', 'Mame Khady', '058990', NULL, NULL),
(10, 5, NULL, NULL, 'FALL7270', 'FALL7270', 'amyfall@orange-sonatel.com', 'amyfall@orange-sonatel.com', 0, NULL, '', NULL, NULL, NULL, 'a:0:{}', NULL, NULL, NULL, NULL, '7270', NULL, NULL),
(11, 6, NULL, NULL, 'MBENGUE9182', 'MBENGUE9182', 'rokhayasolange.mbengue@orange-sonatel.com', 'rokhayasolange.mbengue@orange-sonatel.com', 0, NULL, '', NULL, NULL, NULL, 'a:0:{}', NULL, NULL, NULL, NULL, '9182', NULL, NULL),
(12, 6, NULL, NULL, 'sow7526', 'sow7526', 'Coura.SOW@orange-sonatel.com', 'Coura.SOW@orange-sonatel.com', 0, NULL, '', NULL, NULL, NULL, 'a:0:{}', NULL, NULL, NULL, NULL, '7526', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `workflow`
--

CREATE TABLE `workflow` (
  `id` int(11) NOT NULL,
  `admin_pp_id` int(11) DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `activite`
--
ALTER TABLE `activite`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_B87555156128735E` (`historique_id`),
  ADD KEY `IDX_B87555152534008B` (`structure_id`),
  ADD KEY `IDX_B8755515A76ED395` (`user_id`),
  ADD KEY `IDX_B875551569832F6F` (`tranche_horaire_id`);

--
-- Index pour la table `admin_pp`
--
ALTER TABLE `admin_pp`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_E5F1FF7592FC23A8` (`username_canonical`),
  ADD UNIQUE KEY `UNIQ_E5F1FF75A0D96FBF` (`email_canonical`),
  ADD UNIQUE KEY `UNIQ_E5F1FF75C05FB297` (`confirmation_token`);

--
-- Index pour la table `autorite`
--
ALTER TABLE `autorite`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_BC599E16FD02F13` (`evenement_id`);

--
-- Index pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_67F068BCFD02F13` (`evenement_id`);

--
-- Index pour la table `difficulte`
--
ALTER TABLE `difficulte`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_AF6A33A09B0F88B1` (`activite_id`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `evenement`
--
ALTER TABLE `evenement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B26681E2534008B` (`structure_id`),
  ADD KEY `IDX_B26681E2928752A` (`periodicite_id`),
  ADD KEY `IDX_B26681EA76ED395` (`user_id`);

--
-- Index pour la table `evenement_activite`
--
ALTER TABLE `evenement_activite`
  ADD PRIMARY KEY (`evenement_id`,`activite_id`),
  ADD KEY `IDX_3713CEFDFD02F13` (`evenement_id`),
  ADD KEY `IDX_3713CEFD9B0F88B1` (`activite_id`);

--
-- Index pour la table `extraction`
--
ALTER TABLE `extraction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_3ADCB5D67FEB5CBE` (`type_periodicite_id`);

--
-- Index pour la table `historique`
--
ALTER TABLE `historique`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_EDBFD5ECA76ED395` (`user_id`);

--
-- Index pour la table `historique_evenement`
--
ALTER TABLE `historique_evenement`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_58552356FD02F13` (`evenement_id`);

--
-- Index pour la table `interim`
--
ALTER TABLE `interim`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_615F886992FC23A8` (`username_canonical`),
  ADD UNIQUE KEY `UNIQ_615F8869A0D96FBF` (`email_canonical`),
  ADD UNIQUE KEY `UNIQ_615F8869C05FB297` (`confirmation_token`);

--
-- Index pour la table `periodicite`
--
ALTER TABLE `periodicite`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `personne`
--
ALTER TABLE `personne`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `point_de_coordination`
--
ALTER TABLE `point_de_coordination`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_9BFC84739B0F88B1` (`activite_id`);

--
-- Index pour la table `profil`
--
ALTER TABLE `profil`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_7CE748AA76ED395` (`user_id`);

--
-- Index pour la table `structure`
--
ALTER TABLE `structure`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_6F0137EAA277BA8E` (`type_structure_id`),
  ADD KEY `IDX_6F0137EAF992488A` (`extraction_id`);

--
-- Index pour la table `tranche_horaire`
--
ALTER TABLE `tranche_horaire`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F5A404F9FD02F13` (`evenement_id`);

--
-- Index pour la table `type_periodicite`
--
ALTER TABLE `type_periodicite`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `type_service`
--
ALTER TABLE `type_service`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C9BCF5272534008B` (`structure_id`);

--
-- Index pour la table `type_structure`
--
ALTER TABLE `type_structure`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_1D1C63B392FC23A8` (`username_canonical`),
  ADD UNIQUE KEY `UNIQ_1D1C63B3A0D96FBF` (`email_canonical`),
  ADD UNIQUE KEY `UNIQ_1D1C63B3C05FB297` (`confirmation_token`),
  ADD UNIQUE KEY `UNIQ_1D1C63B3F05F7FC3` (`type_service_id`),
  ADD KEY `IDX_1D1C63B3275ED078` (`profil_id`),
  ADD KEY `IDX_1D1C63B32C7C2CBA` (`workflow_id`),
  ADD KEY `IDX_1D1C63B36DE55096` (`admin_pp_id`);

--
-- Index pour la table `workflow`
--
ALTER TABLE `workflow`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_65C598166DE55096` (`admin_pp_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `activite`
--
ALTER TABLE `activite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `admin_pp`
--
ALTER TABLE `admin_pp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `autorite`
--
ALTER TABLE `autorite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `commentaire`
--
ALTER TABLE `commentaire`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `difficulte`
--
ALTER TABLE `difficulte`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `evenement`
--
ALTER TABLE `evenement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `extraction`
--
ALTER TABLE `extraction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `historique`
--
ALTER TABLE `historique`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `historique_evenement`
--
ALTER TABLE `historique_evenement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `interim`
--
ALTER TABLE `interim`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `periodicite`
--
ALTER TABLE `periodicite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `personne`
--
ALTER TABLE `personne`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `point_de_coordination`
--
ALTER TABLE `point_de_coordination`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `profil`
--
ALTER TABLE `profil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `structure`
--
ALTER TABLE `structure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `tranche_horaire`
--
ALTER TABLE `tranche_horaire`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `type_periodicite`
--
ALTER TABLE `type_periodicite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `type_service`
--
ALTER TABLE `type_service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `type_structure`
--
ALTER TABLE `type_structure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `workflow`
--
ALTER TABLE `workflow`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `activite`
--
ALTER TABLE `activite`
  ADD CONSTRAINT `FK_B87555152534008B` FOREIGN KEY (`structure_id`) REFERENCES `structure` (`id`),
  ADD CONSTRAINT `FK_B87555156128735E` FOREIGN KEY (`historique_id`) REFERENCES `historique` (`id`),
  ADD CONSTRAINT `FK_B875551569832F6F` FOREIGN KEY (`tranche_horaire_id`) REFERENCES `tranche_horaire` (`id`),
  ADD CONSTRAINT `FK_B8755515A76ED395` FOREIGN KEY (`user_id`) REFERENCES `utilisateur` (`id`);

--
-- Contraintes pour la table `autorite`
--
ALTER TABLE `autorite`
  ADD CONSTRAINT `FK_BC599E16FD02F13` FOREIGN KEY (`evenement_id`) REFERENCES `evenement` (`id`);

--
-- Contraintes pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD CONSTRAINT `FK_67F068BCFD02F13` FOREIGN KEY (`evenement_id`) REFERENCES `evenement` (`id`);

--
-- Contraintes pour la table `difficulte`
--
ALTER TABLE `difficulte`
  ADD CONSTRAINT `FK_AF6A33A09B0F88B1` FOREIGN KEY (`activite_id`) REFERENCES `activite` (`id`);

--
-- Contraintes pour la table `evenement`
--
ALTER TABLE `evenement`
  ADD CONSTRAINT `FK_B26681E2534008B` FOREIGN KEY (`structure_id`) REFERENCES `structure` (`id`),
  ADD CONSTRAINT `FK_B26681E2928752A` FOREIGN KEY (`periodicite_id`) REFERENCES `periodicite` (`id`),
  ADD CONSTRAINT `FK_B26681EA76ED395` FOREIGN KEY (`user_id`) REFERENCES `utilisateur` (`id`);

--
-- Contraintes pour la table `evenement_activite`
--
ALTER TABLE `evenement_activite`
  ADD CONSTRAINT `FK_3713CEFD9B0F88B1` FOREIGN KEY (`activite_id`) REFERENCES `activite` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_3713CEFDFD02F13` FOREIGN KEY (`evenement_id`) REFERENCES `evenement` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `extraction`
--
ALTER TABLE `extraction`
  ADD CONSTRAINT `FK_3ADCB5D67FEB5CBE` FOREIGN KEY (`type_periodicite_id`) REFERENCES `type_periodicite` (`id`);

--
-- Contraintes pour la table `historique`
--
ALTER TABLE `historique`
  ADD CONSTRAINT `FK_EDBFD5ECA76ED395` FOREIGN KEY (`user_id`) REFERENCES `utilisateur` (`id`);

--
-- Contraintes pour la table `historique_evenement`
--
ALTER TABLE `historique_evenement`
  ADD CONSTRAINT `FK_58552356FD02F13` FOREIGN KEY (`evenement_id`) REFERENCES `evenement` (`id`);

--
-- Contraintes pour la table `point_de_coordination`
--
ALTER TABLE `point_de_coordination`
  ADD CONSTRAINT `FK_9BFC84739B0F88B1` FOREIGN KEY (`activite_id`) REFERENCES `activite` (`id`);

--
-- Contraintes pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD CONSTRAINT `FK_7CE748AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `utilisateur` (`id`);

--
-- Contraintes pour la table `structure`
--
ALTER TABLE `structure`
  ADD CONSTRAINT `FK_6F0137EAA277BA8E` FOREIGN KEY (`type_structure_id`) REFERENCES `type_structure` (`id`),
  ADD CONSTRAINT `FK_6F0137EAF992488A` FOREIGN KEY (`extraction_id`) REFERENCES `extraction` (`id`);

--
-- Contraintes pour la table `tranche_horaire`
--
ALTER TABLE `tranche_horaire`
  ADD CONSTRAINT `FK_F5A404F9FD02F13` FOREIGN KEY (`evenement_id`) REFERENCES `evenement` (`id`);

--
-- Contraintes pour la table `type_service`
--
ALTER TABLE `type_service`
  ADD CONSTRAINT `FK_C9BCF5272534008B` FOREIGN KEY (`structure_id`) REFERENCES `structure` (`id`);

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `FK_1D1C63B3275ED078` FOREIGN KEY (`profil_id`) REFERENCES `profil` (`id`),
  ADD CONSTRAINT `FK_1D1C63B32C7C2CBA` FOREIGN KEY (`workflow_id`) REFERENCES `workflow` (`id`),
  ADD CONSTRAINT `FK_1D1C63B36DE55096` FOREIGN KEY (`admin_pp_id`) REFERENCES `admin_pp` (`id`),
  ADD CONSTRAINT `FK_1D1C63B3F05F7FC3` FOREIGN KEY (`type_service_id`) REFERENCES `type_service` (`id`);

--
-- Contraintes pour la table `workflow`
--
ALTER TABLE `workflow`
  ADD CONSTRAINT `FK_65C598166DE55096` FOREIGN KEY (`admin_pp_id`) REFERENCES `admin_pp` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
