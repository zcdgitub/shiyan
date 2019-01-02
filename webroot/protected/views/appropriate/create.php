<style type="text/css">
	.status_grid tr td,.status_grid tr th
	{
		border-bottom: 1px dashed #cccccc;
	}
	.status_grid tr th
	{
		text-align: left;
	}
	.status_grid tr td
	{
		text-align: left;
	}
</style>
<?php
/* @var $this AppropriateController */
/* @var $model Appropriate */

$this->breadcrumbs=array(
	t('epmms',$model->modelName)=>array('index'),
	t('epmms','添加'),
);

$this->menu=array(
	array('label'=>t('epmms','浏览') . t('epmms',$model->modelName), 'url'=>array('list')),
	array('label'=>t('epmms','管理') . t('epmms',$model->modelName), 'url'=>array('index')),
);
?>

<h1><?php echo t('epmms',$model->appropriate_type==0?'拨款':'扣款')?></h1>
<table class="status_grid">
	<?php foreach($financeType as $financeOne):?>
		<tr>
			<th style="height: 26px;vertical-align: middle"><?=t('epmms',$financeOne->showName)?>:<?=webapp()->format->formatNumber(@$financeOne->getMemberFinance(user()->isAdmin()?$finance->finance_memberinfo_id:user()->id)->finance_award)?></th>
		</tr>
	<?php endforeach;?>
</table>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>