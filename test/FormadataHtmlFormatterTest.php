<?php

require_once "../src/ITranslator.php";
require_once "DummyTranslator.php";
require_once "../src/IFormdataFormatter.php";
require_once "../src/FormadataHtmlFormatter.php";

/**
 * Created by IntelliJ IDEA.
 * User: james
 * Date: 21/09/15
 * Time: 1:53 PM
 */
class FormadataHtmlFormatterTest extends PHPUnit_Framework_TestCase
{
    public $testId, $testTitleId, $testDescriptionId, $testButtonId, $testSuccessId, $testPromptId;
    public $testFormData;
    public $testFormOutput;

    public function setUp() {
        $this->testId = "testId";
        $this->testTitleId = "testTitleId";
        $this->testDescriptionId = "testDescriptionId";
        $this->testButtonId = "testButtonId";
        $this->testSuccessId = "testSuccessId";
        $this->testPromptId = "textPromptId";

        $this->testFormData = array(
            "id" => $this->testId,
            "titleId" => $this->testTitleId,
            "descriptionId" => $this->testDescriptionId,
            "fields" => array("dummy1" => array("type" => "none", "width" => 1, "height" => 1,
                "isMandatory" => "false", "promptId" => $this->testPromptId)),
            "buttonId" => $this->testButtonId,
            "successId" => $this->testSuccessId
        );

        $this->testFormOutput = '
        <form id="%s" method="post" action="/langTag/forms/%s">
			<div>
				<h2 class="text-center">%s</h2>
				<p class="text-center">%s</p>
			</div>
			<div>
			    %s
			</div>
			<div class="row">
				<p class="text-center" style="color:green;"></p>
			</div>
			<div class="row">
				<div class="large-6 large-centered columns">
					<input id="submit" name="submit" type="submit" class="button expand alert" value="%s" />
				</div>
			</div>
		</form>
		';
    }

    function testTextBasic() {
        $this->testFormData['fields']['dummy1']['type'] = "text";

        $formattedFormOutput = sprintf(
            $this->testFormOutput,
            $this->testId,
            $this->testId,
            strtoupper($this->testTitleId),
            strtoupper($this->testDescriptionId),
            '<div class="large-3 medium-6 columns">
                <label>TESTPROMPTID
                    <input id="testPromptId" name="testPromptId" type="text" placeholder="TESTPROMPTID" />
                </label>
            </div>',
            $this->testButtonId
        );

        $trans = new DummyTranslator();
        $formatter = new \formgenerator\FormadataHtmlFormatter();

        $this->assertXmlStringEqualsXmlString($formattedFormOutput, $formatter->formatEmpty($trans, $this->testFormData));
    }
}