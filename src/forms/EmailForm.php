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

    protected $sendTo;
    protected $sendSubject;
    protected $name;

    function __construct($name, $config, IMailer $mailer)
    {
        $this->mailer = $mailer;

        if(is_array($config))
        {
            $this->sendTo = array_key_exists('destination', $config) ? $config['destination'] : "";
            $this->sendSubject = "Response from online form";
            $this->name = $name;
        }
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
            $mailBody = sprintf("New response for form '%s'\n\n", $this->name);

            foreach($this->formFields as $field)
            {
                if($field instanceof IFormElement)
                {
                    $mailBody .= sprintf("%s: %s\n", $field->getName(), $field->getValue());
                }
            }

            $this->mailer->sendMail($this->sendTo, $this->sendSubject, $mailBody);
        }
    }

    public function getFormdataArray()
    {
        // TODO: Implement getFormdataArray() method.
    }
}