-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 04 jan. 2021 à 16:16
-- Version du serveur :  10.4.14-MariaDB
-- Version de PHP : 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `dodwish`
--

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

CREATE TABLE `article` (
  `idArticle` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prix` float NOT NULL,
  `description` varchar(10000) NOT NULL,
  `type` varchar(255) NOT NULL,
  `img` longtext NOT NULL DEFAULT 'img/no_image.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`idArticle`, `nom`, `prix`, `description`, `type`, `img`) VALUES
(6, 'cookie', 6, 'Des délicieux cookies', 'dessert', 'img/cookie_01.jpg'),
(7, 'brownie', 4, 'Un brownie bien fondant et fort chocolaté, parfait pour les gourmands', 'dessert', 'img/brownie_01.jpg'),
(8, 'coca', 0.5, 'Un coca cola classique', 'boisson', 'img/coca.png'),
(9, 'coca zero', 0.5, 'Un coca cola zéro sucre', 'boisson', 'img/coca-zero.jpg'),
(10, 'coca cherry', 0.5, 'Un coca cola à la cerise', 'boisson', 'img/coca-cherry.png'),
(11, 'eau', 0.3, 'De l\'eau de source en bouteille', 'boisson', 'img/water.jpg'),
(12, 'petite frite', 1.5, 'Une petite portion de frites', 'petiteFaim', 'img/frite_01.jpg'),
(13, 'moyenne frite', 2, 'Une portion de frites classique', 'petiteFaim', 'img/frite_01.jpg'),
(14, 'grande frite', 2.3, 'Une grande portion de frites', 'petiteFaim', 'img/frite_01.jpg'),
(15, 'potatoes', 2.5, 'Des frites plus fournis en pomme de terre', 'petiteFaim', 'img/frite_01.jpg'),
(26, 'Tarte Framboise', 3.5, 'Une tarte avec des framboises fraiche recouvertes de sirop', 'dessert', 'img/tartelette-framboises.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `numCom` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `ville` varchar(255) NOT NULL,
  `cp` varchar(5) NOT NULL,
  `adr` varchar(255) NOT NULL,
  `date` varchar(20) NOT NULL,
  `heure` varchar(20) NOT NULL,
  `contenu` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `valide` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`numCom`, `idUser`, `ville`, `cp`, `adr`, `date`, `heure`, `contenu`, `valide`) VALUES
(11, 13, 'az', '51100', 'az', '20/12/20', '13:45', '[{\"nom\":\"Sandwich compos\\u00e9\",\"description\":\"Pain 1; Ingredient 2; Ingredient 3; Ingredient 6; Ingredient 7; \",\"prix\":\"8.5\",\"id\":\"73\"},{\"nom\":\"Sandwich compos\\u00e9\",\"description\":\"Pain 3; Ingredient 2; Ingredient 4; Ingredient 6; \",\"prix\":\"8.5\",\"id\":\"74\"},{\"nom\":\"potatoes\",\"description\":\"Des frites plus fournis en pomme de terre\",\"prix\":\"2.5\",\"id\":\"75\"},{\"nom\":\"coca\",\"description\":\"Un coca cola classique\",\"prix\":\"0.5\",\"id\":\"76\"},{\"nom\":\"cookie\",\"description\":\"Des d\\u00e9licieux cookies\",\"prix\":\"6\",\"id\":\"77\"}]', 0),
(12, 13, 'Reims', '51100', 'Rue Imaginaire', '20/12/20', '21:10', '[{\"nom\":\"grande frite\",\"description\":\"Une grande portion de frites\",\"prix\":\"2.3\",\"id\":\"78\"}]', 0),
(13, 13, 'Reims', '51100', 'Rue Imaginaire', '20/12/20', '20:30', '[{\"nom\":\"Sandwich compos\\u00e9\",\"description\":\"Pain 1; Ingredient 1; Ingredient 3; Ingredient 6; Ingredient 8; \",\"prix\":\"8.5\",\"id\":\"79\"},{\"nom\":\"Sandwich compos\\u00e9\",\"description\":\"Pain 2; Ingredient 2; Ingredient 5; Ingredient 7; \",\"prix\":\"8.5\",\"id\":\"80\"},{\"nom\":\"potatoes\",\"description\":\"Des frites plus fournis en pomme de terre\",\"prix\":\"2.5\",\"id\":\"81\"}]', 0),
(14, 13, 'Reims', '51100', 'Rue Imaginaire', '20/12/20', '20:30', '[{\"nom\":\"Sandwich compos\\u00e9\",\"description\":\"Pain 1; Ingredient 2; Ingredient 3; Ingredient 6; Ingredient 8; \",\"prix\":\"8.5\",\"id\":\"83\"},{\"nom\":\"Sandwich compos\\u00e9\",\"description\":\"Pain 2; Ingredient 1; Ingredient 4; Ingredient 5; Ingredient 6; \",\"prix\":\"8.5\",\"id\":\"84\"},{\"nom\":\"coca\",\"description\":\"Un coca cola classique\",\"prix\":\"0.5\",\"id\":\"85\"}]', 1),
(15, 13, 'Reims', '51100', 'Rue Imaginaire', '20/12/20', '20:30', '[{\"nom\":\"Sandwich compos\\u00e9\",\"description\":\"Pain 2; Ingredient 1; Ingredient 4; Ingredient 5; Ingredient 6; Ingredient 7; \",\"prix\":\"8.5\",\"id\":\"87\"},{\"nom\":\"potatoes\",\"description\":\"Des frites plus fournis en pomme de terre\",\"prix\":\"2.5\",\"id\":\"88\"}]', 0),
(16, 13, 'Reims', '51100', 'Rue Imaginaire', '04/01/21', '17:30', '[{\"nom\":\"Sandwich compos\\u00e9\",\"description\":\"Seigle; Oeuf; Ognon; Poivrons; Barbecue; Bacon; Cordon bleu; \",\"prix\":\"8.5\",\"id\":\"97\"},{\"nom\":\"moyenne frite\",\"description\":\"Une portion de frites classique\",\"prix\":\"2\",\"id\":\"98\"},{\"nom\":\"Tarte Framboise\",\"description\":\"Une tarte avec des framboises fraiche recouvertes de sirop\",\"prix\":\"3.5\",\"id\":\"99\"},{\"nom\":\"coca cherry\",\"description\":\"Un coca cola \\u00e0 la cerise\",\"prix\":\"0.5\",\"id\":\"101\"}]', 1);

-- --------------------------------------------------------

--
-- Structure de la table `ingredient`
--

CREATE TABLE `ingredient` (
  `idIngr` int(11) NOT NULL,
  `typeIngr` int(11) NOT NULL,
  `nomIngr` varchar(255) NOT NULL,
  `prixIngr` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `ingredient`
--

INSERT INTO `ingredient` (`idIngr`, `typeIngr`, `nomIngr`, `prixIngr`) VALUES
(14, 12, 'Poivrons', 0),
(16, 12, 'Ognon', 0),
(17, 12, 'Salade', 0),
(18, 12, 'Tomates', 0),
(19, 1, 'Basique', 0),
(20, 1, 'Seigle', 0),
(21, 1, 'Campagne', 0),
(22, 10, 'Steak', 0),
(24, 10, 'Cordon bleu', 0),
(25, 10, 'Bacon', 0),
(26, 11, 'Ketchup', 0),
(27, 11, 'Mayonnaise', 0),
(29, 11, 'Barbecue', 0),
(30, 13, 'Oeuf', 0);

-- --------------------------------------------------------

--
-- Structure de la table `panier`
--

CREATE TABLE `panier` (
  `id` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `description` varchar(10000) NOT NULL,
  `prix` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `typeingredient`
--

CREATE TABLE `typeingredient` (
  `idTypeIngr` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `typeingredient`
--

INSERT INTO `typeingredient` (`idTypeIngr`, `nom`) VALUES
(1, 'pain'),
(10, 'Viande'),
(11, 'Sauce'),
(12, 'Légumes'),
(13, 'Autre');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `idUser` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mdpSHA512` varchar(1000) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `tel` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`idUser`, `email`, `mdpSHA512`, `nom`, `prenom`, `tel`) VALUES
(1, 'admin', 'c7ad44cbad762a5da0a452f9e854fdc1e0e7a52a38015f23f3eab1d80b931dd472634dfac71cd34ebc35d16ab7fb8a90c81f975113d6c7538dc69dd8de9077ec', 'admin', 'admin', 'admin'),
(2, 'livreur', '9fa6429f17efb247b3e017acb0e0ed3525c1f2f823f2d669944954d852df729741f19457524a4580f9108a57befa5eb42fcd38cd157148bfb1d55630f8a7b782', 'livreur', 'livreur', 'livreur'),
(9, 'test@yopmail.com', 'afcfc95eec91380b429dc008d67b1091cf76ab10542fa7affbd6161fd9269f48b52bf65305bb913c62ff351bc74080aabcd7eadd49185e46853a28f084147687', 'test', 'test', '2'),
(10, 'test2@yopmail.com', 'ee26b0dd4af7e749aa1a8ee3c10ae9923f618980772e473f8819a5d4940e0db27ac185f8a0e1d5f84f88bc887fd67b143732c304cc5fa9ad8e6f57f50028a8ff', 'test2', 'test2', '0606060606'),
(13, 'test3@yopmail.com', 'ee26b0dd4af7e749aa1a8ee3c10ae9923f618980772e473f8819a5d4940e0db27ac185f8a0e1d5f84f88bc887fd67b143732c304cc5fa9ad8e6f57f50028a8ff', 'test3', 'test3', '0606060606'),
(14, 'test4@gmail.com', '0c93ed52d34edc3510c99043eb50217287b7901ba8647625c909dc493d21a42cac9aa1a1b1c968e115829567b47292dea028cd7edbddfac0a422eac262ac30b3', 'Florian', 'Test', '0606060606');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`idArticle`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`numCom`);

--
-- Index pour la table `ingredient`
--
ALTER TABLE `ingredient`
  ADD PRIMARY KEY (`idIngr`);

--
-- Index pour la table `panier`
--
ALTER TABLE `panier`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `typeingredient`
--
ALTER TABLE `typeingredient`
  ADD PRIMARY KEY (`idTypeIngr`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`idUser`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `article`
--
ALTER TABLE `article`
  MODIFY `idArticle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `numCom` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `ingredient`
--
ALTER TABLE `ingredient`
  MODIFY `idIngr` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pour la table `panier`
--
ALTER TABLE `panier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT pour la table `typeingredient`
--
ALTER TABLE `typeingredient`
  MODIFY `idTypeIngr` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `idUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
