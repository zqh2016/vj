jQuery(document).ready(function() {   
        //查找class为jane_button的对象   
        jQuery('input.jane_button').click(function() {   
            //获取它前面的一个兄弟元素   
             targetfield = jQuery(this).prev('input');   
             tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');   
             return false;   
        });   
        
        window.send_to_editor = function(html) {   
             imgurl = jQuery('img',html).attr('src');   
             jQuery(targetfield).val(imgurl).focus(); //添加获得焦点函数   
             tb_remove();   
        }   
           
        //图片实时预览jane_upload_input为图片url文本框的class属性   
        jQuery('input.jane_upload_input').each(function()   
        {   
            jQuery(this).bind('change focus blur', function()   
            {      
                //获取改文本框的name属性后面   
                $select = '.' + jQuery(this).attr('name') + '_img';   
                $value = jQuery(this).val();   
                $image = '<img src ="'+$value+'" />';   
                               
                var $image = jQuery($select).html('').append($image).find('img');   
                   
                //set timeout because of safari   
                window.setTimeout(function()   
                {   
                    if(parseInt($image.attr('width')) < 20)   
                    {      
                        jQuery($select).html('');   
                    }   
                },500);   
            });   
        });   
// tabs show
jQuery(function(){	
	jQuery('.tabPanel ul li').click(function(){
		jQuery(this).addClass('hit').siblings().removeClass('hit');
		jQuery('.panes>div:eq('+jQuery(this).index()+')').show().siblings().hide();	
	})
})        
});   


