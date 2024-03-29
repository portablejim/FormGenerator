<?php
/**
 * Created by IntelliJ IDEA.
 * User: james
 * Date: 14/09/15
 * Time: 6:06 PM
 */

namespace testForms;


use formgenerator\IFormElement;

class DummyFormElement implements IFormElement
{
    public $name;
    public $value;
    public $valid;
    public $fillDataCalled = array();
    public $dataArray;
    public $errormsg;

    function __construct($name, $value="", $valid=false, $dataArray=null, $errormsg="")
    {
        $this->name = $name;
        $this->value = $value;
        $this->valid = $valid;
        $this->dataArray = $dataArray;
        $this->errormsg = $errormsg;
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
        $this->fillDataCalled[] = $data;
    }

    public function isValid()
    {
        return $this->valid;
    }

    public function getDataArray()
    {
        return $this->dataArray;
    }

    public function getErrorMessage()
    {
        return $this->errormsg;
    }
}