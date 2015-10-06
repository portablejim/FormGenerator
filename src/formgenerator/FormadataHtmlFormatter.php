<?php
/**
 * Created by IntelliJ IDEA.
 * User: james
 * Date: 21/09/15
 * Time: 1:29 PM
 */

namespace formgenerator;


class FormadataHtmlFormatter implements IFormdataFormatter
{
    protected $elementWidths;
    protected $template;
    protected $templateText, $templateEmail, $templatePhone, $templateDate, $templateTextarea;

    public function __construct()
    {
        $this->elementWidths = array(
            1 => "large-3 medium-6 columns",
            2 => "large-6 medium-12 columns",
            4 => "large-12 medium-12 columns"
        );

        /** @noinspection HtmlUnknownTarget */
        $this->template = '<form id="%s" method="post" action="/%s/forms/%s">
			<div>
				<h2 class="text-center">%s</h2>
				<p class="text-center">%s</p>
			</div>
			<div>
			    %s
            </div>
			<div class="row">
			    %s
			</div>
			<div class="row">
				<div class="large-6 large-centered columns">
					<input id="submit" name="submit" type="submit" class="button expand alert" value="%s" />
				</div>
			</div>
		</form>';

        $this->templateError = '<p class="text-center" style="color:red;">%s</p>';
        $this->templateSuccess = '<p class="text-center" style="color:green;">%s</p>';

        $this->templateText = '<div class="%s">
                <label>%s<input id="%s" name="%s" type="text" placeholder="%s" value="%s"/></label>
            </div>';
        $this->templateEmail = '<div class="%s">
                <label>%s<input id="%s" name="%s" type="email" placeholder="%s" value="%s"/></label>
            </div>';
        $this->templatePhone = $this->templateText;
        /** @noinspection HtmlUnknownAttribute */
        $this->templateTextarea = '<div class="%s">
                    <label for="%s">%s</label>
                    <textarea id="%s" name="%s" rows="%d" placeholder="%s" %s >%s</textarea>
                </div>';
    }

    private function translate_or_empty(ITranslator $translator, $formdata, $key)
    {
        if ($translator == null || !is_array($formdata)) {
            return "";
        }

        return array_key_exists($key, $formdata) ? $translator->get($formdata[$key]) : "";
    }

    public function formatEmpty(ITranslator $translator, $formdata)
    {
        return $this->format($translator, $formdata, false);
    }

    public function formatFilled(ITranslator $translator, $formdata)
    {
        return $this->format($translator, $formdata, true);
    }

    protected function format(ITranslator $translator, $formdata, $fill)
    {
        if ($translator == null || !is_array($formdata)) {
            return "";
        }

        $id = array_key_exists('id', $formdata) ? $formdata['id'] : "";
        $title = $this->translate_or_empty($translator, $formdata, 'titleId');
        $description = $this->translate_or_empty($translator, $formdata, 'descriptionId');
        $messages = "";
        if (array_key_exists('error', $formdata) && is_array($formdata['error'])) {
            foreach($formdata['error'] as $errorMessage) {
                $candidateMessage = $translator->get($errorMessage);
                if(strlen($candidateMessage) > 0) {
                    $messages .= sprintf($this->templateError, $candidateMessage);
                }
            }
        }
        else{
            $messages .= sprintf($this->templateSuccess, $this->translate_or_empty($translator, $formdata, 'success'));
        }
        $buttonText = $this->translate_or_empty($translator, $formdata, 'buttonId');


        $fields = "";
        if (array_key_exists('fields', $formdata)) {
            foreach ($formdata['fields'] as $fieldArray) {
                $placeholder = $this->translate_or_empty($translator, $fieldArray, 'promptId');
                $placeholderId = array_key_exists('promptId', $fieldArray) ? $fieldArray['promptId'] : "";
                $value = ($fill and array_key_exists('value', $fieldArray)) ? $fieldArray['value'] : "";
                $widthCss = "columns";
                if(array_key_exists('width', $fieldArray)) {
                    if(array_key_exists($fieldArray['width'], $this->elementWidths)) {
                        $widthCss = $this->elementWidths[$fieldArray['width']];
                    }
                }
                $required = array_key_exists('required', $fieldArray) && $fieldArray['required'] === true ? 'required=""' : "";

                if(array_key_exists('type', $fieldArray)) {
                    if($fieldArray['type'] === "text") {
                        $fields .= sprintf(
                            $this->templateText,
                            $widthCss,
                            $placeholder,
                            $placeholderId,
                            $placeholderId,
                            $placeholder,
                            htmlspecialchars($value));
                    }
                    elseif($fieldArray['type'] === "email") {
                        $fields .= sprintf(
                            $this->templateEmail,
                            $widthCss,
                            $placeholder,
                            $placeholderId,
                            $placeholderId,
                            $placeholder,
                            htmlspecialchars($value));
                    }
                    elseif($fieldArray['type'] === "phone") {
                        $fields .= sprintf(
                            $this->templatePhone,
                            $widthCss,
                            $placeholder,
                            $placeholderId,
                            $placeholderId,
                            $placeholder,
                            htmlspecialchars($value));
                    }
                    elseif($fieldArray['type'] === "textarea") {
                        $rows = 1;
                        if(array_key_exists('rows', $fieldArray) && is_numeric($fieldArray['rows'])) {
                            $rows = $fieldArray['rows'];
                        }
                        $fields .= sprintf(
                            $this->templateTextarea,
                            $widthCss,
                            $placeholderId,
                            $placeholder,
                            $placeholderId,
                            $placeholderId,
                            $rows,
                            $placeholder,
                            $required,
                            htmlspecialchars($value));
                    }
                }
            }
        }

        $outputHtml = sprintf($this->template,
            $id, $translator->langTag(), $id,
            $title,
            $description,
            $fields,
            $messages,
            $buttonText
        );

        return $outputHtml;
    }
}