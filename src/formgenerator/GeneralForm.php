<?php
/**
 * Created by IntelliJ IDEA.
 * User: james
 * Date: 13/09/15
 * Time: 10:10 PM
 */

namespace formgenerator;

abstract class GeneralForm implements IForm
{
    protected $formFields = array();
    protected $success = false;

    /**
     * @param IFormElement $element Field/Element to add to form's field list.
     * @return null
     */
    public function addField(IFormElement $element)
    {
        assert(is_array($this->formFields));

        $this->formFields[] = $element;
    }

    /**
     * @return mixed Current fields in the form
     */
    public function getFields()
    {
        $outputFields = array();

        assert(is_array($this->formFields));

        foreach($this->formFields as $field)
        {
            if($field instanceof IFormElement)
            {
                $outputFields[$field->getName()] = $field;
            }
        }
        return $outputFields;
    }

    /**
     * @param array $formData Associative array of 'field name' => 'value' to assign
     * values to fields according to their field name.
     * @return mixed
     */
    public function fillFields(array $formData)
    {
        assert(is_array($this->formFields));

        if(array_key_exists("success", $formData)) {
            $this->success = true;
        }

        foreach($this->formFields as $field)
        {
            if($field instanceof IFormElement && is_array($formData))
            {
                if(array_key_exists($field->getName(), $formData))
                {
                    $field->fillData($formData[$field->getName()]);
                }
            }
            else
            {
                throw new \UnexpectedValueException("Form data should be an array. (or data structure is messed up.)");
            }
        }
    }
}