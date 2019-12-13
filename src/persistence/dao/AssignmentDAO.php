<?php
namespace aprendamos\persistence\dao;

use aprendamos\model\Assignment;

interface AssignmentDAO
{
    public function findByClassroom(int $classroomId);
    public function findById(int $id);
    public function addAssignment(Assignment $assignment, int $authorId, int $classroomId);
    public function updateAssignment(Assignment $assignment);
    public function removeAssignment(int $assignmentId);
    public function removeFromClassroom(int $classroomId);
}