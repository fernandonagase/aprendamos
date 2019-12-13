<?php
namespace aprendamos\persistence\dao\mariadb;

use aprendamos\persistence\dao\ClassroomDAO;
use aprendamos\model\Classroom;
use aprendamos\persistence\ConnectionFactory;
use aprendamos\lib\Authenticator;

class ClassroomDAOimpl implements ClassroomDAO
{
    public function findAll()
    {
        throw new \Exception('Not implemented');
    }

    public function findById(int $id): ?Classroom
    {
        $conn = ConnectionFactory::getConnection();
        $select = "
            SELECT `name`,
                    `description`
            FROM `classroom`
            WHERE `id_classroom` = ?
        ";

        if (!($stmt = $conn->prepare($select))) {
            $conn->close();
            throw new \Exception("Could not prepare $insert as a PreparedStatement");
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();

        $stmt->bind_result($name, $description);

        $classroom = null;
        if ($stmt->fetch()) {
            $classroom = new Classroom($name, $description);
            $classroom->setId($id);
        }

        $stmt->close();
        $conn->close();

        return $classroom;
    }

    public function addClassroom(Classroom $classroom)
    {
        $conn = ConnectionFactory::getConnection();

        // Insert classroom
        $insert = "
            INSERT INTO `Classroom` (`name`, `description`)
            VALUES (?, ?)
        ";

        if (!($stmt = $conn->prepare($insert))) {
            $conn->close();
            throw new \Exception("Could not prepare $insert as a PreparedStatement");
        }

        $name = $classroom->getName();
        $description = $classroom->getDescription();

        $stmt->bind_param("ss", $name, $description);
        if ($stmt->execute() === false) {
            $stmt->close();
            $conn->rollback();
            $conn->close();
            throw new \Exception('Error while inserting Classroom');
        }

        $classroomId = $stmt->insert_id;
        $stmt->close();

        // Insert classroom's professor
        $insert = "
            INSERT INTO `Enrollment` (`user_id`, `classroom_id`, `professor`)
            VALUES (?, ?, 1)
        ";

        if (!($stmt = $conn->prepare($insert))) {
            $conn->close();
            throw new \Exception("Could not prepare $insert as a PreparedStatement");
        }

        $userId = Authenticator::currentUser();

        $stmt->bind_param("ii", $userId, $classroomId);
        if ($stmt->execute() === false) {
            $stmt->close();
            $conn->rollback();
            $conn->close();
            throw new \Exception('Error while inserting classroom\'s Professor');
        }

        $stmt->close();
        $conn->close();
    }

    public function updateClassroom(Classroom $classroom)
    {
        $conn = ConnectionFactory::getConnection();
        $update = "
            UPDATE `Classroom`
            SET `name` = ?,
                `description` = ?
            WHERE `id_classroom` = ?
        ";

        if (!($stmt = $conn->prepare($update))) {
            $conn->close();
            throw new \Exception("Could not prepare $update as a PreparedStatement");
        }

        $id = $classroom->getId();
        $name = $classroom->getName();
        $description = $classroom->getDescription();

        $stmt->bind_param("ssi", $name, $description, $id);
        $stmt->execute();

        $stmt->close();
        $conn->close();
    }

    public function removeClassroom(int $classroomId)
    {
        $reportDao = new ReportDAOimpl();
        $assignmentDao = new AssignmentDAOimpl();
        $postDao = new PostDAOimpl();
        $enrollmentDao = new EnrollmentDAOimpl();

        $reportDao->removeFromClassroom($classroomId);
        $assignmentDao->removeFromClassroom($classroomId);
        $postDao->removeFromClassroom($classroomId);
        $enrollmentDao->removeFromClassroom($classroomId);

        $conn = ConnectionFactory::getConnection();
        $delete = "
            DELETE
            FROM `Classroom`
            WHERE `id_classroom` = ?
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