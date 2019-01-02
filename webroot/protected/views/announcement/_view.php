<?php
/* @var $this AnnouncementController */
/* @var $data Announcement */
?>


<li style="border:none; list-style:circle; height:30px;">
	<span style="border:none;"><?php echo CHtml::link(CHtml::encode($data->announcement_title), array('view', 'id'=>$data->announcement_id)); ?></span>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<span style="border:none;"><?php echo CHtml::encode($data->announcement_mod_date); ?></span>
</li>