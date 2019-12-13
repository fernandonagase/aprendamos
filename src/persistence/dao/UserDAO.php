<?php
namespace aprendamos\persistence\dao;

use aprendamos\model\User;

interface UserDAO
{
    public function addUser(User $user);
    public function findByCredentials(string $username, string $password): ?User;
    public function findByUsername(string $username): ?User;
}