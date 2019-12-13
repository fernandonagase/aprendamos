<?php
namespace aprendamos\view\auth;

use aprendamos\lib\mvc\View;

class Signup extends View
{
    public function build()
    {
        $this->setTitle('Cadastre-se');
        $signupForm = $this->loadTemplate('auth/signup');
        $this->setContent($signupForm->resolve());
    }
}