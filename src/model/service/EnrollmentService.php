<?php
namespace aprendamos\model\service;

use aprendamos\persistence\dao\EnrollmentDAO;
use aprendamos\persistence\dao\mariadb\UserDAOimpl;

class EnrollmentService
{
    private $dao;
    private $userDao;

    public function __construct(EnrollmentDAO $dao)
    {
        $this->dao = $dao;
        // Definitely not the better way to implement Dependency Injection
        $this->userDao = new UserDAOimpl();
    }

    public function addStudent(string $username, int $classroomId)
    {
        $user = $this->userDao->findByUsername($username);
        $this->dao->addEnrollment($user->getId(), $classroomId, false);
    }

    public function removeStudent(int $userId, int $classroomId)
    {
        $this->dao->removeEnrollment($userId, $classroomId);
    }

    public function findByClassroom(int $classroomId)
    {
        return $this->dao->findByClassroom($classroomId);
    }

    public function findByUser(int $userId)
    {
        return $this->dao->findByUser($userId);
    }

    public function findEnrollment(int $userId, int $classroomId)
    {
        return $this->dao->findById($userId, $classroomId);
    }
}