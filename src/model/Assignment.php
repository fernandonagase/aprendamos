<?php
namespace aprendamos\model;

class Assignment
{
    private $id;
    private $name;
    private $description;
    private $deadline;
    private $status;

    public function __construct(
        string $name,
        string $description,
        string $deadline
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->deadline = $deadline;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDeadline(): string
    {
        return $this->deadline;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status)
    {
        $this->status = $status;
    }
}