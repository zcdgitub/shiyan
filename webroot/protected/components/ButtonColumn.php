<?php

/** 
 * @author hetao
 * 
 */
class ButtonColumn extends CButtonColumn
{
	public $template='{view} {update} {delete} {verify} {lock}';
	/**
	 * @var string the label for the view button. Defaults to "View".
	 * Note that the label will not be HTML-encoded when rendering.
	 */
	public $lockButtonLabel;
	/**
	 * @var string the image URL for the view button. If not set, an integrated image will be used.
	 * You may set this property to be false to render a text link instead.
	 */
	public $lockButtonImageUrl;
	/**
	 * @var string a PHP expression that is evaluated for every view button and whose result is used
	 * as the URL for the view button. In this expression, the variable
	 * <code>$row</code> the row number (zero-based); <code>$data</code> the data model for the row;
	 * and <code>$this</code> the column object.
	 */
	public $lockButtonUrl='Yii::app()->controller->createUrl("lock",array("id"=>$data->primaryKey))';
	/**
	 * @var array the HTML options for the view button tag.
	 */
	public $lockButtonOptions=array('class'=>'lock');
	/**
	 * @var string a PHP expression that is evaluated for every delete button and whose result is used
	 * as the URL for the delete button. In this expression, you can use the following variables:
	 * <ul>
	 *   <li><code>$row</code> the row number (zero-based)</li>
	 *   <li><code>$data</code> the data model for the row</li>
	 *   <li><code>$this</code> the column object</li>
	 * </ul>
	 * The PHP expression will be evaluated using {@link evaluateExpression}.
	 *
	 * A PHP expression can be any PHP code that has a value. To learn more about what an expression is,
	 * please refer to the {@link http://www.php.net/manual/en/language.expressions.php php manual}.
	 */
	//public $deleteButtonUrl='Yii::app()->controller->createUrl("delete",array("id"=>$data->primaryKey,"returnUrl"=>webapp()->request->requestUri))';
	/**
	 * @var string the label for the view button. Defaults to "View".
	 * Note that the label will not be HTML-encoded when rendering.
	 */
	public $verifyButtonLabel;
	/**
	 * @var string the image URL for the view button. If not set, an integrated image will be used.
	 * You may set this property to be false to render a text link instead.
	 */
	public $verifyButtonImageUrl;
	/**
	 * @var string a PHP expression that is evaluated for every view button and whose result is used
	 * as the URL for the view button. In this expression, the variable
	 * <code>$row</code> the row number (zero-based); <code>$data</code> the data model for the row;
	 * and <code>$this</code> the column object.
	 */
	public $verifyButtonUrl='Yii::app()->controller->createUrl("verify",array("id"=>$data->primaryKey))';
	/**
	 * @var array the HTML options for the view button tag.
	 */
	public $verifyButtonOptions=array('class'=>'verify');
	protected function initDefaultButtons()
	{
		if($this->viewButtonImageUrl===null)
			$this->viewButtonImageUrl=themeBaseUrl() . '/images/button/yello-mid-view2.gif';
		if($this->updateButtonImageUrl===null)
			$this->updateButtonImageUrl=themeBaseUrl() . '/images/button/yello-mid-edit2.gif';
		if($this->deleteButtonImageUrl===null)
			$this->deleteButtonImageUrl=themeBaseUrl() . '/images/button/yello-mid-del2.gif';
		if($this->lockButtonImageUrl===null)
			$this->lockButtonImageUrl=themeBaseUrl() . '/images/button/yello-mid-lock2.gif';
		if($this->verifyButtonImageUrl===null)
			$this->verifyButtonImageUrl=themeBaseUrl() . '/images/button/yello-mid-verify2.gif';		
		foreach(array('view','update','delete','verify','lock') as $id)
		{
			$button=array(
					'label'=>$this->{$id.'ButtonLabel'},
					'url'=>$this->{$id.'ButtonUrl'},
					'imageUrl'=>$this->{$id.'ButtonImageUrl'},
					'options'=>$this->{$id.'ButtonOptions'},
					'visible'=>webapp()->controller->checkAccess($id)?'true':'false'//设置按钮
			);
			if($id=='verify' && isset($this->buttons[$id]['isverify']))
			{
				$col=str_replace('.','->',$this->buttons[$id]['isverify']);
				$button['visible']="({$button['visible']}) && (!\$data->{$col})";
			}
			$button['options']['submit']=$button['url'];
			if(isset($this->buttons[$id]))
				$this->buttons[$id]=array_merge($button,$this->buttons[$id]);
			else
				$this->buttons[$id]=$button;
		}
		parent::initDefaultButtons();
	}
	/**
	 * 设置grid列
	 */
	public function filter()
	{
		if(!isset($this->visble))
		{
			$visible=false;
			foreach(array('view','update','delete','verify','lock') as $buttonID)
			{
				if(strpos($this->template,'{'.$buttonID.'}')===false)
					continue;
				if(webapp()->controller->checkAccess($buttonID))
				{
					$visible=true;
					break;
				}
			}
			$this->visible=$visible;
		}
	}
	/**
	 * Renders a link button.
	 * @param string $id the ID of the button
	 * @param array $button the button configuration which may contain 'label', 'url', 'imageUrl' and 'options' elements.
	 * See {@link buttons} for more details.
	 * @param integer $row the row number (zero-based)
	 * @param mixed $data the data object associated with the row
	 */
	protected function renderButton($id,$button,$row,$data)
	{
		if (isset($button['visible']) && !$this->evaluateExpression($button['visible'],array('row'=>$row,'data'=>$data)))
			return;
		$label=isset($button['label']) ? $button['label'] : $id;
		$url=isset($button['url']) ? $this->evaluateExpression($button['url'],array('data'=>$data,'row'=>$row)) : '#';
		$options=isset($button['options']) ? $button['options'] : array();
		$options['submit']=$url;
		if(isset($options['confirm_php']))
		{
			$options['confirm']=$this->evaluateExpression($options['confirm_php'],array('data'=>$data,'row'=>$row));
		}
		$options['params']=['returnUrl'=>webapp()->request->requestUri];
		if(!isset($options['title']))
			$options['title']=$label;
		if(isset($button['imageUrl']) && is_string($button['imageUrl']))
			echo CHtml::link(CHtml::image($button['imageUrl'],$label),$url,$options);
		else
			echo CHtml::link($label,$url,$options);
	}
}
?>