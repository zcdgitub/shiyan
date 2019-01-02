<?php

/** 
 * @author hetao
 * 
 */
class Form extends CForm
{
	public $activeForm=array(
		'class'=>'ActiveForm',
		'enableClientValidation'=>true,
		'enableAjaxValidation'=>true,
		'clientOptions'=>array(	'validateOnSubmit'=>true)
	);
	public $inputElementClass='FormInputElement';
	/**
	 * Renders the {@link elements} in this form.
	 * @return string the rendering result
	 */
	public function renderElements()
	{
		$output="<table class=\"form\">\n";
		$areaColumn=$this->generateArea($this->getElements());
		foreach($areaColumn as $areaAttr)
		{
			$areaParam=array('model' => $this->getModel());
			foreach($areaAttr as $attr=>$value)
			{
				$areaParam[$attr]=$value;
			}
			$output.=webapp()->controller->widget('ext.pacSelector.SelectorWidget',$areaParam,true);
		}
		foreach($this->getElements() as $element)
			$output.=$this->renderElement($element);
		$output.="</table>\n";
		return $output;
	}
	protected function generateArea($columns)
	{
		$area=array('attributeProvince'=>'provience','attributeCity'=>'area','attributeArea'=>'county');
		$areaColumn=array();
		foreach($columns as $column)
		{
				$cname=$column->name;
				foreach($area as $attr=>$col)
				{
					$rcnt=strlen($col);
					$right=right($cname,$rcnt);
					if($right==$col)
					{
						$lcnt=strlen($cname)-strlen($col);
						$left=left($cname,$lcnt);
						$areaColumn[$left][$attr]=$cname;
					}
		
				}
		}
		return $areaColumn;
	}
	/**
	 * Renders the body content of this form.
	 * This method mainly renders {@link elements} and {@link buttons}.
	 * If {@link title} or {@link description} is specified, they will be rendered as well.
	 * And if the associated model contains error, the error summary may also be displayed.
	 * The form tag will not be rendered. Please call {@link renderBegin} and {@link renderEnd}
	 * to render the open and close tags of the form.
	 * You may override this method to customize the rendering of the form.
	 * @return string the rendering result
	 */
	public function renderBody()
	{
		$output='';
		if($this->title!==null)
		{
			if($this->getParent() instanceof self)
			{
				$attributes=$this->attributes;
				unset($attributes['name'],$attributes['type']);
				$attributes['legend']=$this->title;
				$output='<tr><td>';
				ob_start();
				webapp()->controller->beginWidget('ext.coolfieldset.JCollapsibleFieldset',$attributes);
				$output.=ob_get_clean();
			}
			else
			{
				$output='<tr><td>';
				ob_start();
				webapp()->controller->beginWidget(
						'ext.coolfieldset.JCollapsibleFieldset',
						array(
	        				//'onlyFieldset'=>true,
	       					'legend'=>$this->title
	    				)
					);
				$output.=ob_get_clean();
			}
		}
	
		if($this->description!==null)
			$output.="<div class=\"description\">\n".$this->description."</div>\n";
	
		if($this->showErrorSummary && ($model=$this->getModel(false))!==null)
		{
			$output .= $this->getActiveFormWidget()->errorSummary($model) . "\n";
			if(!is_null($model->membermap))
			{
				$output .= $this->getActiveFormWidget()->errorSummary($model->membermap);
			}
		}
	
		$output.=$this->renderElements()."\n".$this->renderButtons()."\n";
	
		if($this->title!==null)
		{
			ob_start();
			webapp()->controller->endWidget('ext.coolfieldset.JCollapsibleFieldset');
			$output.=ob_get_clean();
			$output.="</td></tr>\n";
		}
		return $output;
	}
	/**
	 * Renders a single element which could be an input element, a sub-form, a string, or a button.
	 * @param mixed $element the form element to be rendered. This can be either a {@link CFormElement} instance
	 * or a string representing the name of the form element.
	 * @return string the rendering result
	 */
	public function renderElement($element)
	{
		if(is_string($element))
		{
			if(($e=$this[$element])===null && ($e=$this->getButtons()->itemAt($element))===null)
				return $element;
			else
				$element=$e;
		}
		if($element->getVisible())
		{
			if($element instanceof CFormInputElement)
			{
				if($element->type==='hidden')
					return "<tr style=\"visibility:hidden\">\n".trim($element->render())."\n</tr>\n";
				else
					return "<tr class=\"row field_{$element->name}\">\n".trim($element->render())."\n</tr>\n";
			}
			else if($element instanceof CFormButtonElement)
				return $element->render()."\n";
			else
				return $element->render();
		}
		return '';
	}		
}

?>