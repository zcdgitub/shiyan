<?php
/**
 * Created by PhpStorm.
 * User: 涛
 * Date: 14-3-15
 * Time: 上午10:26
 */

class ModuleConfig extends CComponent
{
	public function check($checkName)
	{
		$versionName=params('versionConfig');
		$allModule=array_keys($this->getModule());
		$theModule=array_keys($this->getModule($this->getVersionConfig($versionName)));
		$diffModule=array_diff($allModule,$theModule);
		foreach($this->getModule($diffModule) as $moduleConfig)
		{
			if(in_array($checkName,$moduleConfig))
			{
				return true;
			}
		}
		return false;
	}
	public function getModule($moduleName=null)
	{
		$modules=['product'=>['Product.View','Product.Admin','Product.Create','Product.Delete','Product.Index',
			'Product.Update','Orders.Create','Orders.Delete','Orders.Index','Orders.Verify','Orders.View',
			'Product.Upload','ProductClass.Index'],
			'privilege'=>['Privilege.AuthItem.Assign','Privilege.AuthItem.Create','Privilege.AuthItem.Delete',
				'Privilege.AuthItem.Permissions','Privilege.AuthItem.Revoke','Privilege.AuthItem.Roles','Privilege.AuthItem.Update'],
			'multiLanguage'=>[],
			'mapEdit'=>['MapEdit.AddMap','MapEdit.Delete','MapEdit.DeleteMap','MapEdit.Index','MapEdit.MoveMap',
				'MapEdit.SwapMap','MapEdit.Update','MapEdit.Verify','MapEdit.View'
			],
			'transfer'=>['Transfer.Index','Transfer.Create','Transfer.View','Transfer.Update','Transfer.CreateSelf'],
			'membertypeUpgrade'=>['MemberUpgrade.Delete','MemberUpgrade.View','MemberUpgrade.Create','MemberUpgrade.Verify','MemberUpgrade.Index'],
			'supplement'=>['Supplement.Create','Supplement.View','Supplement.Index'],
			'prize'=>['Prize.Index','Prize.View','Prize.Verify'],
			'withdrawals'=>['Withdrawals.Index','Withdrawals.Create'],
			'awardperiod'=>['AwardPeriodSum.Index','AwardPeriod.Index'],
			'memberlevel'=>['MemberLevel.Index'],
			'amap'=>['Membermap.Create','Membermap.Index'],
			'bmap'=>['Membermap2.OrgMap','Membermap2.OrgMapJson','Membermap2.Create','Membermap2.Index','Membermap2.Tree','Membermap2.TreeRecommend','Membermap2.Copy'],
			'cmap'=>['Membermap4.OrgMap','Membermap4.OrgMapJson','Membermap4.Create','Membermap4.Index','Membermap4.Tree','Membermap4.TreeRecommend'],
            'futou'=>['Futou.Create','Futou.Index']
		];
		if(is_null($moduleName))
			return $modules;
		if(is_array($moduleName))
		{
			$pModules=[];
			foreach($moduleName as $mName)
			{
				$pModules[$mName]=$modules[$mName];
			}
			return $pModules;
		}
		return $modules[$moduleName];
	}
	public function getVersionConfig($versionName='enterprise')
	{
		$configs=[
		'group'=>['product','privilege','multiLanguage','mapEdit'],
		'enterprise'=>['privilege','transfer','awardperiod','withdrawals','membertypeUpgrade','product','memberlevel'],
		'team'=>[]
		];
		return $configs[$versionName];
	}
}