<?php
namespace aprendamos\model\service;

use aprendamos\persistence\dao\PostDAO;
use aprendamos\persistence\dao\RankingDAO;
use aprendamos\model\Post;
use aprendamos\model\User;

class ForumService
{
    private $dao;
    private $rankingDao;

    public function __construct(PostDAO $dao, RankingDAO $rankingDao)
    {
        $this->dao = $dao;
        $this->rankingDao = $rankingDao;
    }

    public function newQuestion(
        string $title,
        string $description,
        User $user,
        int $classroomId
    ) {
        $post = new Post($title, $description, date("Y-m-d"), $user);
        $this->dao->addQuestion($post, $classroomId);
    }

    public function replyQuestion(
        string $title,
        string $description,
        User $user,
        int $classroomId,
        int $questionId
    ) {
        $post = new Post($title, $description, date("Y-m-d"), $user);
        $this->dao->addReply($post, $questionId, $classroomId);
    }

    public function questionsInClassroom(int $classroomId)
    {
        return $this->dao->findQuestions($classroomId);
    }

    public function findQuestion(int $id)
    {
        $post = $this->dao->findQuestionById($id);
        return $post;
    }

    public function findReplies(int $id)
    {
        return $this->dao->findReplies($id);
    }

    public function questionsRanking(int $classroomId)
    {
        return $this->rankingDao->rankQuestions($classroomId);
    }

    public function answersRanking(int $classroomId)
    {
        return $this->rankingDao->rankAnswers($classroomId);
    }

    public function postsRanking(int $classroomId)
    {
        return $this->rankingDao->rankPosts($classroomId);
    }
}