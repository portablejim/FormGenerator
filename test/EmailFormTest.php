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
    public $testName;
    public $testTo;
    public $testSubject;
    public $testConfig;
    public $testMailer;

    protected function setUp() {
        $this->testName = "test1";
        $this->testTo = "test@example.com";
        $this->testSubject = "Response from online form";
        $this->testConfig = array('type' => 'email',
            'titleId' => 'test_form_title',
            'descriptionId' => 'test_form_description',
            'destination' => $this->testTo,
            'buttonStringId' => 'button_string_id',
            'successId' => 'success_message_id');
        $this->testMailer = new \testForms\DummyMailer();
    }

    public function testEmptyFormIsValid()
    {
        $testForm = new \formgenerator\forms\EmailForm($this->testName, $this->testConfig, $this->testMailer);
        $this->assertTrue($testForm->isValid());

    }

    public function testFormWithInvalidElementsIsInvalid()
    {
        $testForm = new \formgenerator\forms\EmailForm($this->testName, $this->testConfig, $this->testMailer);
        $testForm->addField(new \testForms\DummyFormElement("dummy1", "", true));
        $testForm->addField(new \testForms\DummyFormElement("dummy3", "", false));
        $this->assertFalse($testForm->isValid());
    }

    public function testFormWithNoInvalidElementsIsValid()
    {
        $testForm = new \formgenerator\forms\EmailForm($this->testName, $this->testConfig, $this->testMailer);
        $testForm->addField(new \testForms\DummyFormElement("dummy1", "", true));
        $testForm->addField(new \testForms\DummyFormElement("dummy3", "", true));
        $this->assertTrue($testForm->isValid());
    }

    public function testFormNoSubmitIfInvalid()
    {
        $testForm = new \formgenerator\forms\EmailForm($this->testName, $this->testConfig, $this->testMailer);
        $testForm->addField(new \testForms\DummyFormElement("dummy1", "DUMMY1", false));
        $testForm->submitForm("referringUrl", "1.2.3.4");
        $this->assertEquals(0, count($this->testMailer->mailSent));
    }

    public function testFormSubmitsToEmail()
    {
        $testForm = new \formgenerator\forms\EmailForm($this->testName, $this->testConfig, $this->testMailer);
        $testForm->addField(new \testForms\DummyFormElement("dummy1", "DUMMY1", true));
        $testForm->addField(new \testForms\DummyFormElement("dummy3", "DUMMY2", true));
        $testForm->submitForm("referringUrl", "1.2.3.4");
        $this->assertEquals(1, count($this->testMailer->mailSent));
    }

    public function testformSubmitsToCorrectAddress()
    {
        $testForm = new \formgenerator\forms\EmailForm($this->testName, $this->testConfig, $this->testMailer);
        $testForm->addField(new \testForms\DummyFormElement("dummy1", "DUMMY1", true));
        $testForm->addField(new \testForms\DummyFormElement("dummy3", "DUMMY2", true));
        $testForm->submitForm("referringUrl", "1.2.3.4");
        $this->assertTrue(is_array($this->testMailer->mailSent), "mailSent not array");
        $this->assertEquals(1, count($this->testMailer->mailSent), "mailSent is empty");
        $this->assertSame($this->testTo, $this->testMailer->mailSent[0]['to']);

    }

    public function testformSubmitsWithCorrectSubject()
    {
        $testForm = new \formgenerator\forms\EmailForm($this->testName, $this->testConfig, $this->testMailer);
        $testForm->addField(new \testForms\DummyFormElement("dummy1", "DUMMY1", true));
        $testForm->addField(new \testForms\DummyFormElement("dummy3", "DUMMY2", true));
        $testForm->submitForm("referringUrl", "1.2.3.4");
        $this->assertTrue(is_array($this->testMailer->mailSent), "mailSent not array");
        $this->assertEquals(1, count($this->testMailer->mailSent), "mailSent is empty");
        $this->assertSame($this->testSubject, $this->testMailer->mailSent[0]['subject']);

    }

    public function testformSubmitsWithCorrectBody()
    {
        $testForm = new \formgenerator\forms\EmailForm($this->testName, $this->testConfig, $this->testMailer);
        $testForm->addField(new \testForms\DummyFormElement("dummy1", "DUMMY1", true));
        $testForm->addField(new \testForms\DummyFormElement("dummy2", "DUMMY2", true));
        $testForm->submitForm("referringUrl", "1.2.3.4");

        $testBody = "New response for form 'test1'\n"
            . "\n"
            . "dummy1: DUMMY1\n"
            . "dummy2: DUMMY2\n";

        $this->assertTrue(is_array($this->testMailer->mailSent), "mailSent not array");
        $this->assertEquals(1, count($this->testMailer->mailSent), "mailSent is empty");
        $this->assertSame($testBody, $this->testMailer->mailSent[0]['body']);

    }
}
