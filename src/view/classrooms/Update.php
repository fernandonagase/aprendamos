<?php
namespace aprendamos\view\classrooms;

use aprendamos\lib\mvc\View;

class Update extends View
{
    public function build()
    {
        $classroom = $this->models['classroom'];
        $classroomFormTEMPL = $this->loadTemplate('classrooms/update');
        $classroomFormTEMPL->set('classroomId', $classroom->getId());
        $classroomFormTEMPL->set('classroomName', $classroom->getName());
        $classroomFormTEMPL->set('classroomDescription', $classroom->getDescription());

        $this->setTitle('Editar turma - Aprendamos');
        $this->setContent($classroomFormTEMPL->resolve());
    }
}