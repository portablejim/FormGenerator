<?php
/**
 * Created by IntelliJ IDEA.
 * User: james
 * Date: 15/09/15
 * Time: 10:42 AM
 */

namespace testForms;


use formgenerator\IMailer;

class DummyMailer implements IMailer
{
    public $mailSent = array();

    function sendMail($from, $to, $subject, $body)
    {
        $this->mailSent[] = array('from' => $from, 'to' => $to, 'subject' => $subject, 'body' => $body);
    }
}