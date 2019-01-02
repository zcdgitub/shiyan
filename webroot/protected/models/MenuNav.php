<?php

/**
 * This is the model class for table "{{menu}}".
 *
 * The followings are the available columns in table '{{menu}}':
 * @property integer $menu_id
 * @property string $menu_name
 * @property string $menu_url
 * @property integer $menu_pid
 * @property integer $menu_order
 * @property string $menu_mod_date
 *
 * The followings are the available model relations:
 * @property  * @property  */
class MenuNav extends Model
{
	public $modelName='导航菜单';
	public $nameColumn='menu_name';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MenuNav the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{menu}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('menu_url, menu_pid, menu_order, menu_mod_date', 'filter','filter'=>array($this,'empty2null')),
			array('menu_name', 'required'),
			array('menu_pid, menu_order', 'numerical', 'integerOnly'=>true),
			array('menu_url', 'length', 'max'=>200),
			array('menu_pid', 'exist', 'className'=>'MenuNav','attributeName'=>'menu_id'),
			array('menu_name','length'),
			array('menu_mod_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('menu_id, menu_name, menu_url, menu_pid, menu_order, menu_mod_date', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'menuP' => array(Model::BELONGS_TO, 'MenuNav', 'menu_pid'),
			'menus' => array(Model::HAS_MANY, 'MenuNav', 'menu_pid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'menu_id' => 'Menu',
			'menu_name' => t('epmms','名称'),
			'menu_url' => t('epmms','路由'),
			'menu_pid' => t('epmms','父级菜单'),
			'menu_order' =>  t('epmms','排序'),
			'menu_mod_date' =>  t('epmms','修改日期'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
		$sort=new Sort('MenuNav');
		$sort->defaultOrder=array('menu_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;
		$criteria->compare('menu_id',$this->menu_id);
		$criteria->compare('menu_name',$this->menu_name,true);
		$criteria->compare('menu_url',$this->menu_url,true);
		$criteria->compare('menu_order',$this->menu_order);
		$criteria->compare('menu_mod_date',$this->menu_mod_date,true);
		$criteria->compare('menu_pid',@$this->menuP->menu_name);
		//$criteria->with=array('menuP');
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}
	public function getItems()
	{
		$p = new PhpStringParser();
		$menu_items = array();
		$menu = new MenuNav ();
		$parent_menu = $menu->findAll ( array(
			'select' => 'menu_id,menu_name,menu_url',
			'condition' => 'menu_pid is null',
			'order' => 'menu_order asc'
		) );

		foreach ( $parent_menu as $menu_item )
		{
			$child_menu=$menu->findAll(array(
					'select'=>'menu_id,menu_name,menu_url',
					'condition'=>'menu_pid=:menu_pid',
					'order'=>'menu_order asc',
					'params'=>array('menu_pid'=>$menu_item->menu_id)
				)
			);
		
			$child_menu_items=array();
			foreach($child_menu as $child_menu_item)
			{
				/*var_dump($child_menu_item);
				die;*/
				if($child_menu_item->menu_url=='agent/index' && !MemberinfoItem::model()->itemVisible('membermap_agent_id'))
					continue;
				if($child_menu_item->menu_url=='agent/register' && !MemberinfoItem::model()->itemVisible('membermap_agent_id'))
					continue;
				//if($child_menu_item->menu_url=='memberinfo/create' && !MemberinfoItem::model()->itemVisible('membermap_agent_id') && user()->isAdmin())
				//	continue;
				if(webapp()->id=='141203' && user()->id!=1)
				{
					if ($child_menu_item->menu_url == 'membermap2/orgMap' && !user()->isAdmin() && user()->map->membermap_membertype_level == 4)
						continue;
					if ($child_menu_item->menu_url == 'membermap4/orgMap' && !user()->isAdmin() && user()->map->membermap_membertype_level != 4)
						continue;
				}
				if ($child_menu_item->menu_url == 'membermap/orgMap' && !MemberinfoItem::model()->itemVisible('membermap_parent_id'))
					continue;
				if ($child_menu_item->menu_url == 'membermap/orgMapRecommend' && !MemberinfoItem::model()->itemVisible('membermap_recommend_id'))
					continue;

				if(strcasecmp($child_menu_item->menu_url,'membermap/orgMap')==0)
				{
					if(webapp()->user->checkAccess($this->parseUrl($child_menu_item->menu_url),['nav'=>true]) || webapp()->user->checkAccess($this->parseUrl('membermap/tree'),['nav'=>true]))
					{
						$child_menu_items[]=array(
							'label'=>$p->parse($child_menu_item->menu_name),
							'url'=>array($child_menu_item->menu_url)
						);
					}
				}
				elseif(strcasecmp($child_menu_item->menu_url,'membermap/orgMapRecommend')==0)
				{
					if(webapp()->user->checkAccess($this->parseUrl($child_menu_item->menu_url),['nav'=>true]) || webapp()->user->checkAccess($this->parseUrl('membermap/treeRecommend'),['nav'=>true]))
					{
						$child_menu_items[]=array(
							'label'=>$p->parse($child_menu_item->menu_name),
							'url'=>array($child_menu_item->menu_url)
						);
					}
				}
				elseif(webapp()->user->checkAccess($this->parseUrl($child_menu_item->menu_url),['nav'=>true]))
				{

					$child_menu_items[]=array(
						'label'=>$p->parse($child_menu_item->menu_name),
						'url'=>array($child_menu_item->menu_url)
					);
				}
			}

			if($child_menu_items!==array())
			{

				/*if($menu_item->menu_name=='产品中心')
				{
					$all_class=ProductClass::model()->findAll();
				
					foreach($all_class as $one_class)
					{
						$child_menu_items[] = array('label'=>$p->parse($one_class->product_name),'url'=>array('product/index/' . $one_class->product_class_id));
					}
				}*/
				$menu_items [] = array(
					'label' => $p->parse($menu_item->menu_name),
					'items'=>$child_menu_items,
					'url'=>array($menu_item->menu_url)
				);
			}
		}
        //$menu_items[]=['label'=>'商城购物','items'=>[['label'=>'进入商城','url'=>[params('shop_url')]]]];
    /*    echo "<Pre>";
        var_dump($menu_items);
        die;*/
		return $menu_items;
	}
	public function parseUrl($url)
	{
		$url=trim($url);
		$pos=strpos($url,'&');
		if($pos)
		{
			$url=substr($url,0,$pos);
		}
		$pos=strpos($url,'?');
		if($pos)
		{
			$url=substr($url,0,$pos);
		}
		if($url=='')
			return $url;
		$pos=strpos($url,'/');
		if($pos===false)
			return ucfirst($url) . '.Index';
		else if( $pos+1===strlen($url))
			return ucfirst(left($url,$pos)) . '.Index';
		else if($pos===0)
			return ucfirst(substr($url,1)) . '.Index';
		else
		{
			$items=explode('/',$url);
			foreach($items as $key=>$item)
			{
				$items[$key]=ucfirst($item);
			}
			return implode('.',$items);
		}
	}
}