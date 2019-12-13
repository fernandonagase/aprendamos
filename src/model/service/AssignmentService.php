<?php
namespace aprendamos\model\service;

use aprendamos\model\Assignment;
use aprendamos\persistence\dao\AssignmentDAO;
use aprendamos\lib\Authenticator;

class AssignmentService
{
    private $dao;

    public function __construct(AssignmentDAO $dao)
    {
        $this->dao = $dao;
    }

    public function newAssignment(
        string $name,
        string $description,
        $deadline,
        int $classroomId
    ) {
        $assignment = new Assignment($name, $description, $deadline);
        $this->dao->addAssignment(
            $assignment,
            Authenticator::currentUser(),
            $classroomId
        );
    }

    public function updateAssignment(
        int $id,
        string $name,
        string $description,
        $deadline
    ) {
        $assignment = new Assignment($name, $description, $deadline);
        $assignment->setId($id);
        $this->dao->updateAssignment($assignment);
    }

    public function removeAssignment(int $id)
    {
        $this->dao->removeAssignment($id);
    }

    public function findById(int $id)
    {
        return $this->dao->findById($id);
    }

    public function finishAssignment(int $id)
    {
        $assignment = $this->dao->findById($id);
        $assignment->setStatus('terminado');
        $this->dao->updateAssignment($assignment);
    }
}