<?php
/* @var $this ContactsController */
/* @var $data Contacts */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('profileId')); ?>:</b>
	<?php echo CHtml::encode($data->profileId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('contactType')); ?>:</b>
	<?php echo CHtml::encode($data->contactType); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('isPrimary')); ?>:</b>
	<?php echo CHtml::encode($data->isPrimary); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('contactValue')); ?>:</b>
	<?php echo CHtml::encode($data->contactValue); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('isVerified')); ?>:</b>
	<?php echo CHtml::encode($data->isVerified); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('createdAt')); ?>:</b>
	<?php echo CHtml::encode($data->createdAt); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('createdBY')); ?>:</b>
	<?php echo CHtml::encode($data->createdBY); ?>
	<br />

	*/ ?>

</div>