<?php

include_once "../src/formgenerator/IFormElement.php";
include_once "../src/formgenerator/formelements/DropdownElement.php";

use formgenerator\formelements\DropdownElement;

/**
 * Created by IntelliJ IDEA.
 * User: james
 * Date: 6/10/15
 * Time: 11:31 AM
 */
class DropdownElementTest extends PHPUnit_Framework_TestCase
{
    function testBasicDataValid()
    {
        $testElement = new DropdownElement("testName", 1, "prompt", "error", false, array("a" => "testA", "b" => "testB"));

        $testData = array(
            "name" => "testName",
            "type" => "dropdown",
            "width" => 1,
            "height" => 1,
            "promptId" => "prompt",
            "required" => false,
            "options" => array(
                "a" => "testA",
                "b" => "testB"
            ),
            "value" => ""
        );

        $this->assertSame($testData, $testElement->getDataArray());
    }

    function testBasicDataValidRequired()
    {
        $testElement = new DropdownElement("testName", 1, "prompt", "error", true, array("a" => "testA", "b" => "testB"));

        $testData = array(
            "name" => "testName",
            "type" => "dropdown",
            "width" => 1,
            "height" => 1,
            "promptId" => "prompt",
            "required" => true,
            "options" => array(
                "a" => "testA",
                "b" => "testB"
            ),
            "value" => ""
        );

        $this->assertSame($testData, $testElement->getDataArray());
    }

    function testValidIfEmptyAndNotRequired()
    {
        $testElement = new DropdownElement("testName", 1, "prompt", "error", false, array("a" => "testA", "b" => "testB"));
        $this->assertTrue($testElement->isValid());
    }

    function testInvalidIfEmptyAndRequired()
    {
        $testElement = new DropdownElement("testName", 1, "prompt", "error", true, array("a" => "testA", "b" => "testB"));
        $this->assertFalse($testElement->isValid());
    }

    function testValidIfFilledAndNotRequired()
    {
        $testElement = new DropdownElement("testName", 1, "prompt", "error", false, array("a" => "testA", "b" => "testB"));
        $testElement->fillData("a");
        $this->assertTrue($testElement->isValid());
    }

    function testInvalidIfFilledIncorrectly()
    {
        $testElement1 = new DropdownElement("testName", 1, "prompt", "error", true, array("a" => "testA", "b" => "testB"));
        $testElement2 = new DropdownElement("testName", 1, "prompt", "error", false, array("a" => "testA", "b" => "testB"));
        $testElement1->fillData("c");
        $testElement2->fillData("c");
        $this->assertFalse($testElement1->isValid());
        $this->assertFalse($testElement2->isValid());
    }

    function testErrorMessageIfInvalidEmptyAndRequired()
    {
        $testElement = new DropdownElement("testName", 1, "prompt", "error", true, array("a" => "testA", "b" => "testB"));
        $this->assertEquals("error", $testElement->getErrorMessage());
    }
}
