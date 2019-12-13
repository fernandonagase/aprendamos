<?php
namespace aprendamos\view\auth;

use aprendamos\lib\mvc\View;

class Login extends View
{
    public function build()
    {
        $this->setTitle('Entrar');
        $loginForm = $this->loadTemplate('auth/login');
        $this->setContent($loginForm->resolve());
    }
}