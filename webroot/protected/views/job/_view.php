<?php
/* @var $this JobController */
/* @var $data Job */
?>

<div class="view">
<table class="viewtable">
	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('jobid')); ?></b></td>
	<td class="value"><?php echo CHtml::link(CHtml::encode($data->jobid), array('view', 'id'=>$data->jobid)); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('jobname')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->jobname); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('jobdesc')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->jobdesc); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('jobhostagent')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->jobhostagent); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('jobenabled')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->jobenabled); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('jobcreated')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->jobcreated); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('jobchanged')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->jobchanged); ?></td>
	</tr>

	<tr class="even">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('jobnextrun')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->jobnextrun); ?></td>
	</tr>

	<tr class="odd">
	<td class="title"><b><?php echo CHtml::encode($data->getAttributeLabel('joblastrun')); ?></b></td>
	<td class="value"><?php echo CHtml::encode($data->joblastrun); ?></td>
	</tr>

</table>
</div>