<?php
namespace aprendamos\view\forum;

use aprendamos\lib\mvc\Template;
use aprendamos\lib\mvc\View;

class Ranking extends View
{
    public function build()
    {
        $rankingTEMPL = $this->loadTemplate('forum/ranking');

        $questions = $this->models['questions'];
        $answers = $this->models['answers'];
        $posts = $this->models['posts'];
        
        $questionRowsTEMPL = [];
        foreach ($questions as $question) {
            $questionRowTEMPL = $this->loadTemplate('forum/ranking_row');
            $questionRowTEMPL->set('name', $question->getName());
            $questionRowTEMPL->set('count', $question->getCount());
            $questionRowsTEMPL[] = $questionRowTEMPL;
        }

        $answerRowsTEMPL = [];
        foreach ($answers as $answer) {
            $answerRowTEMPL = $this->loadTemplate('forum/ranking_row');
            $answerRowTEMPL->set('name', $answer->getName());
            $answerRowTEMPL->set('count', $answer->getCount());
            $answerRowsTEMPL[] = $answerRowTEMPL;
        }

        $postRowsTEMPL = [];
        foreach ($posts as $post) {
            $postRowTEMPL = $this->loadTemplate('forum/ranking_row');
            $postRowTEMPL->set('name', $post->getName());
            $postRowTEMPL->set('count', $post->getCount());
            $postRowsTEMPL[] = $postRowTEMPL;
        }

        $rankingTEMPL->set('questions', Template::merge($questionRowsTEMPL));
        $rankingTEMPL->set('answers', Template::merge($answerRowsTEMPL));
        $rankingTEMPL->set('posts', Template::merge($postRowsTEMPL));

        $this->setTitle('Ranking de publicações - Aprendamos');
        $this->setContent($rankingTEMPL->resolve());
    }
}