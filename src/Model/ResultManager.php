<?php

namespace App\Model;

use PDO;
use App\Entity\Game;

class ResultManager extends AbstractManager
{
  //US-5.3.1 : Classement général des 20 premiers par type de jeu avec rang;

    public function selectAllByRank(): array
    {
      // prepared request
        $query = " SELECT * FROM (
        SELECT user_id, username, game_type, rank()
        OVER (PARTITION BY game_type ORDER BY score_moyen DESC, temps_moyen ASC) AS rang
        , nb_parties, score_moyen, temps_moyen
        FROM
        (
        SELECT
        user.id as user_id,
        user.username,
        game.type as game_type,
        COUNT(DISTINCT game.id) as nb_parties,
        ROUND((SUM(game_has_question.is_true)/COUNT(DISTINCT game.id)),2) as score_moyen,
        ROUND((SUM(game_has_question.time)/COUNT(DISTINCT game.id)),2) as temps_moyen
        FROM game_has_question INNER JOIN game ON game.id = game_has_question.game_id
        INNER JOIN user ON user.id = game.userId
        WHERE game.endedAt IS NOT NULL GROUP BY user.id, user.username, game.type
        ORDER BY score_moyen DESC, temps_moyen ASC
        ) sq1
      ) sq2
      WHERE rang<=20;";

        return $this->pdo->query($query)->fetchAll();
    }
  //US-5.3.1 : Podium en fonction du type de jeu

    public function selectPodium($type): array
    {
      // prepared request
        $query = "SELECT * FROM (
        SELECT user_id, username, game_type, picture, rank()
        OVER (PARTITION BY game_type ORDER BY score_moyen DESC, temps_moyen ASC) AS rang
        , nb_parties, score_moyen, temps_moyen
        FROM
        (
        SELECT
        user.id as user_id,
        user.username,
        game.type as game_type,
        user.picture as picture,
        COUNT(DISTINCT game.id) as nb_parties,
        ROUND((SUM(game_has_question.is_true)/COUNT(DISTINCT game.id)),2) as score_moyen,
        ROUND((SUM(game_has_question.time)/COUNT(DISTINCT game.id)),2) as temps_moyen
          FROM game_has_question INNER JOIN game ON game.id = game_has_question.game_id
          INNER JOIN user ON user.id = game.userId
          WHERE game.endedAt IS NOT NULL GROUP BY user.id, user.username, game.type, user.picture
          ORDER BY score_moyen DESC, temps_moyen ASC
          ) sq1
        ) sq2
        WHERE game_type = :type AND rang <=3;";

          $statement = $this->pdo->prepare($query);
          $statement->bindValue('type', $type, \PDO::PARAM_INT);
          $statement->execute();

          return $statement->fetchAll();
    }

  // US-5.3.1 : RANG D'UN JOUEUR AU CLASSEMENT GENERAL AVEC SON id en paramètre

    public function selectOneRankById(int $userId): array
    {
      // prepared request
        $query = "SELECT user_id, username, game_type, rang, nb_parties, score_moyen, temps_moyen
    FROM
    (
    SELECT user_id, username, game_type, rank()
    OVER (PARTITION BY game_type ORDER BY score_moyen DESC, temps_moyen ASC) AS rang
    , nb_parties, score_moyen, temps_moyen
    FROM
    (
    SELECT
    user.id as user_id,
    user.username,
    game.type as game_type,
    COUNT(DISTINCT game.id) as nb_parties,
    ROUND((SUM(game_has_question.is_true)/COUNT(DISTINCT game.id)),2) as score_moyen,
    ROUND((SUM(game_has_question.time)/COUNT(DISTINCT game.id)),2) as temps_moyen
    FROM game_has_question INNER JOIN game ON game.id = game_has_question.game_id
    INNER JOIN user ON user.id = game.userId
    WHERE game.endedAt IS NOT NULL GROUP BY user.id, user.username, game.type
    ORDER BY score_moyen DESC, temps_moyen ASC
    ) sq1
    )sq2
    WHERE sq2.user_id=:userId;";

        $statement = $this->pdo->prepare($query);
        $statement->bindValue('userId', $userId, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

  // US 5.3.1 : % de reussite par question : x % des joueurs ont bien répondu
  //(quand il y a eu au moins une bonne réponse), même pour les parties interrompues

    public function selectAllQuestionSuccess(): array
    {
        $query = "SELECT /*game_has_question.question_id, */
    ROUND((sum(game_has_question.is_true)/COUNT(game_has_question.id))*100,2)
    as pourcentage_reussite
    FROM game_has_question
    INNER JOIN game ON game.id = game_has_question.game_id INNER JOIN user ON user.id = game.userId
    GROUP BY game_has_question.question_id
    ORDER BY game_has_question.question_id;";

        return $this->pdo->query($query)->fetchAll();
    }

  //US 5.3.1 : POURCENTAGE DE REUSSITE POUR UNE QUESTION DONNEE

    public function selectQuestionSuccessById($id): array
    {
        $query = "SELECT game_has_question.question_id,
    ROUND((sum(game_has_question.is_true)/COUNT(game_has_question.id))*100)
    as pourcentage_reussite
    FROM game_has_question
    INNER JOIN game ON game.id = game_has_question.game_id INNER JOIN user ON user.id = game.userId
    WHERE game_has_question.question_id = :id
    GROUP BY game_has_question.question_id;";

        $statement = $this->pdo->prepare($query);
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch();
    }

    public function answerIntoPercent($nbGoodAnswer, $nbQuestions)
    {
        return round($nbGoodAnswer / $nbQuestions  *  100);
    }

//US 5.2 : matching reponse joueur avec réponse correct

    public function matchingAnswerByGameId($game): array
    {
        $query = "SELECT g.question_id AS question_id,
    g.answer_id AS user_answer_id,
    g.is_true AS is_true,
    a.id AS correct_answer_id,
    a.is_correct AS is_correct
    FROM game_has_question g JOIN answer a ON a.question_id = g.question_id
    WHERE g.game_id=:game and is_correct=1;";

        $statement = $this->pdo->prepare($query);
        $statement->bindValue('game', $game, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }
}
