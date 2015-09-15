<?php
/**
 * Created by IntelliJ IDEA.
 * User: james
 * Date: 15/09/15
 * Time: 10:19 AM
 */

namespace formgenerator\forms;


use formgenerator\GeneralForm;
use formgenerator\IMailer;

class EmailForm extends GeneralForm
{
    function __construct($config, IMailer $mailer)
    {

    }

    public function isValid()
    {
        return true;
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