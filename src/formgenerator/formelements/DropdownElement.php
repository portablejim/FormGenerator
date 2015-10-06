<?php
/**
 * Created by IntelliJ IDEA.
 * User: james
 * Date: 6/10/15
 * Time: 11:11 AM
 */

namespace formgenerator\formelements;

use formgenerator\IFormElement;

class DropdownElement implements IFormElement
{
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
    /**
     * @var
     */
    private $options;

    protected $value;

    public function __construct($name, $width, $promptTextId, $errorTextId, $required, $options)
    {
        $this->name = $name;
        $this->width = $width;
        $this->promptTextId = $promptTextId;
        $this->errorTextId = $errorTextId;
        $this->required = $required;
        $this->options = $options;
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
        if($this->value === "" && !$this->required) {
            return true;
        }

        foreach($this->options as $optionKey => $optionValue) {
            if(strlen($this->value) > 0 && $optionKey === $this->value) {
                return true;
            }
        }
        return false;
    }

    public function getErrorMessage()
    {
        return $this->errorTextId;
    }

    public function getDataArray()
    {
        $output = array(
            "name" => $this->name,
            "type" => "dropdown",
            "width" => $this->width,
            "height" => 1,
            "promptId" => $this->promptTextId,
            "required" => $this->required,
            "options" => $this->options,
            "value" => $this->value
        );
        return $output;
    }
}