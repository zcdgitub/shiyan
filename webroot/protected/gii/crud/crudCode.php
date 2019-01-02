<?php

class crudCode extends CCodeModel
{
	public $model;
	public $controller;
	public $baseControllerClass='Controller';
	public $modelName;
	public $areaColumn=array();

	private $_modelClass;
	private $_table;

	public function rules()
	{
		return array_merge(parent::rules(), array(
			array('model, controller', 'filter', 'filter'=>'trim'),
			array('model, controller, baseControllerClass', 'required'),
			array('model', 'match', 'pattern'=>'/^\w+[\w+\\.]*$/', 'message'=>'{attribute} should only contain word characters and dots.'),
			array('controller', 'match', 'pattern'=>'/^\w+[\w+\\/]*$/', 'message'=>'{attribute} should only contain word characters and slashes.'),
			array('baseControllerClass', 'match', 'pattern'=>'/^[a-zA-Z_][\w\\\\]*$/', 'message'=>'{attribute} should only contain word characters and backslashes.'),
			array('baseControllerClass', 'validateReservedWord', 'skipOnError'=>true),
			array('model', 'validateModel'),
			array('baseControllerClass', 'sticky'),
		));
	}

	public function attributeLabels()
	{
		return array_merge(parent::attributeLabels(), array(
			'model'=>'模型类',
			'controller'=>'控制器 ID',
			'baseControllerClass'=>'控制器基类',
			'template'=>'代码模板'
		));
	}

	public function requiredTemplates()
	{
		return array(
			'controller.php',
		);
	}

	public function init()
	{
		if(Yii::app()->db===null)
			throw new CHttpException(500,'An active "db" connection is required to run this generator.');
		parent::init();
	}

	public function successMessage()
	{
		$link=CHtml::link('试用一下', Yii::app()->createUrl($this->controller), array('target'=>'_blank'));
		return "控制器已经成功生成. 你可以 $link.";
	}

	public function validateModel($attribute,$params)
	{
		if($this->hasErrors('model'))
			return;
		$class=@Yii::import($this->model,true);
		if(!is_string($class) || !$this->classExists($class))
			$this->addError('model', "Class '{$this->model}' does not exist or has syntax error.");
		else if(!is_subclass_of($class,'CActiveRecord'))
			$this->addError('model', "'{$this->model}' must extend from CActiveRecord.");
		else
		{
			$this->modelName=CActiveRecord::model($class)->modelName;
			$table=CActiveRecord::model($class)->tableSchema;
			if($table->primaryKey===null)
				$this->addError('model',"Table '{$table->name}' does not have a primary key.");
			else if(is_array($table->primaryKey))
				$this->addError('model',"Table '{$table->name}' has a composite primary key which is not supported by crud generator.");
			else
			{
				$this->_modelClass=$class;
				$this->_table=$table;
				$this->areaColumn=$this->generateArea($this->tableSchema->columns);
			}
		}
	}

	public function prepare()
	{
		$this->files=array();
		$templatePath=$this->templatePath;
		$controllerTemplateFile=$templatePath.DIRECTORY_SEPARATOR.'controller.php';

		$this->files[]=new CCodeFile(
			$this->controllerFile,
			$this->render($controllerTemplateFile)
		);

		$files=scandir($templatePath);
		foreach($files as $file)
		{
			if(is_file($templatePath.'/'.$file) && CFileHelper::getExtension($file)==='php' && $file!=='controller.php')
			{
				$this->files[]=new CCodeFile(
					$this->viewPath.DIRECTORY_SEPARATOR.$file,
					$this->render($templatePath.'/'.$file)
				);
			}
		}
	}

	public function getModelClass()
	{
		return $this->_modelClass;
	}

	public function getControllerClass()
	{
		if(($pos=strrpos($this->controller,'/'))!==false)
			return ucfirst(substr($this->controller,$pos+1)).'Controller';
		else
			return ucfirst($this->controller).'Controller';
	}

	public function getModule()
	{
		if(($pos=strpos($this->controller,'/'))!==false)
		{
			$id=substr($this->controller,0,$pos);
			if(($module=Yii::app()->getModule($id))!==null)
				return $module;
		}
		return Yii::app();
	}

	public function getControllerID()
	{
		if($this->getModule()!==Yii::app())
			$id=substr($this->controller,strpos($this->controller,'/')+1);
		else
			$id=$this->controller;
		if(($pos=strrpos($id,'/'))!==false)
			$id[$pos+1]=strtolower($id[$pos+1]);
		else
			$id[0]=strtolower($id[0]);
		return $id;
	}

	public function getUniqueControllerID()
	{
		$id=$this->controller;
		if(($pos=strrpos($id,'/'))!==false)
			$id[$pos+1]=strtolower($id[$pos+1]);
		else
			$id[0]=strtolower($id[0]);
		return $id;
	}

	public function getControllerFile()
	{
		$module=$this->getModule();
		$id=$this->getControllerID();
		if(($pos=strrpos($id,'/'))!==false)
			$id[$pos+1]=strtoupper($id[$pos+1]);
		else
			$id[0]=strtoupper($id[0]);
		return $module->getControllerPath().'/'.$id.'Controller.php';
	}

	public function getViewPath()
	{
		return Yii::app()->getViewPath() . '/'.$this->getControllerID();
	}

	public function getTableSchema()
	{
		return $this->_table;
	}

	public function generateInputLabel($modelClass,$column)
	{
		return "CHtml::activeLabelEx(\$model,'{$column->name}')";
	}

	public function generateInputField($modelClass,$column)
	{
		if($column->type==='boolean')
			return "CHtml::activeCheckBox(\$model,'{$column->name}')";
		else if(stripos($column->dbType,'text')!==false)
			return "CHtml::activeTextArea(\$model,'{$column->name}',array('rows'=>6, 'cols'=>50))";
		else
		{
			if(preg_match('/(password|pass|passwd|passcode)/i',$column->name))
				$inputField='activePasswordField';
			else
				$inputField='activeTextField';

			if($column->type!=='string' || $column->size===null)
				return "CHtml::{$inputField}(\$model,'{$column->name}')";
			else
			{
				if(($size=$maxLength=$column->size)>60)
					$size=60;
				return "CHtml::{$inputField}(\$model,'{$column->name}',array('size'=>$size,'maxlength'=>$maxLength))";
			}
		}
	}

	public function generateActiveLabel($modelClass,$column)
	{
		return "\$form->labelEx(\$model,'{$column->name}')";
	}

	public function generateActiveField($modelClass,$column)
	{
		$cname=$this->getShortColumnName($column->name);
		if($column->isForeignKey)
		{
			$model=Model::model($modelClass);
			$relations=$model->relations();
			foreach($relations as $relationName=>$relation)
			{
				if($relation[2]==$column->name)
				{
					break;
				}
			}
			if(!empty($relation) && $relation[0]==Model::BELONGS_TO)
			{
				$input="echo \$form->dropDownList(\$model,'{$column->name}',{$relation[1]}::model()->listData,array('prompt'=>t('epmms','请选择') . \$model->getAttributeLabel('{$column->name}' )))";
				return $input;
			}
		}
		foreach($this->areaColumn as $area)
			if(in_array($column->name,$area))
				return "echo \$form->dropDownList(\$model,'{$column->name}',array())";
		if(!strcasecmp($cname,'is_enable'))
			return "echo \$form->enable(\$model,'{$column->name}')";
		if(!strcasecmp($cname,'is_verify'))
			return "echo \$form->verify(\$model,'{$column->name}')";		
		if(!strcasecmp($cname,'is_preset'))
			return "echo \$form->preset(\$model,'{$column->name}')";
		if(!strcasecmp($cname,'idcard_type'))
			return "echo \$form->idCardType(\$model,'{$column->name}')";
		if(!strcasecmp($cname,'birthday'))
			return "echo \$form->date(\$model,'{$column->name}')";
		if(!strcasecmp($cname,'sex'))
			return "echo \$form->sex(\$model,'{$column->name}')";
		if($column->type==='boolean')
			return "echo \$form->checkBox(\$model,'{$column->name}')";
		else if(stripos($column->dbType,'text')!==false)
			return "echo \$form->textArea(\$model,'{$column->name}',array('rows'=>6, 'cols'=>50))";
		else
		{
			if(preg_match('/(password|pass|passwd|passcode)/i',$column->name))
				$inputField='passwordField';
			else
				$inputField='textField';

			if($column->type!=='string' || $column->size===null)
				return "echo \$form->{$inputField}(\$model,'{$column->name}')";
			else
			{
				$maxLength=$column->size;
				$size=20;
				return "echo \$form->{$inputField}(\$model,'{$column->name}',array('size'=>$size,'maxlength'=>$maxLength))";
			}
		}
	}

	public static function guessNameColumn($columns)
	{
		foreach($columns as $column)
		{
			$cname=$column->name;
			$pos=(int)stripos($cname,'_')+1;
			$cname=substr($cname,$pos);
			if(!strcasecmp($cname,'nickname'))
				return $column->name;
		}
		foreach($columns as $column)
		{
			$cname=$column->name;
			$pos=(int)stripos($cname,'_')+1;
			$cname=substr($cname,$pos);
			if(!strcasecmp($cname,'account'))
				return $column->name;
		}
		foreach($columns as $column)
		{
			$cname=$column->name;
			$pos=(int)stripos($cname,'_')+1;
			$cname=substr($cname,$pos);
			if(!strcasecmp($cname,'name'))
				return $column->name;
		}
		foreach($columns as $column)
		{
			$cname=$column->name;
			$pos=(int)stripos($cname,'_')+1;
			$cname=substr($cname,$pos);
			if(!strcasecmp($cname,'title'))
				return $column->name;
		}						
		foreach($columns as $column)
		{
			$cname=$column->name;
			$pos=(int)stripos($cname,'_')+1;
			$cname=substr($cname,$pos);
			if($column->isPrimaryKey)
				return $column->name;
		}
		return 'id';
	}
	public function getAttributeColumns()
	{
		foreach($this->tableSchema->columns as $column)
		{
			$cname=$this->getShortColumnName($column->name);
			if($column->isPrimaryKey)
				continue;
			if(!strcasecmp($cname,'mod_date'))
				continue;
			$columns[]= "'" . $column->name . "'";
		}
		$attr_columns=implode(',', $columns);
		return $attr_columns;
	}
	public function getShortColumnName($name)
	{
		$pos=(int)stripos($name,'_')+1;
		$cname=substr($name,$pos);
		return $cname;
	}
	public function generateArea($columns)
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
		Yii::log(print_r($areaColumn,true),CLogger::LEVEL_ERROR,'ep');
		return $areaColumn;
	}
}