<?php
/* @var $this AuthorController */
/* @var $model Author */

$this->breadcrumbs=array(
	'Authors'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Author', 'url'=>array('index')),
	array('label'=>'Create Author', 'url'=>array('create')),
	array('label'=>'View Author', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Author', 'url'=>array('admin')),
);
?>

<h1>Update Author <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form_ext', array('model'=>$model)); ?>