<?php
/**
 * Created by IntelliJ IDEA.
 * User: james
 * Date: 2/10/15
 * Time: 1:19 PM
 */

namespace formgenerator\formelements;


class TextareaElement extends TextElement
{
    /**
     * @var
     */
    protected $height;

    public function __construct($name, $width, $height, $promptTextId, $errorTextId, $required)
    {
        parent::__construct($name, $width, $promptTextId, $errorTextId, $required);

        $this->height = $height;
    }

    public function isValid()
    {
        if(strlen($this->value) == 0 && $this->required) {
            return false;
        }

        if(strlen($this->value) >= 10000) {
            return false;
        }

        return true;
    }

    public function getDataArray()
    {
        $output = parent::getDataArray();
        $output['type'] = "textarea";
        $output['height'] = $this->height;
        return $output;
    }


}