<?php
/* @var $this ReportController */
/* @var $data Book-row */
?>

<div class="view">

	<b><?php echo 'ID' ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data['id']), array('/book/view', 'id'=>$data['id'])); ?>
	<br />

	<b><?php echo 'Title' ?>:</b>
	<?php echo CHtml::encode($data['title']); ?>
	<br />

	<b><?php echo 'Creation date' ?>:</b>
	<?php echo date('H:i:s d.m.Y',$data['created']); ?>
	<br />

	<b><?php echo 'Update date' ?>:</b>
	<?php echo date('H:i:s d.m.Y',$data['updated']); ?>
	<br />

</div>