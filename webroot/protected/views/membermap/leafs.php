<?php
/**
 * Created by PhpStorm.
 * User: 涛
 * Date: 14-1-17
 * Time: 上午1:28
 */
$this->breadcrumbs=array(
	t('epmms','注册会员')=>array('memberinfo/create'),
	t('epmms','可用会员'),
);
$this->menu=array(
	array('label'=>t('epmms','注册会员') , 'url'=>array('memberinfo/create')),
);
?>
<h1>可以使用的会员</h1>
<h2>推荐人</h2>
<p>
	<?=$r_member->showName?>
</p>
<h2><?=$r_member->showName?> A区</h2>
<p>
	<?=implode(',',$leafs_member)?>
</p>
<h2><?=$r_member->showName?> B区</h2>
<p>
	<?=implode(',',$leafs_member2)?>
</p>