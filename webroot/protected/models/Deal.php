<?php

/**
 * This is the model class for table "{{deal}}".
 *
 * The followings are the available columns in table '{{deal}}':
 * @property integer $deal_id
 * @property integer $deal_sale_id
 * @property integer $deal_buy_id
 * @property string $deal_currency
 * @property string $deal_date
 * @property string $deal_image
 *
 * The followings are the available model relations:
 * @property  * @property
 */
class Deal extends Model
{
    //模型标题
    public $modelName = '配对';
    //命名字段
    public $nameColumn = 'deal_id';

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{deal}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('deal_sale_id, deal_currency, deal_date', 'filter', 'filter' => array($this, 'empty2null')),
            array('deal_buy_id', 'required'),
            array('deal_sale_id, deal_buy_id', 'numerical', 'integerOnly' => true),
            array('deal_sale_id', 'exist', 'className' => 'Sale', 'attributeName' => 'sale_id'),
            array('deal_currency', 'ext.validators.Decimal', 'precision' => 16, 'scale' => 2),
            array('deal_image', 'file', 'types'=>'png,jpg,jpeg,bmp,gif,webp','mimeTypes'=>'image/bmp,image/gif,image/png,image/webp,image/jpeg','allowEmpty'=>true),
            array('deal_date,deal_image', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('deal_id, deal_sale_id, deal_buy_id, deal_currency, deal_date', 'safe', 'on' => 'search'),
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
            'dealSale' => array(Model::BELONGS_TO, 'Sale', 'deal_sale_id'),
            'dealBuy' => array(Model::BELONGS_TO, 'Buy', 'deal_buy_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'deal_id' => t('epmms', 'Deal'),
            'deal_sale_id' => t('epmms', '买入者'),
            'deal_buy_id' => t('epmms', '卖出者'),
            'deal_currency' => t('epmms', '金额'),
            'deal_date' => t('epmms', '日期'),
            'deal_image' => '图片',
            'deal_status' => '状态'
        );
    }

    public function verify()
    {
        $sale = $this->dealSale;
        if (is_null($sale))
        {
            throw new EError('找不到买入者');
        }
        $t = webapp()->db->beginTransaction();
        //$this->refresh();
        if ($this->deal_status == 2)
        {
            $t->rollback();
            return EError::DUPLICATE;
        }
        /*        $bak=new Backup();
                if(!$bak->autoBackup('确认配对：'.$this->deal_id,'确认时间：'.webapp()->format->formatdatetime(time())))
                {
                    $t->rollback();
                    throw new EError('备份失败');
                }*/
        $this->deal_status = 2;
        $this->deal_verify_date = new CDbExpression('now()');
        if ($this->saveAttributes(['deal_status', 'deal_verify_date']) == false)
        {
            $t->rollback();
            throw new EError('保存状态失败');
        }
/*        if($sale->sale_type==1)
        {
            $finance = $sale->saleMember->getFinance(3);
            $finance->add($this->deal_currency * 1.2);
        }
        else
        {
            $finance = $sale->saleMember->getFinance(3);
            $finance->add($this->deal_currency * 0.2);
        }*/

//        $mtype = MemberType::model()->findByAttributes(['membertype_money' => $sale->sale_currency]);
//        $member = $sale->saleMember;
//        $member->membermap->membermap_membertype_level = $mtype->membertype_level;
//        $member->membermap->saveAttributes(['membermap_membertype_level']);
        $finance = $sale->saleMember->getFinance(3);
        $finance->add($this->deal_currency);
        // $finance = $sale->saleMember->getFinance(2);
        // $finance->add($this->deal_currency * 0.8);
/*        $dbPeriod = new DbEvaluate("nextval('award_period')");
        $sumProc = new DbCall('epmms_verify_award_group', array($sale->sale_member_id, $dbPeriod->run(), 1, 0, $this->deal_id));
        $sumProc->run();
        $Proc = new DbCall('gen_finance_log');
        $Proc->run();*/
        //$member->membermap->membermap_pay_count=$member->membermap->membermap_pay_count+1;
        //$member->membermap->saveAttributes(['membermap_pay_count']);
        $t->commit();
        //$sms = Sms::model();
        //$str = "亲爱的 {$member->showName} 会员，配对确认成功。\r\n【GUC】";
        //$sms->send($str, $member->memberinfo_mobi);
        return true;
    }

    public function sendUpload()
    {
        $member = $this->dealBuy->buyMember;
        $sms = Sms::model();
        $str = "亲爱的 {$member->showName} 会员，已为您打款成功，请确认收到款后上系统确认\r\n【GUC】";
        //$sms->send($str, $member->memberinfo_mobi);
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

        $sort = new Sort('Deal');
        $sort->defaultOrder = array('deal_id' => Sort::SORT_DESC);
        $criteria = new CDbCriteria;
        if (webapp()->request->isAjaxRequest)
        {
            $criteria->addColumnCondition(['"dealSale".sale_member_id'=>$this->dealSale->sale_member_id,'"dealBuy".buy_member_id'=>$this->dealBuy->buy_member_id],'or','and');
            $criteria->compare('deal_status',@$this->deal_status,false,'and');
            $criteria->with = array('dealSale', 'dealBuy', 'dealSale.saleMember', 'dealBuy.buyMember');
        } else
        {
            $criteria->compare('deal_id', $this->deal_id);
            $criteria->compare('deal_sale_id', $this->deal_sale_id);
            $criteria->compare('deal_buy_id', $this->deal_buy_id);
            $criteria->compare('deal_status', $this->deal_status);
            $criteria->compare('deal_currency', $this->deal_currency);
            $criteria->compare('deal_date', $this->deal_date, true);
            $criteria->compare('"dealSale".sale_id', @$this->dealSale->sale_id);
            $criteria->compare('"dealSale".sale_member_id', @$this->dealSale->sale_member_id);
            $criteria->compare('"dealBuy".buy_id', @$this->dealBuy->buy_id);
            $criteria->compare('"dealBuy".buy_member_id', @$this->dealBuy->buy_member_id);
            $criteria->with = array('dealSale', 'dealBuy', 'dealSale.saleMember', 'dealBuy.buyMember');
        }
        if (webapp()->request->isAjaxRequest)
        {
            $page = 0;
            $pageSize = 20;
            if (isset($_GET['page']))
                $page = $_GET['page'] - 1;
            if (isset($_GET['limit']))
                $pageSize = $_GET['limit'];
            return new JSonActiveDataProvider($this, array(
                'criteria' => $criteria,
                'sort' => $sort,
                'pagination' => array(
                    'currentPage' => $page,
                    'pageSize' => $pageSize,
                ),
                'includeDataProviderInformation' => true,
                'relations' => [
                    'dealSale' => ['relations' => ['saleMember' ]],
                    'dealBuy' => ['relations' => ['buyMember']],
                ]
            ));
        } else
        {
            return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
                'sort' => $sort,
            ));
        }

    }
    public static function dealConfirm1()
    {
        $deals=Deal::model()->findAll('deal_status=0 and deal_type=1');
        foreach($deals as $deal)
        {
            $deal->deal_status=2;
            $deal->deal_verify_date=new CDbExpression('now()');
            $deal->saveAttributes(['deal_status','deal_verify_date']);
            $saleMember=$deal->dealSale->saleMember;
            $financeJifen=$saleMember->getFinance(1);
            $financeJinbi=$saleMember->getFinance(2);
            $financeJinbi->add($deal->deal_currency*0.8);
            $financeJifen->add($deal->deal_currency*0.2);
            $dbPeriod = new DbEvaluate("nextval('award_period')");
            $sumProc = new DbCall('epmms_verify_award_group', array($deal->dealSale->sale_member_id, $dbPeriod->run(), 1, 0, $deal->deal_id));
            $sumProc->run();
            $dbPeriod = new DbEvaluate("nextval('award_period')");
            $sumProc = new DbCall('epmms_verify_award_group', array($deal->dealSale->sale_member_id, $dbPeriod->run(), 3, 0, $deal->deal_id));
            $sumProc->run();
        }
    }
    public static function dealConfirmAuto()
    {
        $deals=Deal::model()->findAll('deal_status=0 and deal_type=0');
        foreach($deals as $deal)
        {
            $deal->deal_status=2;
            $deal->deal_verify_date=new CDbExpression('now()');
            $deal->saveAttributes(['deal_status','deal_verify_date']);
            $saleMember=$deal->dealSale->saleMember;
            $financeJifen=$saleMember->getFinance(1);
            $financeJinbi=$saleMember->getFinance(2);
            $financeJinbi->add($deal->deal_currency*0.8);
            $financeJifen->add($deal->deal_currency*0.2);
            $dbPeriod = new DbEvaluate("nextval('award_period')");
            $sumProc = new DbCall('epmms_verify_award_group', array($deal->dealSale->sale_member_id, $dbPeriod->run(), 1, 0, $deal->deal_id));
            $sumProc->run();
            $dbPeriod = new DbEvaluate("nextval('award_period')");
            $sumProc = new DbCall('epmms_verify_award_group', array($deal->dealSale->sale_member_id, $dbPeriod->run(), 3, 0, $deal->deal_id));
            $sumProc->run();
        }
        //下面是释放和平衡奖
        $dbPeriod = new DbEvaluate("nextval('award_period')");
        $sumProc = new DbCall('epmms_verify_award_group', array(null, $dbPeriod->run(), 2, 0,0));
        $sumProc->run();

    }
    public function getImageUrl()
    {
        return path2url($this->deal_image);
    }

    public static function match($buy_id,$sale_id)
    {
        $ret=[];
        $buy=Buy::model()->findByPk($buy_id);
        if(empty($buy))
        {
            $ret['success']=false;
            $ret['msg']='卖出者指定错误';
        }
        $sale=Sale::model()->findByPk($sale_id);
        if(empty($sale))
        {
            $ret['success']=false;
            $ret['msg']='买入者指定错误';
            return $ret;
        }
        if($buy->buy_remain_currency<=0)
        {
            $ret['success']=false;
            $ret['msg']='卖出者剩余金额为零';
            return $ret;
        }
        if($sale->sale_remain_currency<=0)
        {
            $ret['success']=false;
            $ret['msg']='买入者剩余金额为零';
            return $ret;
        }
        $deal_currency=min($buy->buy_remain_currency,$sale->sale_remain_currency);
        $deal=new Deal('create');
        $deal->deal_sale_id=$sale_id;
        $deal->deal_buy_id=$buy_id;
        $deal->deal_sale_member_id=$sale->sale_member_id;
        $deal->deal_buy_member_id=$buy->buy_member_id;
        $deal->deal_currency=$deal_currency;
        $deal->deal_date=new CDbExpression("now()");
        $deal->deal_status=0;
        if($deal->save())
        {
            $buy->buy_remain_currency=$buy->buy_remain_currency-$deal_currency;
            if($buy->buy_remain_currency<=0)
            {
                $buy->buy_status=2;
            }
            else
            {
                $buy->buy_status=1;
            }
            $sale->sale_remain_currency=$sale->sale_remain_currency-$deal_currency;

            if($sale->sale_remain_currency<=0)
            {
                $sale->sale_status=2;
            }else
            {
                $sale->sale_status=1;
            }

            $sale->sale_verify_date=new CDbExpression('now()');
            if($sale->saveAttributes(['sale_status','sale_remain_currency','sale_verify_date']) && $buy->saveAttributes(['buy_status','buy_remain_currency']))
            {
                $ret['success']=true;
                $ret['msg']='匹配成功';
                return $ret;
            }
        }
        else
        {
            $ret['success']=false;
            $ret['msg']='交易保存失败';
            $ret['error']=$deal->getErrors();
            return $ret;
        }

    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Deal the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
    public function delete()
    {
        if($this->deal_status==2)
        {
            throw new EError('已完成交易不能删除');
        }
        $finance=Finance::getMemberFinance($this->deal_buy_member_id,3);
        if($finance->add($this->deal_currency))
        {
            if(parent::delete())
                return true;
            else
                throw new EError('删除失败');
        }
        else
            throw new EError('退款失败');

    }
}
