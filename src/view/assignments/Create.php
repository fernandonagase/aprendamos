<?php
namespace aprendamos\view\assignments;

use aprendamos\lib\mvc\View;

class Create extends View
{
    public function build()
    {
        $assignmentFormTEMPL = $this->loadTemplate('assignments/create');

        $assignmentFormTEMPL->set('classroomId', $this->models['classroom']);

        $this->setTitle('Nova atividade - Aprendamos');
        $this->setContent($assignmentFormTEMPL->resolve());
    }
}