<?php
/**
 * Created by IntelliJ IDEA.
 * User: james
 * Date: 15/09/15
 * Time: 10:24 AM
 */

namespace formgenerator;


interface IMailer
{
    function sendMail($from, $to, $subject, $body);
}