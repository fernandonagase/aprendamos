<?php
namespace aprendamos\persistence\dao\mariadb;

use aprendamos\persistence\dao\PostDAO;
use aprendamos\model\Post;
use aprendamos\persistence\ConnectionFactory;

class PostDAOimpl implements PostDAO
{
    public function findQuestions(int $classroomId)
    {
        $enrollmentDao = new EnrollmentDAOimpl();

        $conn = ConnectionFactory::getConnection();
        $select = "
            SELECT `id_post`,
                    `title`,
                    `content`,
                    `publicated_date`,
                    `edited_date`,
                    `user_id`
            FROM `post`
            WHERE `classroom_id` = ?
              AND `post_id` IS NULL
        ";

        if (!($stmt = $conn->prepare($select))) {
            $conn->close();
            throw new \Exception("Could not prepare $select as a PreparedStatement");
        }

        $stmt->bind_param("i", $classroomId);
        $stmt->execute();

        $stmt->bind_result($id, $title, $content, $publicatedDate, $editedDate, $userId);

        $posts = [];
        while ($stmt->fetch()) {
            $author = $enrollmentDao->findById($userId, $classroomId)->getUser();

            $post = new Post($title, $content, $publicatedDate, $author);
            $post->setId($id);
            $post->setEditedDate($editedDate);
            $posts[] = $post;
        }

        $stmt->close();
        $conn->close();

        return $posts;
    }

    public function findQuestionById(int $postId)
    {
        $conn = ConnectionFactory::getConnection();
        $select = "
            SELECT `title`,
                    `content`,
                    `publicated_date`,
                    `edited_date`,
                    `user_id`,
                    `classroom_id`
            FROM `post`
            WHERE `id_post` = ?
        ";

        if (!($stmt = $conn->prepare($select))) {
            $conn->close();
            throw new \Exception("Could not prepare $select as a PreparedStatement");
        }

        $stmt->bind_param("i", $postId);
        $stmt->execute();

        $stmt->bind_result(
            $title,
            $content,
            $publicatedDate,
            $editedDate,
            $userId,
            $classroomId
        );
        
        $post = null;
        if ($stmt->fetch()) {
            $enrollmentDao = new EnrollmentDAOimpl();
            $author = $enrollmentDao->findById($userId, $classroomId)->getUser();

            $post = new Post($title, $content, $publicatedDate, $author);
            $post->setId($postId);
            $post->setEditedDate($editedDate);
        }
        
        $stmt->close();
        $conn->close();

        return $post;
    }

    public function findReplies(int $postId)
    {
        $conn = ConnectionFactory::getConnection();
        $select = "
            SELECT `id_post`,
                    `title`,
                    `content`,
                    `publicated_date`,
                    `edited_date`,
                    `user_id`,
                    `classroom_id`
            FROM `post`
            WHERE `post_id` = ?
        ";

        if (!($stmt = $conn->prepare($select))) {
            $conn->close();
            throw new \Exception("Could not prepare $select as a PreparedStatement");
        }

        $stmt->bind_param("i", $postId);
        $stmt->execute();

        $stmt->bind_result(
            $id,
            $title,
            $content,
            $publicatedDate,
            $editedDate,
            $userId,
            $classroomId
        );

        $replies = [];
        while ($stmt->fetch()) {
            $enrollmentDao = new EnrollmentDAOimpl();
            $author = $enrollmentDao->findById($userId, $classroomId)->getUser();

            $reply = new Post($title, $content, $publicatedDate, $author);
            $reply->setId($id);
            $reply->setEditedDate($editedDate);

            $replies[] = $reply;
        }

        $stmt->close();
        $conn->close();

        return $replies;
    }

    public function addQuestion(Post $post, int $classroomId)
    {
        $conn = ConnectionFactory::getConnection();
        $insert = "
            INSERT INTO `Post` (`title`, `content`, `user_id`, `classroom_id`, `publicated_date`)
            VALUES (?, ?, ?, ?, ?)
        ";

        if (!($stmt = $conn->prepare($insert))) {
            $conn->close();
            throw new \Exception("Could not prepare $insert as a PreparedStatement");
        }

        $title = $post->getTitle();
        $description = $post->getContent();
        $userId = $post->getAuthor()->getId();
        $publicatedDate = $post->getPublicatedDate();

        $stmt->bind_param("ssiis", $title, $description, $userId, $classroomId, $publicatedDate);
        $stmt->execute();

        $stmt->close();
        $conn->close();
    }

    public function addReply(Post $post, int $questionId, int $classroomId)
    {
        $conn = ConnectionFactory::getConnection();
        $insert = "
            INSERT INTO `Post` (`title`, `content`, `post_id`, `user_id`, `classroom_id`, `publicated_date`)
            VALUES (?, ?, ?, ?, ?, ?)
        ";

        if (!($stmt = $conn->prepare($insert))) {
            $conn->close();
            throw new \Exception("Could not prepare $insert as a PreparedStatement");
        }

        $title = $post->getTitle();
        $description = $post->getContent();
        $userId = $post->getAuthor()->getId();
        $publicatedDate = $post->getPublicatedDate();

        $stmt->bind_param("ssiiis", $title, $description, $questionId, $userId, $classroomId, $publicatedDate);
        $stmt->execute();

        $stmt->close();
        $conn->close();
    }

    public function updateQuestion(Post $post)
    {
        throw new \Exception('Not implemented');
    }

    public function removeFromClassroom(int $classroomId)
    {
        $conn = ConnectionFactory::getConnection();

        // Deletes all answers from the classroom
        $delete = "
            DELETE FROM `Post`
            WHERE `post_id` IS NOT NULL
            AND `classroom_id` = ?
        ";

        if (!($stmt = $conn->prepare($delete))) {
            $conn->close();
            throw new \Exception("Could not prepare $delete as a PreparedStatement");
        }

        $stmt->bind_param("i", $classroomId);
        if ($stmt->execute() === false) {
            $stmt->close();
            $conn->rollback();
            $conn->close();
            throw new \Exception('Error while deleting posts');
        }

        // Deletes all questions from the classroom
        $delete = "
            DELETE FROM `Post`
            WHERE `classroom_id` = ?
        ";
        
        if (!($stmt = $conn->prepare($delete))) {
            $conn->close();
            throw new \Exception("Could not prepare $delete as a PreparedStatement");
        }

        $stmt->bind_param("i", $classroomId);
        if ($stmt->execute() === false) {
            $stmt->close();
            $conn->rollback();
            $conn->close();
            throw new \Exception('Error while deleting posts');
        }
        
        $stmt->close();
        $conn->close();
    }

    public function removeFromEnrollment(int $userId, int $classroomId)
    {
        $conn = ConnectionFactory::getConnection();

        // Deletes all answers from the enrollment
        $delete = "
            DELETE FROM `Post`
            WHERE `post_id` IS NOT NULL
              AND `user_id` = ?
              AND `classroom_id` = ?
        ";

        if (!($stmt = $conn->prepare($delete))) {
            $conn->close();
            throw new \Exception("Could not prepare $delete as a PreparedStatement");
        }

        $stmt->bind_param("ii", $userId, $classroomId);
        if ($stmt->execute() === false) {
            $stmt->close();
            $conn->rollback();
            $conn->close();
            throw new \Exception('Error while deleting posts');
        }

        // Deletes all questions from the enrollment
        $delete = "
            DELETE FROM `Post`
            WHERE `user_id` = ?
              AND `classroom_id` = ?
        ";
        
        if (!($stmt = $conn->prepare($delete))) {
            $conn->close();
            throw new \Exception("Could not prepare $delete as a PreparedStatement");
        }

        $stmt->bind_param("ii", $userId, $classroomId);
        if ($stmt->execute() === false) {
            $stmt->close();
            $conn->rollback();
            $conn->close();
            throw new \Exception('Error while deleting posts');
        }
        
        $stmt->close();
        $conn->close();
    }
}