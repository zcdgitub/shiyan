<?php
class EAjaxUploadAction extends CAction
{
	public function run()
	{
		Yii::import("ext.EAjaxUpload.qqFileUploader");
        $folder=params('product.image');
		$allowedExtensions = array("jpg","jpeg","png","gif");
		$sizeLimit = 8 * 1024 * 1024;
		
		$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
		$result = $uploader->handleUpload($folder);
		$result['filename']=strtr($result['filename'],DIRECTORY_SEPARATOR,'/');
		// to pass data through iframe you will need to encode all html tags
		$result=json_encode($result);
		echo $result;
	}
	protected function getUniFilename()
	{
		return uniqid ( 'epmms_' );
	}
}
