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
		<div class="info">
			<span class="source"><?php echo CHtml::encode($data->user->nick_name);?></span>
			<div class="comment-item-info-edit">
				<span class="comment-item-index"><?php echo $widget->dataProvider->getPagination()->getOffset() + $index + 1; ?>楼</span>
				<?php if (Yii::app()->user->isAdmin) {
					$actionUrl = $this->createUrl('comment/delete', array('id'=>$data->id));
					echo CHtml::link('<i class="fa fa-times comment-del" title="删除评论"></i>', $actionUrl);
				}?>
			</div>
		</div>
		<div class="content">			
			<pre><?php echo $data->content ?></pre>			
		</div>
		<div class="feed-main-footer">
			<span class="time"><?php echo CHtml::encode($data->create_datetime);?></span><a class="fa fa-thumbs-o-up zan"></a><span>7</span>
		</div>
	</div>
</div>
