<?php
namespace aprendamos\view\classrooms;

use aprendamos\lib\mvc\Template;
use aprendamos\lib\mvc\View;

class Index extends View
{
    public function build()
    {
        $classroomListTEMPL = $this->loadTemplate('classrooms/index');

        $classroomRows = [];
        foreach ($this->models['enrollments'] as $enrollment) {
            $classroom = $enrollment->getClassroom();

            $classroomRowTEMPL = $this->loadTemplate('classrooms/index_row');
            $classroomRowTEMPL->set('classroomId', $classroom->getId());
            $classroomRowTEMPL->set('classroomName', $classroom->getName());
            $classroomRowTEMPL->set('classroomDescription', $classroom->getDescription());
            $userRole = 'Aluno';
            if ($enrollment->isProfessor()) {
                $userRole = 'Professor';
            }
            $classroomRowTEMPL->set('userRole', $userRole);

            $classroomRows[] = $classroomRowTEMPL;
        }

        $classroomListTEMPL->set('classrooms', Template::merge($classroomRows));

        $this->setTitle('Minhas turmas - Aprendamos');
        $this->setContent($classroomListTEMPL->resolve());
    }
}