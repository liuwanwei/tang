<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main-tang'); ?>
<?php
		 $menu = array();
		 $menu[] = array('label'=>'餐馆管理','url'=>array('/restaurant/admin'));
		 // $menu[] = array('label'=> '餐馆状态管理','url'=>array('/restaurantStatus/admin'));
		 // $menu[] = array('label'=>'县区管理','url'=>array('/county/admin'));
		 // $menu[] = array('label'=>'区域管理','url'=>array('area/admin'));
		 // $menu[] = array('label'=>'评论管理','url'=>array('comment/admin'));
		 // $menu[] = array('label'=>'用户管理','url'=>array('user/admin'));
		 // $menu[] = array('label'=>'gii',    'url'=>array('/gii/'));
	?>

	<div id="area-menu">
		<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>$menu
		)); 
	
		?>
	</div>
<div class="span-19">
	<div id="content">
		<?php echo $content; ?>
	</div><!-- content -->
</div>
<div class="span-5 last">
	<div id="sidebar">
	<?php
		$this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>'Operations',
		));
		$this->widget('zii.widgets.CMenu', array(
			'items'=>$this->menu,
			'htmlOptions'=>array('class'=>'operations'),
		));
		$this->endWidget();
	?>
	</div><!-- sidebar -->
</div>
<?php $this->endContent(); ?>