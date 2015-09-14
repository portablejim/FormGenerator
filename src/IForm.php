<?php
/**
 * Created by IntelliJ IDEA.
 * User: james
 * Date: 13/09/15
 * Time: 11:50 AM
 */

namespace formgenerator;


interface IForm
{
    /**
     * @param IFormElement Field/Element to add to form's field list.
     * @return null
     */
    public function addField(IFormElement $element);

    /**
     * @return mixed Current fields in the form
     */
    public function getFields();

    /**
     * @param array Associative array of 'field name' => 'value' to assign
     * values to fields according to their field name.
     * @return mixed
     */
    public function fillFields(array $formData);

    public function isValid();

    public function submitForm($referringUri);

    public function getFormdataArray();
}