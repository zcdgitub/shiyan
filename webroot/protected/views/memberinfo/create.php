<?php
/* @var $this MemberinfoController */
/* @var $model Memberinfo */

$this->breadcrumbs=array(
	t('epmms',$model->modelName)=>array('index'),
	t('epmms','注册'),
);
?>
<?if(!user()->isAdmin()):?>
	<table class="status_grid">
		<?php foreach($financeType as $finance):?>
			<tr>
				<td style="height: 26px;vertical-align: middle"><?=t('epmms',$finance->showName)?>:<?=webapp()->format->formatNumber(@$finance->getMemberFinance(user()->id)->finance_award)?></td>
			</tr>
		<?php endforeach;?>
	</table>
<?endif;?>

<?php echo $this->renderPartial('_form', array('form'=>$form)); ?>