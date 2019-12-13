<?php
namespace aprendamos\view\forum;

use aprendamos\lib\mvc\View;

class Create extends View
{
    public function build()
    {
        $postForm = $this->loadTemplate('forum/create');

        $postForm->set('classroomId', $this->models['classroom']);

        $this->setTitle('Nova dúvida - Aprendamos');
        $this->setContent($postForm->resolve());
    }
}