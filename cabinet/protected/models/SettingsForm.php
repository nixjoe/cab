<?php

class SettingsForm extends CFormModel {

    const BD_SEND_ALL = '1';
    const BD_SEND_ONLY = '2';
    const BD_NO_SEND = '0';

    public $bd_sendType = 0;
    public $bd_countries = array();

    private $file = null;

    public function rules() {
        return array(
            array('bd_sendType, bd_countries','safe'),
        );
    }

    public function load($file) {
        if (!is_file($file)) {
            throw new CException('File "'.$file.'" not found');
        }
        $this->file = $file;
        $content = file_get_contents($file);
        $arr = unserialize($content);
        if (is_array($arr)) {
            $this->setAttributes($arr);
        }
    }

    public function save($file=null) {
        $file or $file = $this->file;
        if (!$file || !is_writable($file)) {
            throw new CException('File "'.$file.'" is not writable');
        }
        $str = serialize($this->getAttributes());
        return file_put_contents($file, $str);
    }
} 