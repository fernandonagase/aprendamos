<?php
namespace aprendamos\controller;

use aprendamos\lib\mvc\Controller;
use aprendamos\persistence\dao\mariadb\UserDAOimpl;
use aprendamos\model\service\AuthService;
use aprendamos\view\auth\Login;
use aprendamos\view\auth\Signup;
use aprendamos\lib\Path;

class AuthController extends Controller
{
    private $service;

    // Maybe not the better way to implement Dependency Injection
    public function __construct()
    {
        parent::__construct();
        $this->service = new AuthService(new UserDAOimpl());
    }

    public function signup()
    {
        $method = $this->getMethod();
        if ($method === 'GET') {
            $view = new Signup('layout_auth');
            $view->render();
        } else if ($method === 'POST') {
            if ($_POST['password'] !== $_POST['passwordConf']) {
                throw new \Exception('The password and its confirmation does not match');
            }

            $this->service->registerUser(
                $_POST['name'],
                $_POST['username'],
                md5($_POST['password']) // md5 is not that secure!
            );

            $this->redirectToAction('login');
        }
    }

    public function login()
    {
        $method = $this->getMethod();
        if ($method === 'GET') {
            $view = new Login('layout_auth');
            $view->render();
        } else if ($method === 'POST') {
            if (
                $this->service->authenticate(
                    $_POST['username'],
                    md5($_POST['password'])
                )
            ) {
                header('Location: '.Path::join(APP_URL, 'classrooms', 'index'));
            } else {
                echo('Falha ao autenticar!<br />');
            }
        }
    }

    public function logout()
    {
        $method = $this->getMethod();
        if ($method === 'GET') {
            $this->service->disconnect();
            $this->redirectToAction('login');
        }
    }
}