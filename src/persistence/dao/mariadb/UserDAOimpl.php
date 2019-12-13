<?php
namespace aprendamos\persistence\dao\mariadb;

use aprendamos\persistence\dao\UserDAO;
use aprendamos\model\User;
use aprendamos\persistence\ConnectionFactory;

class UserDAOimpl implements UserDAO
{
    public function addUser(User $user)
    {
        $conn = ConnectionFactory::getConnection();

        $insert = "
            INSERT INTO `User` (`name`, `username`, `password`)
            VALUES (?, ?, ?)
        ";

        if (!($stmt = $conn->prepare($insert))) {
            $conn->close();
            throw new \Exception("Could not prepare $insert as a PreparedStatement");
        }

        $name = $user->getName();
        $username = $user->getUsername();
        $password = $user->getPassword();

        $stmt->bind_param("sss", $name, $username, $password);
        $stmt->execute();

        $stmt->close();
        $conn->close();
    }

    public function findByCredentials(string $username, string $password): ?User
    {
        $conn = ConnectionFactory::getConnection();

        $select = "
            SELECT `id_user`,
                    `name`,
                    `username`,
                    `password`
            FROM `User`
            WHERE `username` = ?
              AND `password` = ?
        ";

        if (!($stmt = $conn->prepare($select))) {
            $conn->close();
            throw new \Exception("Could not prepare $select as a PreparedStatement");
        }

        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();

        $stmt->bind_result($id, $name, $username, $password);

        $user = null;
        if ($stmt->fetch()) {
            $user = new User($username, $password);
            $user->setId($id);
            $user->setName($name);
        }

        $stmt->close();
        $conn->close();

        return $user;
    }

    public function findByUsername(string $username): ?User
    {
        $conn = ConnectionFactory::getConnection();

        $select = "
            SELECT `id_user`,
                    `name`,
                    `username`,
                    `password`
            FROM `User`
            WHERE `username` = ?
        ";

        if (!($stmt = $conn->prepare($select))) {
            $conn->close();
            throw new \Exception("Could not prepare $select as a PreparedStatement");
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();

        $stmt->bind_result($id, $name, $username, $password);

        $user = null;
        if ($stmt->fetch()) {
            $user = new User($username, $password);
            $user->setId($id);
            $user->setName($name);
        }

        $stmt->close();
        $conn->close();

        return $user;
    }
}