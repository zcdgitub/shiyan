<?php 

Yii::import('ext.yii-easyui.widgets.EuiContainer');

class EuiTabpanel extends EuiContainer
{
	/**
	 * @var string The title text to display in the tab 	 	 	
	 */
	public $title;	
	
	/**	 
	 * @var string The text to use as the panel's body content 
	 */
	public $content;
	
	/**
	 * @var string	A URL to load remote content to fill the tab panel
	 */
	public $href;

	/**	 
	 * @var boolean	True to cache the tab panel, valid when href property is setted
	 */
	public $cache;
	
	/**
	 * @var string	An icon CSS class to show on tab panel title
	 */
	public $iconCls;
	
	/**	 
	 * @var boolean True to display the 'close' tool button and allow the user to close the tab, false to hide the button and disallow closing the panel
	 */
	public $closable;
	
	/**	 
	 * @var boolean	When set to true, tab tab panel will be selected.
	 */
	public $selected;

									
	public function init()
	{
		parent::init();		
		$this->style = 'padding:0px;';	
	}

}

?>