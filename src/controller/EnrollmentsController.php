<?php
namespace aprendamos\controller;

use aprendamos\lib\mvc\Controller;
use aprendamos\model\service\EnrollmentService;
use aprendamos\persistence\dao\mariadb\EnrollmentDAOimpl;
use aprendamos\view\enrollments\Index;
use aprendamos\lib\Authenticator;

class EnrollmentsController extends Controller
{
    // Maybe not the better way to implement Dependency Injection
    public function __construct()
    {
        parent::__construct();
        $this->service = new EnrollmentService(new EnrollmentDAOimpl());
    }

    public function index()
    {
        $method = $this->getMethod();
        if ($method === 'GET') {
            $classroomId = $_GET['classroomId'];

            $enrollments = $this->service->findByClassroom($classroomId);
            $user = $this->service->findEnrollment(
                Authenticator::currentUser(),
                $classroomId
            );

            $view = new Index();
            $view->setModel('classroomId', $classroomId);
            $view->setModel('enrollments', $enrollments);
            $view->setModel('user', $user);
            $view->render();
        }
    }

    public function create()
    {
        $method = $this->getMethod();
        if ($method === 'POST') {
            $username = $_POST['username'];
            $classroomId = $_REQUEST['classroom'];

            $this->service->addStudent($username, $classroomId);
            $this->redirectToAction("index?classroomId=$classroomId");
        }
    }

    public function remove()
    {
        $method = $this->getMethod();
        if ($method === 'GET') {
            $userId = $_REQUEST['user'];
            $classroomId = $_REQUEST['classroom'];

            $this->service->removeStudent($userId, $classroomId);
            $this->redirectToAction("index?classroomId=$classroomId");
        }
    }
}