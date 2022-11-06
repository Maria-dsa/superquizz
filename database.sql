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
  `username` VARCHAR(45) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `picture` VARCHAR(255),
  `role` VARCHAR(5)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Structure de la table `game`
--

CREATE TABLE `game` (
  `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `type` INT NULL,
  `created_at` DATETIME NOT NULL,
  `ended_at` DATETIME NOT NULL,
  `user_id` INT NOT NULL,
  CONSTRAINT game_user
    FOREIGN KEY (user_id)
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
('Quelle est la couleur dune tomate mure ?','test','facile'),
('Quelle est le meilleur langage de programmation ?','autre','difficile');


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
('C#',0,2);

--
-- contenu de la table `user`
--
INSERT INTO `user` (`username`, `role`) VALUES
('florent', 'admin'),
('nicolas', 'admin'),
('maria', 'admin'),
('magali', 'admin'),
('JF', 'admin');

--
-- Contenu de la table `game`
--

INSERT INTO `game` (`type`, `created_at`, `ended_at`, `user_id`) VALUES
(1, '2022-10-25 10:10:00', '2022-10-25 10:13:25', 1),
(2, '2022-10-26 10:10:00', '2022-10-26 10:11:00', 2);
