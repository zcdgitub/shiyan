<?php

Yii::import('zii.widgets.jui.CJuiWidget');

/**
 *
 * @author haohetao@gmail.com
 *        
 */
class JuiSpinner extends CJuiWidget
{
	/**
	 * 创建jQuery.ui.spinner的widget对象
	 * @see CJuiWidget::init()
	 */
	public function init()
	{
		parent::init();
		$id=$this->getId();
		if (isset($this->htmlOptions['id']))
			$id = $this->htmlOptions['id'];
		else
			$this->htmlOptions['id']=$id;

		$options=empty($this->options) ? '' : CJavaScript::encode($this->options);
		Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$id,"jQuery('#{$id}').spinner($options);");
	}

}

?>