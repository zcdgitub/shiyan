<?php
/**
 * CClientScript class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright 2008-2013 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */


/**
 * CClientScript manages JavaScript and CSS stylesheets for views.
 *
 * @property string $coreScriptUrl The base URL of all core javascript files.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.web
 * @since 1.0
 */
class ClientScript extends CClientScript
{
	public $spdy_css_header_level=-1;
	public $spdy_js_header_level=-1;
	public $spdy_js_begin_level=-1;
	public $spdy_js_end_level=-1;
	public $pushFiles=array();

	/**
	 * Inserts the scripts in the head section.
	 * @param string $output the output to be inserted with scripts.
	 */
	public function renderHead(&$output)
	{
		$html='';
		foreach($this->metaTags as $meta)
			$html.=CHtml::metaTag($meta['content'],null,null,$meta)."\n";
		foreach($this->linkTags as $link)
			$html.=CHtml::linkTag(null,null,null,null,$link)."\n";
		foreach($this->cssFiles as $url=>$media)
		{
			$html .= CHtml::cssFile($url, $media) . "\n";
			if($this->spdy_css_header_level>=0)
				$this->pushFiles[$url]=$this->spdy_css_header_level;
		}
		foreach($this->css as $css)
			$html.=CHtml::css($css[0],$css[1])."\n";
		if($this->enableJavaScript)
		{
			if(isset($this->scriptFiles[self::POS_HEAD]))
			{
				foreach($this->scriptFiles[self::POS_HEAD] as $scriptFileValueUrl=>$scriptFileValue)
				{
					if(is_array($scriptFileValue))
						$html.=CHtml::scriptFile($scriptFileValueUrl,$scriptFileValue)."\n";
					else
						$html.=CHtml::scriptFile($scriptFileValueUrl)."\n";
					if($this->spdy_js_header_level>=0)
						$this->pushFiles[$scriptFileValueUrl]=$this->spdy_js_header_level;
				}
			}

			if(isset($this->scripts[self::POS_HEAD]))
				$html.=$this->renderScriptBatch($this->scripts[self::POS_HEAD]);
		}

		if($html!=='')
		{
			$count=0;
			$output=preg_replace('/(<title\b[^>]*>|<\\/head\s*>)/is','<###head###>$1',$output,1,$count);
			if($count)
				$output=str_replace('<###head###>',$html,$output);
			else
				$output=$html.$output;
		}
	}
	/**
	 * Inserts the scripts at the beginning of the body section.
	 * @param string $output the output to be inserted with scripts.
	 */
	public function renderBodyBegin(&$output)
	{
		$html='';
		if(isset($this->scriptFiles[self::POS_BEGIN]))
		{
			foreach($this->scriptFiles[self::POS_BEGIN] as $scriptFileUrl=>$scriptFileValue)
			{
				if(is_array($scriptFileValue))
					$html.=CHtml::scriptFile($scriptFileUrl,$scriptFileValue)."\n";
				else
					$html.=CHtml::scriptFile($scriptFileUrl)."\n";
				if($this->spdy_js_begin_level>=0)
					$this->pushFiles[$scriptFileUrl]=$this->spdy_js_begin_level;
			}
		}
		if(isset($this->scripts[self::POS_BEGIN]))
			$html.=$this->renderScriptBatch($this->scripts[self::POS_BEGIN]);

		if($html!=='')
		{
			$count=0;
			$output=preg_replace('/(<body\b[^>]*>)/is','$1<###begin###>',$output,1,$count);
			if($count)
				$output=str_replace('<###begin###>',$html,$output);
			else
				$output=$html.$output;
		}
	}
	/**
	 * Inserts the scripts at the end of the body section.
	 * @param string $output the output to be inserted with scripts.
	 */
	public function renderBodyEnd(&$output)
	{
		if(!isset($this->scriptFiles[self::POS_END]) && !isset($this->scripts[self::POS_END])
			&& !isset($this->scripts[self::POS_READY]) && !isset($this->scripts[self::POS_LOAD]))
			return;

		$fullPage=0;
		$output=preg_replace('/(<\\/body\s*>)/is','<###end###>$1',$output,1,$fullPage);
		$html='';
		if(isset($this->scriptFiles[self::POS_END]))
		{
			foreach($this->scriptFiles[self::POS_END] as $scriptFileUrl=>$scriptFileValue)
			{
				if(is_array($scriptFileValue))
					$html.=CHtml::scriptFile($scriptFileUrl,$scriptFileValue)."\n";
				else
					$html.=CHtml::scriptFile($scriptFileUrl)."\n";
				if($this->spdy_js_end_level>=0)
					$this->pushFiles[$scriptFileUrl]=$this->spdy_js_end_level;
			}
		}
		$scripts=isset($this->scripts[self::POS_END]) ? $this->scripts[self::POS_END] : array();
		if(isset($this->scripts[self::POS_READY]))
		{
			if($fullPage)
				$scripts[]="jQuery(function($) {\n".implode("\n",$this->scripts[self::POS_READY])."\n});";
			else
				$scripts[]=implode("\n",$this->scripts[self::POS_READY]);
		}
		if(isset($this->scripts[self::POS_LOAD]))
		{
			if($fullPage)
				$scripts[]="jQuery(window).on('load',function() {\n".implode("\n",$this->scripts[self::POS_LOAD])."\n});";
			else
				$scripts[]=implode("\n",$this->scripts[self::POS_LOAD]);
		}
		if(!empty($scripts))
			$html.=$this->renderScriptBatch($scripts);

		if($fullPage)
			$output=str_replace('<###end###>',$html,$output);
		else
			$output=$output.$html;
	}

	/**
	 * Renders the registered scripts.
	 * This method is called in {@link CController::render} when it finishes
	 * rendering content. CClientScript thus gets a chance to insert script tags
	 * at <code>head</code> and <code>body</code> sections in the HTML output.
	 * @param string $output the existing output that needs to be inserted with script tags
	 */
	public function render(&$output)
	{
		if (!$this->hasScripts)
			return;

		$this->renderCoreScripts();

		if (!empty($this->scriptMap))
			$this->remapScripts();

		$this->unifyScripts();

		$this->renderHead($output);
		if ($this->enableJavaScript)
		{
			$this->renderBodyBegin($output);
			$this->renderBodyEnd($output);
		}
		$this->pushFile($this->pushFiles);
	}
	/**
	 * SPDY server push
	 * @param array $url array($url=>$level)
	 */
	public function pushFile($url=array())
	{
		$pushHeader='';
		$count=0;
		$cache_control='';
		if(isset($_SERVER['HTTP_CACHE_CONTROL']))
			$cache_control=$_SERVER['HTTP_CACHE_CONTROL'];
		if(!isset($_COOKIE["lastPush"]) || $cache_control=='no-cache' ||  $cache_control=='max-age=0')
		{
			if (!is_null($url) && $url != array())
			{
				foreach ($url as $k => $v)
				{
					if ($count != 0)
						$pushHeader .= ',';
					$pushHeader .= '"' . $k . '":' . $v;
					if ($count == 0)
						$count = 1;
				}
				//header("X-Associated-Content: $pushHeader");
			}
		}
	}

	/**
	 * 注册SPDY push文件
	 * @param array $pushFiles
	 */
	public  function registerPushFile($pushFiles=array())
	{
		if(!is_null($pushFiles) && $pushFiles!=array())
			$this->pushFiles=array_merge($this->pushFiles,$pushFiles);

	}
}
