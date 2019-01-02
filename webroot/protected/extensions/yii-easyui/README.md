Yii EasyUI
==========

Yii EasyUI is a extension of [Yii](http://www.yiiframework.com) that helps to work with [jQuery EasyUI](http://www.jeasyui.com) library.

The extension implements widgets for writing the componentes javascript.

Including the resources
------------------------

Download http://www.jeasyui.com/download/, extracting content in ```js/jquery-easyui```

    jquery-easyui - jQuery EasyUI framework (version 1.3.3)
    
Download [yii-easyui](https://github.com/leandrogehlen/yii-easyui/archive/master.zip) extracting content in ```protected\extensions```
    
Configuration
-------------
Is necessary that the controllers extends of class ```EuiController```

```php
Yii::import('ext.yii-easyui.base.EuiController');

class SiteController extends EuiController {

    public function actionIndex() 
    {
	      $this->render('index');	
    }	
}
```

Widgets
------

In view files is possible to write widget components to render JQuery Easyui format, according to the following example:

```php
$this->widget('ext.yii-easyui.widgets.EuiWindow', array(
	'id' => 'win',
	'title' => 'My Window',
	'style' => 'width:500px;height:250px;padding:10px;'			
));

``` 

Results:

![Hello World](https://jquery-easyui.googlecode.com/svn/trunk/share/tutorial/window/win1_1.png)
