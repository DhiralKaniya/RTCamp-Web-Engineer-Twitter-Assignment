
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
						$('<div class="item text-center slider_item"><p>'+data['text']+'<br><img src='+data['images']+' class=tweet_image height=100px width=100px></p></div>').appendTo('.carousel-inner');
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
			console.log(result);
			console.log(result["data"]);
			var count = 0;
            $.each(result['data'],function(index,data){
            	count++;
                $('<div class="row"><div value='+data['name']+' class="follower col-md-12 col-xs-12 col-sm-12 col-lg-12"><br>'+data['name']+'</div></div></div>').appendTo('.followers');
            })
			if(count == 0)
                $("#follower_loading").text('No Record Founded');
			else
				$("#follower_loading").hide();
		},
		failure:function(){
            $("#follower_loading").text('Error in Connection..!! Trye if latter');
			alert(Error);
		}
	});
	$('#search_box').keyup(function(){
		var txt = $(this).val();
		$.ajax({
			type:'GET',
			url : 'Controller.php?search-followers=true&follower='+txt+'',
			dataType:'json',
			success:function(result){
			    $('#followers').html('');
			    var count = 0;
				$.each(result['data'],function(index,data){
					count++;
                    $('<div class="row"><div value='+data['name']+' class="follower col-md-12 col-xs-12 col-sm-12 col-lg-12"><br>'+data['name']+'</div></div></div>').appendTo('.followers');
				})
				if(count == 0){
                    $("#follower_loading").text('No Record Founded').show();
				}else{
                    $("#follower_loading").text('').hide();
				}
				$("search_box").css("background","#FFF");
			}
		});
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
				var count = 0;
				$.each(result['data'],function(index,data){
					if(data['images']!=''){
						$('<div class="item text-center slider_item">'+data['text']+'<br><img height=100px width=100px class=tweet_image src='+data['images']+'></div>').appendTo('.carousel-inner');
					}else{
						$('<div class="item text-center slider_item">'+data['text']+'</div>').appendTo('.carousel-inner');
					}
					count++;
				});
				if(count > 0){
					$('<a class="left carousel-control" href="#carousel-example-generic" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a><a class="right carousel-control" href="#carousel-example-generic" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>').appendTo('.carousel-inner');
					$('.item').first().addClass('active');
					$('#carousel-example-generic').carousel();
				}else{
					$('<div class="alert alert-info"><strong>No Tweet Found..!!</strong></div>').appendTo('.carousel-inner');
				}
				window.location.href = 'home.php#title';
			}
		});
})