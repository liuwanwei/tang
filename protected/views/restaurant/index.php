<?php
/* @var $this RestaurantController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle="老汤馆-分享洛阳好滋味";
$this->pageDescription="老汤馆-牛肉汤 丸子汤 豆腐汤 羊肉汤 驴肉汤";
?>

<div class="tang-tooltip">
	<div class="bottomtitle"></div>
	<div class="content">

	</div>
</div>


<div class="restaurant-content">
	<?php if (! empty($areaMenu) &&  count($areaMenu) >= 1) { ?>
	<div class="county-menu-title"><span>区域</span>
		<div id="county-menu">
			<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>$areaMenu,
			'activeCssClass'=>false,
			//'firstItemCssClass'=>'active',
			)); ?>
		</div><!-- area-menu -->
	</div>
	<div class="clear"></div>
	<?php } ?>


	<?php if (! empty($typeMenu)) { ?>
	<div class="county-menu-title"><span>分类</span>
		<div id="area-menu">
			<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>$typeMenu,
			'activeCssClass'=>'active',
			)); ?>
		</div><!-- type-menu -->
	</div><div class="clear"></div>
	<?php } ?>

	<div>
		<div class="restaurant-left">
			<?php

			$this->widget('zii.widgets.CListView', array(
				'dataProvider'=>$dataProvider,
				'itemView'=>'_view',
				'cssFile' => Yii::app()->request->baseUrl. '/css/_restaurant_item.css',
				'template' => "{pager}\n{summary}\n{items}\n{pager}",
				'ajaxUpdate'=> false,
		// 'pagerCssClass'=>'tang-pager',
		// 'pager'=>array('header'=>'',
		// 		'prevPageLabel'=>'«',
		// 		'nextPageLabel'=>'»',
		// 		'firstPageLabel'=>'首页',
		// 		'lastPageLabel'=>'末页',
		// 		'cssFile'=>Yii::app()->request->baseUrl.'/css/pager.css'),

				));
				?>
				<div class="list-footer-load"><span><i class="fa fa-spinner fa-spin fa-2" id="icon-load"></i> 正在载入<span>0</span>个汤馆...</span>
				</div>
			</div>
			<div class="right-content">
				<div class="title top-rank-title b-tottom">人气排行</div>
                <div class="top-list">                                        
                        <ol>
                        	<?php 
                        	foreach ($topVisits as $value) {
                        		echo '<li><span class="top-item-detail">'.$value["visits"].'</span><a href="'.$this->createUrl('comment/index',array('restaurantId'=>$value['id'])).'" target="_blank">'.$value["name"].'</a></li>';
                        	} ?>
                        </ol>                                
                </div>                                

                <div class="title top-rank-title b-tottom"><a href="#">评论排行</a></div>
                <div class="top-list">                                        
                        <ol>
                        	<?php 
                        	foreach ($topComments as $value) {
                        		echo '<li><span class="top-item-detail">'.$value["comments"].'</span><a href="'.$this->createUrl('comment/index',array('restaurantId'=>$value['id'])).'" target="_blank">'.$value["name"].'</a></li>';
                        	} ?>
                        </ol>
                </div>

                <!-- <div class="title top-rank-title b-tottom">星级排行</div>
                <div class="rating-top">                                        
                                <div><a href="#"><span class="ra-title">汤中一绝</span><span class="rating-icon rating-init ra"></span><span class="rating-icon rating-init ra"></span><span class="rating-icon rating-init ra"></span><span class="rating-icon rating-init ra"></span><span class="rating-icon rating-init ra"></span></a><div class="clear"></div></div>
                                <div><a href="#"><span class="ra-title">值得品尝</span><span class="rating-icon rating-init ra"></span><span class="rating-icon rating-init ra"></span><span class="rating-icon rating-init ra"></span><span class="rating-icon rating-init ra"></span></a><div class="clear"></div></div>
                                <div><a href="#"><span class="ra-title">日常饮食</span><span class="rating-icon rating-init ra"></span><span class="rating-icon rating-init ra"></span><span class="rating-icon rating-init ra"></span></a><div class="clear"></div></div>
                                <div><a href="#"><span class="ra-title">聊胜于无</span><span class="rating-icon rating-init ra"></span><span class="rating-icon rating-init ra"></span></a><div class="clear"></div></div>
                                <div><a href="#"><span class="ra-title">不推荐</span><span class="rating-icon rating-init ra"></span></a><div class="clear"></div></div>                                        
                </div> -->
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl.'/js/tang_rating.js' ?>"></script>
	<?php if (Yii::app()->user->isAdmin) { 
		echo '<script type="text/javascript" src="'.Yii::app()->request->baseUrl.'/js/tang_home_edit.js"></script>';
	}?>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl.'/js/tang_home.js' ?>"></script>
<script type="text/javascript">
$(function(){
/*
 *分页
 *@pageCurrent 从1开始是第二页，0是第一页已经在面页加载时加载过
 */
var tangHomeObj=tangHome;
	tangHomeObj.count=<?php echo $count;?>,//总数;
	tangHomeObj.area=<?php echo $area;?>,//县区;
	tangHomeObj.type=<?php echo $type;?>,//类型;
	tangHomeObj.county=<?php echo $county;?>,//区域;
	tangHomeObj.pageCurrent=1;//当前页数
	tangHomeObj.limit=10;//每页显示的条数
	tangHomeObj.itemIndex=10;//数据的排列顺序号
	tangHomeObj.voteCreateUrl="<?php echo $this->createUrl('vote/create')?>";//新增评分;   
	tangHomeObj.voteDeleteUrl="<?php echo $this->createUrl('vote/delete');?>";//删除评分;
	tangHomeObj.restaurantFeatureQueryUrl="<?php echo $this->createUrl('restaurantFeature/query'); ?>";//查询特色数据;
	tangHomeObj.featureAddRestaurantFeatureUrl="<?php echo $this->createUrl('feature/addrestaurantfeature'); ?>";//查询特色数据;
	tangHomeObj.restaurantIndexByPageUrl="<?php echo $this->createUrl('restaurant/indexByPage');?>";//分页的URl;
	tangHomeObj.commentIndexUrl="<?php echo $this->createUrl('comment/index',array('restaurantId'=>'')); ?>";//详情页URl
	tangHomeObj.userId="<?php echo Yii::app()->user->id ?>";//当前登陆的用户ID;
	tangHomeObj.isdataload=true; //数量是否已经加载;
	tangHomeObj.isAdmin="<?php echo Yii::app()->user->isAdmin;?>";//是否是管理员;
tangHomeObj.initRating();
tangHomeObj.initScroll();
});

</script>
