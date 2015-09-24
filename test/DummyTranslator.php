<?php

/**
 * Created by IntelliJ IDEA.
 * User: james
 * Date: 21/09/15
 * Time: 1:54 PM
 */
class DummyTranslator implements \formgenerator\ITranslator
{

    public function get($key)
    {
        return strtoupper($key);
    }

    public function langTag()
    {
        return "langTag";
    }
}