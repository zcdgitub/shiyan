<?php

class LicenceCode extends CCodeModel
{
	public $modelPath='application.config';
	public $domainExpiry='';
	public $spaceExpiry='';
	public $tryExpiry='';


	public function rules()
	{
		return array_merge(parent::rules(), array(
			array('domainExpiry,spaceExpiry,tryExpiry',  'ext.validators.Date','format'=>'yyyy-MM-dd'),
		));
	}

	public function attributeLabels()
	{
		return array_merge(parent::attributeLabels(), array(
			'domainExpiry'=>'正式域名到期时间',
			'spaceExpiry'=>'正式空间到期时间',
			'tryExpiry'=>'试用到期时间',
		));
	}
	public function init()
	{
		if(Yii::app()->db===null)
			throw new CHttpException(500,'An active "db" connection is required to run this generator.');
		parent::init();
	}
	public function prepare()
	{
		$this->files=array();
		$templatePath=$this->templatePath;

		$files=scandir($templatePath);
		foreach($files as $file)
		{
			if(is_file($templatePath.'/'.$file) && CFileHelper::getExtension($file)==='php')
			{
				$this->files[]=new CCodeFile(
					Yii::getPathOfAlias($this->modelPath).'/'.$file,
					$this->render($templatePath.'/'.$file, array('domainExpiry'=>$this->domainExpiry,'spaceExpiry'=>$this->spaceExpiry,'tryExpiry'=>$this->tryExpiry))
				);
			}
		}
	}

	public function requiredTemplates()
	{
		return array(
			'licence.php',
		);
	}

}
