<?php

//require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'source'.DIRECTORY_SEPARATOR.'ckeditor'.DIRECTORY_SEPARATOR.'ckeditor.php');

class KCFinder extends CInputWidget
{
    public $kcFinderPath;
    public $height = '375px';
    public $width = '100%';
    public $toolbarSet;
    public $config;
    public $filespath;
    public $filesurl;
    public $value;
    public $name;

    public $type = 'images';

    private $baseurl;

    protected $cssFile;
    protected $jsFile;
    protected $openURL;

    public function init()
    {
        $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'source';
        $this->baseurl = Yii::app()->getAssetManager()->publish($dir);
        $this->kcFinderPath = $this->baseurl . "/kcfinder/";

        if ($this->cssFile===null) {
            $this->cssFile=Yii::app()->getAssetManager()->publish($dir . DIRECTORY_SEPARATOR . 'kcfinder.select.css');
        }
        if ($this->jsFile===null) {
            $this->jsFile=Yii::app()->getAssetManager()->publish($dir . DIRECTORY_SEPARATOR . 'kcfinder.select.js');
        }
        $this->registerClientScript();

        parent::init();
    }

    public function run()
    {
        if (!$this->hasModel() && !isset($this->name)) {
            throw new CHttpException(500, 'Parameters "model" and "attribute" or "name" have to be set!');
        }

        $ok = $this->attribute;

        if (!empty($this->model) && $ok) {
            $this->value = CHtml::resolveValue($this->model, $this->attribute);
            list($this->name, $id) = $this->resolveNameID();
        } elseif (!empty($this->name)) {
            $this->value = isset($this->value) ? $this->value : null;
        }

        $session = new CHttpSession;
        $session->open();

        $session['KCFINDER'] = array(
            'disabled' => false,
            'uploadURL' => $this->filesurl,
            'uploadDir' => realpath($this->filespath) . DIRECTORY_SEPARATOR,
        );

        $this->openURL = $this->kcFinderPath.'browse.php?type='.$this->type;

        echo $this->out();
    }

    protected function registerClientScript()
    {
        $cs=Yii::app()->clientScript;
        $cs->registerCssFile($this->cssFile);
        $cs->registerScriptFile($this->jsFile);
    }

    protected function out()
    {
        if ($this->type == 'images') {
            $res = '<div class="thumbnail kcf-select" onclick="openKCFinder(this)" data-url="'.$this->openURL.'">';
            $res .= ($this->hasModel() ? CHtml::activeHiddenField($this->model, $this->attribute) : CHtml::hiddenField($this->name, $this->value));
            if ($this->value) {
                $res .= '<img src="'.$this->value.'" style="visibility:visible" />';
            } else {
                $res .= '<div style="margin:5px" class="preload">Выбрать изображение</div></div>';
            }
        } else {
            $res = '<div class="input-append">'
                        .($this->hasModel() ? CHtml::activeTextField($this->model, $this->attribute) : CHtml::textField($this->name, $this->value))
                        .'<button class="btn" type="button" onclick="openKCFinderInput(this)" data-url="'.$this->openURL.'">Выбрать файл</button>
                    </div>';
        }

        return $res;
    }
}

?>