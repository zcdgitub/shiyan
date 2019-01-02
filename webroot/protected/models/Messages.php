<?php

/**
 * This is the model class for table "{{messages}}".
 *
 * The followings are the available columns in table '{{messages}}':
 * @property string $messages_id
 * @property string $messages_title
 * @property string $messages_content
 * @property string $messages_add_date
 * @property string $messages_sender_member_id
 * @property string $messages_receiver_member_id
 * @property string $messages_session
 * @property string $messages_member_id
 * @property string @messages_is_read
 *
 * The followings are the available model relations:
 * @property  * @property  * @property  */
class Messages extends Model
{
	public $modelName='邮件';
	public $nameColumn='messages_title';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Messages the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{messages}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('messages_add_date, messages_sender_member_id, messages_receiver_member_id, messages_session, messages_member_id', 'filter','filter'=>array($this,'empty2null')),
			array('messages_title, messages_content', 'required'),
			array('messages_receiver_member_id, messages_member_id, messages_member_id', 'exist', 'className'=>'Memberinfo','attributeName'=>'memberinfo_id'),
			array('messages_add_date, messages_sender_member_id, messages_receiver_member_id, messages_session, messages_member_id', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('messages_id, messages_title, messages_content, messages_add_date, messages_sender_member_id, messages_receiver_member_id, messages_session, messages_member_id,messages_is_read', 'safe', 'on'=>'search'),
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
			'messagesSenderMember' => array(Model::BELONGS_TO, 'Memberinfo', 'messages_sender_member_id'),
			'messagesReceiverMember' => array(Model::BELONGS_TO, 'Memberinfo', 'messages_receiver_member_id'),
			'messagesMember' => array(Model::BELONGS_TO, 'Memberinfo', 'messages_member_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'messages_id' => 'Messages',
			'messages_title' => t('epmms','标题'),
			'messages_content' => t('epmms','内容'),
			'messages_add_date' => t('epmms','日期'),
			'messages_sender_member_id' => t('epmms','发件人'),
			'messages_receiver_member_id' => t('epmms','收件人'),
			'messages_session' => 'Messages Session',
			'messages_member_id' => 'Messages Member',
			'messages_is_read'=>t('epmms','是否已读')
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
		$sort=new Sort('Messages');
		$sort->defaultOrder=array('messages_id'=>Sort::SORT_DESC);
		$criteria=new CDbCriteria;
		$criteria->compare('messages_id',$this->messages_id,false);
		$criteria->compare('messages_title',$this->messages_title,false);
		$criteria->compare('messages_content',$this->messages_content,false);
		$criteria->compare('messages_add_date',$this->messages_add_date,false);
		$criteria->compare('messages_session',$this->messages_session,false);




		if($this->messages_member_id==-1)
			$criteria->addCondition('messages_member_id is null');
		else
			$criteria->compare('messages_member_id',$this->messages_member_id,false);
		if($this->messages_sender_member_id==-1)
			$criteria->addCondition('messages_sender_member_id is null');
		elseif($this->messages_sender_member_id==-2)
			$criteria->addCondition('messages_sender_member_id is not null');
		else
			$criteria->compare('messages_sender_member_id',$this->messages_sender_member_id,false);
		if($this->messages_receiver_member_id==-1)
			$criteria->addCondition('messages_receiver_member_id is null');
		else
			$criteria->compare('messages_receiver_member_id',$this->messages_receiver_member_id,false);
		$criteria->compare('"messagesSenderMember".memberinfo_account',@$this->messagesSenderMember->memberinfo_account);
		$criteria->compare('"messagesReceiverMember".memberinfo_account',@$this->messagesReceiverMember->memberinfo_account);
		$criteria->compare('"messagesMember".memberinfo_account',@$this->messagesMember->memberinfo_account);
		$criteria->with=array('messagesSenderMember','messagesReceiverMember','messagesMember');
		return  new JSonActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}
	public static function send($title,$content,$target)
	{
		$model=new Messages('create');
		$model->messages_title=$title;
		$model->messages_content=$content;
		$model->messages_receiver_member_id=$target;
		$model->messages_sender_member_id=null;
		$model->messages_member_id=null;
		$transaction=webapp()->db->beginTransaction();
		if($model->save(true,array('messages_title','messages_content','messages_sender_member_id','messages_receiver_member_id','messages_member_id')))
		{
			$sumProc=new DbCall('epmms_send_message',array((int)$model->messages_id));
			$sumProc->run();
			$transaction->commit();
			return true;
		}
		else
		{
			$transaction->rollback();
			return false;
		}
	}
}