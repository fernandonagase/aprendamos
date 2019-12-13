<?php
namespace aprendamos\persistence\dao\mariadb;

use aprendamos\persistence\dao\ReportDAO;
use aprendamos\model\Report;
use aprendamos\model\Assignment;
use aprendamos\persistence\ConnectionFactory;

class ReportDAOimpl implements ReportDAO
{
    public function findByAssignment(int $assignmentId)
    {
        $conn = ConnectionFactory::getConnection();
        $select = "
            SELECT `user_id`,
                `grade`
            FROM `Report`
            WHERE `assignment_id` = ?
        ";

        if (!($stmt = $conn->prepare($select))) {
            $conn->close();
            throw new \Exception("Could not prepare $select as a PreparedStatement");
        }

        $stmt->bind_param("i", $assignmentId);
        $stmt->execute();

        $stmt->bind_result(
            $userId,
            $grade
        );

        $reports = [];
        while ($stmt->fetch()) {
            $reports[$userId] = $grade;
        }

        $stmt->close();
        $conn->close();

        return $reports;
    }

    public function findNamedByAssignment(int $assignmentId)
    {
        $conn = ConnectionFactory::getConnection();
        $select = "
            SELECT u.`name`,
                    r.`grade`
            FROM `Report` r
            INNER JOIN `User` u ON r.`user_id` = u.`id_user`
            WHERE r.`assignment_id` = ?
        ";

        if (!($stmt = $conn->prepare($select))) {
            $conn->close();
            throw new \Exception("Could not prepare $select as a PreparedStatement");
        }

        $stmt->bind_param("i", $assignmentId);
        $stmt->execute();

        $stmt->bind_result(
            $name,
            $grade
        );

        $reports = [];
        while ($stmt->fetch()) {
            $reports[$name] = $grade;
        }

        $stmt->close();
        $conn->close();

        return $reports;
    }

    public function findByEnrollment(int $userId, int $classroomId)
    {
        $conn = ConnectionFactory::getConnection();
        $select = "
            SELECT a.`id_assignment`,
                    a.`name`,
                    a.`description`,
                    a.`deadline`,
                    a.`status`,
                    r.`grade`
            FROM `Assignment` a
            LEFT JOIN `Report` r ON a.`id_assignment` = r.`assignment_id`
            AND r.`user_id` = ?
            WHERE a.`classroom_id` = ?
        ";

        if (!($stmt = $conn->prepare($select))) {
            $conn->close();
            throw new \Exception("Could not prepare $select as a PreparedStatement");
        }

        $stmt->bind_param("ii", $userId, $classroomId);
        $stmt->execute();

        $stmt->bind_result(
            $id,
            $name,
            $description,
            $deadline,
            $status,
            $grade
        );

        $reports = [];
        while ($stmt->fetch()) {
            $assignment = new Assignment($name, $description, $deadline);
            $assignment->setId($id);
            $assignment->setStatus($status);

            $report = new Report($grade, $assignment);
            $reports[] = $report;
        }

        $stmt->close();
        $conn->close();

        return $reports;
    }

    public function addReports(
        $reports,
        int $assignmentId,
        int $classroomId
    ) {
        $conn = ConnectionFactory::getConnection();
        $insert = "
            INSERT INTO `Report` (`user_id`, `classroom_id`, `assignment_id`, `grade`)
            VALUES (?, ?, ?, ?)
        ";

        if (!($stmt = $conn->prepare($insert))) {
            $conn->close();
            throw new \Exception("Could not prepare $insert as a PreparedStatement");
        }

        $stmt->bind_param("iiid", $user, $classroomId, $assignmentId, $grade);

        foreach ($reports as $userId => $userGrade) {
            $user = $userId;
            $grade = $userGrade;
            $stmt->execute();
        }

        $stmt->close();
        $conn->close();
    }

    public function updateReport(Report $report)
    {
        throw new \Exception('Not implemented');
    }

    public function updateBatch($reports, int $assignmentId, int $classroomId)
    {
        $conn = ConnectionFactory::getConnection();
        $update = "
            UPDATE `report`
            SET `grade` = ?
            WHERE `user_id`= ?
              AND `classroom_id` = ?
              AND `assignment_id` = ?
        ";

        if (!($stmt = $conn->prepare($update))) {
            $conn->close();
            throw new \Exception("Could not prepare $update as a PreparedStatement");
        }

        $stmt->bind_param("diii", $userGrade, $userId, $classroomId, $assignmentId);
        
        foreach ($reports as $user => $grade) {
            $userId = $user;
            $userGrade = $grade;
            
            $stmt->execute();
        }

        $stmt->close();
        $conn->close();
    }

    public function removeFromClassroom(int $classroomId)
    {
        $conn = ConnectionFactory::getConnection();
        $delete = "
            DELETE
            FROM `Report`
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

    public function removeFromEnrollment(int $userId, int $classroomId)
    {
        $conn = ConnectionFactory::getConnection();
        $delete = "
            DELETE
            FROM `Report`
            WHERE `user_id` = ?
              AND `classroom_id` = ?
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
}