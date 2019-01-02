<?php
/**
 * Created by PhpStorm.
 * User: 涛
 * Date: 14-7-28
 * Time: 下午4:52
 */
?>
<div class="group-member" style="background-color:<?=$data->groupmap_is_award==0?'lightgreen':'#19C1D0'?>" >
	<?php if($data->groupmap_order>=$my_order):?>
<!--	--><?/*=$data->showName*/?>
	<br/>关联:<?=$data->groupmapmemberinfo->memberinfo_account?>
	<br/>
	次序:<?=$data->groupmap_order?>
	<br/>
<!--	--><?/*=$data->groupmap_is_award==0?'':'已出局'*/?>
	<div class="datetime" ><br/><?=$data->groupmap_group_date?></div>
	<?php endif;?>
</div>
