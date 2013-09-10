<?php

namespace models;

use models\UserDatabaseModel as UserDB;

class UserModel
{

    // Authorization levels
    const NOT_AUTHORIZED = 0;
    const AUTHORIZED_BY_USER = 1;
    const AUTHORIZED_BY_COOKIES = 2;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * Indicates if, and how a user is logged in using the constants
     * @var integer
     */
    private $authorization;

    public function __construct($username, $password, $authorization = 0)
    {
        $this->setUsername($username);
        $this->setPassword($password);
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {

        $this->username = $username;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        if (strlen($password) < 8) {
            throw new \Exception('Password is too short.');
        }

        $this->password = $password;
    }

    /**
     * @param integer $authLevel
     */
    public function setAuthorization($authLevel)
    {
        $this->authorization = $authLevel;
    }

    /**
     * Authorizes a user by matching the username against the password hash
     * @param  string $username
     * @param  string $password
     * @return UserModel
     */
    public static function authorizeUser($username, $password)
    {
        // If user exists
        if (! UserDB::userExists($username)) {
            throw new \Exception('User does not exist');
        }

        // And is authorized
        if (! UserDB::getPasswordHash($username) === sha1($password)) {
            throw new \Exception('Username and password do not match');
        }

        // Return new authenticated user
        return new UserModel($username, $password, self::AUTHORIZED_BY_USER);

    }
}