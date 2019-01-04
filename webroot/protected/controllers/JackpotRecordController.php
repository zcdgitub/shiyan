<?php

class JackpotRecordController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout='//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'rights', // rights rbac filter
            'postOnly + delete', // 只能通过POST请求删除
            //'authentic + update,create,delete',//需要二级密码
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view',array(
            'model'=>$this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model=new JackpotRecord('create');

        // 如果需要AJAX验证反注释下面一行
        // $this->performAjaxValidation($model);


        if(isset($_POST['JackpotRecord']))
        {
            $model->attributes=$_POST['JackpotRecord'];
            $this->log['target']=$model->jackpot_id;
            if($model->save(true,array('jackpot_member_id','jackpot_money','jackpot_type','jackpot_start_time','jackpot_end_time')))
            {
                $this->log['status']=LogFilter::SUCCESS;
                $this->log();
                user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
                $this->redirect(array('view','id'=>$model->jackpot_id));
            }
            else
            {
                $this->log['status']=LogFilter::FAILED;
                $this->log();
                user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败"));
            }
        }

        $this->render('create',array(
            'model'=>$model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model=$this->loadModel($id);
        $model->scenario='update';
        // 如果需要AJAX验证反注释下面一行
        // $this->performAjaxValidation($model);

        if(isset($_POST['JackpotRecord']))
        {
            $model->attributes=$_POST['JackpotRecord'];
            $this->log['target']=$model->jackpot_id;
            if($model->save(true,array('jackpot_member_id','jackpot_money','jackpot_type','jackpot_start_time','jackpot_end_time')))
            {
                $this->log['status']=LogFilter::SUCCESS;
                $this->log();
                user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
                $this->redirect(array('view','id'=>$model->jackpot_id));
            }
            else
            {
                $this->log['status']=LogFilter::SUCCESS;
                $this->log();
                user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败"));
            }
        }

        $this->render('update',array(
            'model'=>$model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $model=$this->loadModel($id);
        $this->log['target']=$model->showName;
        if($model->delete())
        {
            $this->log['status']=LogFilter::SUCCESS;
            $this->log();
            user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
        }
        else
        {
            $this->log['status']=LogFilter::FAILED;
            $this->log();
            user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败"));
        }

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

    /**
     * Lists all models.
     */
    public function actionList()
    {
        $dataProvider=new CActiveDataProvider('JackpotRecord');
        $this->render('list',array(
            'dataProvider'=>$dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionIndex($selTab=0)
    {
        $model=new JackpotRecord('search');
        $model->unsetAttributes();  // clear any default values
        $model->jackpot=new Memberinfo('search');

        if(isset($_GET['JackpotRecord']))
        {
            $model->attributes=$_GET['JackpotRecord'];
            $model->startTime = ltrim($_GET['JackpotRecord']['jackpot_start_time'],'>');
            $model->endTime = ltrim($_GET['JackpotRecord']['jackpot_end_time'],'>');
            $model->jackpot_start_time = ">=".strtotime($model->startTime);
            $model->jackpot_end_time   = ">=".strtotime($model->endTime);

            if(isset($_GET['JackpotRecord']['jackpot']))
                $model->jackpot->attributes=$_GET['JackpotRecord']['jackpot'];

        }

        $this->render('index',array(
            'model'=>$model,
            'selTab'=>(int)$selTab
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model=JackpotRecord::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,t('epmms','请求的页面不存在。'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='jackpot-record-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
