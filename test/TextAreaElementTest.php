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
include_once "../src/formgenerator/formelements/TextareaElement.php";

use formgenerator\formelements\TextareaElement;

class TextAreaElementTest extends \PHPUnit_Framework_TestCase
{
    function testBasicDataValidArray()
    {
        $textElem = new TextareaElement("testName", 1, 1, "prompt", "error", false);

        $testData = array(
            "name" => "testName",
            "type" => "textarea",
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
        $textElem = new TextareaElement("testName", 1, 1, "prompt", "error", true);

        $testData = array(
            "name" => "testName",
            "type" => "textarea",
            "width" => 1,
            "height" => 1,
            "promptId" => "prompt",
            "required" => true,
            "value" => ""
        );

        $this->assertSame($testData, $textElem->getDataArray());
    }


    function testValidIfEmptyAndNotRequired()
    {
        $textElem = new TextareaElement("testName", 1, 1, "prompt", "error", false);

        $this->assertTrue($textElem->isValid());
    }

    function testInvalidIfEmptyAndRequired()
    {
        $textElem = new TextareaElement("testName", 1, 1, "prompt", "error", true);

        $this->assertFalse($textElem->isValid());
    }

    function testValidIfFilledAndRequired()
    {
        $textElem = new TextareaElement("testName", 1, 1, "prompt", "error", true);
        $textElem->fillData("testData");

        $this->assertTrue($textElem->isValid());
    }

    function testValidIfFilledWithLongText()
    {
        $longText =
              "This text is 00999 long ----------------------------------------------------------------------------"
              // /\ 100 chars
            . str_repeat("-", 9800)
              // \/ 99 chars
            . "---------------------------------------------------------------------------------------------------";

        $textElem1 = new TextareaElement("testName", 1, 1, "prompt", "error", true);
        $textElem1->fillData($longText);
        $textElem2 = new TextareaElement("testName", 1, 1, "prompt", "error", true);
        $textElem2->fillData($longText);

        $this->assertTrue($textElem1->isValid());
        $this->assertTrue($textElem2->isValid());
    }

    function testInvalidIfTooLongFilled()
    {
        $longText =
            "This text is 01000 long ----------------------------------------------------------------------------"
            // /\ 100 chars
            . str_repeat("-", 9800)
            // \/ 100 chars
            . "----------------------------------------------------------------------------------------------------";

        $textElem1 = new TextareaElement("testName", 1, 1, "prompt", "error", true);
        $textElem1->fillData($longText);
        $textElem2 = new TextareaElement("testName", 1, 1, "prompt", "error", true);
        $textElem2->fillData($longText);

        $this->assertFalse($textElem1->isValid());
        $this->assertFalse($textElem2->isValid());
    }
}
