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
    protected $sendFrom;
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
            $this->sendFrom = array_key_exists('fromField', $config) ? $config['fromField'] : "";
            $this->sendSubject = "Response from '%s' form";
            $this->name = $name;
            $this->title = array_key_exists('titleId', $config) ? $config['titleId'] : "";
            $this->description = array_key_exists('descriptionId', $config) ? $config['descriptionId'] : "";
            $this->buttonTextId = array_key_exists('buttonStringId', $config) ? $config['buttonStringId'] : "";
            $this->successTextId = array_key_exists('successId', $config) ? $config['successId'] : "";
            $this->prefixKey = array_key_exists('toPrefix', $config) ? $config['toPrefix'] : "";
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
            $fullFrom = "";
            $fullSubject = sprintf($this->sendSubject, $this->name);

            foreach($this->formFields as $field)
            {
                if($field instanceof IFormElement)
                {
                    if(strlen($this->prefixKey) > 0 && $field->getName() === $this->prefixKey) {
                        $fullTo = $field->getValue() . $fullTo;
                    }
                    if(strlen($this->sendFrom) > 0 && $field->getName() == $this->sendFrom) {
                        $fullFrom = $field->getValue();
                    }
                    $mailBody .= sprintf("%s: %s\n", $this->translator->get($field->getName()), $field->getValue());
                }
            }

            $this->mailer->sendMail($fullFrom, $fullTo, $fullSubject, $mailBody);
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