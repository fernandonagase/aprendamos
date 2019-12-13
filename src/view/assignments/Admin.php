<?php
namespace aprendamos\view\assignments;

use aprendamos\lib\mvc\Template;
use aprendamos\lib\mvc\View;

class Admin extends View
{
    public function build()
    {
        $assignmentsTEMPL = $this->loadTemplate('assignments/admin');

        $reports = $this->models['reports'];
        $classroomId = $this->models['classroom'];

        $assignmentsTEMPL->set('classroomId', $this->models['classroom']);

        $reportRows = [];
        foreach ($reports as $report) {
            $reportRow = $this->loadTemplate('assignments/admin_row');

            $assignment = $report->getAssignment();

            $reportRow->set('assignmentId', $assignment->getId());
            $reportRow->set('assignmentName', $assignment->getName());
            $reportRow->set('classroomId', $classroomId);
            $reportRow->set('assignmentDeadline', $assignment->getDeadline());
            $reportRow->set('assignmentStatus', $assignment->getStatus());

            $reportRows[] = $reportRow;
        }

        $assignmentsTEMPL->set('assignments', Template::merge($reportRows));

        $this->setTitle('Gerenciamento de atividades - Aprendamos');
        $this->setContent($assignmentsTEMPL->resolve());
    }
}