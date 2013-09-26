<?php

namespace Phplogin\Models;

use PDO;
use Phplogin\Models\TemporaryPasswordModel;

class TemporaryPasswordStorageModel
{
    /**
     * @var PDO
     */
    private $pdo;

    /**
     * @var string
     */
    private static $tableName = 'TemporaryPassword';

    /**
     * @param PDO $database
     */
    public function __construct(PDO $database)
    {
        $this->pdo = $database;
    }

    /**
     * @param  TemporaryPasswordModel $temppw
     * @return bool true on success, false on failure
     */
    public function insert(TemporaryPasswordModel $temppw)
    {
        // Prepare statement
        $sql = "INSERT INTO " . self::$tableName . " (UserID, TemporaryPassword)
                VALUES (:userid, :password);";

        $stmt = $this->pdo->prepare($sql);

        // Bind values
        $stmt->bindValue(':userid', $temppw->getUserId(), PDO::PARAM_INT);
        $stmt->bindValue(':password', $temppw->getTemporaryPassword(), PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * @param  TemporaryPasswordModel $temppw
     * @return bool true on success, false on failure
     */
    public function update(TemporaryPasswordModel $temppw)
    {
        // Prepare statement
        $sql = "UPDATE " . self::$tableName . "
                SET TemporaryPassword = :password
                WHERE UserID = :userid";
        $stmt = $this->pdo->prepare($sql);

        // Bind values
        $stmt->bindValue(':userid', $temppw->getUserId(), PDO::PARAM_INT);
        $stmt->bindValue(':password', $temppw->getTemporaryPassword(), PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * If there is a temporary password saved for the user with specified id
     * @param  int $userId
     * @return bool
     */
    public function idExists($userId)
    {
        // Prepare statement
        $sql = "SELECT * FROM " . self::$tableName . " WHERE UserID = :userid";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':userid', $userId, PDO::PARAM_INT);
        $stmt->execute();

        // Returns false on not found
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result === false ? false : true;
    }
}
