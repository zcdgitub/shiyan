<?php
/**
 *
 * @author hetao
 *        
 */
class FormInputElement extends CFormInputElement
{
	public $suffix;
	/**
	 * @var array Core input types (alias=>CHtml method name)
	 */
	public static $coreTypes=array(
			'text'=>'textField',
			'hidden'=>'hiddenField',
			'password'=>'passwordField',
			'textarea'=>'textArea',
			'file'=>'fileField',
			'radio'=>'radioButton',
			'checkbox'=>'checkBox',
			'listbox'=>'listBox',
			'dropdownlist'=>'dropDownList',
			'checkboxlist'=>'checkBoxList',
			'radiolist'=>'radioButtonList',
			'url'=>'urlField',
			'email'=>'emailField',
			'number'=>'numberField',
			'range'=>'rangeField',
			'date'=>'date',
			'idCardType'=>'idCardType',
			'sex'=>'sex',
			'enable'=>'enable',
			'bank'=>'bank',
			'spinner'=>'spinner',
			'mapOrder'=>'mapOrder',
			'textPlain'=>'textPlain',
			'yesno'=>'yesno',
			'datetime'=>'datetime',
			'autoaccount'=>'autoaccount',
			'parentType'=>'parentType'
	);
	/**
	 * Renders everything for this input.
	 * The default implementation simply returns the result of {@link renderLabel}, {@link renderInput},
	 * {@link renderHint}. When {@link CForm::showErrorSummary} is false, {@link renderError} is also called
	 * to show error messages after individual input fields.
	 * @return string the complete rendering result for this input, including label, input field, hint, and error.
	 */
	public function render()
	{
		if($this->type==='hidden')
			return $this->renderInput();
		$output=array(
				'{label}'=>'<td class="title">' . $this->renderLabel() . '&nbsp<strong>:</strong>&nbsp</td>',
				'{input}'=>'<td class="value">' . $this->renderInput() .  $this->suffix . '</td>' ,
				'{hint}'=>'<td class="hint">' . $this->renderHint() . '</td>',
				'{error}'=>'<td class="error">' . ($this->getParent()->showErrorSummary ? '' : $this->renderError()) . '</td>',
		);
		return strtr($this->layout,$output);
	}
	/**
	 * Renders the input field.
	 * The default implementation returns the result of the appropriate CHtml method or the widget.
	 * @return string the rendering result
	 */
	public function renderInput()
	{
		if(isset(self::$coreTypes[$this->type]))
		{
			$method=self::$coreTypes[$this->type];
			$form=$this->getParent()->getActiveFormWidget();
			if(strpos($method,'List')!==false)
				return $form->$method($this->getParent()->getModel(), $this->name, $this->items, $this->attributes);
			else
			{
				return $form->$method($this->getParent()->getModel(), $this->name, $this->attributes);
			}
		}
		else
		{
			$attributes=$this->attributes;
			$attributes['model']=$this->getParent()->getModel();
			$attributes['attribute']=$this->name;
			ob_start();
			$this->getParent()->getOwner()->widget($this->type, $attributes);
			return ob_get_clean();
		}
	}

}

?>