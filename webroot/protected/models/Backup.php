<?php

/**
 * This is the model class for table "{{backup}}".
 *
 * The followings are the available columns in table '{{backup}}':
 * @property integer $backup_id
 * @property string $backup_name
 * @property string $backup_add_date
 * @property string $backup_remark
 * @property string $backup_file
 * @property integer $backup_type
 */
class Backup extends Model
{
	public $modelName='备份';
	public $nameColumn='backup_name';
	private $pass = '5059196e';
	private $method = 'aes128';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Backup the static model class
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
		return '{{backup}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('backup_name, backup_add_date, backup_remark, backup_type', 'filter','filter'=>array($this,'empty2null')),
			array('backup_name', 'required'),
			array('backup_type', 'numerical', 'integerOnly'=>true),
			//array('backup_name', 'ext.validators.Account','allowZh'=>true),
			array('backup_add_date, backup_remark,backup_name', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('backup_id, backup_name, backup_add_date, backup_remark, backup_file, backup_type', 'safe', 'on'=>'search'),
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
			'backup_id' => 'Backup',
			'backup_name' => t('epmms','备份名称'),
			'backup_add_date' => t('epmms','备份日期'),
			'backup_remark' => t('epmms','备注'),
			'backup_file' => 'Backup File',
			'backup_type' => 'Backup Type',
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
		$sort=new Sort('Backup');
		$sort->defaultOrder=array('backup_id'=>Sort::SORT_DESC);
		$criteria=new CDbCriteria;
		$criteria->compare('backup_id',$this->backup_id);
		$criteria->compare('backup_name',$this->backup_name,true);
		$criteria->compare('backup_add_date',$this->backup_add_date);
		$criteria->compare('backup_remark',$this->backup_remark,true);
		$criteria->compare('backup_file',$this->backup_file);
		$criteria->compare('backup_type',$this->backup_type);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}

	/**
	 * 执行备份操作
	 * @return bool
	 */
	public function backupdb()
	{
		if(!file_exists(params('db_backup')))
		{
			throw new Error('备份目录不存在');
			return false;
		}
		setPgsqlEnv();
		$cmd_file=$this->genbakname();
		$cmd_file=  params('db_backup') . $cmd_file ;
		$cmd_bak=webapp()->db->dump_cmd . " --blobs -Fc " .  '"' . webapp()->db->database . '">' . $cmd_file;
		//$cmd_bak=webapp()->db->dump_cmd . " --blobs -Fc " . webapp()->db->database;
		//$cmd_bak.=' 2>&1';

		$out=null;
		$error=null;
		//exit($cmd_bak);

		exec($cmd_bak ,$out,$error);

		if($error===0)
		{
			$this->backup_file=$cmd_file;
			return true;
		}
		return false;
	}

	/**
	 * 清除过期备份
	 */
	protected function cleanOld()
	{
		$count=config('backup','count');
		if($count>0)
		{
			//倒数第count以后的记录
			$model=Backup::model()->findAll(['order'=>'backup_id desc','offset'=>config('backup','count'),'condition'=>'backup_type=1']);
			foreach($model as $bak)
			{
				LogFilter::log(['category'=>'operate','source'=>'备份恢复','operate'=>'删除','user'=>'Guest','role'=>null,'target'=>$bak->backup_name,'status'=>LogFilter::SUCCESS,'info'=>$bak->backup_remark]);
				$bak->delete();
			}
		}
		$days=config('backup','days');
		if($days>0)
		{
			//超过days天数的记录
			$model=Backup::model()->findAll(['condition'=>"now()-backup_add_date>interval '$days day' and backup_type=1"]);
			foreach($model as $bak)
			{
				$bak->delete();
			}
		}
	}
	/**
	 * 清除系统备份
	 */
	public function cleanSys()
	{
		//倒数第count以后的记录
		$model=Backup::model()->findAll(['order'=>'backup_id desc']);
		foreach($model as $bak)
		{
			$bak->delete();
		}
		$con=webapp()->db;
		$cmd=$con->createCommand('truncate table epmms_backup');
		$cmd->execute();
		$cmd=$con->createCommand('alter sequence epmms_backup_backup_id_seq restart');
		$cmd->execute();
		return true;
	}
	/**
	 * 执行一个自动备份，成功返回Backup
	 * @param $name 备份名称
	 * @param $remark 备注
	 * @return Backup|bool
	 */
	public function autoBackup($name,$remark)
	{
		//取消自动备份
		$bak=new Backup();
		$bak->backup_type=1;//自动备份
		$bak->backup_name=$name;
		$bak->backup_remark=$remark;

		if($bak->backupdb())
		{

			$bak->save();
			$this->cleanOld();
			return $bak;
		}

		return false;
	}
	/**
	 * 执行一个自动备份，成功返回Backup
	 * @param $name 备份名称
	 * @param $remark 备注
	 * @return Backup|bool
	 */
	public function createBackup($name,$remark)
	{
		$bak=new Backup();
		$bak->backup_type=1;//自动备份
		$bak->backup_name=$name;
		$bak->backup_remark=$remark;

		if($bak->backupdb())
		{
			$bak->save();
			$this->cleanOld();
			return $bak;
		}
		return false;
	}
	/**
	 * 执行恢复
	 * @return bool
	 */
	public function restore()
	{
		setPgsqlEnv();
		$out=null;
		$error=null;
		if(!file_exists(params('db_backup')))
		{
			throw new Error('备份目录不存在');
			return false;
		}
		$out=null;
		$error=null;
		//备份backup表,这条命令不能换行，不然会执行出错
		$cmd_backup_table=webapp()->db->dump_cmd . ' --blobs -Fc -t epmms_backup -t epmms_messages -t epmms_announcement -t epmms_help -t epmms_userinfo -t epmms_config_auth -t epmms_config_backup -t epmms_config_map -t epmms_config_site -t epmms_config_smtp  -t epmms_rights -t epmms_menu -t epmms_authassignment -t epmms_authitem -t epmms_authitemchild -t epmms_award_config -t epmms_cap_award -t epmms_cap_member_award -t epmms_cap_member_sum -t epmms_cap_sum -t epmms_memberinfo_item -t epmms_rights -t epmms_transfer_config -t epmms_config_site -t epmms_config_map -t epmms_config_sms -t epmms_config_auth -t epmms_config_withdrawals -t epmms_config_smtp -t epmms_config_backup "'. webapp()->db->database . '">' . params('db_backup') . 'backup' . $this->backup_id.'.bak';
		exec($cmd_backup_table,$out,$error);
		if($error==0 && file_exists(params('db_backup') . 'backup' . $this->backup_id.'.bak'))
		{
			$out=null;
			$error=null;
			$cmd_file=$this->backup_file;
			$cmd_bak=webapp()->db->restore_cmd . " -e -1 --disable-triggers -c -Fc -d " . '"' . webapp()->db->database . '" ' . realpath( $cmd_file);
			exec($cmd_bak,$out,$error);

			if($error===0)
			{
				$out=null;
				$error=null;
				//恢复backup表
				$cmd_bak=webapp()->db->restore_cmd . " -e -1 --disable-triggers -c -Fc -n public -a award -d " . webapp()->db->database . ' ' . realpath(params('db_backup') . 'backup' . $this->backup_id.'.bak');
				exec($cmd_bak,$out,$error);
				unlink(params('db_backup') . 'backup' . $this->backup_id.'.bak');
				if($error===0)
				{
					return true;
				}
				else
				{
					throw new Error('恢复保留数据失败');
				}
			}
			else
			{
				throw new Error('恢复数据失败');
			}
		}
		else
		{
			throw new Error('备份保留数据失败');
		}
		return false;
	}

	/**
	 * 删除备份
	 * @return bool
	 */
	public function delete()
	{
		if(file_exists($this->backup_file))
			unlink($this->backup_file);
		return parent::delete();
	}

	public function down()
	{
		header('Content-type: application/octet-stream');
		$down_name=$this->backup_name . '.bak';
		header('Content-Disposition: attachment; filename=' . $down_name);
		echo openssl_encrypt(file_get_contents(params('db_backup') . $this->backup_file),$this->method,$this->pass . webapp()->id,OPENSSL_RAW_DATA,"1234567812345678");
	}

	public function upload()
	{
		$cmd_file=$this->genbakname() ;
		$enc_file=params('db_backup') . 'enc_' . $cmd_file;
		$bak_file=params('db_backup') . $cmd_file;
		$file=CUploadedFile::getInstanceByName('Backup[file]'); //获取表单名为filename的上传信息
		if(!$file->hasError)
		{
			if ($file->extensionName != 'bak')
			{
				throw new Error('文件类型不对');
			}
			if ($file->saveAs($enc_file))
			{
				$bak_str=openssl_decrypt(file_get_contents($enc_file), $this->method, $this->pass . webapp()->id,OPENSSL_RAW_DATA,"1234567812345678");
				unlink($enc_file);
				if($bak_str!==false)
				{
					$file_size = file_put_contents($bak_file, $bak_str);
					if ($file_size >= 1)
					{
						$this->backup_file = $bak_file;
						return true;
					}
					else
					{
						throw new Error('保存备份失败');
					}
				}
				else
				{
					throw new Error('上传的文件不正确');
				}
			}
			else
			{
				throw new Error('保存文件失败');
			}
		}
		else
		{
			throw new Error('上传失败');
		}
		return false;
	}

	public function genbakname()
	{
		return webapp()->db->database
			. '_' . date('Y-m-d_H_i_s',time()) . '_'
			.  hash('md5',$this->backup_name . time()) . '_' . $this->backup_type . ".bak";
	}
}
