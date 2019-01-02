<?php

class Dup3600Controller extends Controller
{
	public function actionIndex()
	{
		if(!user()->isAdmin())
		{
			$finance = Finance::getMemberFinance(user()->id, 3);
			if (!is_null($finance))
			{
				$status = $finance->finance_award >= 3600 ? 1 : 0;
				$this->render('index', ['status' => $status, 'fee' => $finance->finance_award]);
			}
		}
	}

	public function filters()
	{
		return array(
			'closeSite',
			'rights', // rights rbac filter
		);
	}
}