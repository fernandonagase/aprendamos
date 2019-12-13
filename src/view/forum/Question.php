<?php
namespace aprendamos\view\forum;

use aprendamos\lib\mvc\Template;
use aprendamos\lib\mvc\View;

class Question extends View
{
    public function build()
    {
        $questionDetailsTEMPL = $this->loadTemplate('forum/question');

        $question = $this->models['question'];
        $answers = $this->models['answers'];

        $questionDetailsTEMPL->set('classroomId', $this->models['classroom']);
        $questionDetailsTEMPL->set('questionId', $question->getId());
        $questionDetailsTEMPL->set('questionTitle', $question->getTitle());
        $questionDetailsTEMPL->set('questionDescription', $question->getContent());

        $answersTEMPL = [];
        foreach ($answers as $answer) {
            $answerTEMPL = $this->loadTemplate('forum/question_answer');
            $answerTEMPL->set('answer', $answer->getContent());
            $answerTEMPL->set('userName', $answer->getAuthor()->getName());

            $answersTEMPL[] = $answerTEMPL;
        }

        $questionDetailsTEMPL->set('answers', Template::merge($answersTEMPL));

        $this->setTitle('Respostas da dúvida - Aprendamos');
        $this->setContent($questionDetailsTEMPL->resolve());
    }
}