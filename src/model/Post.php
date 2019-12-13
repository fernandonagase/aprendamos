<?php
namespace aprendamos\model;

class Post
{
    private $id;
    private $title;
    private $content;
    private $publicatedDate;
    private $editedDate;
    private $author;

    public function __construct(
        string $title,
        string $content,
        string $publicatedDate,
        User $author
    ) {
        $this->title = $title;
        $this->content = $content;
        $this->publicatedDate = $publicatedDate;
        $this->author = $author;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getPublicatedDate(): string
    {
        return $this->publicatedDate;
    }

    public function getEditedDate(): string
    {
        return $this->editedDate;
    }

    public function setEditedDate(?string $editedDate)
    {
        $this->editedDate = $editedDate;
    }

    public function getAuthor()
    {
        return $this->author;
    }
}