<?php
	// get our parameters for user in the form but also in the curl requests

	$talbots_point_balance = array("client_id" => '01d64c87b96d4c18a1cb22fadcd41335',
		  			"client_secret" => 'bed6c1f720154c8b90fdcec2d2a94537',
		  			"hostname" => 'https://apipprd.biworldwide.com',
		  			"resource" => '/v1/profile/point-balance',
		  			"querystring" => '?username=StoreTest01',
					"grant_type" => "client_credentials");

	$g5alpha_recog_wall = array("client_id" => '000049ee2f5c444097354d0834949970',
		  			"client_secret" => '5261676c150448898439cfdad3d3f3ec',
		  			"hostname" => 'https://apipprd.biworldwide.com',
		  			"resource" => '/v1/recognition/public-wall',
		  			"querystring" => '',
					"grant_type" => "client_credentials");

	$params = $g5alpha_recog_wall;

	// construct this once here so we can use it a couple times later
	$endpoint = $params['hostname'].$params['resource'];
?>

<html>
<head>
	<link rel="stylesheet" href="styles.css?1">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.5/handlebars.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.13.1/lodash.min.js"></script>
</head>
<body>

<section class="most-recent" id="most-recent">
</section>
<section class="border">
</section>
<section class="recognitions" id="recognitions">
</section>
<div class="as-of-date">As Of: <span id="as-of-date"></span></div>
<script id="recognition-template" type="x-handlebars-template">
  <div class="recognition {{receiverCountClass recievers}} zebra-stripe-{{oddEven}}">
    <div class="avatars">
      <div class="receivers">
        {{#each recievers}}
         {{{avatar avatarUrl firstName lastName}}}
        {{/each}}
      </div>
      <div class="giver">
        {{{avatar giver.avatarUrl giver.firstName giver.lastName}}}
      </div>
    </div>
    <div class="details">
      <div class="giver-name">
        {{#each recievers}}
          {{firstName}} {{lastName}}
        {{/each}}
      </div>
      <div class="action-line">
        was recognized by
      </div>
      <div class="receiver-name">
        {{giver.firstName}} {{giver.lastName}}
      </div>
      <div class="behaviors">
        {{#each behaviors}}
          <div class="behavior">
            
          </div>
        {{/each}}
      </div>
      <div class="comment">
        {{{comments}}}
      </div> 
    </div>
  </div>
</script>
<script>

var recogTmplt = Handlebars.compile($('#recognition-template').html());

var config = {
  onScreen: 20,
  viewDelay: 3000
}


var longPoll = function(){
  	var $recognitions = $('#recognitions .recognition');

  	$.ajax({
	    url: 'get-data.php',
	    complete: function( response, textStatus ){
	    	if( response.status === 200 ){
		    	// console.log(JSON.parse(response.responseText));
		    	$('#as-of-date').text(response.responseJSON.asOfDate);
		    	_.each(response.responseJSON.recognitions,function(recog,idx){
					$('#recognitions').append(recogTmplt(Object.assign({},recog,{oddEven:idx % 2})));	    		
					if( idx === response.responseJSON.recognitions.length - 1){
						$('#most-recent').html(recogTmplt(recog));
					}
		    	})
		    	// console.log(response.responseJSON);
	    	}else{
	    		console.error('get-data failed!',response);
	    	}
	    }
  	})
}

var updateView = function(){
  
  var $recognitions = $('#recognitions .recognition');
  
  if( $recognitions.length > config.onScreen ){
    $recognitions.first().animate({
		height: 0,
		opacity: 0
	},300,function(){
		$(this).remove();
	})
  }
}

Handlebars.registerHelper('receiverCountClass', function( receivers ) {
  if( receivers.length > 1 ){
    return 'receivers-many';
  }else{
    return 'receivers-single';
  }
});

Handlebars.registerHelper('avatar', function(path,firstName,lastName) {
	if( firstName && lastName ){
	  return '<span class="avatar">'+firstName.slice()[0]+lastName.slice()[0]+'</span>';
	}
});

longPoll();
setInterval( longPoll, ( config.viewDelay * ( config.onScreen - 5 ) ) );
setInterval( updateView, config.viewDelay);

</script>
</body>	
</html>