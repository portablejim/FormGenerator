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
use formgenerator\ITranslator;

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
    protected $prefixKey;
    /**
     * @var ITranslator
     */
    private $translator;

    function __construct($name, $config, IMailer $mailer, ITranslator $translator)
    {
        $this->mailer = $mailer;
        $this->translator = $translator;

        if(is_array($config))
        {
            $this->sendTo = array_key_exists('toAddress', $config) ? $config['toAddress'] : "";
            $this->sendSubject = "Response from online form";
            $this->name = $name;
            $this->title = array_key_exists('titleId', $config) ? $config['titleId'] : "";
            $this->description = array_key_exists('descriptionId', $config) ? $config['descriptionId'] : "";
            $this->buttonTextId = array_key_exists('buttonStringId', $config) ? $config['buttonStringId'] : "";
            $this->successTextId = array_key_exists('successId', $config) ? $config['successId'] : "";
            $this->prefixKey = array_key_exists('prefix', $config) ? $config['prefix'] : "";
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
            $mailBody = sprintf("New response for form '%s' with referrer of %s from %s\n\n", $this->name, $referringUrl, $ipAddress);

            $fullTo = $this->sendTo;

            foreach($this->formFields as $field)
            {
                if($field instanceof IFormElement)
                {
                    if(strlen($this->prefixKey) > 0 && $field->getName() === $this->prefixKey) {
                        $fullTo = $field->getValue() . $fullTo;
                    }
                    $mailBody .= sprintf("%s: %s\n", $this->translator->get($field->getName()), $field->getValue());
                }
            }

            $this->mailer->sendMail($fullTo, $this->sendSubject, $mailBody);
        }
    }

    public function getFormdataArray()
    {
        $fieldsFormdata = array();
        $error = array();

        foreach($this->formFields as $field) {
            if($field instanceof IFormElement) {
                $dataArray = $field->getDataArray();
                $fieldsFormdata[] = $dataArray;
                if(!$field->isValid()) {
                    $fieldError = $field->getErrorMessage();
                    if (strlen($fieldError) > 0) {
                        $error[$fieldError] = array($field->getName());
                    }
                }
            }
        }

        $returnArray = array(
            "id" => $this->name,
            "titleId" => $this->title,
            "descriptionId" => $this->description,
            "fields" => $fieldsFormdata,
            "buttonId" => $this->buttonTextId,
            "successId" => $this->successTextId
        );
        if(count($error) > 0) {
            $returnArray["error"] = $error;
        }
        if($this->success) {
            $returnArray["success"] = $this->successTextId;
        }
        return $returnArray;
    }
}