<?php
namespace aprendamos\view\classrooms;

use aprendamos\lib\mvc\View;

class Create extends View
{
    public function build()
    {
        $classroomForm = $this->loadTemplate('classrooms/create');

        $this->setTitle('Nova turma - Aprendamos');
        $this->setContent($classroomForm->resolve());
    }
}