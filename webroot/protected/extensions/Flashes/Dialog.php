<?php
/** 
 * @author: WAW (wawancell@gmail.com)
 * @version: 1.0
 * Copyright (C) 2012 by WAW.
 * 
 * @description: Widget for showing flash message with auto hide using JQuery effect.
 * @setup: - copy this widget under protected/component.
 * 		   - call this widget in layout/main.php to display the message for all view.
 *         - example usage in controller: Yii::app()->user->setFlash(<type>,<message>);
 *		   - <type> : success, error, notice
 *		   - <message> : output text to displayed.
 *
 * 	This program is distributed in the hope that it will be useful,
 * 	but WITHOUT ANY WARRANTY; without even the implied warranty of
 * 	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * 	GNU Lesser General Public License for more details.
 */
class Dialog extends CWidget
{
	public $keys=array();
	public $autoDelete=false;
	public $target='.grid-view';
	public function run()
	{
		$flashMessages = Yii::app ()->user->getFlashes ($this->autoDelete);
		if ($flashMessages)
		{
			foreach ( $this->keys as $key)
			{
				if(isset($flashMessages[$key]))
				{
					$this->beginWidget ( 'zii.widgets.jui.CJuiDialog', array(
							'id' => $key,
							'options' => array(
									//'show' => 'blind',
									'hide' => 'explode',
									'modal' => 'true',
									'title' => t('yii',$key) ,
									'autoOpen' => true,
									'position'=>new CJavaScriptExpression('{of:"' . $this->target . '"}'),
									'buttons'=>new CJavaScriptExpression('[{text:"' . t('epmms','确定') .  '", click: function() { $( this ).dialog( "close" ); }}]')
							)
					) );
					printf ( '<span class="dialog" >%s</span>', $flashMessages[$key] );
					$this->endWidget ( 'zii.widgets.jui.CJuiDialog' );
				}
			}
		}
	}
}
?>