<?php

/**
 * This is the model class for table "{{product_class}}".
 *
 * The followings are the available columns in table '{{product_class}}':
 * @property integer $product_class_id
 * @property string $product_name
 * @property string $product_info
 */
class ProductClass extends Model
{
	//模型标题
	public $modelName='产品分类';
	//命名字段
	public $nameColumn='product_name';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{product_class}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('product_info', 'filter','filter'=>array($this,'empty2null')),
			array('product_name', 'required'),
	/*	array('product_parent_id','exist', 'className'=>'ProductClass','attributeName'=>'product_class_id'),*/
		array('product_parent_id','required'),
			array('product_name', 'ext.validators.Account','allowZh'=>true),
			array('product_info', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('product_class_id, product_name, product_info', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'product_class_id' => t('epmms','Product Class'),
			'product_name' => t('epmms','分类名称'),
			'product_info' => t('epmms','分类说明'),
			'product_parent_id' => t('epmms','一级分类'),
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

		$sort=new Sort('ProductClass');
		$sort->defaultOrder=array('product_class_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;

		$criteria->compare('product_class_id',$this->product_class_id);
		$criteria->compare('product_name',$this->product_name,true);
		$criteria->compare('product_info',$this->product_info,true);

		return new JSonActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ProductClass the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    public function tree($arr,$id=0,$lev=1){
	    static  $list = array(); 
	    foreach($arr as $v) {
	      if($v['product_parent_id'] == $id) {
	         $v['lev'] = $lev;	   	     	   
	         $list[] = $v; 
	       $this->tree($arr,$v['product_class_id'],$lev+1);
	      }
	    }
	    return $list;
	  }
}
