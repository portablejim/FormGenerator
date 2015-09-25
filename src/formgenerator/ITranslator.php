<?php
/**
 * Created by IntelliJ IDEA.
 * User: james
 * Date: 19/09/15
 * Time: 7:38 PM
 */

namespace formgenerator;


interface ITranslator
{
    public function get($key);

    public function langTag();
}