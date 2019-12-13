<?php
namespace aprendamos\view\forum;

use aprendamos\lib\mvc\Template;
use aprendamos\lib\mvc\View;

class Index extends View
{
    public function build()
    {
        $postsListTEMPL = $this->loadTemplate('forum/index');

        $posts = $this->models['posts'];
        $classroomId = $this->models['classroom'];

        $postRowsTEMPL = [];
        foreach ($posts as $post) {
            $postRowTEMPL = $this->loadTemplate('forum/index_question');
            $postRowTEMPL->set('postId', $post->getId());
            $postRowTEMPL->set('classroomId', $classroomId);
            $postRowTEMPL->set('title', $post->getTitle());
            $postRowTEMPL->set('author', $post->getAuthor()->getName());
            $postRowTEMPL->set('publicatedDate', $post->getPublicatedDate());
            
            $postRowsTEMPL[] = $postRowTEMPL;
        }

        $postsListTEMPL->set('classroomId', $classroomId);
        $postsListTEMPL->set('questions', Template::merge($postRowsTEMPL));
        
        $this->setTitle('Dúvidas da turma - Aprendamos');
        $this->setContent($postsListTEMPL->resolve());
    }
}