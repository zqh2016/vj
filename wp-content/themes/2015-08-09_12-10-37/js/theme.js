/* javascript text */

//���ض���
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

// �õ�Ƭ
$(".slide").slide({ titCell:".num li", mainCell:".pic",effect:"fold", autoPlay:true,trigger:"click",delayTime:700,	
//����startFun�������ڿ������������л�
startFun:function(i){		 
$(".slide .txt li").eq(i).animate({"bottom":0}).siblings().animate({"bottom":-50});	
}});		
	
// �Ȱ�����
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

// ����
$(document).ready(function(e) {			
	t = $('.fixed').offset().top;
	//mh = $('.main').height(); // ��box�߶�
	fh = $('.fixed').height(); // ����box�߶�
	$(window).scroll(function(e){ // ����ʱ����scroll()����
		s = $(document).scrollTop(); // ��ҳ�������,Ȼ�������������������	
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

// ���۷�ҳajax
$(document).ready( function(){
$('#comments').on("click",' .commentnav a', // on�����󶨷�ҳa��ǩ
function() {
    $.ajax({ // ajax����
        type: "GET",
        dataType: "html",
        url: $(this).attr('href'),
        beforeSend: function(){
            $('.comment-list').remove(); // �Ƴ�ԭ���������б���
            $('#css-loading').show(); // ���ض�����ʾ
        },      
        success: function(data){
            result = $(data).find('.comment-list'); // �������ñ���find�����ҵ������б���
            $('#css-loading').fadeOut(550,function(){ // ���ض������� ���ص�������
            $('#css-loading').after(result.fadeIn());}); // �����б�������
        }
    });
})
});

