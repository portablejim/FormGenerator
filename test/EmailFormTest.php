<?php

/**
 * Created by IntelliJ IDEA.
 * User: james
 * Date: 15/09/15
 * Time: 10:41 AM
 */

include_once "../src/IForm.php";
include_once "../src/IMailer.php";
include_once "../src/GeneralForm.php";
include_once "../src/forms/EmailForm.php";

include_once "testForms/DummyMailer.php";

class EmailFormTest extends PHPUnit_Framework_TestCase
{
    public $testTo;
    public $testSubject;
    public $testConfig;
    public $testMailer;

    protected function setUp() {
        $this->testTo = "test@example.com";
        $this->testSubject = "Testing Subject to objections";
        $this->testConfig = array('type' => 'email',
            'to' => $this->testTo,
            'subject' => $this->testSubject,
            'name' => 'test1');
        $this->testMailer = new \testForms\DummyMailer();
    }

    public function testEmptyFormIsValid()
    {
        $testForm = new \formgenerator\forms\EmailForm($this->testConfig, $this->testMailer);
        $this->assertTrue($testForm->isValid());

    }

    public function testEmptyForm2()
    {
        $currentTestConfig = array('type' => 'email',
            'to' => $this->testTo,
            'subject' => $this->testSubject);
        $testForm = new \formgenerator\forms\EmailForm($currentTestConfig, $this->testMailer);
        $testForm->submitForm("referringUrl");
    }
}
