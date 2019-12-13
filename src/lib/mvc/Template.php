<?php
namespace aprendamos\lib\mvc;

class Template
{
    private $templateName;
    private $values = array();

    public function __construct(string $templateName)
    {
        $this->templateName = $templateName;
    }

    public function set($key, $value)
    {
        $this->values[$key] = $value;
    }

    /**
     * 
     * Resolves the contents of a template file.
     * 
     * This function replaces all placeholder values
     * contained in the template file with the values
     * here provided.
     * 
     * @throws Exception If the template file informed
     * does not exist.
     * @return string A string containing the resolved
     * content from the template file.
     * 
     */
    public function resolve()
    {
        if (!file_exists($this->templateName)) {
            throw new \Exception('ERRO APOCALIPTICO!!! SEU SISTEMA EXPLODIRÁ EM BREVE');
        }
        $content = file_get_contents($this->templateName);

        foreach ($this->values as $key => $value) {
            $placeholder = "{@$key}";
            $content = str_replace($placeholder, $value, $content);
        }

        return $content;
    }

    /**
     * 
     * Merges the content of a list of template files.
     * 
     * This function merges the content of an informed
     * array of template files. It can be used when
     * iterating over a collection to be displayed
     * (for instance, rendering rows of a table).
     * 
     * @param Template[] A list of templates.
     * @return string A merged template file.
     * 
     */
    public static function merge($templates)
    {
        $output = "";
 
        foreach ($templates as $template) {
            $content = (get_class($template) !== "aprendamos\lib\mvc\Template")
                ? "Error, incorrect type - expected Template."
                : $template->resolve();
            $output .= $content;
        }
    
        return $output;
    }
}
