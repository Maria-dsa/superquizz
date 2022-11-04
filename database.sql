-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Jeu 26 Octobre 2017 à 13:53
-- Version du serveur :  5.7.19-0ubuntu0.16.04.1
-- Version de PHP :  7.0.22-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


DROP DATABASE IF EXISTS superquizz;
CREATE DATABASE superquizz;
USE superquizz;

--
-- Base de données :  `super-quizz`
--
-- --------------------------------------------------------

--
-- Structure de la table `question`
--
CREATE TABLE `question` (
  `id` INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `content` TEXT NOT NULL,
  `theme` VARCHAR(255),
  `difficulty_level` VARCHAR(50)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Structure de la table `answer`
--

CREATE TABLE `answer` (
  `id` INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `content` TEXT NOT NULL,
  `is_correct` BOOLEAN NOT NULL,
  `question_id` INT,
  CONSTRAINT `fk_answer_question`
    FOREIGN KEY (question_id)
    REFERENCES `question`(`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `nickname` VARCHAR(45) NOT NULL,
  `password` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Structure de la table `game`
--

CREATE TABLE `game` (
  `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `type` INT NULL,
  `createdAt` DATETIME NOT NULL,
  `endedAt` DATETIME,
  `userId` INT NOT NULL,
  CONSTRAINT game_user
    FOREIGN KEY (userId)
    REFERENCES user(id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Structure de la table `game_has_question`
--

CREATE TABLE `game_has_question` (
  `id` INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `game_id` INT NOT NULL,
  `question_id` INT NOT NULL,
  `answer_id` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `game_has_question`
ADD CONSTRAINT fk_game_has_question_game
FOREIGN KEY (game_id)
REFERENCES game(id);

ALTER TABLE `game_has_question`
ADD CONSTRAINT fk_game_has_question_question
FOREIGN KEY (question_id)
REFERENCES question(id);

ALTER TABLE `game_has_question`
ADD CONSTRAINT fk_game_has_question_answer
FOREIGN KEY (answer_id)
REFERENCES answer(id);


--
-- Contenu de la table `question`
--

INSERT INTO `question` (`content`, `theme`, `difficulty_level`) VALUES
('Quelle est la couleur d''une tomate mure ?','test','facile'),
('Quelle est le meilleur langage de programmation ?','autre','difficile'),
('Trouver l''intrus parmi ces mots :','Logique','Facile'),
('Un seul de ses ustensiles de cuisine à consonance sexuelle n''existe pas, lequel ?','Humour','Facile'),
('Dans l''Antiquité, sur quoi les romains prêtaient-t-il serment ?','Humour','Difficile'),
('Où n''y a-t-il pas de contrepèterie ?','Humour','Difficile'),
('Lequel de ces requins existe vraiment ?','Humour','Moyen'),
('Parmi ces 4 voitures, laquelle a vraiment existé ?','Humour','Moyen'),
('Alain Delon a un CAP de ?','Humour','Moyen'),
('Au poker, comment appelle-t-on la réunion de trois cartes de même valeur ?','Culture Générale','Moyen'),
('A quel pays est associé ce drapeau ?','Culture Générale','Facile'),
('Qui est l''auteur du roman "La Guerre des mondes", adapté au cinéma','Culture Générale','Difficile'),
('D''après le proverbe que ne fait pas une hirondelle','Culture Générale','Facile'),
('Quel est le nom de cet acteur américain ?','Culture Générale','Moyen'),
('En quelle année est arrivée le cinéma parlant ?','Culture Générale','Difficile'),
('Quels sont les muscles les plus actifs dans la journée ?','Sciences','Moyen'),
('Combien de temps met la lumière du soleil pour nous atteindre ?','Sciences','Difficile'),
('Chassez l''intrus...','Logique','Moyen'),
('A ; b ; e ; c ; i ; d ; o ;','Logique','Difficile'),
('(1 * 10) ; (2 * 9) ; (3 * 8) ; (4 * ? )','Logique','Facile'),
('Quel grand physicien découvrit la radioactivité ?','Sciences','Difficile'),
('Par quelle théorie Einstein révolutionna-t-il la physique ?','Sciences','Facile'),
('Complétez cette suite logique. Quelle forme doit venir ensuite ?','Logique','Difficile'),
('Lequel de ces fruits ne pousse pas dans un arbre','Sciences','Moyen'),
('Comment compléter cette suite logique : S, O, N, D, J, F, ...','Culture Générale','Moyen'),
('Comment s''appelle la science qui étudie les serpents ?','Sciences','Difficile'),
('Quelle est la capitale du plus grand archipel du monde, composé de plus de 17000 îles ?','Géographie','Moyen'),
('Quelle est la capitale du Surinam ?','Géographie','Difficile'),
('Quel est le pays dont la capitale arrosée par le Tigre et dont les habitants sont les Bagdadis ?','Géographie','Facile'),
('Quelle est la capitale de la province autonome du Danemark, située dans l''océan Atlantique ?','Géographie','Difficile'),
('Dans quel état se situe le plus haut sommet des Etats-Unis ?','Géographie','Moyen'),
('A quel pays appartient ce drapeau ?','Géographie','Difficile');

--
-- Contenu de la table `answer`
--

INSERT INTO `answer` (`content`, `is_correct`, `question_id`) VALUES
('bleu',0,1),
('rouge',1,1),
('vert',0,1),
('jaune',0,1),
('PHP',1,2),
('JavaScript',0,2),
('Java',0,2),
('C#',0,2),
('Agglomérer',1,3),
('Disséminer',0,3),
('Répandre',0,3),
('Eparpiller',0,3),
('démonte-pneu',1,4),
('Cul-de-poule',0,4),
('ouvre-boîte',0,4),
('lèchefrite',0,4),
('Leurs testicules',1,5),
('Un album d’Astérix',0,5),
('Les lauriers de César',0,5),
('Une Bible, comme tout le monde',0,5),
('Il y a deux couilles sous la bite',1,6),
('Le Pont-Neuf fait soixante-pieds',0,6),
('Les nouilles cuisent au jus de cane',0,6),
('Les laborieuses populations du Cap',0,6),
('Le requin-citron',1,7),
('Le requin-fraise',0,7),
('Le requin-banane',0,7),
('Le requin-chocolat-pistache',0,7),
('La Mazda « Laputa »',1,8),
('La Fiat « 500 l’amour et 200 la pipe »',0,8),
('La Nissan « Gigolo »',0,8),
('La Skoda « Tapina »',0,8),
('Charcutier',1,9),
('Philosophie orientale',0,9),
('Menuisier',0,9),
('Reconnaissance en paternité',0,9),
('Brelan',1,10),
('La quinte',0,10),
('La quinte flush',0,10),
('Le full',0,10),
('Russie',1,11),
('Arménie',0,11),
('Bulgarie',0,11),
('Pays-Bas',0,11),
('Herbert George Wells',1,12),
('Jules Verne',0,12),
('Jack London',0,12),
('Aldous Huxley',0,12),
('le printemps',1,13),
('l''amour',0,13),
('le bonheur',0,13),
('la vaisselle',0,13),
('Christopher Lloyd',1,14),
('Bob Odenkirk',0,14),
('Robert Zemeckis',0,14),
('Bryan Cranston',0,14),
('1927',1,15),
('1924',0,15),
('1934',0,15),
('1939',0,15),
('les muscles oculaires',1,16),
('les muscles de la langue',0,16),
('les muscles du dos',0,16),
('les muscles des bras',0,16),
('8min et 20sec',1,17),
('35sec',0,17),
('3min et 15sec',0,17),
('17min et 40sec',0,17),
('82',1,18),
('27',0,18),
('45',0,18),
('108',0,18),
('F',1,19),
('E',0,19),
('G',0,19),
('H',0,19),
('7',1,20),
('5',0,20),
('6',0,20),
('8',0,20),
('Henri Becquerel',1,21),
('Frédéric Joliot-Curie',0,21),
('Wilhelm Röntgen',0,21),
('John Dalton',0,21),
('de la relativité',1,22),
('de l''évolution',0,22),
('de l''espace temps',0,22),
('du complot',0,22),
('C',1,23),
('A',0,23),
('B',0,23),
('D',0,23),
('L''ananas',1,24),
('Le corossol',0,24),
('Le ramboutan',0,24),
('Le cajou',0,24),
('M',1,25),
('R',0,25),
('P',0,25),
('E',0,25),
('L''herpétologie',1,26),
('La reptilogie',0,26),
('La serpentologie',0,26),
('L''hichyologie',0,26),
('Jakarta',1,27),
('Tokyo',0,27),
('Manille',0,27),
('Kuala Lumpur',0,27),
('Paramaribo',1,28),
('Georgetown',0,28),
('Asmara',0,28),
('Roseau',0,28),
('Irak',1,29),
('Iran',0,29),
('Italie',0,29),
('Syrie',0,29),
('Nuuk',1,30),
('Muuk',0,30),
('Buuk',0,30),
('Puuk',0,30),
('Alaska',1,31),
('Wyoming',0,31),
('Nevada',0,31),
('Oregon',0,31),
('Les îles Caïmans',1,32),
('Belize',0,32),
('Antigua et Barbuda',0,32),
('Trinité et Tobago',0,32);

--
-- contenu de la table `user`
--
INSERT INTO `user` (`nickname`, `password`) VALUES
('florent', 'florent'),
('nicolas', 'nicolas'),
('maria', 'maria'),
('magali', 'magali'),
('JF', 'JF');

--
-- Contenu de la table `game`
--

INSERT INTO `game` (`type`, `createdAt`, `endedAt`, `userId`) VALUES
(1, '2022-10-25 10:10:00', '2022-10-25 10:13:25', 1),
(2, '2022-10-26 10:10:00', '2022-10-26 10:11:00', 2);
