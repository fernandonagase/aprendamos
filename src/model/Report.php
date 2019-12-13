<?php
namespace aprendamos\model;

class Report
{
    private $grade;
    private $assignment;

    public function __construct(?float $grade, Assignment $assignment)
    {
        $this->grade = $grade;
        $this->assignment = $assignment;
    }

    public function getGrade()
    {
        return $this->grade;
    }

    public function getAssignment()
    {
        return $this->assignment;
    }
}