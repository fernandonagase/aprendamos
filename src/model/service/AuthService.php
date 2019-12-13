<?php
namespace aprendamos\model\service;

use aprendamos\model\User;
use aprendamos\persistence\dao\UserDAO;
use aprendamos\lib\Authenticator;

class AuthService
{
    private $dao;

    public function __construct(UserDAO $dao)
    {
        $this->dao = $dao;
    }

    public function registerUser(
        string $name,
        string $username,
        string $password
    ) {
        $user = new User($username, $password);
        $user->setName($name);
        $this->dao->addUser($user);
    }

    public function authenticate(string $username, string $password): bool
    {
        $user = $this->dao->findByCredentials($username, $password);
        if ($user === null) {
            return false;
        }
        Authenticator::createSession($user->getId(), $user->getName());
        return true;
    }

    public function disconnect()
    {
        Authenticator::disconnect();
    }
}