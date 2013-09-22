<?php
/* @var $this CommentController */
/* @var $data Comment */
?>

<div class="feed-item folding feed-item-hook feed-item-a first-combine combine navigable-focusin">	
	<div class="avatar">
		<img src=<?php echo CHtml::encode($data->user->image_url)?>>
	</div>

	<div class="feed-main">
		<div class="source"> 
			<?php echo CHtml::encode($data->user->nick_name); ?>
			<span class="time"><?php echo CHtml::encode($data->create_datetime); ?></span>
		</div>
		
		<div class="content">
			<?php echo CHtml::encode($data->content); ?>
		</div>
	</div>
</div>