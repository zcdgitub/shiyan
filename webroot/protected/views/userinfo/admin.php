<?php
/* @var $this UserinfoController */
/* @var $model Userinfo */

$this->breadcrumbs=array(
	t('epmms',$model->modelName)=>array('admin'),
);

$this->menu=array(
	array('label'=>t('epmms','创建帐号'), 'url'=>array('create')),
);
?>
<h1><?=t('epmms','管理后台帐号')?></h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'userinfo-grid',
	'dataProvider'=>$model->search(),
	'filter'=>null,
	'columns'=>array(
		array('class'=>'DataColumn','value'=>'$row+1','header'=>'序号','htmlOptions' => array('style'=>'width:40px')),
		array('name'=>'userinfo_account','htmlOptions' => array('style'=>'width:100px')),
		array('name'=>'userinfo_name','htmlOptions' => array('style'=>'width:100px')),
		array('class'=>'DataColumn','name'=>'userinfo_sex','type'=>'sex','htmlOptions' => array('style'=>'width:40px')),
		array('name'=>'userinfo_email','htmlOptions' => array('style'=>'width:150px')),
		array('name'=>'userinfo_mobi','htmlOptions' => array('style'=>'width:100px')),
		array('name'=>'userinfo_jobtitle','htmlOptions' => array('style'=>'width:100px')),
		array('name'=>'userinfo_role','type'=>'role'),
		array('class'=>'DataColumn','name'=>'userinfo_add_date','type'=>'datetime','htmlOptions' => array('style'=>'width:120px')),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}',
			'updateButtonImageUrl'=>themeBaseUrl() . '/images/button/yello-mid-edit2.gif',
		),
		array(
				'class'=>'CButtonColumn',
				'template'=>'{delete}',
				'deleteButtonImageUrl' =>themeBaseUrl() . '/images/button/yello-mid-del2.gif',
				
		),
	),
)); ?>
