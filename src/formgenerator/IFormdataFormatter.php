<?php
/**
 * Created by IntelliJ IDEA.
 * User: james
 * Date: 21/09/15
 * Time: 1:21 PM
 */

namespace formgenerator;


interface IFormdataFormatter
{
    public function formatEmpty(ITranslator $translator, $formdata);
    public function formatFilled(ITranslator $translator, $formdata);
}