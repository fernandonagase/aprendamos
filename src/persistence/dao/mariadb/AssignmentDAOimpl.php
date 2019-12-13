<?php
namespace aprendamos\persistence\dao\mariadb;

use aprendamos\persistence\dao\AssignmentDAO;
use aprendamos\model\Assignment;
use aprendamos\persistence\ConnectionFactory;

class AssignmentDAOimpl implements AssignmentDAO
{
    public function findByClassroom(int $classroomId)
    {
        throw new \Exception('Not implemented');
    }

    public function findById(int $id)
    {
        $conn = ConnectionFactory::getConnection();
        $select = "
            SELECT `name`,
                    `description`,
                    `deadline`,
                    `status`
            FROM `assignment`
            WHERE `id_assignment` = ?
        ";

        if (!($stmt = $conn->prepare($select))) {
            $conn->close();
            throw new \Exception("Could not prepare $select as a PreparedStatement");
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();

        $stmt->bind_result($name, $description, $deadline, $status);
        
        $assignment = null;
        if ($stmt->fetch()) {
            $assignment = new Assignment($name, $description, $deadline);
            $assignment->setId($id);
            $assignment->setStatus($status);
        }
        
        $stmt->close();
        $conn->close();

        return $assignment;
    }

    public function addAssignment(Assignment $assignment, int $authorId, int $classroomId)
    {
        $conn = ConnectionFactory::getConnection();
        $insert = "
            INSERT INTO `Assignment` (`name`, `description`, `user_id`, `classroom_id`, `deadline`, `status`)
            VALUES (?, ?, ?, ?, ?, 'aberto')
        ";

        if (!($stmt = $conn->prepare($insert))) {
            $conn->close();
            throw new \Exception("Could not prepare $insert as a PreparedStatement");
        }

        $name = $assignment->getName();
        $description = $assignment->getDescription();
        $deadline = $assignment->getDeadline();

        $stmt->bind_param("ssiis", $name, $description, $authorId, $classroomId, $deadline);
        $stmt->execute();

        $stmt->close();
        $conn->close();
    }

    public function updateAssignment(Assignment $assignment)
    {
        $conn = ConnectionFactory::getConnection();
        $update = "
            UPDATE `assignment`
            SET `name` = ?,
                `description` = ?,
                `deadline` = ?
            WHERE `id_assignment` = ?
        ";

        if (!($stmt = $conn->prepare($update))) {
            $conn->close();
            throw new \Exception("Could not prepare $update as a PreparedStatement");
        }

        $id = $assignment->getId();
        $name = $assignment->getName();
        $description = $assignment->getDescription();
        $deadline = $assignment->getDeadline();

        $stmt->bind_param("sssi", $name, $description, $deadline, $id);
        $stmt->execute();

        $stmt->close();
        $conn->close();
    }

    public function removeAssignment(int $assignmentId)
    {
        $conn = ConnectionFactory::getConnection();
        $delete = "
            DELETE
            FROM `Report`
            WHERE `assignment_id` = ?
        ";
        
        if (!($stmt = $conn->prepare($delete))) {
            $conn->close();
            throw new \Exception("Could not prepare $delete as a PreparedStatement");
        }

        $stmt->bind_param("i", $assignmentId);
        $stmt->execute();

        $delete = "
            DELETE
            FROM `assignment`
            WHERE `id_assignment` = ?
        ";

        if (!($stmt = $conn->prepare($delete))) {
            $conn->close();
            throw new \Exception("Could not prepare $delete as a PreparedStatement");
        }

        $stmt->bind_param("i", $assignmentId);
        $stmt->execute();

        $stmt->close();
        $conn->close();
    }

    public function removeFromClassroom(int $classroomId)
    {
        $conn = ConnectionFactory::getConnection();
        $delete = "
            DELETE
            FROM `Assignment`
            WHERE `classroom_id` = ?
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