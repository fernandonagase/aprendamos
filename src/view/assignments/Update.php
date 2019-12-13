<?php
namespace aprendamos\view\assignments;

use aprendamos\lib\mvc\View;

class Update extends View
{
    public function build()
    {
        $assignmentFormTEMPL = $this->loadTemplate('assignments/update');

        $assignment = $this->models['assignment'];

        $assignmentFormTEMPL->set('assignmentId', $assignment->getId());
        $assignmentFormTEMPL->set('assignmentName', $assignment->getName());
        $assignmentFormTEMPL->set('assignmentDescription', $assignment->getDescription());
        $assignmentFormTEMPL->set('assignmentDeadline', $assignment->getDeadline());
        $assignmentFormTEMPL->set('classroomId', $this->models['classroom']);

        $this->setTitle('Editar turma - Aprendamos');
        $this->setContent($assignmentFormTEMPL->resolve());
    }
}