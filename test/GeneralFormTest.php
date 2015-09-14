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

class GeneralFormTest extends PHPUnit_Framework_TestCase
{
    public function testNoInitialFields(){
        $testForm = new \testForms\TestGeneralForm();
        $testFields = $testForm->getFields();
        assert(is_array($testFields));
        assert(sizeof($testFields) === 0);
    }
}
