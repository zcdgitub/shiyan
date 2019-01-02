<?php
class EJsonBehavior extends CBehavior{

    private $owner;
    private $relations;

    public function toJSON(){
        $ret=$this->toArray();
        if($ret)
            return CJSON::encode($ret);
        return false;
    }
    public function toArray($relations=array()){
        $this->owner = $this->getOwner();

        if (is_subclass_of($this->owner,'CActiveRecord')){

            $attributes = $this->owner->getAttributes();
            $this->relations 	= $this->getRelated($relations);
            //$this->relations 	= $relations;
            $jsonDataSource = array_merge($attributes,$this->relations);

            return $jsonDataSource;
        }
        return false;
    }
    private function getRelated($relations)
    {
        $related = array();

        $obj = null;

        $md=$this->owner->getMetaData();

        foreach($md->relations as $name=>$relation){
            if(array_search($name,$relations)!==false)
            {
                $obj = $this->owner->getRelated($name);
                $related[$name] = $obj instanceof CActiveRecord ? $obj->getAttributes() : $obj;
            }

        }

        return $related;
    }
}