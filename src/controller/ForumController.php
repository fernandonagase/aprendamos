<?php
namespace aprendamos\controller;

use aprendamos\lib\mvc\Controller;
use aprendamos\model\service\EnrollmentService;
use aprendamos\model\service\ForumService;
use aprendamos\persistence\dao\mariadb\EnrollmentDAOimpl;
use aprendamos\persistence\dao\mariadb\PostDAOimpl;
use aprendamos\persistence\dao\mariadb\RankingDAOimpl;
use aprendamos\view\forum\Create;
use aprendamos\view\forum\Index;
use aprendamos\view\forum\Question;
use aprendamos\view\forum\Ranking;
use aprendamos\view\forum\Reply;
use aprendamos\view\forum\Update;
use aprendamos\lib\Authenticator;

class ForumController extends Controller
{
    private $service;
    private $enrollmentService;

    public function __construct()
    {
        parent::__construct();
        $this->service = new ForumService(
            new PostDAOimpl(),
            new RankingDAOimpl()
        );
        $this->enrollmentService = new EnrollmentService(new EnrollmentDAOimpl());
    }

    public function index(int $id = null, string $subAction = null)
    {   
        $classroomId = $_GET['classroom'];

        $method = $this->getMethod();

        if ($subAction === 'reply') {
            if ($method === 'GET') {
                $view = new Reply();
                $view->setModel('classroom', $classroomId);
                $view->setModel('question', $this->service->findQuestion($id));
                $view->render();
            } else if ($method === 'POST') {
                $user = $this->enrollmentService->findEnrollment(
                    Authenticator::currentUser(),
                    $classroomId
                )->getUser();
                $this->service->replyQuestion(
                    $_POST['title'],
                    $_POST['description'],
                    $user,
                    $classroomId,
                    $id
                );
                $this->redirectToAction("index", "$id?classroom=$classroomId");
            }
            return;
        }

        if ($id !== null) {
            $view = new Question();
            $view->setModel('question', $this->service->findQuestion($id));
            $view->setModel('answers', $this->service->findReplies($id));
            $view->setModel('classroom', $classroomId);
            $view->render();
            return;
        }

        $posts = $this->service->questionsInClassroom($classroomId);

        $view = new Index();
        $view->setModel('classroom', $classroomId);
        $view->setModel('posts', $posts);
        $view->render();
    }

    public function create()
    {
        $method = $this->getMethod();

        $classroomId = $_GET['classroom'];

        if ($method === 'GET') {
            $view = new Create();
            $view->setModel('classroom', $classroomId);
            $view->render();
        } else if ($method === 'POST') {
            $user = $this->enrollmentService->findEnrollment(
                Authenticator::currentUser(),
                $classroomId
            )->getUser();

            $this->service->newQuestion($_POST['title'], $_POST['description'], $user, $classroomId);
            $this->redirectToAction("index?classroom=$classroomId");
        }
    }

    public function update(int $id)
    {
        $method = $this->getMethod();

        $classroomId = $_GET['classroom'];

        if ($method === 'GET') {
            $view = new Update();
            $view->setModel('classroom', $classroomId);
            $view->render();
        }
    }

    public function ranking()
    {
        $classroomId = $_GET['classroom'];

        $questionsRanking = $this->service->questionsRanking($classroomId);
        $answersRanking = $this->service->answersRanking($classroomId);
        $postsRanking = $this->service->postsRanking($classroomId);

        $view = new Ranking();
        $view->setModel('questions', $questionsRanking);
        $view->setModel('answers', $answersRanking);
        $view->setModel('posts', $postsRanking);
        $view->render();
    }
}