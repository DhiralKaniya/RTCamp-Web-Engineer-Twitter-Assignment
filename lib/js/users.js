$(document).ready(function(){
    var value = $('#users').attr('value');
    $.ajax({
        type:'GET',
        dataType:'json',
        url:'Controller.php?search-username=true&username='+value+'',
        success:function (result) {
            if(result['users'].length == 0){

            }else{
                var user = result['users'][0];
                $('<img src = '+ user['image'] + '>').appendTo('.userProfilePic');
                $('.userScreenName').text(user['screen_name']);
                $('.userName').text(user['name']);
                $('.userLocation').text('Location :- '+user['location']+'');
                $('.uFollowers').text('Followers :- '+user['followers']+'');
                $('.userTweetes').text('Tweets :- '+user['tweets']+'');
            }
        }
    });
});