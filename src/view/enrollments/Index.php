<?php
namespace aprendamos\view\enrollments;

use aprendamos\lib\mvc\Template;
use aprendamos\lib\mvc\View;

class Index extends View
{
    public function build()
    {
        $enrollsList = $this->loadTemplate('enrollments/index');

        $enrollments = $this->models['enrollments'];
        $classroomId = $this->models['classroomId'];
        $user = $this->models['user'];

        if ($user->isProfessor()) {
            $removeHeader = $this->loadTemplate('enrollments/index_remove_header');
            $enrollsList->set('removeHeader', $removeHeader->resolve());

            $options = $this->loadTemplate('enrollments/index_options');
            $options->set('classroomId', $classroomId);
            $enrollsList->set('options', $options->resolve());
        } else {
            $enrollsList->set('removeHeader', '');
            $enrollsList->set('options', '');
        }
        
        $enrollRows = [];
        foreach ($enrollments as $enrollment) {
            $enrollRow = $this->loadTemplate('enrollments/index_row');
            $enrollRow->set('name', $enrollment->getUser()->getName());
            $role = $enrollment->isProfessor() ? 'Professor' : 'Aluno';
            $enrollRow->set('role', $role);

            $removeOption = '';
            if ($user->isProfessor()) {
                $removeOption = $this->loadTemplate('enrollments/index_row_remove');
                $removeOption->set('classroomId', $enrollment->getClassroom()->getId());
                $removeOption->set('userId', $enrollment->getUser()->getId());
                $removeOption = $removeOption->resolve();
            }

            $enrollRow->set('removeOption', $removeOption);

            $enrollRows[] = $enrollRow;
        }

        $enrollsList->set('enrollments', Template::merge($enrollRows));

        $this->setTitle('Participantes da turma - Aprendamos');
        $this->setContent($enrollsList->resolve());
    }
}