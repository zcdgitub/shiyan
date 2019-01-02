<?php
echo $event->sender->menu->run();
echo '<div>第 '.$event->sender->currentStep.'步，共 '.$event->sender->stepCount . '步';
echo '<h3>'.$event->sender->getStepLabel($event->step).'</h3>';
echo CHtml::tag('div',array('class'=>'form'),$form);
