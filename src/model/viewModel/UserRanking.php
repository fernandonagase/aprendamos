<?php
namespace aprendamos\model\viewModel;

use aprendamos\model\User;

class UserRanking
{
    private $user;
    private $count;

    public function __construct(User $user, float $count)
    {
        $this->user = $user;
        $this->count = $count;
    }

    public function getName(): string
    {
        return $this->user->getName();
    }

    public function getCount(): float
    {
        return $this->count;
    }
}