<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Model\AnswerManager;
use App\Model\QuestionManager;
use App\Model\ThemeManager;

define('APP_DB_USER', 'florent');
define('APP_DB_PASSWORD', 'objectiflune');
define('APP_DB_HOST', 'localhost');
define('APP_DB_NAME', 'testsuperquizz');

//Model (for connexion data, see unversionned db.php)
define('DB_USER', getenv('DB_USER') ? getenv('DB_USER') : APP_DB_USER);
define('DB_PASSWORD', getenv('DB_PASSWORD') ? getenv('DB_PASSWORD') : APP_DB_PASSWORD);
define('DB_HOST', getenv('DB_HOST') ? getenv('DB_HOST') : APP_DB_HOST);
define('DB_NAME', getenv('DB_NAME') ? getenv('DB_NAME') : APP_DB_NAME);

// database dump file path for automatic import
define('DB_DUMP_PATH', __DIR__ . '/database.sql');

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . '; charset=utf8',
        DB_USER,
        DB_PASSWORD
    );
    /*
    $pdo->exec('DROP DATABASE IF EXISTS ' . DB_NAME);
    $pdo->exec('CREATE DATABASE ' . DB_NAME);
    $pdo->exec('USE ' . DB_NAME);

    if (is_file(DB_DUMP_PATH) && is_readable(DB_DUMP_PATH)) {
        $sql = file_get_contents(DB_DUMP_PATH);
        $statement = $pdo->prepare($sql);
        $statement->execute();
    } else {
        echo DB_DUMP_PATH . ' file does not exist';
    }*/
} catch (PDOException $exception) {
    echo $exception->getMessage();
}

/* Essai fonctionnel
$question = [
    'question' => 'question test',
    'theme' => 'theme test',
    'level' => 'hardu'
];

$questionManager = new QuestionManager();
$val = $questionManager->insert($question);
echo $val; */


// Import et décodage du fichier JSON
$json = file_get_contents('./src/Service/openquizzdb_124.json');
$decoded_json = json_decode($json, true);

// Récupération du thème commun à tout le fichier
$theme = $decoded_json['catégorie-nom-slogan']['fr']['nom'];
//var_dump($theme);

// Initialisation du tableau qui va contenir les questions
$questions = [];

//On boucle sur le tableau des questions en français
foreach ($decoded_json['quizz']['fr'] as $level => $questionsInfo) {
    foreach ($questionsInfo as $content) {
        $question = [
            'level' => $level,
            'theme' => $theme,
            'question' => $content['question']];
            $i = 1;
        foreach ($content['propositions'] as $ans) {
            if ($ans === $content['réponse']) {
                $question['goodAnswer'] = $ans;
            } else {
                $question['badAnswer' . $i] = $ans;
                $i++;
            }
        }
        $questions[] = $question;
    }
}

//var_dump($questions);
$questionManager = new QuestionManager();
$answerManager = new AnswerManager();
$themeManager = new ThemeManager();
$themeManager->insert($theme);

foreach ($questions as $question) {
    $lastId = $questionManager->insert($question);
    $answerManager->insertAtId($question, $lastId);
}

echo 'fini';
