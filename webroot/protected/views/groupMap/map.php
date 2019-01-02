<style type="text/css">
	html{ -webkit-text-size-adjust:none; }
	li{
		float:left;
		padding:0px;
		margin:0px;
	}
	ul
	{
		padding:0px;
		margin:0px;
		list-style-type:none;
	}
	.group-container
	{
		padding:0px;
		margin:0px;
	}
	.group-member
	{
		border:1px solid gray;
		width:120px;
		height:100px;
		background-color: #E9F5E9;
		color:#000000;
		font-size:12px;
		line-height:18px;
		text-align:center;
		margin-top:4px;
		margin-bottom:4px;
		margin-left:1px;
		margin-right:8px;
		padding:0px;
		margin:2px;
		position:relative;
		float:left;
	}
	.groupinfo
	{
		padding:0px;
		margin:0px 0px 8px 0px;
		border:none;
		font-size:24px;
	}
	.group
	{
		width:630px;
	}
	.datetime
	{
		font-size:12px;position:absolute;left:0px;bottom:0px;text-align:center;
	}
	.glist
	{
		word-wrap: break-word;
	}
</style>
<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hetao
 * Date: 13-9-30
 * Time: 下午11:42
 * To change this template use File | Settings | File Templates.
 */

$this->breadcrumbs=array(
	t('epmms',$model->modelName)
);
?>
<h1>公排图</h1>
<?php
$my_group=[];
if(user()->isAdmin())
	$my_group=Group::model()->findAll(['order'=>'group_seq asc']);
else
{
	$group_id = GroupMap::model()->findByAttributes(['groupmap_member_id'=>user()->id],['order'=>'groupmap_order asc']);

	if(is_null($group_id))
	{
		echo "尚未进入公排";
	}
	else
	{
		$my_group = Group::model()->findAllByAttributes(['group_id' => $group_id->groupmap_group_id], ['order' => 'group_seq asc']);
	}
}
?>
<style type="text/css">
	div.list-view
	{
		height:260px;
	}
</style>
<?foreach($my_group as $group):?>
公排组:<?=$group->group_seq?>
	<?php
	$model->unsetAttributes();
	$model->groupmap_group_id=$group->group_id;
	?>
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$model->search(),
	'viewData'=>['my_order'=>user()->isAdmin()?-1:$group_id->groupmap_order],
	'itemView'=>'_view',
)); ?>
<?endforeach;?>