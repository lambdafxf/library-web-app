<?php

class ReportController extends Controller
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
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','popular','coauthorship','random'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Author');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
	
	public function actionPopular()
	{
		$title = 'Popular authors';
		$dataProvider = Author::mostPopular();
		$this->render('list',array(
			'title'=>$title,
			'view' =>'a_view',
			'dataProvider'=>$dataProvider,
		));
	}
	
	public function actionCoauthorship()
	{
		$title = 'Co-Authors books';
		$dataProvider = Book::coAuthorship();
		$this->render('list',array(
			'title'=>$title,
			'view' =>'b_view',
			'dataProvider'=>$dataProvider,
		));
	}
	
	public function actionRandom()
	{
		$title = 'Random books';
		$dataProvider = Book::randomBook();
		$this->render('list',array(
			'title'=>$title,
			'view' =>'b_view',
			'dataProvider'=>$dataProvider,
		));
	}
}
