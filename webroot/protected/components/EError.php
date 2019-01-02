<?php

/** 
 * @author hetao
 * 
 */
class EError extends \CException
{
	const SUCCESS=0;//操作成功
	const ERROR=1;//发生错误
	const DUPLICATE=2;//重复操作或已存在
	const DENY=3;//权限拒绝或不合法的操作
	const INEXISTENCE=4;//对象不存在
	const NOMONEY=5;//电子币不足
	const NOVERIFY_AGENT=6;//代理中心未审核
	const SAVE=7;//保存失败
	const NOSAVE=8;//保存失败
	const VERIFY_FAILED=9;//审核错误
	const NOVERIFY=10;//会员不存在或未审核
	const NOPARENT=11;//接点人无效或未审核
	const NORECOMMEND=12;//推荐人无效或未审核
}

?>