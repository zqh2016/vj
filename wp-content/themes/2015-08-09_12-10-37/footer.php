<?php $options = (ClassicOptions::getOptions()); ?>
<div id="footer">
	<div class="wp">
		<span><a title="<?php bloginfo( 'mane' ); ?>" href="<?php bloginfo('url');?>"></a></span>
		<p>Copyright <i class="fa fa-copyright"></i> 2015. All Rights Reserved. Designed by <a href="www.175750.com">起舞主题网</a> (www.175750.com)</p>  
        <p>| 基于 WordPress,Theme by wordpress主题 | <?php echo($options['jane_beian']); ?>  | <?php echo($options['jane_tongji']); ?></p>
        <p>声明：本站所有主题/文章除标明原创外，均来自网络转载，版权归原作者所有，如果有侵犯到您的权益，请联系本站删除，谢谢合作！</p>
	</div>
</div>
<!--浮动盒子-->
<div class="other">
	<a class="backtop" href="javascript:;" title="返回顶部"><i class="fa fa-angle-double-up"></i></a>
</div>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/theme.js"></script>

<body>
	
</body>
</html>
<?php wp_footer();?>