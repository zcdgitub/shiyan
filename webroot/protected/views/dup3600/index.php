<?php
/* @var $this Dup3600Controller */

$this->breadcrumbs=array(
	'进入矩阵',
);
?>
<h1>进入矩阵</h1>

<p>
<?if($status==1):?>
	咨询公司
<?else:?>
	费用不够，已扣<?=$fee?>
<?endif;?>
</p>
