<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 11-11-23
 * Time: 下午1:23
 * To change this template use File | Settings | File Templates.
 */

class JCropZoom extends CWidget
{
    /**
     * @var array
     */
    protected $defaultOptions = array(
    	'start' =>true,
        'width' => 400,
        'height' => 300,
        'bgColor' => '#CCC',
        'enableRotation' => true,
        'enableZoom' => true,
        'zoomSteps' => 10,
        'rotationSteps' => 10,
        'selector' => array(
            'centered' => true,
            'borderColor' => 'blue',
            'borderColorHover' => 'yellow',
            'aspectRatio' => true,
        ),
        'image' => array(
            'snapToContainer' => true,
        ),
    );
    /**
     * @var array
     * will pass to the cropzoom plugin for initialization
     * @see http://www.cropzoom.com.ar/?page_id=5
     */
    public $options = array();

    /**
     * @var string
     */
    public $containerId ;

    /**
     * @var string
     * some id  of  html element which can be a link or button
     */
    public $cropTriggerId = '';
    public $restoreTriggerId = '';

    /**
     * @var string
     * the url address which handle the crop resize or rotate operation
     * example:  Yii::app()->createUrl('');
     */
    public $callbackUrl = '';

    /**
     * @var string
     * the js function which used to handle the server response after the server
     * end had settled the cropped image , normally the response is the cropped image url
     * you can display it in dialog or some html div element .
     */
    public $onServerHandled = 'js:function(response){
      alert("response from server end ,you should give you own  js function here! the response is ==> "+response);
    }';

    /**
     * @var bool
     * if  use the external css js source file
     */
    public $useExternalScripts = false;
    /**
     * @var string
     */
    protected $baseUrl = '';

    /**
     * @param $h
     * @param $n
     * @return bool
     * if $h  stars with $n
     *
     */
    protected function startsWith($h, $n)
    {
        return (false !== ($i = strrpos($h, $n)) &&
                $i === strlen($h) - strlen($n));
    }

    /**
     * @param bool $autoGenerate
     * @return string
     */
    public function getId($autoGenerate = true)
    {
        $id = parent::getId($autoGenerate);
        if ($this->startsWith($id, 'yw')) {
            return __CLASS__ . substr($id, 2);
        }
        return $id;
    }


    /**
     * @return void
     */
    public function init()
    {

        parent::init();
        $this->options = CMap::mergeArray($this->defaultOptions, $this->options);

		if($this->options['start'])
		{
	        //the  image should be in proper Aspect ratio now i  do this in browser client with javascript 
	        $newDim = $this->getNewDimension($this->options['image']['source'], $this->options['width'], $this->options['height']);
	        $this->options['image']['width'] = $newDim['width'];
	        $this->options['image']['height'] = $newDim['height'];
	        $this->options['image']['x']= $newDim['x'];
	        $this->options['image']['y']= $newDim['y'];
		}
    }

    /**
     * @param string $imageSource
     * @param int $targetWidth
     * @param int $targetHeight
     * @return array
     * calculate  the new dimension for the image
     */
    protected function getNewDimension($imageSource, $targetWidth = null, $targetHeight = null)
    {
        list($sourceWidth, $sourceHeight) = getimagesize($imageSource);
        if ($sourceWidth / $targetWidth > $sourceHeight / $targetHeight) {
            $newWidth = $targetWidth;
            $newHeight = $newWidth / $sourceWidth * $sourceHeight;
        } else {
            $newHeight = $targetHeight;
            $newWidth = $newHeight / $sourceHeight * $sourceWidth;
        }
        $x=($targetWidth-$newWidth)/2;
        $y=($targetHeight-$newHeight)/2;
        return array(
            'width' => (int)$newWidth,
            'height' => (int)$newHeight,
        	'x'=>(int)$x,
        	'y'=>(int)$y
        );
    }

    /**
     * @return void
     * you can use the id passed to this widget
     * access the cropzoom api , please view the
     * generated javascript code .
     */
    public function  run()
    {
        $this->publishAssets();
        $this->registerClientScripts();

        if(empty($this->containerId)){
          /* throw new CException('you must give an image container id for using this widget,
            which just is a placeholder where this widget will be display');*/
           $this->containerId = __CLASS__.'_container_'.$this->id;
            echo "<div id='{$this->containerId}'></div>";
        }

        $options = CJavaScript::encode($this->options);
        
        $jsfunction=<<<CROP
        var cropZoomOptions4_{$this->id} = {$options};
        var cropStart=true;
        var {$this->id};
        function auto_size(width,height)
        {
        	var size=new Object();
        	sourceWidth=width;
        	sourceHeight=height;
        	targetWidth={$this->options['width']};
        	targetHeight={$this->options['height']};
	        if (sourceWidth / targetWidth > sourceHeight / targetHeight) {
	            newWidth = targetWidth;
	            newHeight = newWidth / sourceWidth * sourceHeight;
	        } else {
	            newHeight = targetHeight;
	            newWidth = newHeight / sourceHeight * sourceWidth;
	        }
	        x=(targetWidth-newWidth)/2;
	        y=(targetHeight-newHeight)/2;
	        size.width=newWidth;
	        size.height=newHeight;
	        size.x=x;
	        size.y=y;
	        return size;
        }
        function create_crop(img,width,height)
        {
        	var size;
        	if(cropStart)
        	{
        		\$('#{$this->cropTriggerId}').show();
        		\$('#{$this->restoreTriggerId}').show();
        		cropZoomOptions4_{$this->id}.image.source=img;
        		size=auto_size(width,height);
        		cropZoomOptions4_{$this->id}.image.width=size.width;
        		cropZoomOptions4_{$this->id}.image.height=size.height;
        		cropZoomOptions4_{$this->id}.image.x=size.x;
        		cropZoomOptions4_{$this->id}.image.y=size.y;
        		{$this->id} = $('#{$this->containerId}').cropzoom(cropZoomOptions4_{$this->id});
        		cropStart=false;
        	}
        	else
        	{
				size=auto_size(width,height);
				{$this->id}.setImage(img,size.width,size.height,size.x,size.y);
        	}
        }
CROP;
        Yii::app()->getClientScript()->registerScript(__CLASS__ . '#create.' . $this->id,$jsfunction, CClientScript::POS_HEAD);
        if($this->options['start'])
        {
        	Yii::app()->getClientScript()->registerScript(__CLASS__ . '#start' . $this->id, "{$this->id} = $('#{$this->containerId}').cropzoom(cropZoomOptions4_{$this->id});", CClientScript::POS_READY);
        }
        else
        {
        	Yii::app()->getClientScript()->registerScript(__CLASS__ . '#hidden' . $this->id,"\$('#{$this->cropTriggerId}').hide();\$('#{$this->restoreTriggerId}').hide();",CClientScript::POS_LOAD);
        }

        /**
         * if you didn't give an cropTriggerId , i  will use a Button to do that.
         */
        if (empty($this->cropTriggerId)) {
            $this->cropTriggerId = $this->id . '_crop_trigger';
            //here  generate the default trigger element:
            $defaultTriggerElement = CHtml::Button('裁剪图片', array('id' => $this->cropTriggerId));
            echo $defaultTriggerElement;
        }

        $this->listenCrop();
    }


    /**
     * @return JCropZoom
     * publish the plugin dir to the assets dir under the webRoot
     * then the css , js or image can be accessed from browser
     */
    public function publishAssets()
    {
        $assetsDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
        $this->baseUrl = Yii::app()->getAssetManager()->publish($assetsDir, false, -1, YII_DEBUG);
        return $this;
    }

    /**
     * @return \JCropZoom
     * register core jquery and cropzoom js file...
     * -------------------------------
     * -------------------------------
     */
    public function registerClientScripts()
    {
        $cs = Yii::app()->getClientScript();
        $cs->registerMetaTag('IE=7,9,10,11', null, 'X-UA-Compatible');
        $cs->registerCssFile( Yii::app()->clientScript->getCoreScriptUrl().'/jui/css/base/jquery-ui.css');
		$cs->registerCssFile($this->baseUrl . '/css/jquery.cropzoom.css');
		$cs->registerCoreScript('jquery');
        $cs->registerScriptFile('/js/jquery-migrate-1.2.1.min.js');
		$cs->registerCoreScript('jquery.ui');
		$cs->registerScriptFile($this->baseUrl . '/js/jquery.cropzoom.js');
        return $this;
    }

    /**
     * @return void
     * register the js handler for the crop action
     */
    public function listenCrop()
    {
        $onServerCropped = CJavaScript::encode($this->onServerHandled);

        $jsListenCrop = <<<CROP_HANDLE
        $(function(){
        $("#{$this->cropTriggerId}").click(function(){
            {$this->getId()}.send('{$this->callbackUrl}','POST',{},{$onServerCropped});
           });
        });
CROP_HANDLE;

        Yii::app()->getClientScript()
                ->registerScript(__METHOD__ . '#' . $this->id, $jsListenCrop, CClientScript::POS_HEAD);
    }

    /**
     * @param $name
     * @param $value
     * @return void
     * magic method this assume that the not declared variable will
     * default pass to the cropZoom options variable.
     */
    public function __set($name, $value)
    {
        try{
            //shouldn't swallow the parent ' __set operation
            parent::__set($name,$value);
        }catch(Exception $e){
            $this->options[$name] = $value;
        }
    }

    /**
     * @static
     * @return CropZoomHandler
     * use this instance to handle the crop ,rotate or resize at php server end
     */
    public static function getHandler(){
        require_once(dirname(__FILE__).DIRECTORY_SEPARATOR .'CropZoomHandler.php');
        return new CropZoomHandler();
    }
}

