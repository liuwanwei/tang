<?php
/* @var $this CommentController */
/* @var $data Comment */
?>

<div class="feed-item folding feed-item-hook feed-item-a first-combine combine navigable-focusin">	
	<div class="avatar">
		<?php 
			if (! empty($data->user->image_url)) {
				echo CHtml::image($data->user->image_url);
			}else{
				echo CHtml::image('images/male.png'); 
			}	
		?>
	</div>

	<div class="feed-main">
		<div class="source"> 
			<?php echo CHtml::encode($data->user->nick_name); ?>
			<span class="time"><?php echo CHtml::encode($data->create_datetime); ?></span>
		</div>
		
		<div class="content">
			<?php echo CHtml::encode($data->content); ?>
		</div>	
		<?php if (User::model()->isAdmin()) {
			$actionUrl = $this->createUrl('comment/delete', array('id'=>$data->id));
			echo CHtml::link('<i class="fa fa-times" style="color:#999;"></i>', $actionUrl);
		}?>
	</div>
</div>