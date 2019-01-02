<?php
/* @var $this ChargeController */
/* @var $model Charge */

$this->breadcrumbs=array(
	t('epmms',$model->modelName)=>array('index'),
	t('epmms','添加'),
);

$this->menu=array(
	array('label'=>t('epmms','管理') . t('epmms',$model->modelName), 'url'=>array('index')),
);
?>

<h1><?php echo t('epmms','添加') . t('epmms',$model->modelName)?></h1>
	<table class="status_grid">
		<?php foreach($financeType as $finance):?>
			<tr  >
				<th style="height: 26px;vertical-align: middle"><?=t('epmms',$finance->showName)?>:<?=webapp()->format->formatNumber(@$finance->getMemberFinance(user()->id)->finance_award)?></th>
			</tr>
		<?php endforeach;?>
	</table>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>