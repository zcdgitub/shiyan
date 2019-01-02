<?php
/* @var $this MembermapController */
/* @var $data Membermap */
?>

<div class="view">
<table class="viewtable">
	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('membermap_id')); ?></b></td>
	<td class="value"><?php echo CHtml::link(CHtml::encode($data->membermap_id), array('view', 'id'=>$data->membermap_id)); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('membermap_id')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->membermap->memberinfo_account); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('membermap_parent_id')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->membermapParent->membermap_id); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('membermap_recommend_id')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->membermapRecommend->membermap_id); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('membermap_membertype_level')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->membermapMembertypeLevel->membertype_name); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('membermap_layer')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->membermap_layer); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('membermap_path')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->membermap_path); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('membermap_recommend_path')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->membermap_recommend_path); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('membermap_recommend_number')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->membermap_recommend_number); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('membermap_recommend_under_number')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->membermap_recommend_under_number); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('membermap_child_number')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->membermap_child_number); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('membermap_sub_number')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->membermap_sub_number); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('membermap_sub_product_count')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->membermap_sub_product_count); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('membermap_recommend_under_product_count')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->membermap_recommend_under_product_count); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('membermap_product_money')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->membermap_product_money); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('membermap_product_count')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->membermap_product_count); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('membermap_agent_id')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->membermapAgent->membermap_id); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('membermap_is_verify')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(webapp()->format->format($data->membermap_is_verify,'verify')); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('membermap_is_agent')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->membermap_is_agent); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('membermap_verify_date')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(webapp()->format->format($data->membermap_verify_date,'datetime')); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('membermap_verify_member_id')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->membermapVerifyMember->memberinfo_account); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('membermap_add_date')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(webapp()->format->format($data->membermap_add_date,'datetime')); ?></td>
	</tr>

</table>
</div>