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
    protected $templateText, $templateEmail, $templatePhone, $templateDate;

    public function __construct()
    {
        $this->elementWidths = array(
            1 => "large-3 medium-6 columns",
            2 => "large-6 medium-12 columns",
            4 => "large-12 medium-12 columns"
        );

        $this->template = '<form id="%s" method="post" action="/%s/forms/%s">
			<div>
				<h2 class="text-center">%s</h2>
				<p class="text-center">%s</p>
			</div>
			<div>
			    %s
            </div>
			<div class="row">
				<p class="text-center" style="color:green;">%s</p>
			</div>
			<div class="row">
				<div class="large-6 large-centered columns">
					<input id="submit" name="submit" type="submit" class="button expand alert" value="%s" />
				</div>
			</div>
		</form>';

        $this->templateText = '<div class="%s">
                <label>%s<input id="%s" name="%s" type="text" placeholder="%s" value="%s"/></label>
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
        $successMessage = "";
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
                $fields .= sprintf($this->templateText, $widthCss, $placeholder, $placeholderId, $placeholderId, $placeholder, $value);
            }
        }

        $outputHtml = sprintf($this->template,
            $id, $translator->langTag(), $id,
            $title,
            $description,
            $fields,
            $successMessage,
            $buttonText
        );

        return $outputHtml;
    }
}