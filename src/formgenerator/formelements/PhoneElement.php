<?php
/**
 * Created by IntelliJ IDEA.
 * User: james
 * Date: 2/10/15
 * Time: 1:19 PM
 */

namespace formgenerator\formelements;


class PhoneElement extends TextElement
{

    public function isValid()
    {
        $parentValid = parent::isValid();

        if ($parentValid && preg_match('/^[0-9a-z\-\.#* ]{1,50}', $this->value)) {
            return true;
        } else {
            return false;
        }
    }

    public function getDataArray()
    {
        $output = parent::getDataArray();
        $output['type'] = "phone";
        return $output;
    }


}