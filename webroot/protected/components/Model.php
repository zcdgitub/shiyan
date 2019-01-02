<?php
/**
 *
 * @author hetao
 *        
 */
class Model extends CActiveRecord
{
	/**
	 * 模型标题
	 */
	public $modelName;
	/**
	 * 模型的命名列
	 */
	public $nameColumn;
	public static $mysqlDatetimeFormat = 'Y-m-d H:i:s';
	public $_showName;
	public function empty2null($value)
	{
		return isEmpty ( $value ) ? null : $value;
	}
	public function behaviors()
	{
        $prefixLen = strlen ( webapp ()->db->tablePrefix );
        $addDate = right ( $this->tableSchema->name, strlen ( $this->tableSchema->name ) - $prefixLen ) . '_' . 'add_date';
        $modDate = right ( $this->tableSchema->name, strlen ( $this->tableSchema->name ) - $prefixLen ) . '_' . 'mod_date';
        return array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'timestampExpression'=>'date(Model::$mysqlDatetimeFormat)',
                'createAttribute' => $this->hasAttribute ( $addDate ) ? $addDate : null,
                'updateAttribute' => $this->hasAttribute ( $modDate ) ? $modDate : null
            ),
            'EJsonBehavior'=>array(
                'class'=>'application.behaviors.EJsonBehavior'
            ),
        );
	}
	public function getShowName()
	{
		if(is_null($this->_showName))
		{
			$column=$this->nameColumn;
			$this->_showName=$this->$column;
		}
		return $this->_showName;
	}
	public function getListData($condition='')
	{
		return CHtml::listData($this->findAll($condition),$this->tableSchema->primaryKey,$this->nameColumn);
	}
	public function getFilterListData($condition='')
	{
		return CHtml::listData($this->findAll($condition),$this->nameColumn,$this->nameColumn);
	}

}

?>