<?php
/**
 * Created by IntelliJ IDEA.
 * User: james
 * Date: 13/09/15
 * Time: 12:16 PM
 */

namespace formgenerator;


interface IFormElement
{
    public function getName();

    public function getValue();

    public function fillData($data);

    public function isValid();

    public function getDataArray();
}