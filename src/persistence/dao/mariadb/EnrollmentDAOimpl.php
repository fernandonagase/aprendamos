<?php
namespace aprendamos\persistence\dao\mariadb;

use aprendamos\persistence\dao\EnrollmentDAO;
use aprendamos\model\Classroom;
use aprendamos\model\Enrollment;
use aprendamos\model\User;
use aprendamos\persistence\ConnectionFactory;

class EnrollmentDAOimpl implements EnrollmentDAO
{
    public function findById(int $userId, int $classroomId): ?Enrollment
    {
        $conn = ConnectionFactory::getConnection();

        $select = "
            SELECT u.`name`,
                    u.`username`,
                    u.`password`,
                    e.`professor`,
                    c.`name`,
                    c.`description`
            FROM `Enrollment` e
            INNER JOIN `User` u ON e.`user_id` = u.`id_user`
            INNER JOIN `Classroom` c ON e.`classroom_id` = c.`id_classroom`
            WHERE e.`user_id` = ?
              AND e.`classroom_id` = ?
        ";

        if (!($stmt = $conn->prepare($select))) {
            $conn->close();
            throw new \Exception("Could not prepare $select as a PreparedStatement");
        }

        $stmt->bind_param("ii", $userId, $classroomId);
        $stmt->execute();

        $stmt->bind_result(
            $name,
            $username,
            $password,
            $isProfessor,
            $classroomName,
            $classroomDescription
        );

        $enrollment = null;
        if ($stmt->fetch()) {
            $user = new User($username, $password);
            $user->setId($userId);
            $user->setName($name);
            $classroom = new Classroom($classroomName, $classroomDescription);
            $classroom->setId($classroomId);

            $enrollment = new Enrollment($user, $classroom, $isProfessor);
        }

        $stmt->close();
        $conn->close();

        return $enrollment;
    }

    public function findByClassroom(int $classroomId)
    {
        $conn = ConnectionFactory::getConnection();

        $select = "
            SELECT u.`id_user`,
                    u.`name`,
                    u.`username`,
                    u.`password`,
                    e.`professor`,
                    c.`name`,
                    c.`description`
            FROM `Enrollment` e
            INNER JOIN `User` u ON e.`user_id` = u.`id_user`
            INNER JOIN `Classroom` c ON e.`classroom_id` = c.`id_classroom`
            WHERE e.`classroom_id` = ?
        ";

        if (!($stmt = $conn->prepare($select))) {
            $conn->close();
            throw new \Exception("Could not prepare $select as a PreparedStatement");
        }

        $stmt->bind_param("i", $classroomId);
        $stmt->execute();

        $stmt->bind_result(
            $userId,
            $name,
            $username,
            $password,
            $isProfessor,
            $classroomName,
            $classroomDescription
        );

        $classroom = null;
        $enrollments = [];
        while ($stmt->fetch()) {
            if ($classroom === null) {
                $classroom = new Classroom($classroomName, $classroomDescription);
                $classroom->setId($classroomId);
            }
            $user = new User($username, $password);
            $user->setId($userId);
            $user->setName($name);

            $enrollments[] = new Enrollment($user, $classroom, $isProfessor);
        }

        $stmt->close();
        $conn->close();

        return $enrollments;
    }

    public function findByUser(int $userId)
    {
        $conn = ConnectionFactory::getConnection();

        $select = "
            SELECT u.`name`,
                    u.`username`,
                    u.`password`,
                    e.`professor`,
                    c.`id_classroom`,
                    c.`name`,
                    c.`description`
            FROM `Enrollment` e
            INNER JOIN `User` u ON e.`user_id` = u.`id_user`
            INNER JOIN `Classroom` c ON e.`classroom_id` = c.`id_classroom`
            WHERE e.`user_id` = ?
        ";

        if (!($stmt = $conn->prepare($select))) {
            $conn->close();
            throw new \Exception("Could not prepare $select as a PreparedStatement");
        }

        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $stmt->bind_result(
            $name,
            $username,
            $password,
            $isProfessor,
            $classroomId,
            $classroomName,
            $classroomDescription
        );

        $user = null;
        $enrollments = [];
        while ($stmt->fetch()) {
            if ($user === null) {
                $user = new User($username, $password);
                $user->setId($userId);
                $user->setName($name);
            }
            $classroom = new Classroom($classroomName, $classroomDescription);
            $classroom->setId($classroomId);

            $enrollments[] = new Enrollment($user, $classroom, $isProfessor);
        }

        $stmt->close();
        $conn->close();

        return $enrollments;
    }

    public function addEnrollment(int $userId, int $classroomId, bool $professor)
    {
        $conn = ConnectionFactory::getConnection();

        $insert = "
            INSERT INTO `Enrollment` (`user_id`, `classroom_id`, `professor`)
            VALUES (?, ?, ?)
        ";

        if (!($stmt = $conn->prepare($insert))) {
            $conn->close();
            throw new \Exception("Could not prepare $insert as a PreparedStatement");
        }

        $stmt->bind_param("iii", $userId, $classroomId, $professor);
        $stmt->execute();

        $stmt->close();
        $conn->close();
    }

    public function removeEnrollment(int $userId, int $classroomId)
    {
        $postDao = new PostDAOimpl();
        $reportDao = new ReportDAOimpl();

        $postDao->removeFromEnrollment($userId, $classroomId);
        $reportDao->removeFromEnrollment($userId, $classroomId);

        $conn = ConnectionFactory::getConnection();
        $delete = "
            DELETE
            FROM `enrollment`
            WHERE `user_id` = ?
              AND `classroom_id` = ?;
        ";

        if (!($stmt = $conn->prepare($delete))) {
            $conn->close();
            throw new \Exception("Could not prepare $delete as a PreparedStatement");
        }

        $stmt->bind_param("ii", $userId, $classroomId);
        $stmt->execute();

        $stmt->close();
        $conn->close();
    }

    public function removeFromClassroom(int $classroomId)
    {
        $conn = ConnectionFactory::getConnection();
        $delete = "
            DELETE
            FROM `enrollment`
            WHERE `classroom_id` = ?;
        ";

        if (!($stmt = $conn->prepare($delete))) {
            $conn->close();
            throw new \Exception("Could not prepare $delete as a PreparedStatement");
        }

        $stmt->bind_param("i", $classroomId);
        $stmt->execute();

        $stmt->close();
        $conn->close();
    }
}