<?php
namespace aprendamos\persistence\dao;

use aprendamos\model\Classroom;

interface ClassroomDAO
{
    public function findAll();
    public function findById(int $id): ?Classroom;
    public function addClassroom(Classroom $classroom);
    public function updateClassroom(Classroom $classroom);
    public function removeClassroom(int $classroomId);
}