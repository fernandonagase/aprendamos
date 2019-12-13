<?php
namespace aprendamos\view\reports;

use aprendamos\lib\mvc\Template;
use aprendamos\lib\mvc\View;

class Create extends View
{
    public function build()
    {
        $createFormTEMPL = $this->loadTemplate('reports/create');

        $enrollments = $this->models['enrollments'];
        $classroom = $enrollments[0]->getClassroom();
        $assignmentId = $this->models['assignmentId'];

        $gradesTEMPL = [];
        foreach ($enrollments as $enrollment) {
            $gradeTEMPL = $this->loadTemplate('reports/create_grade_form');

            $user = $enrollment->getUser();
            if ($enrollment->isProfessor()) {
                continue;
            }

            $gradeTEMPL->set('userId', $user->getId());
            $gradeTEMPL->set('userName', $user->getName());

            $gradesTEMPL[] = $gradeTEMPL;
        }

        $createFormTEMPL->set('classroomId', $classroom->getId());
        $createFormTEMPL->set('assignmentId', $assignmentId);
        $createFormTEMPL->set('gradeForms', Template::merge($gradesTEMPL));

        $this->setTitle('Corrigir atividade - Aprendamos');
        $this->setContent($createFormTEMPL->resolve());
    }
}