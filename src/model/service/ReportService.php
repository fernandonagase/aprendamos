<?php
namespace aprendamos\model\service;

use aprendamos\model\Report;
use aprendamos\persistence\dao\ReportDAO;
use aprendamos\lib\Authenticator;

class ReportService
{
    private $dao;

    public function __construct(ReportDAO $dao)
    {
        $this->dao = $dao;
    }

    public function reportsByEnrollment(int $userId, int $classroomId)
    {
        return $this->dao->findByEnrollment($userId, $classroomId);
    }

    public function reportsByAssignment(int $assignmentId)
    {
        return $this->dao->findByAssignment($assignmentId);
    }

    public function namedReportsByAssignment(int $assignmentId)
    {
        return $this->dao->findNamedByAssignment($assignmentId);
    }

    public function reportsFromAssignment(
        $reports,
        int $assignmentId,
        int $classroomId
    ) {
        $this->dao->addReports($reports, $assignmentId, $classroomId);
    }

    public function updateBatch($reports, int $assignmentId, int $classroomId)
    {
        $this->dao->updateBatch($reports, $assignmentId, $classroomId);
    }
}