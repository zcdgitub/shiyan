<?php
if ($event->step):
	echo CHtml::tag('p', array(), '结束于'.$event->sender->getStepLabel($event->step));
	echo CHtml::tag('p', array(), '你提供的信息如下:');
	foreach ($event->data as $step=>$data):
		echo CHtml::tag('h2', array(), $event->sender->getStepLabel($step));
		echo ('<ul>');
		foreach ($data as $k=>$v)
			echo "<li>$k: $v</li>";
	endforeach;
else:
	echo '<p>密码找回失败</p>';
endif;
