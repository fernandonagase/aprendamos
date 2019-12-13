<?php
namespace aprendamos\controller;

use aprendamos\lib\mvc\Controller;
use aprendamos\model\service\AssignmentService;
use aprendamos\model\service\EnrollmentService;
use aprendamos\model\service\ReportService;
use aprendamos\model\service\UserService;
use aprendamos\persistence\dao\mariadb\AssignmentDAOimpl;
use aprendamos\persistence\dao\mariadb\EnrollmentDAOimpl;
use aprendamos\persistence\dao\mariadb\ReportDAOimpl;
use aprendamos\persistence\dao\mariadb\UserDAOimpl;
use aprendamos\view\reports\Create;
use aprendamos\view\reports\Index;
use aprendamos\view\reports\Update;

class ReportsController extends Controller
{
    private $service;
    private $enrollmentService;
    private $assignmentService;

    public function __construct()
    {
        parent::__construct();
        $this->service = new ReportService(new ReportDAOimpl());
        $this->assignmentService = new AssignmentService(new AssignmentDAOimpl());
        $this->enrollmentService = new EnrollmentService(new EnrollmentDAOimpl());
    }

    public function index(int $id = null)
    {
        $assignmentId = $_GET['assignment'];

        $reports = $this->service->namedReportsByAssignment($assignmentId);
        $average = array_sum($reports) / count($reports);

        $below = 0;
        $over = 0;

        foreach ($reports as $report) {
            if ($report >= $average) {
                $over++;
            }else {
                $below++;
            }
        }

        $view = new Index();
        $view->setModel('reports', $reports);
        $view->setModel('average', $average);
        $view->setModel('overAverage', $over);
        $view->setModel('belowAverage', $below);
        $view->render();
    }

    public function create()
    {
        $method = $this->getMethod();

        $assignmentId = $_REQUEST['assignment'];
        $classroomId = $_REQUEST['classroom'];
        
        if ($method === 'GET') {
            $enrollments = $this->enrollmentService->findByClassroom($classroomId);

            $view = new Create();
            $view->setModel('enrollments', $enrollments);
            $view->setModel('assignmentId', $assignmentId);
            $view->render();
        } else if ($method === 'POST') {
            $reports = $_POST['grades'];
            $this->service->reportsFromAssignment($reports, $assignmentId, $classroomId);
            $this->assignmentService->finishAssignment($assignmentId);
            header("Location: /aprendamos/assignments/index/$assignmentId?classroom=$classroomId");
        }
    }

    public function update()
    {
        $method = $this->getMethod();

        $assignmentId = $_REQUEST['assignment'];
        $classroomId = $_REQUEST['classroom'];

        if ($method === 'GET') {
            $reports = $this->service->reportsByAssignment($assignmentId);

            $users = [];
            foreach ($reports as $user => $grade) {
                $users[$user] = $this->enrollmentService->findEnrollment($user, $classroomId)->getUser();
            }

            $view = new Update();
            $view->setModel('assignment', $assignmentId);
            $view->setModel('classroom', $classroomId);
            $view->setModel('reports', $reports);
            $view->setModel('users', $users);
            $view->render();
        } else if ($method === 'POST') {
            $reports = $_POST['grades'];
            $this->service->updateBatch($reports, $assignmentId, $classroomId);
            header("Location: /aprendamos/assignments/index/$assignmentId?classroom=$classroomId");
        }
    }

    public function overall()
    {}
}