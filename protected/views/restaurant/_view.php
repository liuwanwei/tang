<?php
/* @var $this RestaurantController */
/* @var $data Restaurant */
?>

<div class="view-item">
<!-- 	<div class="restaurant-profile">
		<?php 
		if (! empty($data->image_url)) {
			echo CHtml::image(Yii::app()->baseUrl.$data->image_url, $data->name,array("width"=>75, 'height'=>75));
		}else{
			echo CHtml::image('images/default_profile.jpg'); 
		}
		?>
	</div> -->

	<span class="ranking"><?php echo $widget->dataProvider->getPagination()->getOffset() + $index + 1; ?>.</span>
	<div class="restaurant-detail">
		<!--
		<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
		<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
		<br />
		-->
		
<ul>
	<li><ul>
		<li><strong>
		<?php echo CHtml::link(CHtml::encode($data->name), array('comment/index', 'restaurant_id'=>$data->id)); ?></strong>
		
		</li>
		
		<!--
		<b><?php echo CHtml::encode($data->getAttributeLabel('county_id')); ?>:</b>
		<?php echo CHtml::encode($data->county['name']); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('area_id')); ?>:</b>
		<?php echo CHtml::encode($data->area['name']); ?>
		<br />
		-->
		

		<!--<li>
		<span class="title"><?php echo CHtml::encode($data->getAttributeLabel('business_hour')); ?>:</span>
		<span><?php echo CHtml::encode($data->business_hour); ?></span>
		</li>

		<li>
		<span class="title"><?php echo CHtml::encode($data->getAttributeLabel('phone')); ?>:</span>
		<span><?php echo CHtml::encode($data->phone); ?></span>
		</li>

		<li>
		<span class="title"><?php echo CHtml::encode($data->getAttributeLabel('is_shutdown')); ?>:</span>
		<span><?php echo CHtml::encode($data->status['name']); ?></span>
		</li>
-->
		<li>
		<span  class="title"><?php echo CHtml::encode($data->getAttributeLabel('address')); ?>:</span>
		<span><?php echo CHtml::encode($data->address); ?></span>
		</li>

		<li>
		<!--<span class="title"><?php echo CHtml::encode($data->getAttributeLabel('votes')); ?>:</span>
		<span><?php echo CHtml::encode($data->votes); ?></span>
		-->
		
		<div class="rating-widget">
		<span class="rating-widget-lable">平均分:</span><!--<span class="rating-imdb " style="width: 0px; display:block;"></span>-->
		<div class="rating-list m" isclick="false" data-rating-default="<?php echo sprintf("%.1f",CHtml::encode($data->average_points)); ?>" 
			data-clicknum="0" 
			data-user="<?php echo Yii::app()->user->id ?>"
			data-id="<?php echo CHtml::encode($data->id);?>"
			data-userlogin="<?php echo Yii::app()->user->isGuest ?>">
		<span class="rating-stars">
		<a class="rating-icon star-on" data-title="不推荐"><span>1</span></a>
		<a class="rating-icon star-on" data-title="聊胜于无"><span>2</span></a>
		<a class="rating-icon star-on" data-title="日常饮食"><span>3</span></a>
		<a class="rating-icon star-on" data-title="值得品尝"><span>4</span></a>
		<a class="rating-icon star-on" data-title="汤中一绝"><span>5</span></a>
		<!--<a class="rating-icon star-on"><span>6</span></a>
		<a class="rating-icon star-on"><span>7</span></a>
		<a class="rating-icon star-on"><span>8</span></a>
		<a class="rating-icon star-on"><span>9</span></a>
		<a class="rating-icon star-on"><span>10</span></a>-->
		</span>
		<span class="rating-rating">
		<span class="fonttext-shadow-2-3-5-000 value"><?php echo sprintf("%.1f",CHtml::encode($data->average_points)); ?></span>
		<span class="grey">/</span>
		<span class="grey">5</span>
		</span>
		<span class="rating-cancel ">
			<a title="删除">
				<span>X</span>
			</a>
		</span>
		</div>
		</div>
			<div class="clear"><!--清除浮动--></div>
		</li>
		<?php if (!empty($data->features)) {
			?>
		<li><span class="title">特色:</span>
			<?php 			
			 foreach ($data->features as $value) {			 	
			echo '<span class="feature">'.CHtml::encode($value->details->name).'</span>';
		} ?></li>
		<?php
		} ?>
		</ul>
		<ul>
			<li><span>投票数:</span> <strong><?php echo CHtml::encode($data->votes);?></strong>人</li>
			<li><span>评论数:</span> <strong><?php echo CHtml::encode($data->comment_count);?></strong>人</li>
		</ul>

		<div class="clear"></div>
	</li>

		</ul>

		<?php /*
		<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
		<?php echo CHtml::encode($data->description); ?>
		<br />

		*/ ?>

	</div>

	<?php if (User::model()->isAdmin()) {
	?>	
	<!--编辑功能-->
<div class="view-edit-btn" ><div class="view-edit-header"><a title="编辑 <?php echo CHtml::encode($data->name); ?>">编辑</a>
<ul>
<li class="feature-btn">贴标</li>

</ul>
</div>

<div class="feature-content" data-item-id="<?php echo $data->id; ?>" data-selected-items="<?php
				foreach ($data->features as $value) {
			 	echo $value->feature_id.",";
			 	} ?>">
<div class="feature-content-content"></div>
<div class="feature-content-footer"><button id="feature-edit-submit">提交</button><button id="feature-edit-close">关闭</button></div>
			 </div>
</div>
<?php } ?>
	<div style="clear:both;"></div>

</div>