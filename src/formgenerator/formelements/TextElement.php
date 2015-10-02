<?php
/**
 * Created by IntelliJ IDEA.
 * User: james
 * Date: 2/10/15
 * Time: 10:03 AM
 */

namespace formgenerator\formelements;


use formgenerator\IFormElement;

class TextElement implements IFormElement
{
    protected $value;
    /**
     * @var
     */
    protected $name;
    /**
     * @var
     */
    protected $width;
    /**
     * @var
     */
    protected $promptTextId;
    /**
     * @var
     */
    protected $errorTextId;
    /**
     * @var
     */
    protected $required;

    public function __construct($name, $width, $promptTextId, $errorTextId, $required)
    {

        $this->name = $name;
        $this->width = $width;
        $this->promptTextId = $promptTextId;
        $this->errorTextId = $errorTextId;
        $this->required = $required;
        $this->value = "";
    }

    public function getName()
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function fillData($data)
    {
        $this->value = $data;
    }

    public function isValid()
    {
        if(strlen($this->value) == 0 && $this->required) {
            return false;
        }

        if(strlen($this->value) >= 1000) {
            return false;
        }

        return true;
    }

    public function getErrorMessage()
    {
        return $this->errorTextId;
    }

    public function getDataArray()
    {
        $output = array(
            "name" => $this->name,
            "type" => "text",
            "width" => $this->width,
            "height" => 1,
            "promptId" => $this->promptTextId,
            "required" => $this->required,
            "value" => $this->value
        );
        return $output;
    }
}