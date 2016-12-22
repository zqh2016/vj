<?php         
//类ClassOptions
class ClassicOptions {            
	//getOptions函数获取选项组
    function getOptions() {
		//在数据库中获取选项组
        $options = get_option('classic_options'); 
		//如果数据库中不存在该选项组，设定这些选项的默认值，并将它们插入数据库           
        if (!is_array($options)) { 
			//初始默认数组 基本设置部分
            $options['jane_keywords'] = ''; // 网站关键字
            $options['jane_description'] = ''; // 网站描述
            $options['jane_menu'] = ''; // 二级导航
            $options['jane_user'] = ''; // 登录
            $options['jane_banner'] = ''; // banner广告
            $options['jane_banner_link'] = ''; // banner广告链接
            $options['jane_slide1'] = ''; // 幻灯图片1
            $options['jane_slide2'] = ''; // 幻灯图片2
            $options['jane_slide3'] = ''; // 幻灯图片3
            $options['jane_slide1_title'] = ''; // 幻灯图片1标题
            $options['jane_slide2_title'] = ''; // 幻灯图片2标题
            $options['jane_slide3_title'] = ''; // 幻灯图片3标题
            $options['jane_slide1_link'] = ''; // 幻灯图片1链接
            $options['jane_slide2_link'] = ''; // 幻灯图片2链接
            $options['jane_slide3_link'] = ''; // 幻灯图片3链接
            $options['jane_jin'] = ''; // 今日推荐
            $options['jane_jin_title'] = ''; // 今日推荐标题
            $options['jane_jin_link'] = ''; // 今日推荐链接
            $options['jane_links'] = ''; // 友链
            $options['jane_beian'] = ''; // 备案号
            $options['jane_ad1'] = ''; // AD1
            $options['jane_ad2'] = ''; // AD2
            $options['jane_ad3'] = ''; // AD3
            $options['jane_ad4'] = ''; // AD4
            $options['jane_tongji'] = ''; // 统计
			//更新数据
            update_option('classic_options', $options);         
        }   
        return $options;   // 返回选项组     
    }
	/* -- init函数 初始化 -- */   
    function init() {
		// 如果是 POST 提交数据, 对数据进行限制, 并更新到数据库   
        if(isset($_POST['classic_save'])) {
			// 获取选项组, 因为有可能只修改部分选项, 所以先整个拿下来再进行更改
			$options = ClassicOptions::getOptions();
			// 数据处理    
            $options['jane_keywords'] = stripslashes($_POST['jane_keywords']);
            $options['jane_description'] = stripslashes($_POST['jane_description']);
            $options['jane_menu'] = stripslashes($_POST['jane_menu']);
            $options['jane_user'] = stripslashes($_POST['jane_user']);
            $options['jane_banner'] = stripslashes($_POST['jane_banner']);
            $options['jane_banner_link'] = stripslashes($_POST['jane_banner_link']);
            $options['jane_slide1'] = stripslashes($_POST['jane_slide1']);
            $options['jane_slide2'] = stripslashes($_POST['jane_slide2']);
            $options['jane_slide3'] = stripslashes($_POST['jane_slide3']);
            $options['jane_slide1_title'] = stripslashes($_POST['jane_slide1_title']);
            $options['jane_slide2_title'] = stripslashes($_POST['jane_slide2_title']);
            $options['jane_slide3_title'] = stripslashes($_POST['jane_slide3_title']);
            $options['jane_slide1_link'] = stripslashes($_POST['jane_slide1_link']);
            $options['jane_slide2_link'] = stripslashes($_POST['jane_slide2_link']);
            $options['jane_slide3_link'] = stripslashes($_POST['jane_slide3_link']);
            $options['jane_jin_link'] = stripslashes($_POST['jane_jin_link']);
            $options['jane_jin_title'] = stripslashes($_POST['jane_jin_title']);
            $options['jane_jin'] = stripslashes($_POST['jane_jin']);
            $options['jane_links'] = stripslashes($_POST['jane_links']);
            $options['jane_beian'] = stripslashes($_POST['jane_beian']);
            $options['jane_ad1'] = stripslashes($_POST['jane_ad1']);
            $options['jane_ad2'] = stripslashes($_POST['jane_ad2']);
            $options['jane_ad3'] = stripslashes($_POST['jane_ad3']);
            $options['jane_ad4'] = stripslashes($_POST['jane_ad4']);
            $options['jane_tongji'] = stripslashes($_POST['jane_tongji']);
			// 更新数据  
            update_option('classic_options', $options);   
        } else {   
		    // 否则, 重新获取选项组, 也就是对数据进行初始化
            ClassicOptions::getOptions();         
        }   
		//添加设置页面 
        add_theme_page("Theme Options", "Theme Options", 'edit_themes', basename(__FILE__), array('ClassicOptions', 'display'));         
    } 
	/* -- 标签页 -- */   
    function display() {   
        //加载upload.js文件   
        wp_enqueue_script('my-upload', get_bloginfo( 'stylesheet_directory' ) . '/js/options.js'); 
		wp_enqueue_style('my-upload', get_bloginfo('stylesheet_directory') . '/css/options.css');
        //加载上传图片的js(wp自带)   
        wp_enqueue_script('thickbox');   
        //加载css(wp自带)   
        wp_enqueue_style('thickbox');   
        $options = ClassicOptions::getOptions(); 
		?>

<form method="post" enctype="multipart/form-data" name="classic_form" id="classic_form">
    <!-- 设置内容 -->
    <div class="options">
        <div class="tabPanel">
            <ul>
                <li class="hit">主题介绍</li>
                <li>基本设置</li>
                <li>SEO设置</li>
                <li>底部设置</li>
                <li>AD广告</li>
            </ul>
            <div class="panes">
                <div class="pane options-title" style="display:block;"> <a style="border: medium none;" href="http://www.heyiba.com/" target="_blank"><img src="<?php bloginfo('template_url'); ?>/img/logo.png"></a>
                    <h1><span>JaneStyle</span>主题发布来源于: <a target="_blank" href="http://175750.com/">起舞主题网</a></h1>
                    <p>本主题JaneStyle怀着分享精神基于MIT开源协议免费开放使用给大家；请大家到<a target="_blank" href="http://www.175750.com/">起舞主题网</a>下载试用体验，本着作者费时费力请大家保留页脚版权即可,主题交流群：263555652 欢迎大家提意见或建议; 作者QQ：786065406 。</p>
                    <p>演示站：<a target="_blank" href="http://www.heyiba.com/">合意网</a></p>
                </div>
                <div class="pane">
                    <div class="section">
                        <h4 class="heading">登录设置</h4>
                        <div class="option">
                            <div class="controls">
                                <input type="radio" name="jane_user" value="1" <?php if ($options['jane_user'] == '1'){ echo 'checked="checked"';} ?>/>
                                <label>隐藏&nbsp;&nbsp;&nbsp;</label>
                                <input type="radio" name="jane_user" value="0" <?php if ($options['jane_user'] == '0'){ echo 'checked="checked"';} ?>/>
                                <label>显示 </label>
                            </div>
                            <div class="explain">设置登录菜单默认是隐藏。</div>
                        </div>
                    </div>
                    <div class="section">
                        <h4 class="heading">二级导航设置</h4>
                        <div class="option">
                            <div class="controls">
                                <input type="radio" name="jane_menu" value="1" <?php if ($options['jane_menu'] == '1'){ echo 'checked="checked"';} ?>/>
                                <label>隐藏&nbsp;&nbsp;&nbsp;</label>
                                <input type="radio" name="jane_menu" value="0" <?php if ($options['jane_menu'] == '0'){ echo 'checked="checked"';} ?>/>
                                <label>显示 </label>
                            </div>
                            <div class="explain">设置二级导航默认是隐藏。</div>
                        </div>
                    </div>
                    <div class="section">
                        <h4 class="heading">推荐专题</h4>
                        <div class="option">
                            <div class="controls">
                                <?php //添加预览图片代码   
				if($options['jane_jin'] != ''){ echo '<span class="jane_jin"><img src='.$options['jane_jin'].' alt="" /></span>'; };?>
                                <input type="text" class="upload" name="jane_jin" value="<?php echo($options['jane_jin']);?>"/>
                                <input type="button" class="button jane_button" value="upload"/>
                                <input type="text" class="upload" name="jane_jin_title" value="<?php echo($options['jane_jin_title']);?>"/>
                                <input type="text" class="upload" name="jane_jin_link" value="<?php echo($options['jane_jin_link']);?>"/>
                            </div>
                            <div class="explain">图片上传，标题填写，网址。</div>
                        </div>
                    </div>
                    <div class="section">
                        <h4 class="heading">幻灯图片上传（670x320）</h4>
                        <div class="option">
                            <div class="controls">
                                <?php //添加预览图片代码   
				if($options['jane_slide1'] != ''){ echo '<span class="jane_jin"><img src='.$options['jane_slide1'].' alt="" /></span>';  };?>
                                <input type="text" class="upload" name="jane_slide1" value="<?php echo($options['jane_slide1'] ) ?>"/>
                                <input type="button" class="button jane_button" value="upload"/>
                                <input type="text" class="upload" name="jane_slide1_title" value="<?php echo($options['jane_slide1_title']);?>"/>
                                <input type="text" class="upload" name="jane_slide1_link" value="<?php echo($options['jane_slide1_link']);?>"/>
                            </div>
                            <div class="explain">顺序为幻灯片1,2,3限定3篇幅。图片上传，标题填写，网址。</div>
                            <div class="controls">
                                <?php //添加预览图片代码   
				if($options['jane_slide2'] != ''){ echo '<span class="jane_jin"><img src='.$options['jane_slide2'].' alt="" /></span>';  };?>
                                <input type="text" class="upload" name="jane_slide2" value="<?php echo($options['jane_slide2'] ) ?>"/>
                                <input type="button" class="button jane_button" value="upload"/>
                                <input type="text" class="upload" name="jane_slide2_title" value="<?php echo($options['jane_slide2_title']);?>"/>
                                <input type="text" class="upload" name="jane_slide2_link" value="<?php echo($options['jane_slide2_link']);?>"/>
                            </div>
                            <div class="controls">
                                <?php //添加预览图片代码   
				if($options['jane_slide3'] != ''){ echo '<span class="jane_jin"><img src='.$options['jane_slide3'].' alt="" /></span>';  };?>
                                <input type="text" class="upload" name="jane_slide3" value="<?php echo($options['jane_slide3'] ) ?>"/>
                                <input type="button" class="button jane_button" value="upload"/>
                                <input type="text" class="upload" name="jane_slide3_title" value="<?php echo($options['jane_slide3_title']);?>"/>
                                <input type="text" class="upload" name="jane_slide3_link" value="<?php echo($options['jane_slide3_link']);?>"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pane">
                    <div class="section">
                        <h4 class="heading">网站关键字设置</h4>
                        <div class="option">
                            <div class="controls">
                                <textarea rows="3" class="upload" name="jane_keywords"><?php echo($options['jane_keywords']);?></textarea>
                            </div>
                            <div class="explain">这栏填写网站的关键字。</div>
                        </div>
                    </div>
                    <div class="section">
                        <h4 class="heading">网站描述设置</h4>
                        <div class="option">
                            <div class="controls">
                                <textarea rows="8" class="upload" type="text"  name="jane_description"><?php echo($options['jane_description']);?></textarea>
                            </div>
                            <div class="explain">这栏填写网站的站点描述。</div>
                        </div>
                    </div>
                </div>
                <div class="pane">
                    <div class="section">
                        <h4 class="heading">友情链接设置</h4>
                        <div class="option">
                            <div class="controls">
                                <textarea rows="8" class="upload" type="text"  name="jane_links"><?php echo($options['jane_links']);?></textarea>
                            </div>
                            <div class="explain">这栏填写友情链接，例如：〈a target="_blank" href="www.abc.com"〉abc网站〈/a〉可多个。</div>
                        </div>
                    </div>
                    <div class="section">
                        <h4 class="heading">统计代码</h4>
                        <div class="option">
                            <div class="controls">
                                <textarea rows="2" class="upload" type="text"  name="jane_tongji"><?php echo($options['jane_tongji']); ?></textarea>
                            </div>
                            <div class="explain">这栏是填写统计代码</div>
                        </div>
                    </div>
                    <div class="section">
                        <h4 class="heading">备案号代码</h4>
                        <div class="option">
                            <div class="controls">
                                <textarea rows="2" class="upload" type="text"  name="jane_beian"><?php echo($options['jane_beian']);?></textarea>
                            </div>
                            <div class="explain">这栏填写备案号代码。</div>
                        </div>
                    </div>
                </div>
                <div class="pane">
                    <div class="section">
                        <h4 class="heading">头部banner广告设置</h4>
                        <div class="option">
                            <div class="controls">
                                <textarea rows="2" class="upload" type="text"  name="jane_banner"><?php echo($options['jane_banner']);?></textarea>
                            </div>
                            <div class="explain">头部banner广告代码填写。</div>
                        </div>
                    </div>
                    <div class="section">
                        <h4 class="heading">边栏广告广告设置</h4>
                        <div class="option">
                            <div class="controls">
                                <textarea rows="2" class="upload" type="text"  name="jane_ad1"><?php echo($options['jane_ad1']);?></textarea>
                            </div>
                            <div class="explain">边栏广告代码填写,从上到下顺序。</div>
                            <div class="controls">
                                <textarea rows="2" class="upload" type="text"  name="jane_ad2"><?php echo($options['jane_ad2']);?></textarea>
                            </div>
                            <div class="controls">
                                <textarea rows="2" class="upload" type="text"  name="jane_ad3"><?php echo($options['jane_ad3']);?></textarea>
                            </div>
                            <div class="controls">
                                <textarea rows="2" class="upload" type="text"  name="jane_ad4"><?php echo($options['jane_ad4']);?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- TODO: 在这里追加其他选项内容 -->
        <div class="submit">
            <input type="submit"  class="button button-primary button-large" name="classic_save" value="<?php _e('保存设置') ?>" />
        </div>
    </div>
</form>
<?php         
    }         
} 
/*初始化，执行ClassicOptions类的init函数*/        
add_action('admin_menu', array('ClassicOptions', 'init'));  
?>
