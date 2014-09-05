<?php
/* @var $this ReportController */

$this->breadcrumbs=array(
	'Report',
);

$this->menu=array(
	array('label'=>'Popular authors', 'url'=>array('popular')),
	array('label'=>'Random books', 'url'=>array('random')),
	array('label'=>'Co-authorship books', 'url'=>array('coauthorship')),
);
?>

<h1><?echo 'Library reports'?></h1>

<br>
<ul>
	<li><? echo CHtml::link('Popular authors', array('popular')) ?> realizes report by authors which are read more than two readers</li>
	<li><? echo CHtml::link('Co-authorship books', array('coauthorship')) ?> realizes report by books which are read and have more than three co-authors</li>
	<li><? echo CHtml::link('Random books', array('random')) ?> gives five random books</li>
</ul>