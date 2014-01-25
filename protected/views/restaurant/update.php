<?php
/* @var $this RestaurantController */
/* @var $model Restaurant */
$this->pageTitle="老汤馆-汤馆修改";
?>
<div class="clear"></div>
<h4 style="text-align:center;"><?php echo $model->name; ?></h4>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'selectors'=>$selectors,'returnUrl'=>$returnUrl)); ?>
