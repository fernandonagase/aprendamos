<?php
namespace aprendamos\persistence\dao;

use aprendamos\model\Report;

interface ReportDAO
{
    public function findByAssignment(int $assignmentId);
    public function findByEnrollment(int $userId, int $classroomId);
    public function addReports($reports, int $assignmentId, int $classroomId);
    public function updateReport(Report $report);
    public function updateBatch($reports, int $assignmentId, int $classroomId);
    public function removeFromClassroom(int $classroomId);
    public function removeFromEnrollment(int $userId, int $classroomId);
}