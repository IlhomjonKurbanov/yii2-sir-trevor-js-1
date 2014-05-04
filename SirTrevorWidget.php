<?php
/**
 * 2014 , 05 03

Copyright (c) 2014, Pascal Brewing <pascalbrewing@gmail.com>
All rights reserved.

Redistribution and use in source and binary forms, with or without modification,
are permitted provided that the following conditions are met:

 * Redistributions of source code must retain the above copyright notice, this
list of conditions and the following disclaimer.

 * Redistributions in binary form must reproduce the above copyright notice, this
list of conditions and the following disclaimer in the documentation and/or
other materials provided with the distribution.

 * Neither the name of the {organization} nor the names of its
contributors may be used to endorse or promote products derived from
this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR
ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */
namespace drmabuse\sirtrevorjs;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\widgets\InputWidget;
use drmabuse\sirtrevorjs\assets\SirTrevorCompleteAsset;

/**
 * Class SirTrevorWidget
 * @package yii2-sirtrevorjs
 */
class SirTrevorWidget extends InputWidget {

    public $debug           = 'false';
    public $language        = 'en';
    public $blockOptions    = null;
    public $blockTypes      = ["Heading","Text","List","Quote","Image","Video","Tweet"];
    public $element         = '.sir-trevor';
    public $imageUploadUrl  = 'site/upload';
    public $initJs          = null;

    public $options;
    public $assetMode       = 'complete';


    public function init(){
        parent::init();

        if (is_null($this->blockOptions)) {
            $this->blockOptions = Json::encode([
                'el'            => new JsExpression("$('{$this->element}')"),
                'blockTypes'    => $this->blockTypes
            ]);
        }

        if (is_null($this->initJs)) {
            $this->initJs = 'SirTrevor.DEBUG = ' . $this->debug . ';'.PHP_EOL;
            $this->initJs .= 'SirTrevor.LANGUAGE = "' . $this->language . '";'.PHP_EOL;
            $this->initJs .= 'SirTrevor.setDefaults({ uploadUrl: "' . $this->imageUploadUrl . '" });'.PHP_EOL;
            $this->initJs .= "window.editor = new SirTrevor.Editor(" . $this->blockOptions . ");".PHP_EOL;
        }

        $this->options['class'] = $this->element;
        Yii::setAlias('@sirtrevorjs',dirname(__FILE__));

        $this->registerAsset();

    }

    public function run(){
        echo $this->renderInput();
    }

    /**
     * register the concated files
     */
    private function registerAsset(){
        SirTrevorCompleteAsset::register($this->view)->language = $this->language;
        $this->view->registerJs('$(function(){' . $this->initJs . '});');
    }

    /**
     * Render the text area input
     */
    protected function renderInput()
    {
        if ($this->hasModel()) {
            $input = Html::activeTextArea($this->model, $this->attribute, $this->options);
        } else {
            $input = Html::textArea($this->name, $this->value, $this->options);
        }

        return $input;
    }

} 