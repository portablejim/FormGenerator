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
include_once "../src/formgenerator/formelements/EmailElement.php";

use formgenerator\formelements\EmailElement;
use formgenerator\formelements\TextElement;

class EmailElementTest extends \PHPUnit_Framework_TestCase
{
    function testBasicDataValidArray()
    {
        $textElem = new EmailElement("testName", 1, "prompt", "error", false);

        $testData = array(
            "name" => "testName",
            "type" => "email",
            "width" => 1,
            "height" => 1,
            "promptId" => "prompt",
            "required" => false,
            "value" => ""
        );

        $this->assertSame($testData, $textElem->getDataArray());
    }

    function testValidIfEmptyAndNotRequired()
    {
        $textElem = new EmailElement("testName", 1, "prompt", "error", false);

        $this->assertTrue($textElem->isValid());
    }

    function testInvalidIfEmptyAndRequired()
    {
        $textElem = new EmailElement("testName", 1, "prompt", "error", true);

        $this->assertFalse($textElem->isValid());
    }

    function testValidIfFilledAndRequired()
    {
        $textElem = new EmailElement("testName", 1, "prompt", "error", true);
        $textElem->fillData("test@example.com");

        $this->assertTrue($textElem->isValid());
    }

    function testValidIfFilledWithLongText()
    {
        $longText = "ReallyLongButValidEmail-exampleeeeeeeeeeeeeeeeeeeeeeeeeeeeeee"
            ."@eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee.example.com";

        $textElem1 = new TextElement("testName", 1, "prompt", "error", false);
        $textElem1->fillData($longText);
        $textElem2 = new TextElement("testName", 1, "prompt", "error", true);
        $textElem2->fillData($longText);

        $this->assertTrue($textElem1->isValid());
        $this->assertTrue($textElem2->isValid());
    }

    function testInvalidIfTooLongFilled()
    {
        $longText = "ReallyLongButValidEmail-exampleeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee"
            ."@eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee.example.com";

        $textElem1 = new EmailElement("testName", 1, "prompt", "error", false);
        $textElem1->fillData($longText);
        $textElem2 = new EmailElement("testName", 1, "prompt", "error", true);
        $textElem2->fillData($longText);

        $this->assertFalse($textElem1->isValid());
        $this->assertFalse($textElem2->isValid());
    }
}
