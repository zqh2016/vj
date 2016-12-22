<?php $options = (ClassicOptions::getOptions()); ?>
<div class="sidebar w300 fr">
	<div class="hot month">
		<div class="stit">
			<h3><b>HOT</b>点击热榜</h3>
		</div>
		<ul>
		<?php get_mostviewed($limit = 10,0);?>
		</ul>
	</div>
	<!--广告-->
	<div class="w300"><?php echo($options['jane_ad3']); ?></div>
	<div class="cat mt">
			<div class="stit">
				<strong><i class="fa fa-folder"></i>分类集合</strong>
				<span>Classified collection</span>
			</div>
		<ul>
		<?php show_category(); ?>
		</ul>
	</div>
	<!--广告-->
	<div class="w300 mt fixed">
		<div class="stit"><h3>广而告之</h3></div>
		<?php echo($options['jane_ad4']); ?>
	</div>
	<?php include('link.php'); ?>
</div>