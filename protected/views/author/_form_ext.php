<?php
/* @var $this AuthorController */
/* @var $model Author */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'author-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
	
	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'minlength'=>5,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'books'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
			'id'  => 'books-ac',
			'name'=>'term',
			'sourceUrl'=>Yii::app()->createUrl('book/search',array('id'=>$model->id,'model'=>'author')),
			'options'=>array(
			 'showAnim'=>'fold',
			 'minLength'=>'3',
			 'type'=>'get',
			 'select'=>'js:function(event, ui) {
			  
				$("#author-form").append("<input type=\"hidden\" name=\"Author[books][]\" value=\""+ui.item.id+"\" />");
				$("#books-container").append("<div style=\"text-decoration:underline;\">"+ui.item.value+"</div>");
				$("#books-ac").val("");
				event.preventDefault();
			 }'


			),
			'htmlOptions'=>array(
				'style'=>'width: 500px;',
				'placeholder' => 'Enter book...'
			   ),

			 ));
		?>
	</div>
	<div class="row" id="books-container">
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->