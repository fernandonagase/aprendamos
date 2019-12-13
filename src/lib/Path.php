<?php
namespace aprendamos\lib;

class Path
{
    /**
     * 
     * Builds a joined representation of a number
     * of path elements passed.
     * 
     * @return string A string representing a path
     * build upon the informed path elements.
     * 
     */
    public static function join(...$elements)
    {
        $path = $elements[0];
        foreach (array_slice($elements, 1) as $element) {
            $path .= DIRECTORY_SEPARATOR."$element";
        }
        return $path;
    }
}