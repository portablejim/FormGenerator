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
    protected $title;
    protected $description;
    protected $buttonTextId;
    protected $successTextId;

    function __construct($name, $config, IMailer $mailer)
    {
        $this->mailer = $mailer;

        if(is_array($config))
        {
            $this->sendTo = array_key_exists('destination', $config) ? $config['destination'] : "";
            $this->sendSubject = "Response from online form";
            $this->name = $name;
            $this->title = array_key_exists('titleId', $config) ? $config['titleId'] : "";
            $this->description = array_key_exists('descriptionId', $config) ? $config['descriptionId'] : "";
            $this->buttonTextId = array_key_exists('buttonStringId', $config) ? $config['buttonStringId'] : "";
            $this->successTextId = array_key_exists('successId', $config) ? $config['successId'] : "";
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

    public function submitForm($referringUrl, $ipAddress)
    {
        if($this->isValid()) {
            $mailBody = sprintf("New response for form '%s' from %s\n\n", $this->name, $ipAddress);

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
        $fieldsFormdata = array();

        foreach($this->formFields as $field) {
            if($field instanceof IFormElement) {
                $fieldsFormdata[] = $field->getDataArray();
            }
        }

        return array(
            "id" => $this->name,
            "titleId" => $this->title,
            "fields" => $fieldsFormdata,
            "buttonId" => $this->buttonTextId,
            "successId" => $this->successTextId
        );
    }
}