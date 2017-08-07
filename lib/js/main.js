
$(document).ready(function(){
	$("#suggesstion-box").hide();
	$("#suggesstion-box").html('');
	$.ajax({
		type:'GET',
		url:'Controller.php?user-tweet=true',
		dataType:'json',
		success:function(result){
			var html_data = '';
			$.each(result['data'],function(index,data){
				if(data['images']!=''){
						$('<div class="item text-center slider_item">'+data['text']+'<br><img src='+data['images']+' class=tweet_image height=100px width=100px></div>').appendTo('.carousel-inner');
					}else{
						$('<div class="item text-center slider_item">'+data['text']+'</div>').appendTo('.carousel-inner');
					}
			});
			$('<a class="left carousel-control" href="#carousel-example-generic" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a><a class="right carousel-control" href="#carousel-example-generic" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>').appendTo('.carousel-inner');
			$('.item').first().addClass('active');
			$('#carousel-example-generic').carousel();
		},
		failure: function(){
			alert(Error);
		}
	});
	$.ajax({
		type:'GET',
		url:'Controller.php?user-followers=true',
		dataType:'json',
		success:function(result){
			var html_data = '';
			var i = 1;
			$.each(result['data'],function(index,data){
				if(i%2==0){
					$('<div class="row"><div value='+data['name']+' class="follower col-md-6 col-xs-6 col-sm-6 col-lg-6"><img src='+data['image']+'/><br>'+data['name']+'</div></div>').appendTo('.followers');		
				}else{
					$('<div class="follower col-md-6 col-xs-6 col-sm-6 col-lg-6" value='+data['name']+'><img src='+data['image']+' /><br>'+data['name']+'</div></div>').appendTo('.followers');	
				}	
				i++;
			})
			$("#follower_loading").hide();
		},
		failure:function(){
			alert(Error);
		}
	});
	$('#search_box').keyup(function(){
		var txt = $(this).val();
		$("#suggesstion-box").html('');
		if(txt == ''){
			$("#suggesstion-box").hide();
			$("#suggesstion-box").html('');
		}else{
			$.ajax({
				type:'GET',
				url : 'Controller.php?search-followers=true&follower='+txt+'',
				dataType:'json',
				success:function(result){
					var html_data ='<ul>';
					$.each(result['data'],function(index,data){
						html_data+='<li class=suggestion_follower value='+data['name']+'><img src = '+data['image']+' height = 25px widht=25px; />'+data['name']+'</li>';
					})
					html_data+='</ul>';
					$("#suggesstion-box").show();
					$("#suggesstion-box").html(html_data);
					$("search_box").css("background","#FFF");
					setTimeout(function(){
						$("#suggesstion-box").hide();
						$("#suggesstion-box").html('');
					},10000);
				}
			});
		}
	});
});	
$(document).on('click','.follower, .suggestion_follower',function(){
	var value = $(this).attr('value');
	$.ajax({
			type:'GET',
			url:'Controller.php?user-tweet=true&follower='+value+'',
			dataType:'json',
			success:function(result){
				$('.carousel-inner').html('');
				var caption = value + " 's tweet ";
				$('#title').html(caption);
				$.each(result['data'],function(index,data){
					
					if(data['images']!=''){
						$('<div class="item text-center slider_item">'+data['text']+'<br><img height=100px width=100px class=tweet_image src='+data['images']+'></div>').appendTo('.carousel-inner');
					}else{
						$('<div class="item text-center slider_item">'+data['text']+'</div>').appendTo('.carousel-inner');
					}
				});
				$('<a class="left carousel-control" href="#carousel-example-generic" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a><a class="right carousel-control" href="#carousel-example-generic" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>').appendTo('.carousel-inner');
				$('.item').first().addClass('active');
				$('#carousel-example-generic').carousel();	
			}
		});
})