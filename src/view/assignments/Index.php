<?php
namespace aprendamos\view\assignments;

use aprendamos\lib\mvc\Template;
use aprendamos\lib\mvc\View;

class Index extends View
{
    public function build()
    {
        $assignListTEMPL = $this->loadTemplate('assignments/index');

        $reports = $this->models['reports'];
        $classroomId = $this->models['classroom'];

        $reportRows = [];
        foreach ($reports as $report) {
            $reportRow = $this->loadTemplate('assignments/index_row');

            $assignment = $report->getAssignment();

            $reportRow->set('assignmentId', $assignment->getId());
            $reportRow->set('assignmentName', $assignment->getName());
            $reportRow->set('classroomId', $classroomId);
            $reportRow->set('assignmentDeadline', $assignment->getDeadline());
            $reportRow->set('assignmentStatus', $assignment->getStatus());
            $reportRow->set('assignmentGrade', $report->getGrade() ?: '-');

            $reportRows[] = $reportRow;
        }

        $assignListTEMPL->set('assignments', Template::merge($reportRows));

        $this->setTitle('Atividades da turma - Aprendamos');
        $this->setContent($assignListTEMPL->resolve());
    }
}