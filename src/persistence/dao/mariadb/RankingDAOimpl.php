<?php
namespace aprendamos\persistence\dao\mariadb;

use aprendamos\persistence\dao\RankingDAO;
use aprendamos\model\User;
use aprendamos\model\viewModel\UserRanking;
use aprendamos\persistence\ConnectionFactory;

class RankingDAOimpl implements RankingDAO
{
    public function rankQuestions(int $classroomId)
    {
        $conn = ConnectionFactory::getConnection();
        $select = "
            SELECT u.`id_user`,
                    u.`name`,
                    u.`username`,
                    u.`password`,
                    count(*) qtd_posts
            FROM `User` u
            INNER JOIN `Post` p ON u.`id_user` = p.`user_id`
            WHERE p.`classroom_id` = ?
            AND p.`post_id` IS NULL
            GROUP BY u.`id_user`,
                    u.`name`,
                    u.`username`,
                    u.`password`
            ORDER BY qtd_posts DESC,
                    u.`name` ASC
            LIMIT 5
        ";

        if (!($stmt = $conn->prepare($select))) {
            $conn->close();
            throw new \Exception("Could not prepare $select as a PreparedStatement");
        }

        $stmt->bind_param("i", $classroomId);
        $stmt->execute();

        $stmt->bind_result($id, $name, $username, $password, $count);

        $rankings = [];
        while ($stmt->fetch()) {
            $user = new User($username, $password);
            $user->setId($id);
            $user->setName($name);

            $rankings[] = new UserRanking($user, $count);
        }

        $stmt->close();
        $conn->close();

        return $rankings;
    }

    public function rankAnswers(int $classroomId)
    {
        $conn = ConnectionFactory::getConnection();
        $select = "
            SELECT u.`id_user`,
                    u.`name`,
                    u.`username`,
                    u.`password`,
                    count(*) qtd_posts
            FROM `User` u
            INNER JOIN `Post` p ON u.`id_user` = p.`user_id`
            WHERE p.`classroom_id` = ?
            AND p.`post_id` IS NOT NULL
            GROUP BY u.`id_user`,
                    u.`name`,
                    u.`username`,
                    u.`password`
            ORDER BY qtd_posts DESC,
                    u.`name` ASC
            LIMIT 5
        ";

        if (!($stmt = $conn->prepare($select))) {
            $conn->close();
            throw new \Exception("Could not prepare $select as a PreparedStatement");
        }

        $stmt->bind_param("i", $classroomId);
        $stmt->execute();

        $stmt->bind_result($id, $name, $username, $password, $count);

        $rankings = [];
        while ($stmt->fetch()) {
            $user = new User($username, $password);
            $user->setId($id);
            $user->setName($name);

            $rankings[] = new UserRanking($user, $count);
        }

        $stmt->close();
        $conn->close();

        return $rankings;
    }

    public function rankPosts(int $classroomId)
    {
        $conn = ConnectionFactory::getConnection();
        $select = "
            SELECT u.`id_user`,
                    u.`name`,
                    u.`username`,
                    u.`password`,
                    count(*) qtd_posts
            FROM `User` u
            INNER JOIN `Post` p ON u.`id_user` = p.`user_id`
            WHERE p.`classroom_id` = ?
            GROUP BY u.`id_user`,
                    u.`name`,
                    u.`username`,
                    u.`password`
            ORDER BY qtd_posts DESC,
                    u.`name` ASC
            LIMIT 5
        ";

        if (!($stmt = $conn->prepare($select))) {
            $conn->close();
            throw new \Exception("Could not prepare $select as a PreparedStatement");
        }

        $stmt->bind_param("i", $classroomId);
        $stmt->execute();

        $stmt->bind_result($id, $name, $username, $password, $count);

        $rankings = [];
        while ($stmt->fetch()) {
            $user = new User($username, $password);
            $user->setId($id);
            $user->setName($name);

            $rankings[] = new UserRanking($user, $count);
        }

        $stmt->close();
        $conn->close();

        return $rankings;
    }
}