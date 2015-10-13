<?php

require_once "../src/formgenerator/ITranslator.php";
require_once "DummyTranslator.php";
require_once "DummyTranslator2.php";
require_once "../src/formgenerator/IFormdataFormatter.php";
require_once "../src/formgenerator/FormadataHtmlFormatter.php";

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
                "required" => "false", "promptId" => $this->testPromptId)),
            "buttonId" => $this->testButtonId,
            "successId" => $this->testSuccessId
        );

        /** @noinspection HtmlUnknownTarget */
        $this->testFormOutput = '
        <form id="%s" method="post" action="">
			<div>
				<h2 class="text-center">%s</h2>
				<p class="text-center">%s</p>
			</div>
			<div>
			    %s
            </div>
			<div class="row">
				<p class="text-center" style="%s">%s</p>
			</div>
			<div class="row">
				<div class="large-6 large-centered columns">
					<input id="submit" name="submit" type="submit" class="button expand alert" value="%s" />
				</div>
			</div>
		</form>
		';
    }

    function testNoFieldsOnNoneType() {
        $formattedFormOutput = sprintf(
            $this->testFormOutput,
            $this->testId,
            strtoupper($this->testTitleId),
            strtoupper($this->testDescriptionId),
            "",
            "color:green;",
            "",
            strtoupper($this->testButtonId)
        );

        $trans = new DummyTranslator();
        $formatter = new \formgenerator\FormadataHtmlFormatter();

        $this->assertXmlStringEqualsXmlString($formattedFormOutput, $formatter->formatEmpty($trans, $this->testFormData));

    }

    function testTextBasic() {
        $this->testFormData['fields']['dummy1']['type'] = "text";

        $formattedFormOutput = sprintf(
            $this->testFormOutput,
            $this->testId,
            strtoupper($this->testTitleId),
            strtoupper($this->testDescriptionId),
            '<div class="large-3 medium-6 columns">
                <label for="textPromptId">TEXTPROMPTID</label><!--
                 --><input id="textPromptId" name="textPromptId" type="text" placeholder="TEXTPROMPTID" value="" />
            </div>',
            "color:green;",
            "",
            strtoupper($this->testButtonId)
        );

        $trans = new DummyTranslator();
        $formatter = new \formgenerator\FormadataHtmlFormatter();

        $this->assertXmlStringEqualsXmlString($formattedFormOutput, $formatter->formatEmpty($trans, $this->testFormData));
    }

    function testTextBasicRequired() {
        $this->testFormData['fields']['dummy1']['type'] = "text";
        $this->testFormData['fields']['dummy1']['required'] = true;

        $formattedFormOutput = sprintf(
            $this->testFormOutput,
            $this->testId,
            strtoupper($this->testTitleId),
            strtoupper($this->testDescriptionId),
            '<div class="large-3 medium-6 columns">
                <label for="textPromptId">TEXTPROMPTID</label><!--
                 --><input id="textPromptId" name="textPromptId" type="text" placeholder="TEXTPROMPTID" value="" required="" />
            </div>',
            "color:green;",
            "",
            strtoupper($this->testButtonId)
        );

        $trans = new DummyTranslator();
        $formatter = new \formgenerator\FormadataHtmlFormatter();

        $this->assertXmlStringEqualsXmlString($formattedFormOutput, $formatter->formatEmpty($trans, $this->testFormData));
    }

    function testTextBasicRequiredString() {
        $this->testFormData['fields']['dummy1']['type'] = "text";
        $this->testFormData['fields']['dummy1']['required'] = "1";

        $formattedFormOutput = sprintf(
            $this->testFormOutput,
            $this->testId,
            strtoupper($this->testTitleId),
            strtoupper($this->testDescriptionId),
            '<div class="large-3 medium-6 columns">
                <label for="textPromptId">TEXTPROMPTID</label><!--
                 --><input id="textPromptId" name="textPromptId" type="text" placeholder="TEXTPROMPTID" value="" required="" />
            </div>',
            "color:green;",
            "",
            strtoupper($this->testButtonId)
        );

        $trans = new DummyTranslator();
        $formatter = new \formgenerator\FormadataHtmlFormatter();

        $this->assertXmlStringEqualsXmlString($formattedFormOutput, $formatter->formatEmpty($trans, $this->testFormData));
    }

    function testTextBasicFilled()
    {
        $this->testFormData['fields']['dummy1']['type'] = "text";
        $this->testFormData['fields']['dummy1']['value'] = "test Value";

        $formattedFormOutput = sprintf(
            $this->testFormOutput,
            $this->testId,
            strtoupper($this->testTitleId),
            strtoupper($this->testDescriptionId),
            '<div class="large-3 medium-6 columns">
                <label for="textPromptId">TEXTPROMPTID</label><!--
                 --><input id="textPromptId" name="textPromptId" type="text" placeholder="TEXTPROMPTID" value="test Value" />
            </div>',
            "color:green;",
            "",
            strtoupper($this->testButtonId)
        );

        $trans = new DummyTranslator();
        $formatter = new \formgenerator\FormadataHtmlFormatter();

        $this->assertXmlStringEqualsXmlString($formattedFormOutput,
            $formatter->formatFilled($trans, $this->testFormData));
    }

    function testTextBasicFilledError() {
        $this->testFormData['fields']['dummy1']['type'] = "text";
        $this->testFormData['fields']['dummy1']['value'] = "test Value";
        $this->testFormData['error'] = ["test_error" => ['dummy1']];

        $formattedFormOutput = sprintf(
            $this->testFormOutput,
            $this->testId,
            strtoupper($this->testTitleId),
            strtoupper($this->testDescriptionId),
            '<div class="large-3 medium-6 columns">
                <label for="textPromptId">TEXTPROMPTID</label><!--
                 --><input id="textPromptId" name="textPromptId" type="text" placeholder="TEXTPROMPTID" value="test Value" />
            </div>',
            "color:red;",
            "TEST_ERROR",
            strtoupper($this->testButtonId)
        );

        $trans = new DummyTranslator();
        $formatter = new \formgenerator\FormadataHtmlFormatter();

        $this->assertXmlStringEqualsXmlString($formattedFormOutput, $formatter->formatFilled($trans, $this->testFormData));
    }
    function testTextBasicEmptyNoError() {
        $this->testFormData['fields']['dummy1']['type'] = "text";
        $this->testFormData['fields']['dummy1']['value'] = "test Value";
        $this->testFormData['error'] = ["test_error" => ['dummy1']];

        $formattedFormOutput = sprintf(
            $this->testFormOutput,
            $this->testId,
            strtoupper($this->testTitleId),
            strtoupper($this->testDescriptionId),
            '<div class="large-3 medium-6 columns">
                <label for="textPromptId">TEXTPROMPTID</label><!--
                 --><input id="textPromptId" name="textPromptId" type="text" placeholder="TEXTPROMPTID" value="" />
            </div>',
            "color:green;",
            "",
            strtoupper($this->testButtonId)
        );

        $trans = new DummyTranslator();
        $formatter = new \formgenerator\FormadataHtmlFormatter();

        $this->assertXmlStringEqualsXmlString($formattedFormOutput, $formatter->formatEmpty($trans, $this->testFormData));
    }

    function testEmailBasic() {
        $this->testFormData['fields']['dummy1']['type'] = "email";

        $formattedFormOutput = sprintf(
            $this->testFormOutput,
            $this->testId,
            strtoupper($this->testTitleId),
            strtoupper($this->testDescriptionId),
            '<div class="large-3 medium-6 columns">
                <label for="textPromptId">TEXTPROMPTID</label><!--
                     --><input id="textPromptId" name="textPromptId" type="email" placeholder="TEXTPROMPTID" value="" />
                </div>',
            "color:green;",
            "",
            strtoupper($this->testButtonId)
        );

        $trans = new DummyTranslator();
        $formatter = new \formgenerator\FormadataHtmlFormatter();

        $this->assertXmlStringEqualsXmlString($formattedFormOutput, $formatter->formatEmpty($trans, $this->testFormData));
    }

    function testEmailBasicRequired() {
        $this->testFormData['fields']['dummy1']['type'] = "email";
        $this->testFormData['fields']['dummy1']['required'] = true;

        $formattedFormOutput = sprintf(
            $this->testFormOutput,
            $this->testId,
            strtoupper($this->testTitleId),
            strtoupper($this->testDescriptionId),
            '<div class="large-3 medium-6 columns">
                <label for="textPromptId">TEXTPROMPTID</label><!--
                     --><input id="textPromptId" name="textPromptId" type="email" placeholder="TEXTPROMPTID" value="" required="" />
                </div>',
            "color:green;",
            "",
            strtoupper($this->testButtonId)
        );

        $trans = new DummyTranslator();
        $formatter = new \formgenerator\FormadataHtmlFormatter();

        $this->assertXmlStringEqualsXmlString($formattedFormOutput, $formatter->formatEmpty($trans, $this->testFormData));
    }

    function testPhoneBasic() {
        $this->testFormData['fields']['dummy1']['type'] = "phone";

        $formattedFormOutput = sprintf(
            $this->testFormOutput,
            $this->testId,
            strtoupper($this->testTitleId),
            strtoupper($this->testDescriptionId),
            '<div class="large-3 medium-6 columns">
                    <label for="textPromptId">TEXTPROMPTID</label><!--
                     --><input id="textPromptId" name="textPromptId" type="text" placeholder="TEXTPROMPTID" value="" />
                </div>',
            "color:green;",
            "",
            strtoupper($this->testButtonId)
        );

        $trans = new DummyTranslator();
        $formatter = new \formgenerator\FormadataHtmlFormatter();

        $this->assertXmlStringEqualsXmlString($formattedFormOutput, $formatter->formatEmpty($trans, $this->testFormData));
    }

    function testPhoneBasicRequired() {
        $this->testFormData['fields']['dummy1']['type'] = "phone";
        $this->testFormData['fields']['dummy1']['required'] = true;

        $formattedFormOutput = sprintf(
            $this->testFormOutput,
            $this->testId,
            strtoupper($this->testTitleId),
            strtoupper($this->testDescriptionId),
            '<div class="large-3 medium-6 columns">
                    <label for="textPromptId">TEXTPROMPTID</label><!--
                     --><input id="textPromptId" name="textPromptId" type="text" placeholder="TEXTPROMPTID" value="" required="" />
                </div>',
            "color:green;",
            "",
            strtoupper($this->testButtonId)
        );

        $trans = new DummyTranslator();
        $formatter = new \formgenerator\FormadataHtmlFormatter();

        $this->assertXmlStringEqualsXmlString($formattedFormOutput, $formatter->formatEmpty($trans, $this->testFormData));
    }

    function testTextareaBasic() {
        $this->testFormData['fields']['dummy1']['type'] = "textarea";

        $formattedFormOutput = sprintf(
            $this->testFormOutput,
            $this->testId,
            strtoupper($this->testTitleId),
            strtoupper($this->testDescriptionId),
            '<div class="large-3 medium-6 columns">
                    <label for="textPromptId">TEXTPROMPTID</label><!--
                     --><textarea id="textPromptId" name="textPromptId" rows="1" placeholder="TEXTPROMPTID" ></textarea><!--
                 -->
                </div>',
            "color:green;",
            "",
            strtoupper($this->testButtonId)
        );

        $trans = new DummyTranslator();
        $formatter = new \formgenerator\FormadataHtmlFormatter();

        $this->assertXmlStringEqualsXmlString($formattedFormOutput, $formatter->formatEmpty($trans, $this->testFormData));
    }

    function testTextareaBasicRequired() {
        $this->testFormData['fields']['dummy1']['type'] = "textarea";
        $this->testFormData['fields']['dummy1']['required'] = true;

        $formattedFormOutput = sprintf(
            $this->testFormOutput,
            $this->testId,
            strtoupper($this->testTitleId),
            strtoupper($this->testDescriptionId),
            '<div class="large-3 medium-6 columns">
                    <label for="textPromptId">TEXTPROMPTID</label>
                    <textarea id="textPromptId" name="textPromptId" rows="1" placeholder="TEXTPROMPTID" required=""></textarea>
                </div>',
            "color:green;",
            "",
            strtoupper($this->testButtonId)
        );

        $trans = new DummyTranslator();
        $formatter = new \formgenerator\FormadataHtmlFormatter();

        $expectedDom = new DomDocument();
        $expectedDom->loadHTML($formattedFormOutput);
        $expectedDom->preserveWhiteSpace = false;

        $actualDom = new DomDocument();
        $actualDom->loadHTML($formatter->formatEmpty($trans, $this->testFormData));
        $actualDom->preserveWhiteSpace = false;

        $this->assertXmlStringEqualsXmlString($formattedFormOutput, $formatter->formatEmpty($trans, $this->testFormData));
    }

    function testTextBasicFilledErrorFormatted() {
        $this->testFormData['fields']['dummy1']['type'] = "text";
        $this->testFormData['fields']['dummy1']['value'] = "test Value";
        $this->testFormData['error'] = ["errors%" => ['dummy1']];

        $formattedFormOutput = sprintf(
            $this->testFormOutput,
            $this->testId,
            strrev($this->testTitleId),
            strrev($this->testDescriptionId),
            '<div class="large-3 medium-6 columns">
                <label for="textPromptId">dItpmorPtxet</label><!--
                 --><input id="textPromptId" name="textPromptId" type="text" placeholder="dItpmorPtxet" value="test Value" />
            </div>',
            "color:red;",
            "textPromptIdrorre",
            "dInottuBtset"
        );

        $trans = new DummyTranslator2();
        $formatter = new \formgenerator\FormadataHtmlFormatter();

        $this->assertXmlStringEqualsXmlString($formattedFormOutput, $formatter->formatFilled($trans, $this->testFormData));
    }

    function testDropdownBasic() {
        $this->testFormData['fields']['dummy1']['type'] = "dropdown";
        $this->testFormData['fields']['dummy1']['options'] = array("a" => "aopt", "b" => "bopt");

        $formattedFormOutput = sprintf(
            $this->testFormOutput,
            $this->testId,
            strtoupper($this->testTitleId),
            strtoupper($this->testDescriptionId),
            '<div class="large-3 medium-6 columns">
                    <label for="textPromptId">TEXTPROMPTID</label><!--
                     --><select id="textPromptId" name="textPromptId">
						<option value="" disabled="" style="display:none;" selected="selected">TEXTPROMPTID</option>
						<option value="a">AOPT</option>
						<option value="b">BOPT</option>
					</select><!--
                 -->
                </div>',
            "color:green;",
            "",
            strtoupper($this->testButtonId)
        );

        $trans = new DummyTranslator();
        $formatter = new \formgenerator\FormadataHtmlFormatter();

        $this->assertXmlStringEqualsXmlString($formattedFormOutput, $formatter->formatEmpty($trans, $this->testFormData));
    }

    function testDropdownBasicRequired() {
        $this->testFormData['fields']['dummy1']['type'] = "dropdown";
        $this->testFormData['fields']['dummy1']['required'] = true;
        $this->testFormData['fields']['dummy1']['options'] = array("a" => "aopt", "b" => "bopt");

        $formattedFormOutput = sprintf(
            $this->testFormOutput,
            $this->testId,
            strtoupper($this->testTitleId),
            strtoupper($this->testDescriptionId),
            '<div class="large-3 medium-6 columns">
                    <label for="textPromptId">TEXTPROMPTID</label><!--
                     --><select id="textPromptId" name="textPromptId" required="">
						<option value="" disabled="" style="display:none;" selected="selected">TEXTPROMPTID</option>
						<option value="a">AOPT</option>
						<option value="b">BOPT</option>
					</select><!--
                 -->
                </div>',
            "color:green;",
            "",
            strtoupper($this->testButtonId)
        );

        $trans = new DummyTranslator();
        $formatter = new \formgenerator\FormadataHtmlFormatter();

        $this->assertXmlStringEqualsXmlString($formattedFormOutput, $formatter->formatEmpty($trans, $this->testFormData));
    }

    function testDropdownBasicFilled() {
        $this->testFormData['fields']['dummy1']['type'] = "dropdown";
        $this->testFormData['fields']['dummy1']['options'] = array("a" => "aopt", "b" => "bopt");
        $this->testFormData['fields']['dummy1']['value'] = "a";

        $formattedFormOutput = sprintf(
            $this->testFormOutput,
            $this->testId,
            strtoupper($this->testTitleId),
            strtoupper($this->testDescriptionId),
            '<div class="large-3 medium-6 columns">
                    <label for="textPromptId">TEXTPROMPTID</label><!--
                     --><select id="textPromptId" name="textPromptId">
						<option value="" disabled="" style="display:none;" selected="selected">TEXTPROMPTID</option>
						<option value="a" selected="selected">AOPT</option>
						<option value="b">BOPT</option>
					</select><!--
                 -->
                </div>',
            "color:green;",
            "",
            strtoupper($this->testButtonId)
        );

        $trans = new DummyTranslator();
        $formatter = new \formgenerator\FormadataHtmlFormatter();

        $this->assertXmlStringEqualsXmlString($formattedFormOutput, $formatter->formatFilled($trans, $this->testFormData));
    }

    function testSuccessMessage() {
        $this->testFormData['fields']['dummy1']['type'] = "text";
        $this->testFormData['success'] = "$this->testSuccessId";

        $formattedFormOutput = sprintf(
            $this->testFormOutput,
            $this->testId,
            strtoupper($this->testTitleId),
            strtoupper($this->testDescriptionId),
            '<div class="large-3 medium-6 columns">
                <label for="textPromptId">TEXTPROMPTID</label><!--
                 --><input id="textPromptId" name="textPromptId" type="text" placeholder="TEXTPROMPTID" value="" />
            </div>',
            "color:green;",
            strtoupper($this->testSuccessId),
            strtoupper($this->testButtonId)
        );

        $trans = new DummyTranslator();
        $formatter = new \formgenerator\FormadataHtmlFormatter();

        $this->assertXmlStringEqualsXmlString($formattedFormOutput, $formatter->formatEmpty($trans, $this->testFormData));
        $this->assertXmlStringEqualsXmlString($formattedFormOutput, $formatter->formatFilled($trans, $this->testFormData));
    }
}
