<?php
/* @var $this RestaurantController */
/* @var $data Restaurant */
?>
<div class="view-item">
	<span class="ranking badge1"><?php echo $widget->dataProvider->getPagination()->getOffset() + $index + 1; ?></span>
	<?php if(!empty($data->image_url)) {?>
	<a href="<?php echo $data->image_url; ?>" class="restaurant_img"  title="<?php echo $data->name;?>"><img src="<?php echo $data->image_url; ?>"></a>
	<?php }else{?>
	<span class="restaurant_defalut_img"><i class="fa fa-smile-o"></i></span>
		<?php } ?>
	<div class="restaurant-detail">
		<ul>
			<li>
				<strong><?php echo CHtml::link(CHtml::encode($data->name), array('comment/index', 'restaurantId'=>$data->id),array('target'=>'_blank')); ?></strong>
			</li>
			<li>
				<span class="title"><?php echo CHtml::encode($data->getAttributeLabel('address')); ?>:</span>				
				<span class="detail-value"><?php echo CHtml::encode($data->address); ?></span>
				<?php if (! empty($data->coordinate)) { 
					echo CHtml::link('<i class="fa fa-map-marker"></i>',  array('comment/index', 'restaurantId'=>$data->id), array('alt'=>"地图",'title'=>"看看汤馆的位置", 'target'=>"_blank"));
				} ?>				
			</li>

			<?php if (!empty($data->features)) {
			?>
			<li><span class="title">特色:</span>
				<?php foreach ($data->features as $value) {			 	
					echo '<span class="feature">'.CHtml::encode($value->details->name).'</span>';
				} ?>
			</li>
			<?php
			} ?>

			<li>
				<div class="rating-widget">
					<span class="rating-widget-lable">平均得分:</span><!--<span class="rating-imdb " style="width: 0px; display:block;"></span>-->
					<div class="rating-list m" isclick="false" data-rating-default="<?php echo sprintf("%.1f",CHtml::encode($data->average_points)); ?>" 
						data-clicknum="0" 
						data-user="<?php echo Yii::app()->user->id ?>"
						data-id="<?php echo CHtml::encode($data->id);?>" 
						data-name="<?php echo CHtml::encode($data->name);?>" 
						data-userlogin="<?php echo Yii::app()->user->isGuest ?>">
						<span class="rating-stars">
							<a class="rating-icon star-on" data-title="不推荐"><span>1</span></a>
							<a class="rating-icon star-on" data-title="聊胜于无"><span>2</span></a>
							<a class="rating-icon star-on" data-title="日常饮食"><span>3</span></a>
							<a class="rating-icon star-on" data-title="值得品尝"><span>4</span></a>
							<a class="rating-icon star-on" data-title="汤中一绝"><span>5</span></a>
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
					<?php if ($data->votes>0) {?>
					<div class="rating-count-p">
						<span><?php echo CHtml::encode($data->votes);?></span>个评分
					</div>
						<?php } ?>
				</div>
				<div class="fenxiang"><a class="sina" href="http://service.weibo.com/share/share.php?url=http://www.laotangguan.com&pic=<?php echo 'http://www.laotangguan.com'.$data->image_url;?>&title=原来汤馆也可以这么玩，快来看看我已经对【<?php echo $data->name; ?>】打过分了&appkey=3495571392&ralateUid=" target="_blank"><i class="fa fa-share"></i> 分享</a></div>
				<div class="clear"><!--清除浮动--></div>
			</li>
			
			<div class="clear"></div>
			</ul>
		</div>

		<?php if (Yii::app()->user->isAdmin) {
			?>	
			<!--编辑功能-->
			<div class="view-edit-btn" >
				<div class="view-edit-header">
					<a title="<?php echo CHtml::encode($data->name); ?>" class="fa fa-pencil"></a>
					<ul>
						<li class="feature-btn">贴标</li>
						<li class="itemEdit-btn" data-item-url="<?php echo $this->createUrl('restaurant/update',array('id'=>$data->id)); ?>"><a href="<?php echo $this->createUrl('restaurant/update',array('id'=>$data->id)); ?>" target="_blank">修改</a></li>
					</ul>
				</div>

				<div class="feature-content" data-item-id="<?php echo $data->id; ?>" data-selected-items="<?php
				foreach ($data->features as $value) {
					echo $value->feature_id.",";
				} ?>">
				<div class="feature-content-title"><?php echo CHtml::encode($data->name); ?></div>
				<div class="feature-content-content"></div>
				<div class="feature-content-footer"><button id="feature-edit-submit">提交</button><button id="feature-edit-close">关闭</button></div>
			</div>
		</div>
		<?php } ?>
		<div style="clear:both;"></div>

	</div>