<?php
/**
 * Created by IntelliJ IDEA.
 * User: james
 * Date: 2/10/15
 * Time: 10:06 AM
 */

namespace testForms;

include_once "../src/formgenerator/IFormElement.php";
include_once "../src/formgenerator/formelements/TextElement.php";

use formgenerator\formelements\TextElement;

class TextElementTest extends \PHPUnit_Framework_TestCase
{
    function testBasicDataValidArray()
    {
        $textElem = new TextElement("testName", 1, "prompt", "error", false);

        $testData = array(
            "name" => "testName",
            "type" => "text",
            "width" => 1,
            "height" => 1,
            "promptId" => "prompt",
            "required" => false,
            "value" => ""
        );

        $this->assertSame($testData, $textElem->getDataArray());
    }

    function testBasicDataValidArrayRequired()
    {
        $textElem = new TextElement("testName", 1, "prompt", "error", true);

        $testData = array(
            "name" => "testName",
            "type" => "text",
            "width" => 1,
            "height" => 1,
            "promptId" => "prompt",
            "required" => true,
            "value" => ""
        );

        $this->assertSame($testData, $textElem->getDataArray());
    }

    function testName()
    {
        $textElem = new TextElement("testName", 1, "prompt", "error", false);

        $this->assertSame("testName", $textElem->getName());
    }

    function testValue()
    {
        $textElem = new TextElement("testName", 1, "prompt", "error", false);

        $this->assertSame("", $textElem->getValue());
    }

    function testErrorMessage()
    {
        $textElem = new TextElement("testName", 1, "prompt", "error", false);

        $this->assertSame("error", $textElem->getErrorMessage());
    }

    function testFillForm()
    {
        $textElem = new TextElement("testName", 1, "prompt", "error", false);
        $textElem->fillData("dummyData");

        $this->assertSame("dummyData", $textElem->getValue());
    }

    function testValidIfEmptyAndNotRequired()
    {
        $textElem = new TextElement("testName", 1, "prompt", "error", false);

        $this->assertTrue($textElem->isValid());
    }

    function testInvalidIfEmptyAndRequired()
    {
        $textElem = new TextElement("testName", 1, "prompt", "error", true);

        $this->assertFalse($textElem->isValid());
    }

    function testValidIfFilledAndRequired()
    {
        $textElem = new TextElement("testName", 1, "prompt", "error", true);
        $textElem->fillData("testData");

        $this->assertTrue($textElem->isValid());
    }

    function testValidIfFilledWithLongText()
    {
        $longText =
              "This text is 00999 long ----------------------------------------------------------------------------"
            . "----------------------------------------------------------------------------------------------------"
            . "----------------------------------------------------------------------------------------------------"
            . "----------------------------------------------------------------------------------------------------"
            . "----------------------------------------------------------------------------------------------------"
            . "----------------------------------------------------------------------------------------------------"
            . "----------------------------------------------------------------------------------------------------"
            . "----------------------------------------------------------------------------------------------------"
            . "----------------------------------------------------------------------------------------------------"
            . "---------------------------------------------------------------------------------------------------";

        $textElem1 = new TextElement("testName", 1, "prompt", "error", true);
        $textElem1->fillData($longText);
        $textElem2 = new TextElement("testName", 1, "prompt", "error", true);
        $textElem2->fillData($longText);

        $this->assertTrue($textElem1->isValid());
        $this->assertTrue($textElem2->isValid());
    }

    function testInvalidIfTooLongFilled()
    {
        $longText =
            "This text is 00999 long ----------------------------------------------------------------------------"
            . "----------------------------------------------------------------------------------------------------"
            . "----------------------------------------------------------------------------------------------------"
            . "----------------------------------------------------------------------------------------------------"
            . "----------------------------------------------------------------------------------------------------"
            . "----------------------------------------------------------------------------------------------------"
            . "----------------------------------------------------------------------------------------------------"
            . "----------------------------------------------------------------------------------------------------"
            . "----------------------------------------------------------------------------------------------------"
            . "----------------------------------------------------------------------------------------------------";

        $textElem1 = new TextElement("testName", 1, "prompt", "error", true);
        $textElem1->fillData($longText);
        $textElem2 = new TextElement("testName", 1, "prompt", "error", true);
        $textElem2->fillData($longText);

        $this->assertFalse($textElem1->isValid());
        $this->assertFalse($textElem2->isValid());
    }
}
