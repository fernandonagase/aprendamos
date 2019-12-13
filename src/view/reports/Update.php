<?php
namespace aprendamos\view\reports;

use aprendamos\lib\mvc\Template;
use aprendamos\lib\mvc\View;

class Update extends View
{
    public function build()
    {
        $gradesFormTEMPL = $this->loadTemplate('reports/update');

        $reports = $this->models['reports'];
        $users = $this->models['users'];
        $assignment = $this->models['assignment'];
        $classroom = $this->models['classroom'];

        $gradesTEMPL = [];
        foreach ($reports as $user => $report) {
            $userId = $user;
            $report = $report;

            $gradeTEMPL = $this->loadTemplate('reports/update_grade_form');
            $gradeTEMPL->set('userId', $userId);
            $gradeTEMPL->set('userName', $users[$userId]->getName());
            $gradeTEMPL->set('grade', $report);
            $gradesTEMPL[] = $gradeTEMPL;
        }

        $gradesFormTEMPL->set('gradeForms', Template::merge($gradesTEMPL));
        $gradesFormTEMPL->set('assignmentId', $assignment);
        $gradesFormTEMPL->set('classroomId', $classroom);

        $this->setTitle('Editar correção - Aprendamos');
        $this->setContent($gradesFormTEMPL->resolve());
    }
}