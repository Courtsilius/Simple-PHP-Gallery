<script src="./js/jquery.min.1.11.3.js"></script>
<script type="text/javascript" src="./js/w2ui-1.4.2.min.js"></script>
<link rel="stylesheet" type="text/css" href="./js/w2ui-1.4.2.min.css" />
<script>

<?php echo 'var images = ', json_encode($timelapseArray), ';'; ?>
var startID=0;
function sleep(ms) {
  return new Promise(
    resolve => setTimeout(resolve, ms)
  );
}

async function startTimelapse(){
	startID++;
	var i;
	var waitTime = $("input[name=tbp]").val();
	if($("input[name=order]:checked").val() == "des"){
		var currentID = startID;
		for(i=0; i < images.length;i++){
			document.getElementById("timelapseimg").src=images[i];
			await sleep(waitTime);
			if(currentID != startID){break;}
		};
	}else{
		var currentID = startID;
		for(i=images.length-1; i >=0;i--){
			document.getElementById("timelapseimg").src=images[i];
			await sleep(waitTime);
			if(currentID != startID){break;}
		};
	}
}
$(document).ready(function() {

  $(".popup_image").on('click', function() {
    var height = $(window).height() * 0.8;
	var width = $(window).width() * 0.8;
	var imgheight = Number(height)*0.7;
	var imgwidth = Number(width)*0.7;
    w2popup.open({
      title: 'Timelapse',
      body: '<div class="w2ui-centered"><img class="timg" id="timelapseimg" src="' + images[0] + '" style="max-width:' + imgwidth + 'px;max-height:' + imgheight + 'px;"></img></div>',
	  buttons: 'Time between Pictures in ms: <input name="tbp" type="text" value="<?php echo $DEFAULTTIMELAPSEVALUE ?>" size="5"> - <input type="button" value="Start Timelapse" id="timelapseStart" onclick="startTimelapse();" /> - <input type="radio" id="timelapseorder1" name="order" value="asc" checked> Ascending <input type="radio" id="timelapseorder2" name="order" value="des"> Descending',
	  width: width,
	  height: height
    });

  });

});
</script>