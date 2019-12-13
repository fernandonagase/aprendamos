<?php
namespace aprendamos\persistence\dao;

interface RankingDAO
{
    public function rankQuestions(int $classroomId);
    public function rankAnswers(int $classroomId);
    public function rankPosts(int $classroomId);
}