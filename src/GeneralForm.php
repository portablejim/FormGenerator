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
    /**
     * @param IFormElement Field/Element to add to form's field list.
     * @return null
     */
    public function addField(IFormElement $element)
    {

    }

    /**
     * @return mixed Current fields in the form
     */
    public function getFields()
    {
        return array();
    }

    /**
     * @param array Associative array of 'field name' => 'value' to assign
     * values to fields according to their field name.
     * @return mixed
     */
    public function fillFields(array $formData)
    {

    }
}