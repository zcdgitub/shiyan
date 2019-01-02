<?php

/**
 * Created by PhpStorm.
 * User: лн
 * Date: 2015/7/12
 * Time: 19:10
 */
class Captcha extends CCaptcha
{
	public function registerClientScript()
	{
		$cs=Yii::app()->clientScript;
		$id=$this->imageOptions['id'];
		$url=$this->getController()->createUrl($this->captchaAction,array(CCaptchaAction::REFRESH_GET_VAR=>true));

		$js="";
		if($this->showRefreshButton)
		{
			// reserve a place in the registered script so that any enclosing button js code appears after the captcha js
			$cs->registerScript('Yii.CCaptcha#'.$id,'// dummy');
			$label=$this->buttonLabel===null?Yii::t('yii','Get a new code'):$this->buttonLabel;
			$options=$this->buttonOptions;
			if(isset($options['id']))
				$buttonID=$options['id'];
			else
				$buttonID=$options['id']=$id.'_button';
			if($this->buttonType==='button')
				$html=CHtml::button($label, $options);
			else
				$html=CHtml::link($label, $url, $options);
			$js="jQuery('#$id').after(".CJSON::encode($html).");";
			$selector="#$buttonID";
		}

		if($this->clickableImage)
			$selector=isset($selector) ? "$selector, #$id" : "#$id";

		if(!isset($selector))
			return;

		$js.="
jQuery(document).on('click', '$selector', function(){
jQuery.ajax({
    url: ".CJSON::encode($url).",
    dataType: 'json',
    cache: false,
    success: function(data) {
        jQuery('#$id').attr('src', data['url']);
        jQuery('body').data('{$this->captchaAction}.hash', [data['hash1'], data['hash2']]);
    }
});
return false;
});
";
		$url=$this->getController()->createUrl($this->captchaAction,['v'=>new CJavaScriptExpression("Math.random()")]);
		$js2="jQuery(document).ready(function(){jQuery('#$id').attr('src','$url');});";
		$cs->registerScript('Yii.CCaptcha#'.$id,$js2);
		$cs->registerScript('Yii.CCaptcha1#'.$id,$js);
	}
	protected function renderImage()
	{
		if(!isset($this->imageOptions['id']))
			$this->imageOptions['id']=$this->getId();

		//$url=$this->getController()->createUrl($this->captchaAction,array('v'=>uniqid()));
		$url='';
		$alt=isset($this->imageOptions['alt'])?$this->imageOptions['alt']:'';
		echo CHtml::image($url,$alt,$this->imageOptions);
	}
}