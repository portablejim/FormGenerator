<?php
/**
 * Created by IntelliJ IDEA.
 * User: james
 * Date: 2/10/15
 * Time: 10:03 AM
 */

namespace formgenerator\formelements;


class EmailElement extends TextElement
{

    public function isValid()
    {
        if(strlen($this->value) == 0) {
            return !$this->required;
        }

        // RFC 3696 - max email length 254
        // Validate email using PHP filter.
        // Email must have "@" then "."
        if(strlen($this->value) > 254 || !filter_var($this->value, FILTER_VALIDATE_EMAIL) || !preg_match('/@.+\./', $this->value)) {
            return false;
        }

        return true;
    }

    public function getDataArray()
    {
        $output = parent::getDataArray();
        $output['type'] = "email";
        return $output;
    }
}