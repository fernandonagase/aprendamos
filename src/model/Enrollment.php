<?php
namespace aprendamos\model;

class Enrollment
{
    private $user;
    private $classroom;
    private $professor;

    public function __construct(
        User $user,
        Classroom $classroom,
        bool $professor
    ) {
        $this->user = $user;
        $this->classroom = $classroom;
        $this->professor = $professor;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getClassroom(): Classroom
    {
        return $this->classroom;
    }

    public function isProfessor(): bool
    {
        return $this->professor;
    }
}