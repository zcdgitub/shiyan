<?php

/**
 * This is the model class for table "{{futou}}".
 *
 * The followings are the available columns in table '{{futou}}':
 * @property integer $futou_id
 * @property integer $futou_member_id
 * @property string $futou_deduct1
 * @property string $futou_deduct2
 * @property string $futou_deduct3
 * @property string $futou_deduct4
 * @property string $futou_deduct
 * @property string $futou_add_date
 *
 * The followings are the available model relations:
 * @property  */
class Futou extends Model
{
	//模型标题
	public $modelName = '复投';
	//命名字段
	public $nameColumn = 'futou_id';

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{futou}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('futou_deduct1, futou_deduct2, futou_deduct3, futou_deduct4, futou_deduct, futou_add_date', 'filter', 'filter' => array($this, 'empty2null')),
			array('futou_member_id,futou_deduct', 'required'),
			array('futou_member_id', 'numerical', 'integerOnly' => true),
			array('futou_member_id', 'exist', 'className' => 'Memberinfo', 'attributeName' => 'memberinfo_id'),
			array('futou_deduct1, futou_deduct2, futou_deduct3, futou_deduct4, futou_deduct', 'ext.validators.Decimal', 'precision' => 16, 'scale' =>2),
			array('futou_deduct1','ext.validators.AbleFutou1'),
			//array('futou_deduct2','ext.validators.AbleFutou2'),
//			array('futou_deduct3','ext.validators.AbleFutou3'),
//			array('futou_deduct4','ext.validators.AbleFutou4'),
			array('futou_deduct','ext.validators.AbleFutou'),
			array('futou_add_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('futou_id, futou_member_id, futou_deduct1, futou_deduct2, futou_deduct3, futou_deduct4, futou_deduct, futou_add_date', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'futouMember' => array(Model::BELONGS_TO, 'Memberinfo', 'futou_member_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'futou_id' => t('epmms', 'Futou'),
			'futou_member_id' => t('epmms', '会员'),
			'futou_deduct1' => t('epmms', '复投币'),
			'futou_deduct2' => t('epmms', '注册币'),
			'futou_deduct3' => t('epmms', '赞助劵商基金'),
			'futou_deduct4' => t('epmms', '提拔公益基金'),
			'futou_deduct' => t('epmms', '复投金额'),
			'futou_add_date' => t('epmms', '复投日期'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$sort=new Sort('Futou');
		$sort->defaultOrder=array('futou_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;

		$criteria->compare('futou_id',$this->futou_id);
		$criteria->compare('futou_member_id',$this->futou_member_id);
		$criteria->compare('futou_deduct1',$this->futou_deduct1,true);
		$criteria->compare('futou_deduct2',$this->futou_deduct2,true);
		$criteria->compare('futou_deduct3',$this->futou_deduct3,true);
		$criteria->compare('futou_deduct4',$this->futou_deduct4,true);
		$criteria->compare('futou_deduct',$this->futou_deduct,true);
		$criteria->compare('futou_add_date',$this->futou_add_date,true);
		$criteria->compare('"futouMember".memberinfo_account',@$this->futouMember->memberinfo_account);
		$criteria->with=array('futouMember');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}
	public function verify()
	{
		$finance1=Finance::getMemberFinance($this->futou_member_id,2);
		//$finance2=Finance::getMemberFinance($this->futou_member_id,2);
//		$finance4=Finance::getMemberFinance($this->futou_member_id,4);
//		$finance5=Finance::getMemberFinance($this->futou_member_id,5);
		if($this->futou_deduct>0)
		{
			if(!$finance1->deduct($this->futou_deduct))
			{
				throw new Error('扣除金额失败');
			}
            $membermap=Membermap::model()->findByPk(user()->id);
			$membermap->membermap_buyall=$membermap->membermap_buyall+$this->futou_deduct;
			$membermap->saveAttributes(['membermap_buyall']);
			//结算奖金
/*			Yii::import('ext.award.MySystem');
			$membermap=Membermap::model()->findByPk($this->futou_member_id);
			$mysystem=new MySystem($membermap);
			if(is_null($membermap))
				throw new Error('无效的会员');
			$mysystem->run(1,1,1);*/
		}
		/*
		if($this->futou_deduct2>0)
		{
			if(!$finance2->deduct($this->futou_deduct2))
			{
				throw new Error('扣除金额失败');
			}
		}*/
/*		if($this->futou_deduct3>0)
		{
			if(!$finance4->deduct($this->futou_deduct3))
			{
				throw new Error('扣除金额失败');
			}
		}
		if($this->futou_deduct4>0)
		{
			if(!$finance5->deduct($this->futou_deduct4))
			{
				throw new Error('扣除金额失败');
			}
		}*/
		return true;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Futou the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
