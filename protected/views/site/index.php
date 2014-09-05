<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<br>
<p>You may use following controllers:</p>
<ul>
	<li><? echo CHtml::link('Books', array('/book')) ?> for manage and view Book-entities</li>
	<li><? echo CHtml::link('Authors', array('/author')) ?> for manage and view Author-entities</li>
	<li><? echo CHtml::link('Readers', array('/reader')) ?> for manage and view Reader-entities</li>
	<li><? echo CHtml::link('Reports', array('/report')) ?> for view custom reports</li>
</ul>
