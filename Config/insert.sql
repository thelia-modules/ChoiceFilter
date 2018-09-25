--
-- Contenu de la table `choice_filter_other`
--

INSERT INTO `choice_filter_other` (`id`, `type`, `visible`) VALUES
(1, 'price', 1),
(2, 'brand', 1),
(3, 'category', 1);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `choice_filter_other`
--
ALTER TABLE `choice_filter_other`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `choice_filter_other`
--
ALTER TABLE `choice_filter_other`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;


-- --------------------------------------------------------

--
-- Structure de la table `choice_filter_other_i18n`
--

CREATE TABLE `choice_filter_other_i18n` (
  `id` int(11) NOT NULL,
  `locale` varchar(5) NOT NULL DEFAULT 'en_US',
  `title` varchar(255) DEFAULT NULL,
  `description` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `choice_filter_other_i18n`
--

INSERT INTO `choice_filter_other_i18n` (`id`, `locale`, `title`, `description`) VALUES
(1, 'en_US', 'Price', NULL),
(1, 'fr_FR', 'Prix', NULL),
(2, 'en_US', 'Brand', NULL),
(2, 'fr_FR', 'Marque', NULL),
(3, 'en_US', 'Category', NULL),
(3, 'fr_FR', 'Catégorie', NULL);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `choice_filter_other_i18n`
--
ALTER TABLE `choice_filter_other_i18n`
  ADD PRIMARY KEY (`id`,`locale`);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `choice_filter_other_i18n`
--
ALTER TABLE `choice_filter_other_i18n`
  ADD CONSTRAINT `choice_filter_other_i18n_FK_1` FOREIGN KEY (`id`) REFERENCES `choice_filter_other` (`id`) ON DELETE CASCADE;
