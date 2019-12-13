<?php
namespace aprendamos\lib\mvc;

use aprendamos\lib\Path;

abstract class View
{
    protected $template;
    protected $models = array();

    public function __construct(string $layoutTemplate = 'layout')
    {
        $this->template = new Template("view/template/shared/$layoutTemplate.templ");

        /*
            Replacing {@loggedUser} here may violate Interface Segregation Principle,
            as there are views that does not need it.
            Also, accessing $_SESSION['userName'] here kind of violates the idea behind
            using an Authenticator class.
        */
        if (!isset($_SESSION)) {
            session_start();
        }
        if (!isset($_SESSION['userName'])) {
            session_destroy();
            return;
        }
        $this->template->set('loggedUser', $_SESSION['userName']);
    }

    // EXPERIMENTAL
    public function loadTemplate(string $templateName)
    {
        return new Template(Path::join('view', 'template', "$templateName.templ"));
    }

    public function setModel(string $key, $model)
    {
        $this->models[$key] = $model;
    }

    public function setTitle(string $title)
    {
        $this->template->set('title', $title);
    }

    public function setContent(string $content)
    {
        $this->template->set('content', $content);
    }

    /**
     * 
     * Renders the view.
     * 
     * This function if responsible for the rendering logic
     * for the view, meaning it must resolve the layout
     * template and display it on the screen.
     * 
     */
    public function render()
    {
        $this->build();
        echo $this->template->resolve();
    }

    /**
     * 
     * Builds the content of the view.
     * 
     * This function is responsible for building the contents
     * of the view, meaning it must set current page's data
     * based on the model data passed by a controller.
     * 
     */
    abstract public function build();
}