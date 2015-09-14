<?php
/**
 * Created by IntelliJ IDEA.
 * User: james
 * Date: 14/09/15
 * Time: 3:34 PM
 */

namespace testForms;


use formgenerator\Associative;
use formgenerator\Field;
use formgenerator\GeneralForm;
use formgenerator\IFormElement;

class TestGeneralForm extends GeneralForm
{
    /**
     * @param array Associative array of 'field name' => 'value' to assign
     * values to fields according to their field name.
     * @return mixed
     */
    public function fillFields(array $formData)
    {
        // TODO: Implement fillFields() method.
    }

    public function isValid()
    {
        // TODO: Implement isValid() method.
    }

    public function submitForm($referringUri)
    {
        // TODO: Implement submitForm() method.
    }

    public function getFormdataArray()
    {
        // TODO: Implement getFormdataArray() method.
    }
}