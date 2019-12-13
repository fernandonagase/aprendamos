<?php
namespace aprendamos\persistence\dao;

use aprendamos\model\Post;

interface PostDAO
{
    public function findQuestions(int $classroomId);
    public function findQuestionById(int $postId);
    public function findReplies(int $postId);
    public function addQuestion(Post $post, int $classroomId);
    public function addReply(Post $post, int $questionId, int $classroomId);
    public function updateQuestion(Post $post);
    public function removeFromClassroom(int $classroomId);
    public function removeFromEnrollment(int $userId, int $classroomId);
}