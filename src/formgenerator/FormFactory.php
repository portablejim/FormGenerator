<?php
/**
 * Created by IntelliJ IDEA.
 * User: james
 * Date: 2/10/15
 * Time: 1:43 PM
 */

namespace formgenerator;

use formgenerator\formelements\DropdownElement;
use formgenerator\formelements\EmailElement;
use formgenerator\formelements\PhoneElement;
use formgenerator\formelements\TextareaElement;
use formgenerator\formelements\TextElement;
use formgenerator\forms\EmailForm;

include_once "loadall.php";

class FormFactory
{
    private $folder;

    public function __construct($folder)
    {
        $this->folder = $folder;
    }

    function join_paths()
    {
        $paths = array();

        foreach (func_get_args() as $arg) {
            if ($arg !== '') {
                $paths[] = $arg;
            }
        }

        return preg_replace('#/+#', '/', join('/', $paths));
    }

    function parse_ini_file_multi($file, $process_sections = false, $scanner_mode = INI_SCANNER_NORMAL) {
        $explode_str = '.';
        $escape_char = "'";
        // load ini file the normal way
        $data = parse_ini_file($file, $process_sections, $scanner_mode);
        if (!$process_sections) {
            $data = array($data);
        }
        foreach ($data as $section_key => $section) {
            // loop inside the section
            foreach ($section as $key => $value) {
                if (strpos($key, $explode_str)) {
                    if (substr($key, 0, 1) !== $escape_char) {
                        // key has a dot. Explode on it, then parse each subkeys
                        // and set value at the right place thanks to references
                        $sub_keys = explode($explode_str, $key);
                        $subs =& $data[$section_key];
                        foreach ($sub_keys as $sub_key) {
                            if (!isset($subs[$sub_key])) {
                                $subs[$sub_key] = [];
                            }
                            $subs =& $subs[$sub_key];
                        }
                        // set the value at the right place
                        $subs = $value;
                        // unset the dotted key, we don't need it anymore
                        unset($data[$section_key][$key]);
                    }
                    // we have escaped the key, so we keep dots as they are
                    else {
                        $new_key = trim($key, $escape_char);
                        $data[$section_key][$new_key] = $value;
                        unset($data[$section_key][$key]);
                    }
                }
            }
        }
        if (!$process_sections) {
            $data = $data[0];
        }
        return $data;
    }

    function test_for_array_keys($array, $keys) {
        foreach($keys as $key) {
            if(!array_key_exists($key, $array)) {
                return false;
            }
        }
        return true;
    }

    public function makeForm($formName, $translatorEmail, $mailer)
    {

        $iniFilename = $this->join_paths($this->folder, $formName . ".ini");

        if(file_exists($iniFilename)) {
            $iniFile = $this->parse_ini_file_multi($iniFilename, true);

            $newForm = null;

            if (array_key_exists("form", $iniFile)) {
                $formType = $iniFile["form"]["type"];
                if ($formType === "email") {
                    $newForm = new EmailForm($formName, $iniFile["form"], $mailer, $translatorEmail);
                }
            }

            if($newForm != null && array_key_exists("fields", $iniFile)) {
                assert($newForm instanceof IForm);
                foreach($iniFile["fields"] as $fieldName => $fieldSetting) {
                    $type = array_key_exists("type", $fieldSetting) ? $fieldSetting["type"] : "";
                    if($type === "text" && $this->test_for_array_keys($fieldSetting, array("name", "width", "promptId", "errorId", "required"))) {
                        $newField = new TextElement(
                            $fieldSetting["name"],
                            $fieldSetting["width"],
                            $fieldSetting["promptId"],
                            $fieldSetting["errorId"],
                            $fieldSetting["required"]
                            );
                        $newForm->addField($newField);
                    }
                    elseif($type === "textarea" && $this->test_for_array_keys($fieldSetting, array("name", "width", "height", "promptId", "errorId", "required"))) {
                        $newField = new TextareaElement(
                            $fieldSetting["name"],
                            $fieldSetting["width"],
                            $fieldSetting["height"],
                            $fieldSetting["promptId"],
                            $fieldSetting["errorId"],
                            $fieldSetting["required"]
                        );
                        $newForm->addField($newField);
                    }
                    elseif($type === "phone" && $this->test_for_array_keys($fieldSetting, array("name", "width", "promptId", "errorId", "required"))) {
                        $newField = new PhoneElement(
                            $fieldSetting["name"],
                            $fieldSetting["width"],
                            $fieldSetting["promptId"],
                            $fieldSetting["errorId"],
                            $fieldSetting["required"]
                        );
                        $newForm->addField($newField);
                    }
                    elseif($type === "email" && $this->test_for_array_keys($fieldSetting, array("name", "width", "promptId", "errorId", "required"))) {
                        $newField = new EmailElement(
                            $fieldSetting["name"],
                            $fieldSetting["width"],
                            $fieldSetting["promptId"],
                            $fieldSetting["errorId"],
                            $fieldSetting["required"]
                        );
                        $newForm->addField($newField);
                    }
                    elseif($type === "phone" && $this->test_for_array_keys($fieldSetting, array("name", "width", "promptId", "errorId", "required"))) {
                        $newField = new PhoneElement(
                            $fieldSetting["name"],
                            $fieldSetting["width"],
                            $fieldSetting["promptId"],
                            $fieldSetting["errorId"],
                            $fieldSetting["required"]
                        );
                        $newForm->addField($newField);
                    }
                    elseif($type === "dropdown" && $this->test_for_array_keys($fieldSetting, array("name", "width", "promptId", "errorId", "required", "options"))) {
                        $newField = new DropdownElement(
                            $fieldSetting["name"],
                            $fieldSetting["width"],
                            $fieldSetting["promptId"],
                            $fieldSetting["errorId"],
                            $fieldSetting["required"],
                            $fieldSetting["options"]
                        );
                        $newForm->addField($newField);
                    }
                }

            }
            return $newForm;
        }

        return null;
    }

}