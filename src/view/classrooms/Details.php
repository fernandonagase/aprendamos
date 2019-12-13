<?php
namespace aprendamos\view\classrooms;

use aprendamos\lib\mvc\Template;
use aprendamos\lib\mvc\View;

class Details extends View
{
    public function build()
    {
        $classroomDetailsTEMPL = $this->loadTemplate('classrooms/details');

        $classroom = $this->models['classroom'];
        $reports = $this->models['reports'];
        $enrollments = $this->models['enrollments'];
        $posts = $this->models['posts'];
        $user = $this->models['user'];

        $classroomDetailsTEMPL->set('classroomId', $classroom->getId());
        $classroomDetailsTEMPL->set('className', $classroom->getName());
        if ($user->isProfessor()) {
            $options = $this->loadTemplate('classrooms/details_options');
            $options->set('classroomId', $classroom->getId());
            $classroomDetailsTEMPL->set('classroomId', $classroom->getId());
            $classroomDetailsTEMPL->set('options', $options->resolve());
        } else {
            $classroomDetailsTEMPL->set('options', '');
        }

        $enrollRows = [];
        foreach ($enrollments as $enrollment) {
            $enrollRow = $this->loadTemplate('classrooms/details_enroll_row');
            $enrollRow->set('name', $enrollment->getUser()->getName());
            $enrollRows[] = $enrollRow;
        }

        $assignmentRows = [];
        foreach ($reports as $report) {
            $assignment = $report->getAssignment();

            $assignmentRow = $this->loadTemplate('classrooms/details_assignment_row');
            $assignmentRow->set('assignmentName', $assignment->getName());
            $assignmentRow->set('assignmentDeadline', $assignment->getDeadline());
            $assignmentRow->set('assignmentStatus', $assignment->getStatus());
            $assignmentRows[] = $assignmentRow;
        }

        $postRows = [];
        foreach ($posts as $post) {
            $postRow = $this->loadTemplate('classrooms/details_question_row');
            
            $postRow->set('question', $post->getTitle());
            $postRow->set('author', $post->getAuthor()->getName());
            $postRow->set('date', $post->getPublicatedDate());

            $postRows[] = $postRow;
        }

        $classroomDetailsTEMPL->set('enrollments', Template::merge($enrollRows));
        $classroomDetailsTEMPL->set('assignments', Template::merge($assignmentRows));
        $classroomDetailsTEMPL->set('posts', Template::merge($postRows));

        $this->setTitle('Detalhes da turma');
        $this->setContent($classroomDetailsTEMPL->resolve());
    }
}