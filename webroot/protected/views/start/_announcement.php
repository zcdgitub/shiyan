<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hetao
 * Date: 13-8-29
 * Time: 下午10:40
 * To change this template use File | Settings | File Templates.
 */
$cnt=0;
?>
<table class="news_banner">
	<tr>
		<?php foreach(AnnouncementClass::model()->findAll(['order'=>'class_sort asc']) as $news_class):?>
			<?php
			$cnt++;
			?>
			<td class="<?=$cnt>1?'news_none_leftborder':''?>">
				<div class="mod TopicList" style="">
					<h2>
						<a class="title" href="<?=$this->createUrl('announcement/list')?>"><?=t('epmms',$news_class->class_name)?></a>
					</h2>
					<div class="news_content  news_height">
						<table width="100%" cellspacing="0" cellpadding="0" id="announcement_list">
							<tbody>
							<?php
							$models=Announcement::model()->findAll(['condition'=>'announcement_class=:cid','limit'=>5,'order'=>'announcement_mod_date desc','params'=>[':cid'=>$news_class->class_id]]);
							foreach($models as $item)
							{
								?>
								<tr>
									<td class="thread"><a class="thread_type_1"
														  title="<?= t('epmms', $item->announcement_title) ?>"
														  target="_self"
														  href="<?= $this->createUrl('announcement/view', array('id' => $item->announcement_id)) ?>"><?= webapp()->format->contentThumb($item->announcement_title); ?></a>
									</td>
									<td class="last_post"><?= t('epmms', $item->announcement_mod_date) ?></td>
								</tr>
							<?php
							}
							?>
							</tbody>
						</table>
						<div id="announcement_area" class="area_more">
							<a class="more" href="<?=$this->createUrl('announcement/list',['class_id'=>$news_class->class_id])?>"><?=t('epmms','更多')?>&nbsp;»</a>
							<div class="clear"></div>
						</div>
					</div>
				</div>
			</td>
		<?php endforeach;?>
	</tr>
</table>