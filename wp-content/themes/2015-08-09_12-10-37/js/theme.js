/* javascript text */

//返回顶部
$(document).ready(function() {
	var h = $(window).height();
    $(window).scroll(function () {
        if($(window).scrollTop()>=h*1) {
            $(".backtop").fadeIn(300);
        }else {
            $(".backtop").fadeOut(300);
        };
    });
    $(".backtop").click(function(event){   
        $('html,body').animate({scrollTop:0}, 500);
        return false;
    });
});

// 幻灯片
$(".slide").slide({ titCell:".num li", mainCell:".pic",effect:"fold", autoPlay:true,trigger:"click",delayTime:700,	
//下面startFun代码用于控制文字上下切换
startFun:function(i){		 
$(".slide .txt li").eq(i).animate({"bottom":0}).siblings().animate({"bottom":-50});	
}});		
	
// 热榜序列
$(function(){
	$(".month ul li").each(function (i) {
		i = i+1;
		$(this).prepend('<em>'+i+'</em>');
   });
   	$(".week ul li").each(function (i) {
		i = i+1;
		$(this).prepend('<em>'+i+'</em>');
   });     
});	

// 浮动
$(document).ready(function(e) {			
	t = $('.fixed').offset().top;
	//mh = $('.main').height(); // 主box高度
	fh = $('.fixed').height(); // 浮动box高度
	$(window).scroll(function(e){ // 滚动时触动scroll()函数
		s = $(document).scrollTop(); // 等页面加载完,然后滚动条滑动到最上面	
		if(s > t - 10){
			$('.fixed').css('position','fixed');
			//if(s + fh > mh){ 
			//	$('.fixed').css('top',mh-s-fh+'px');	
			//}				
		}else{
			$('.fixed').css('position','');
		}
	})
});

// 评论分页ajax
$(document).ready( function(){
$('#comments').on("click",' .commentnav a', // on方法绑定分页a标签
function() {
    $.ajax({ // ajax方法
        type: "GET",
        dataType: "html",
        url: $(this).attr('href'),
        beforeSend: function(){
            $('.comment-list').remove(); // 移除原来的评论列表区
            $('#css-loading').show(); // 加载动画显示
        },      
        success: function(data){
            result = $(data).find('.comment-list'); // 变量：用遍历find方法找到评论列表区
            $('#css-loading').fadeOut(550,function(){ // 加载动画淡出 ，回调函数：
            $('#css-loading').after(result.fadeIn());}); // 评论列表区淡入
        }
    });
})
});

