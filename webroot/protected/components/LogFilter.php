<?php

/** 
 * @author hetao
 * 
 */
class LogFilter extends CFilter
{
	private $_rules=array();
	const SUCCESS=0;
	const FAILED=1;
	
	/**
	 * @return array list of access rules.
	*/
	public function getRules()
	{
		return $this->_rules;
	}
	
	/**
	 * @param array $rules list of access rules.
	 */
	public function setRules($rules)
	{
		foreach($rules as $op=>$rule)
		{
			if(is_array($rule))
			{
				$this->_rules[$op]=$rule;
			}
			elseif(is_string($rule))
			{
				$this->_rules[$op]=array('operate'=>$rule);
			}
		}
	}	
	/**
	 * Performs the pre-action filtering.
	 * @param CFilterChain $filterChain the filter chain that the filter is on.
	 * @return boolean whether the filtering process should continue and the action
	 * should be executed.
	 */
	protected function preFilter($filterChain)
	{
		return true;
	}
	
	/**
	 * Performs the post-action filtering.
	 * @param CFilterChain $filterChain the filter chain that the filter is on.
	 */
	protected function postFilter($filterChain)
	{
		$log=array();
		$activeLog=$filterChain->controller->log;
		$rules=isset($this->rules[$filterChain->action->id])?$this->rules[$filterChain->action->id]:array();		
		$log['status']=self::SUCCESS;
		$log['source']=$filterChain->controller->controllerName;
		$log['operate']=$filterChain->controller->actionName;
		if(isset($rules['target']) )
			$rules['target']=$this->evaluateExpression($rules['target']);
		
		if(isset($rules['value']) )
			$rules['value']=$this->evaluateExpression($rules['value']);
		
		if(isset($this->rules['info']) )
			$rules['info']=$this->evaluateExpression($rules['info']);
		$log['ip']=webapp()->request->userHostAddress;
		if(user()->isGuest())
		{
			$log['user']='Guest';
			$log['role']='Guest';
		}
		else
		{
			$log['user']=user()->name;
			$log['role']=user()->role;
		}

		$logParam=array_merge($log,$rules,$activeLog);
		$this->log($logParam);
	}
	public static function log($logParams)
	{
		$model=new Log();
		$model->log_source=$logParams['source'];
		$model->log_operate=$logParams['operate'];
		$model->log_target=isset($logParams['target'])?$logParams['target']:null;
		$model->log_value=isset($logParams['value'])?$logParams['value']:null;
		$model->log_info=isset($logParams['info'])?$logParams['info']:null;
		$model->log_ip=isset($logParams['ip'])?$logParams['ip']:null;
		$model->log_user=$logParams['user'];
		$model->log_role=$logParams['role'];
		$model->log_status=$logParams['status'];
		$model->log_category=isset($logParams['category'])?$logParams['category']:'operate';
		$model->save();
	}
}

?>