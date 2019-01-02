<?php
/* @var $this MemberinfoController */
/* @var $data Memberinfo */
?>

<div class="view">
<table class="viewtable">
	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('memberinfo_nickname')); ?></b></td>
	<td class="value"><?php echo CHtml::link(CHtml::encode($data->memberinfo_nickname), array('view', 'id'=>$data->memberinfo_id)); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('memberinfo_account')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->memberinfo_account); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('memberinfo_name')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->memberinfo_name); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('memberinfo_nickname')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->memberinfo_nickname); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('memberinfo_email')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->memberinfo_email); ?></td>
	</tr>

	<tr class="even">
	<td class="title " style="border-bottom: 1px solid #ccc"><b><?php echo CHtml::encode($data->getAttributeLabel('memberinfo_mobi')); ?></b></td>
	<td class="value " style="border-bottom: 1px solid #ccc"><?php echo CHtml::encode($data->memberinfo_mobi); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('memberinfo_phone')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->memberinfo_phone); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('memberinfo_qq')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->memberinfo_qq); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('memberinfo_msn')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->memberinfo_msn); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('memberinfo_sex')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(webapp()->format->format($data->memberinfo_sex,'sex')); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('memberinfo_idcard_type')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->memberinfo_idcard_type); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('memberinfo_idcard')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->memberinfo_idcard); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('memberinfo_zipcode')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->memberinfo_zipcode); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('memberinfo_birthday')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->memberinfo_birthday); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('memberinfo_address_provience')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->memberinfo_address_provience); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('memberinfo_address_area')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->memberinfo_address_area); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('memberinfo_address_county')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->memberinfo_address_county); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('memberinfo_address_detail')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->memberinfo_address_detail); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('memberinfo_bank_id')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(@$data->memberinfoBank->bank_name); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('memberinfo_bank_name')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->memberinfo_bank_name); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('memberinfo_bank_account')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->memberinfo_bank_account); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('memberinfo_bank_provience')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->memberinfo_bank_provience); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('memberinfo_bank_area')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->memberinfo_bank_area); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('memberinfo_bank_branch')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->memberinfo_bank_branch); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('memberinfo_memo')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->memberinfo_memo); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('memberinfo_is_enable')); ?></b></td>
	<td class="value"><?php echo CHtml::encode(webapp()->format->format($data->memberinfo_is_enable,'enable')); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('memberinfo_register_ip')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->memberinfo_register_ip); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('memberinfo_last_ip')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->memberinfo_last_ip); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('memberinfo_last_date')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->memberinfo_last_date); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('memberinfo_add_date')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->memberinfo_add_date); ?></td>
	</tr>

	<tr class="odd ">
	<td class="title "><b><?php echo CHtml::encode($data->getAttributeLabel('memberinfo_mod_date')); ?></b></td>
	<td class="value" ><?php echo CHtml::encode($data->memberinfo_mod_date); ?></td>
	</tr>

</table>
</div>