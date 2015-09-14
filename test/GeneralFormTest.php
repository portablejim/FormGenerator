<?php

/**
 * Created by IntelliJ IDEA.
 * User: james
 * Date: 14/09/15
 * Time: 3:30 PM
 */

include_once "../src/IForm.php";
include_once "../src/IFormElement.php";
include_once "../src/GeneralForm.php";

include_once "testForms/TestGeneralForm.php";
include_once "testForms/DummyFormElement.php";

class GeneralFormTest extends PHPUnit_Framework_TestCase
{
    public function testNoInitialFields()
    {
        $testForm = new \testForms\TestGeneralForm();
        $testFields = $testForm->getFields();
        $this->assertTrue(is_array($testFields));
        $this->assertTrue(sizeof($testFields) === 0);
    }

    public function testOneInitialField()
    {
        $testForm = new testForms\TestGeneralForm();
        $testField1 = new \testForms\DummyFormElement("dummy1");
        $testOutputArray = array("dummy1" => $testField1);
        $testForm->addField($testField1);
        $actualOutput = $testForm->getFields();
        $this->assertSame($testOutputArray, $actualOutput);


    }
}
