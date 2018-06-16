	   

    // Can also be used with $(document).ready()
$(window).load(function() {
  $('.flexslider1').flexslider({
    animation: "slide"
  });
});

//////////СЛАЙДЕР БОЛЬШИХ БОТИНОК///////////////////////////////////////////////////

//////////ОТКРЫТИе ФИЛЬТРОВ///////////////////////////////////////////////////
			$(document).ready(function(){
			  $('.filter_unit .title').click(function() {
				   
				  if($(this).find('.sign').html() == '+'){
                    $(this).closest('.filter_unit').find('.sub_brend').slideDown(200);
					$(this).find('.sign').html('-')
                  }else{
                    $(this).closest('.filter_unit').find('.sub_brend').slideUp(200);
					$(this).find('.sign').html('+')
                  }				  
			    });
			});


    $(document).ready(function(){
        $('.action-comment').click(function() {
            $(this).closest('.record-unit').find('.comment-form').show();

        });

        $('.comment-box').keyup(function() {
            $(this).closest('form').find('.comment-panel').show();
        });
    });
	
	
