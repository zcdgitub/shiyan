<?php
if ($event->step):
	echo CHtml::tag('p', array(), '������'.$event->sender->getStepLabel($event->step));
	echo CHtml::tag('p', array(), '���ṩ����Ϣ����:');
	foreach ($event->data as $step=>$data):
		echo CHtml::tag('h2', array(), $event->sender->getStepLabel($step));
		echo ('<ul>');
		foreach ($data as $k=>$v)
			echo "<li>$k: $v</li>";
	endforeach;
else:
	echo '<p>�����һ�ʧ��</p>';
endif;
