<?php
/**
 * Created by IntelliJ IDEA.
 * User: james
 * Date: 9/10/15
 * Time: 10:36 AM
 */

namespace formgenerator;


class StringTranslator implements ITranslator
{
    private $translation, $langTag;

    public function __construct($translation, $langTag) {
        $this->translation = $translation;
        $this->langTag = $langTag;
    }

    public function get($key)
    {
        return array_key_exists($key, $this->translation) ? $this->translation[$key] : "";
    }

    public function langTag()
    {
        return $this->langTag;
    }
}