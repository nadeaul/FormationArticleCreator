-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  jeu. 19 oct. 2017 à 08:45
-- Version du serveur :  5.7.19
-- Version de PHP :  5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `article_creator`
--

-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

DROP TABLE IF EXISTS `articles`;
CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(75) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `image` varchar(256) NOT NULL DEFAULT '',
  `alt_image` varchar(256) NOT NULL DEFAULT '',
  `save` text,
  `url_article` varchar(256) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `articles`
--

INSERT INTO `articles` (`id`, `title`, `description`, `image`, `alt_image`, `save`, `url_article`) VALUES
(20, 'Comment rÃ©ussir sa campagne dâ€™Emailing ?', '<p>Quand on sait que l&rsquo;emailing est, dans une campagne de marketing digitale, l&rsquo;action avec un retour sur investissement (ROI) le plus &eacute;lev&eacute;, autant vous dire qu&rsquo;il faut &eacute;viter les erreurs lorsque vous pr&eacute;parez vos campagnes. <a href=\"http://wdmstudio.fr/\">Votre agence digitale &agrave; Bordeaux</a> vous explique comment faire&hellip;</p>\r\n', 'upload/CAMPAGNE_EMAILING_WDM.png', 'Image : Comment rÃ©ussir sa campagne d\'emailing', '\n                <div class=\"label-canvas ui-sortable-handle\">Contenu de la page</div>\n                \n                <div class=\"label-canvas ui-sortable-handle\">Contenu de la page</div>\n                \n                <div class=\"label-canvas ui-sortable-handle\">Contenu de la page</div>\n                \n                <div class=\"label-canvas ui-sortable-handle\">Contenu de la page</div>\n                \n                <div class=\"label-canvas ui-sortable-handle\">Contenu de la page</div>\n                \n                <div class=\"label-canvas ui-sortable-handle\">Contenu de la page</div>\n                \n                <div class=\"label-canvas ui-sortable-handle\">Contenu de la page</div>\n                \n                <div class=\"label-canvas ui-sortable-handle\">Contenu de la page</div>\n                \n                <div class=\"label-canvas ui-sortable-handle\">Contenu de la page</div>\n                \n                <div class=\"label-canvas ui-sortable-handle\">Contenu de la page</div>\n                \n                <div class=\"label-canvas ui-sortable-handle\">Contenu de la page</div>\n                \n                <div class=\"label-canvas ui-sortable-handle\">Contenu de la page</div>\n                \n                <div class=\"label-canvas ui-sortable-handle\">Contenu de la page</div>\n                \n                <div class=\"label-canvas ui-sortable-handle\">Contenu de la page</div>\n                \n                <div class=\"label-canvas ui-sortable-handle\">Contenu de la page</div>\n                \n                <div class=\"label-canvas ui-sortable-handle\">Contenu de la page</div>\n                \n                <div class=\"label-canvas ui-sortable-handle\">Contenu de la page</div>\n                \n                <div class=\"label-canvas ui-sortable-handle\">Contenu de la page</div>\n                \n                <div class=\"label-canvas ui-sortable-handle\">Contenu de la page</div>\n                            <div class=\"row row-edit ui-sortable-handle\" id=\"1508397865044\"><div class=\"toolbox-content\"><button class=\"btn btn-danger deleteRow\"><i class=\"fa fa-times\"></i></button></div><div class=\"col-md-12 column-edit ui-sortable ui-droppable\"><div class=\"label-canvas ui-sortable-handle\">Colonnes</div><div class=\"module-edit ui-sortable-handle\" data-module=\"image\" id=\"1508397867177\"><div class=\"toolbox-content\"><button class=\"btn btn-info editModule\"><i class=\"fa fa-edit\"></i></button><button class=\"btn btn-danger deleteModule\"><i class=\"fa fa-times\"></i></button></div><img src=\"upload/CAMPAGNE_EMAILING_WDM2.png\" class=\"img-editor\" alt=\"Image\"></div><div class=\"module-edit ui-sortable-handle\" data-module=\"text\" id=\"1508398749945\"><div class=\"toolbox-content\"><button class=\"btn btn-info editModule\"><i class=\"fa fa-edit\"></i></button><button class=\"btn btn-danger deleteModule\"><i class=\"fa fa-times\"></i></button></div><div class=\"edit-text-template\"><p>De plus en plus souvent, vous remarquez que vos campagnes dâ€™Emailing ne sont pas ouvertes, pas cliquÃ©es, pas distribuÃ©esâ€¦ Sachez que pour palier Ã  cela, il vous faut respecter les bonnes pratiques du digital. Et vous verrez vos rÃ©sultats sâ€™amÃ©liorer.</p>\n\n<p><strong>Vous Ãªtes une personne&nbsp;</strong></p>\n\n<p>Lorsque vous envoyez un mail, il nâ€™y a rien de plus impersonnel que de signer au nom de votre entreprise. Mettez en avant lâ€™humain, mÃªme Ã  travers un email. Vos lecteurs sentiront que vous avez mis du cÅ“ur Ã  lâ€™ouvrage et quâ€™il ne sâ€™agit pas uniquement dâ€™un email commercial sans cÅ“ur. Pensez-y</p>\n\n<p><strong>RÃ©flÃ©chissez Ã  votre titre&nbsp;</strong></p>\n\n<p>Quelle est la premiÃ¨re chose que vos clients vont lire en premier&nbsp;? Le sujet de votre emailing Ã©videmment. Ce dernier doit donc Ãªtre clair et expliquer ce dont va parler votre courriel. Attention Ã  ne pas en faire trop, sinon, votre email sera bien ouvert mais aussi mit dans la corbeille. Susciter de lâ€™intÃ©rÃªt auprÃ¨s de vos lecteurs.</p>\n\n<p><strong>Mettez Ã  jour vos contacts&nbsp;</strong></p>\n\n<p>Si vous envoyez rÃ©guliÃ¨rement des emails Ã  vos clients et prospects, il est important de mettre Ã  jour votre base de donnÃ©es. Cela vous Ã©vitera les doublons mais Ã©galement les adresses erronÃ©es Ã  cause dâ€™une faute dâ€™orthographe ou inactives suite Ã  un changement dâ€™adresses.</p>\n\n<p>Les rÃ©sultats de vos campagnes seront ainsi amÃ©liorÃ©s.</p>\n\n<p><strong>Pensez Â«&nbsp;Mobile First&nbsp;Â»&nbsp;</strong></p>\n\n<p>Lorsque vous allez crÃ©er votre campagne dâ€™emailing, pensez Ã  vos lecteurs qui vont utiliser leurs smartphones pour lire votre mail. Nâ€™oubliez donc pas de proposer une version responsive, câ€™est-Ã -dire adaptÃ© la lecture de lâ€™email Ã  un Ã©cran de tÃ©lÃ©phone mobile, et Ã  adapter la taille de vos images.</p>\n\n<p>Quand on sait que 50% des emails sont lus sur mobile, il faut y penser&nbsp;!</p>\n\n<p><strong>Allez droit au but&nbsp;</strong></p>\n\n<p>La finalitÃ© de votre email sera certainement dâ€™amener le lecteur sur votre <a href=\"http://wdmstudio.fr/web.php\">site internet </a>pour lire vos articles, dÃ©couvrir vos produits, les acheterâ€¦ Allez donc Ã  lâ€™essentiel dans le contenu de votre email, câ€™est-Ã -dire quâ€™il ne faut pas trop en dire pour Ã©veiller la curiositÃ©, sinon vous risquez de les perdre rapidement.</p>\n\n<p><strong>Ajoutez un call-to-action&nbsp;</strong></p>\n\n<p>Une fois votre contenu rÃ©digÃ©, ajouter un bouton dâ€™appel Ã  lâ€™action. Vous pourrez ajouter Â«&nbsp;en savoir plus&nbsp;Â», Â«&nbsp;Ã  lire ici&nbsp;Â», Â«&nbsp;cliquez ici&nbsp;Â». Ce bouton doit inciter le lecteur Ã  effectuer une action qui lâ€™enverra vers votre landing page.</p>\n\n<p><strong>Travaillez votre landing page&nbsp;</strong></p>\n\n<p>Une landing page, ou page dâ€™atterrissage, est la page de votre site internet oÃ¹ votre lecteur atterrira aprÃ¨s avoir cliquÃ© sur le call-to-action. Cette derniÃ¨re doit donc Ãªtre en adÃ©quation avec le contenu de lâ€™email. En effet, si vous avez envoyÃ© une offre promotionnelle sur votre nouvelle collection de chaussures, la landing page devra alors traitÃ©e cette information.</p>\n\n<p><strong>Ajoutez les autres boutons&nbsp;</strong></p>\n\n<p>Et pourquoi vos lecteurs ne feraient-ils pas le travail pour vous&nbsp;? Proposez-leur de partager certains Ã©lÃ©ments importants de votre email ou suggÃ©rez-leur de vous suivre sur les <a href=\"http://wdmstudio.fr/social-media.php\">rÃ©seaux sociaux </a>afin de rentrer dans votre <a href=\"http://wdmstudio.fr/article-57-comment-integrer-les-reseaux-sociaux-a-sa-strategie-.html\">stratÃ©gie de social media </a>. En une pierre deux coups, vous attirerez du trafic sur votre site internet et gagnerez en notoriÃ©tÃ© grÃ¢ce Ã  un seul email.</p>\n\n<p><strong>Testez avant dâ€™envoyer&nbsp;</strong></p>\n\n<p>Car toutes les messageries sont diffÃ©rentes, que vos lecteurs lisent vos emails sur Gmail, Outlook ou Apple Mail, le rendu ne sera pas toujours le mÃªme. Pour Ãªtre sÃ»r dâ€™apporter une information claire et distinctif mais similaire selon la messagerie, pensez Ã  tester vos campagnes avant dâ€™effectuer vos envois dÃ©finitifs.</p>\n\n<p><strong>Enfin, analysez vos rÃ©sultats&nbsp;</strong></p>\n\n<p>Le taux dâ€™ouverture en dÃ©but de semaine est faible, testez une autre plage horaire. Le bouton call-to-action nâ€™est quasiment pas cliquÃ©, changez sa taille, sa couleur ou son emplacement. Vos campagnes ne sont pas ouvertes, revoyez votre titre dâ€™accroche. Tester est le mot clÃ© dâ€™une campagne dâ€™email. Et Ã  force de tester, vous trouverez votre stratÃ©gie et cette derniÃ¨re sera efficace&nbsp;!</p>\n\n<p>DÃ©sormais câ€™est Ã  vous de jouer. Ou alors, <a href=\"http://wdmstudio.fr/contact.php\">contactez-nous </a>directement !</p>\n</div></div></div></div>                                                                                                                                                                                                                        ', ''),
(19, 'Nouvelle article', '<p>Description</p>\r\n', '', '', NULL, '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
