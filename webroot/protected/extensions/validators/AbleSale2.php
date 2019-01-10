<?php
/**
 * CNumberValidator class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright 2008-2013 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CNumberValidator validates that the attribute value is a number.
 *
 * In addition to the {@link message} property for setting a custom error message,
 * CNumberValidator has a couple custom error messages you can set that correspond to different
 * validation scenarios. To specify a custom message when the numeric value is too big,
 * you may use the {@link tooBig} property. Similarly with {@link tooSmall}.
 * The messages may contain additional placeholders that will be replaced
 * with the actual content. In addition to the "{attribute}" placeholder, recognized by all
 * validators (see {@link CValidator}), CNumberValidator allows for the following placeholders
 * to be specified:
 * <ul>
 * <li>{min}: when using {@link tooSmall}, replaced with the lower limit of the number {@link min}.</li>
 * <li>{max}: when using {@link tooBig}, replaced with the upper limit of the number {@link max}.</li>
 * </ul>
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.validators
 * @since 1.0
 */
class AbleSale2 extends CValidator
{

	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 * @param CModel $object the object being validated
	 * @param string $attribute the attribute being validated
	 */
	protected function validateAttribute($object,$attribute)
	{
		$value=$object->$attribute;
		$message=t('epmms','未正确填写会员');
		if(is_null($value))
		{
			$this->addError($object,$attribute,$message);
			return;
		}
/*		if(Sale::model()->exists('sale_member_id=:id and sale_status<2',[':id'=>user()->id]))
		{
			//throw new CHttpException(500,"有买入没有完成，不能再买入");
			$this->addError($object,$attribute,'有买入没有完成，不能再买入');
			return;
		}
        if(Deal::model()->exists(['condition'=>'deal_count<1 and "dealSale".sale_type=0 and deal_sale_id="dealSale".sale_id and "dealSale".sale_member_id=:id','with'=>'dealSale','params'=>[':id'=>user()->id]]))
        {
            //throw new CHttpException(500,"没有回本，不能再买入");
            $this->addError($object,$attribute,'没有回本，不能再买入');
            return;
        }*/
        if(webapp()->id=='180821')
        {
            if($value%500!=0)
            {
                $this->addError($object,$attribute,'必须是500的整数倍');
            }
        }
		if(webapp()->id=='180725')
        {
            $command = Yii::app()->db->createCommand("select max(sale_currency) from epmms_sale where sale_member_id=:id;");
            $max_money = $command->queryScalar([':id'=>user()->id]);
            if($value<$max_money)
            {
                $this->addError($object,$attribute,'金额不能小于上次金额！');
            }
            if(self::getDayTotal()>=getAwardConfig(370))
            {
                $this->addError($object,$attribute,'亲爱的家人，排单金额已达上线，请明天再来，么么哒！');
                return;
            }
            if(getdate()['hours']<9)
            {
                $this->addError($object,$attribute,'亲爱的家人，抢单时间9:00-24:00，别忘了购买排单币哦，么么哒！');
                return;
            }

/*            if(user()->map->membermap_recommend_number<2)
            {
                if($value>5000)
                {
                    $this->addError($object,$attribute,'直推不足2人只可排单不大于5000元');
                }

            }
            if(user()->map->membermap_recommend_number>=2 && user()->map->membermap_recommend_number<5)
            {
                if($value>10000)
                {
                    $this->addError($object,$attribute,'直推2人以上可排单不大于10000元');
                }
            }*/

        }
	}
    //获取账户提现总额
    public static function getDayTotal()
    {
        $connection = Yii::app()->db;
        $str_sql  = "select sum(sale_currency) as total_money from epmms_sale";
        $str_sql .= " where sale_date::date=current_date";
        $command = $connection->createCommand($str_sql);
        return $command->queryScalar();
    }
}
