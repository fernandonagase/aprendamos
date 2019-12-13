<?php
namespace aprendamos\controller;

use aprendamos\lib\mvc\Controller;
use aprendamos\model\service\AssignmentService;
use aprendamos\model\service\EnrollmentService;
use aprendamos\model\service\ReportService;
use aprendamos\persistence\dao\mariadb\AssignmentDAOimpl;
use aprendamos\persistence\dao\mariadb\EnrollmentDAOimpl;
use aprendamos\persistence\dao\mariadb\ReportDAOimpl;
use aprendamos\view\assignments\Admin;
use aprendamos\view\assignments\Create;
use aprendamos\view\assignments\Details;
use aprendamos\view\assignments\Index;
use aprendamos\view\assignments\Update;
use aprendamos\lib\Authenticator;

class AssignmentsController extends Controller
{
    private $service;
    private $reportService;

    public function __construct()
    {
        parent::__construct();
        $this->service = new AssignmentService(new AssignmentDAOimpl());
        $this->enrollmentService = new EnrollmentService(new EnrollmentDAOimpl());
        $this->reportService = new ReportService(new ReportDAOimpl());
    }

    public function index(int $id = null)
    {
        $method = $this->getMethod();
        if ($method === 'GET') {
            $classroomId = $_GET['classroom'];
            $user = $this->enrollmentService->findEnrollment(
                Authenticator::currentUser(),
                $classroomId
            );

            if ($id !== null) {
                $assignment = $this->service->findById($id);

                $view = new Details();
                $view->setModel('assignment', $assignment);
                $view->setModel('classroom', $classroomId);
                $view->setModel('user', $user);
                $view->render();

                return;
            }

            $reports = $this->reportService->reportsByEnrollment(
                Authenticator::currentUser(),
                $classroomId
            );
            
            if ($user->isProfessor()) {
                $view = new Admin();
            } else {
                $view = new Index();
            }

            $view->setModel('classroom', $classroomId);
            $view->setModel('reports', $reports);
            
            $view->render();
        }
    }

    public function create()
    {
        $classroomId = $_REQUEST['classroom'];

        $method = $this->getMethod();
        if ($method === 'GET') {
            $view = new Create();
            $view->setModel('classroom', $classroomId);
            $view->render();
        } else if ($method === 'POST') {
            $this->service->newAssignment(
                $_POST['name'],
                $_POST['description'],
                $_POST['deadline'],
                $classroomId
            );
            $this->redirectToAction("index?classroom=$classroomId");
        }
    }

    public function update(int $id)
    {
        $method = $this->getMethod();

        $classroomId = $_REQUEST['classroom'];

        if ($method === 'GET') {
            $assignment = $this->service->findById($id);

            $view = new Update();
            $view->setModel('assignment', $assignment);
            $view->setModel('classroom', $classroomId);
            $view->render();
        } else if ($method === 'POST') {
            $this->service->updateAssignment($id, $_POST['name'], $_POST['description'], $_POST['deadline']);
            $this->redirectToAction("index?classroom=$classroomId");
        }
    }

    public function remove(int $id)
    {
        $method = $this->getMethod();

        $classroomId = $_REQUEST['classroom'];

        if ($method === 'GET') {
            $this->service->removeAssignment($id);
            $this->redirectToAction("index?classroom=$classroomId");
        }
    }
}