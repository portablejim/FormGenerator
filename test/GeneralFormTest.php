<?php

/**
 * Created by IntelliJ IDEA.
 * User: james
 * Date: 14/09/15
 * Time: 3:30 PM
 */

include_once "../src/formgenerator/IForm.php";
include_once "../src/formgenerator/IFormElement.php";
include_once "../src/formgenerator/GeneralForm.php";

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

    public function testTwoInitialFields()
    {
        $testForm = new testForms\TestGeneralForm();
        $testField1 = new \testForms\DummyFormElement("dummy1");
        $testField2 = new \testForms\DummyFormElement("dummy2");
        $testOutputArray = array("dummy1" => $testField1, "dummy2" => $testField2);
        $testForm->addField($testField1);
        $testForm->addField($testField2);
        $actualOutput = $testForm->getFields();
        $this->assertSame($testOutputArray, $actualOutput);
    }

    public function testFillDataNormal()
    {
        $testForm = new testForms\TestGeneralForm();
        $testField1 = new \testForms\DummyFormElement("dummy1");
        $testField2 = new \testForms\DummyFormElement("dummy2");
        $testForm->addField($testField1);
        $testForm->addField($testField2);
        $data1 = "apple";
        $data2 = "banana";
        $testFormData = array("dummy1" => $data1, "dummy2" => $data2);
        $testForm->fillFields($testFormData);

        $this->assertSame($testField1->fillDataCalled, array($data1));
        $this->assertSame($testField2->fillDataCalled, array($data2));
    }

    public function testFillDataMissing()
    {
        $testForm = new testForms\TestGeneralForm();
        $testField1 = new \testForms\DummyFormElement("dummy1");
        $testField2 = new \testForms\DummyFormElement("dummy2");
        $testForm->addField($testField1);
        $testForm->addField($testField2);
        $data1 = "apple";
        $data2 = "banana";
        $testFormData = array("dummy1" => $data1, "dummyBad" => $data2);
        $testForm->fillFields($testFormData);

        $this->assertSame($testField1->fillDataCalled, array($data1));
        $this->assertNotSame($testField2->fillDataCalled, array($data2));
    }

    public function testFillDataExtra()
    {
        $testForm = new testForms\TestGeneralForm();
        $testField1 = new \testForms\DummyFormElement("dummy1");
        $testField2 = new \testForms\DummyFormElement("dummy2");
        $testForm->addField($testField1);
        $testForm->addField($testField2);
        $data1 = "apple";
        $data2 = "banana";
        $data3 = "carrot";
        $testFormData = array("dummy1" => $data1, "dummy2" => $data2, "dummyBad" => $data3);
        $testForm->fillFields($testFormData);

        $this->assertSame($testField1->fillDataCalled, array($data1));
        $this->assertSame($testField2->fillDataCalled, array($data2));
    }

    public function testFillDataNoSuccess()
    {
        $testForm = new testForms\TestGeneralForm();
        $testField1 = new \testForms\DummyFormElement("dummy1");
        $testField2 = new \testForms\DummyFormElement("dummy2");
        $testForm->addField($testField1);
        $testForm->addField($testField2);
        $testFormData = array();
        $testForm->fillFields($testFormData);

        $this->assertFalse($testForm->getSuccess());
    }

    public function testFillDataSuccess()
    {
        $testForm = new testForms\TestGeneralForm();
        $testField1 = new \testForms\DummyFormElement("dummy1");
        $testField2 = new \testForms\DummyFormElement("dummy2");
        $testForm->addField($testField1);
        $testForm->addField($testField2);
        $testFormData = array("success" => "success");
        $testForm->fillFields($testFormData);

        $this->assertTrue($testForm->getSuccess());
    }
}
