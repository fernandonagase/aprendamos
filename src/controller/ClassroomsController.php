<?php
namespace aprendamos\controller;

use aprendamos\lib\mvc\Controller;
use aprendamos\persistence\dao\mariadb\ClassroomDAOimpl;
use aprendamos\persistence\dao\mariadb\EnrollmentDAOimpl;
use aprendamos\persistence\dao\mariadb\PostDAOimpl;
use aprendamos\persistence\dao\mariadb\ReportDAOimpl;
use aprendamos\persistence\dao\mariadb\RankingDAOimpl;
use aprendamos\model\service\ClassroomService;
use aprendamos\model\service\EnrollmentService;
use aprendamos\model\service\ForumService;
use aprendamos\model\service\ReportService;
use aprendamos\view\classrooms\Create;
use aprendamos\view\classrooms\Details;
use aprendamos\view\classrooms\Index;
use aprendamos\view\classrooms\Update;
use aprendamos\lib\Authenticator;
use aprendamos\lib\Path;

class ClassroomsController extends Controller
{
    private $service;
    private $enrollmentService;
    private $forumService;
    private $reportService;

    // Maybe not the better way to implement Dependency Injection
    public function __construct()
    {
        parent::__construct();
        $this->service = new ClassroomService(new ClassroomDAOimpl());
        $this->enrollmentService = new EnrollmentService(new EnrollmentDAOimpl());
        $this->forumService = new ForumService(new PostDAOimpl(), new RankingDAOimpl());
        $this->reportService = new ReportService(new ReportDAOimpl());
    }

    public function index(int $id = null)
    {
        if (!Authenticator::authorize()) {
            http_response_code(401);
            return;
        }

        if ($id !== null) {
            $classroom = $this->service->findClassroom($id);
            $user = $this->enrollmentService->findEnrollment(
                Authenticator::currentUser(),
                $id
            );
            $enrollments = $this->enrollmentService->findByClassroom($id);
            $posts = $this->forumService->questionsInClassroom($id);
            $reports = $this->reportService->reportsByEnrollment($user->getUser()->getId(), $user->getClassroom()->getId());

            $view = new Details();
            $view->setModel('classroom', $classroom);
            $view->setModel('enrollments', $enrollments);
            $view->setModel('posts', $posts);
            $view->setModel('reports', $reports);
            $view->setModel('user', $user);
            $view->render();
        } else {
            $enrollments = $this->enrollmentService->findByUser(
                Authenticator::currentUser()
            );

            $view = new Index();
            $view->setModel('enrollments', $enrollments);
            $view->render();
        }
    }

    public function create()
    {
        $method = $this->getMethod();
        if ($method === 'GET') {
            $view = new Create();
            $view->render();
        } else if ($method === 'POST') {
            $this->service->createClassroom($_POST['name'], $_POST['description']);
            $this->redirectToAction('index');
        }
    }

    public function update(int $id)
    {
        $method = $this->getMethod();
        if ($method === 'GET') {
            $classroom = $this->service->findClassroom($id);

            $view = new Update();
            $view->setModel('classroom', $classroom);
            $view->render();
        } else if ($method === 'POST') {
            $this->service->updateClassroom($id, $_POST['name'], $_POST['description']);
            $this->redirectToAction('index');
        }
    }

    public function remove(int $id)
    {
        $method = $this->getMethod();
        if ($method === 'GET') {
            $this->service->removeClassroom($id);
            $this->redirectToAction('index');
        }
    }
}