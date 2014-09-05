<?php
/* @var $this ReportController */
/* @var $dataProvider CSqlDataProvider */

$this->breadcrumbs=array(
	'Report',
	$title,
);

$this->menu=array(
	array('label'=>'Popular authors', 'url'=>array('popular')),
	array('label'=>'Random Books', 'url'=>array('random')),
	array('label'=>'Co-authorship Books', 'url'=>array('coauthorship')),
);
?>

<h1><?echo $title?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>$view,
)); ?>
