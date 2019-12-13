<?php
namespace aprendamos\view\forum;

use aprendamos\lib\mvc\View;

class Update extends View
{
    public function build()
    {
        $updateFormTEMPL = $this->loadTemplate('forum/update');

        $updateFormTEMPL->set('classroomId', $this->models['classroom']);

        $this->setTitle('Editar dúvida - Aprendamos');
        $this->setContent($updateFormTEMPL->resolve());
    }
}