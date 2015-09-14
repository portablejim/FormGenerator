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
    public $fillDataCalled = array();

        function __construct($name)
        {
            $this->name = $name;
        }

    public function getName()
    {
        return $this->name;
    }

    public function fillData($data)
    {
        $this->fillDataCalled[] = $data;
    }

    public function isValid()
    {
    }

    public function getDataArray()
    {
    }
}