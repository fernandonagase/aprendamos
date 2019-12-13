<?php
namespace aprendamos\view\forum;

use aprendamos\lib\mvc\View;

class Reply extends View
{
    public function build()
    {
        $replyFormTEMPL = $this->loadTemplate('forum/reply');

        $question = $this->models['question'];

        $replyFormTEMPL->set('questionId', $question->getId());
        $replyFormTEMPL->set('title', "Re: {$question->getTitle()}");
        $replyFormTEMPL->set('classroomId', $this->models['classroom']);

        $this->setTitle('Responder a dúvida - Aprendamos');
        $this->setContent($replyFormTEMPL->resolve());
    }
}