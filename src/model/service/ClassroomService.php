<?php
namespace aprendamos\model\service;

use aprendamos\persistence\dao\ClassroomDAO;
use aprendamos\model\Classroom;

class ClassroomService
{
    private $dao;

    public function __construct(ClassroomDAO $dao)
    {
        $this->dao = $dao;
    }

    public function createClassroom(string $name, string $description)
    {
        $classroom = new Classroom($name, $description);
        $this->dao->addClassroom($classroom);
    }

    public function updateClassroom(int $id, string $name, string $description)
    {
        $classroom = new Classroom($name, $description);
        $classroom->setId($id);
        $this->dao->updateClassroom($classroom);
    }

    public function findClassroom(int $id)
    {
        return $this->dao->findById($id);
    }

    public function removeClassroom(int $id)
    {
        $this->dao->removeClassroom($id);
    }
}