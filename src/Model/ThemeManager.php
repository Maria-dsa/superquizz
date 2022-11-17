<?php

namespace App\Model;

use PDO;

class ThemeManager extends AbstractManager
{
    public const TABLE = 'theme';

    public function insert(string $theme)
    {
        $statement = $this->pdo->prepare('INSERT INTO ' . self::TABLE .
            ' (`theme`) VALUES (:theme)');
        $statement->bindValue(':theme', $theme, PDO::PARAM_STR);
        $statement->execute();
    }
}
