<?php
namespace aprendamos\persistence\dao;

use aprendamos\model\Enrollment;

interface EnrollmentDAO
{
    public function findById(int $userId, int $classroomId): ?Enrollment;
    public function findByClassroom(int $classroomId);
    public function findByUser(int $userId);
    public function addEnrollment(int $userId, int $classroomId, bool $professor);
    public function removeEnrollment(int $userId, int $classroomId);
    public function removeFromClassroom(int $classroomId);
}