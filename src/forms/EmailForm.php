<?php
/**
 * Created by IntelliJ IDEA.
 * User: james
 * Date: 15/09/15
 * Time: 10:19 AM
 */

namespace formgenerator\forms;


use formgenerator\GeneralForm;
use formgenerator\IFormElement;
use formgenerator\IMailer;

class EmailForm extends GeneralForm
{
    protected $mailer;

    function __construct($config, IMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function isValid()
    {
        foreach($this->formFields as $field)
        {
            if($field instanceof IFormElement && !($field->isValid()))
            {
                return false;
            }
        }
        return true;
    }

    public function submitForm($referringUri)
    {
        if($this->isValid()) {
            $this->mailer->sendMail("", "", "");
        }
    }

    public function getFormdataArray()
    {
        // TODO: Implement getFormdataArray() method.
    }
}