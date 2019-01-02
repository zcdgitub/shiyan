<?php

/**
 *
 * @author hetao
 *        
 */
class CaptchaAction extends CCaptchaAction
{
    /**
     * Runs the action.
     */
    public function run()
    {
        if(isset($_GET[self::REFRESH_GET_VAR]))  // AJAX request for regenerating code
        {
            $code=$this->getVerifyCode(true);
            echo CJSON::encode(array(
                'hash1'=>$this->generateValidationHash($code),
                'hash2'=>$this->generateValidationHash(strtolower($code)),
                // we add a random 'v' parameter so that FireFox can refresh the image
                // when src attribute of image tag is changed
                'url'=>$this->getController()->createUrl($this->getId(),array('v' => uniqid(),'isRest'=>1)),
            ));
        }
        else
            $this->renderImage($this->getVerifyCode());
        Yii::app()->end();
    }
	/**
	 * Generates a new verification code.
	 * @return string the generated verification code
	 */
	protected function generateVerifyCode()
	{
		if($this->minLength < 3)
			$this->minLength = 3;
		if($this->maxLength > 20)
			$this->maxLength = 20;
		if($this->minLength > $this->maxLength)
			$this->maxLength = $this->minLength;
		$length = mt_rand($this->minLength,$this->maxLength);
	
		$letters = '0123456789';
		$code = '';
		for($i = 0; $i < $length; ++$i)
		{
			$code.=$letters[mt_rand(0,9)];
		}
	
		return $code;
	}
}

?>