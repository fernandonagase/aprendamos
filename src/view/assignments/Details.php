<?php
namespace aprendamos\view\assignments;

use aprendamos\lib\mvc\View;

class Details extends View
{
    public function build()
    {
        $assignmentDetailsTEMPL = $this->loadTemplate('assignments/details');

        $assignment = $this->models['assignment'];
        $classroomId = $this->models['classroom'];
        $user = $this->models['user'];

        if ($user->isProfessor()) {
            $options = $this->loadTemplate('assignments/details_options');
            $options->set('assignmentId', $assignment->getId());
            $options->set('classroomId', $classroomId);
            $assignmentDetailsTEMPL->set('options', $options->resolve());
        } else {
            $assignmentDetailsTEMPL->set('options', '');
        }

        $assignmentDetailsTEMPL->set('assignmentName', $assignment->getName());
        $assignmentDetailsTEMPL->set('assignmentDescription', $assignment->getDescription());
        $assignmentDetailsTEMPL->set('assignmentDeadline', $assignment->getDeadline());
        $assignmentDetailsTEMPL->set('assignmentStatus', $assignment->getStatus());

        $this->setTitle('Detalhes da atividade');
        $this->setContent($assignmentDetailsTEMPL->resolve());
    }
}