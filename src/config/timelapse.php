<script src="./config/jquery.min.1.11.3.js"></script>
<script type="text/javascript" src="./config/w2ui-1.4.2.min.js"></script>
<link rel="stylesheet" type="text/css" href="./config/w2ui-1.4.2.min.css" />
<link rel="stylesheet" type="text/css" href="./config/timelapse.css" />
<script>

<?php echo 'var images = ', json_encode($timelapseArray), ';'; ?>
var playpause=0; //0 - Play, 1 - Pause
var startID=0; //for pausing / stopping the timelapse
var index=-1; //current image for timelapse, -1 means not started
function sleep(ms) {
  return new Promise(
    resolve => setTimeout(resolve, ms)
  );
}

async function startTimelapse(){
	updatePlayPause();
	startID++;
	var i;
	var startIndex
	var waitTime = $("input[name=tbp]").val();
	var startingPoint = index;
	if(startingPoint == -1){
		if($("input[name=order]:checked").val() == "des"){
			startingPoint = 0;
		}
		if($("input[name=order]:checked").val() == "asc"){
			
			startingPoint = images.length-1;
		}
	}
	if($("input[name=order]:checked").val() == "des"){
		var currentID = startID;
		for(i=startingPoint; i < images.length;i++){
			document.getElementById("timelapseimg").src=images[i];
			await sleep(waitTime);
			index = i;
			if(currentID != startID){break;}
		};
	}else{
		var currentID = startID;
		for(i=startingPoint; i >=0;i--){
			document.getElementById("timelapseimg").src=images[i];
			await sleep(waitTime);
			index = i;
			if(currentID != startID){break;}
		};
	}
}

function updatePlayPause(){
	
	if(playpause){
		playpause=0;
		document.getElementById("timelapseStartPausei").classList.remove('fa-pause');
		document.getElementById("timelapseStartPausei").classList.add('fa-play');
		document.getElementById("timelapseStartPausebtn").setAttribute('onclick','startTimelapse()');
		return;
	}
	playpause=1;
	document.getElementById("timelapseStartPausei").classList.remove('fa-play');
	document.getElementById("timelapseStartPausei").classList.add('fa-pause');
	document.getElementById("timelapseStartPausebtn").setAttribute('onclick','pauseTimelapse()');

	
}

function updateImage(){
	document.getElementById("timelapseimg").src=images[index];
}
function advanceImage(dir){
	index = index + dir;
	if(index > images.length-1){
		index = images.length-1;
	}
	if(index < 0){
		index=0;
	}
	updateImage();
	

}

function pauseTimelapse(){
	updatePlayPause();
	startID++;
}

function stopTimelapse(){
	index = -1;
	startID++;
	updateImage();
}

function openTimelapse(start){
    var height = $(window).height() * 0.8;
	var width = $(window).width() * 0.8;
	var imgheight = Number(height)*0.7;
	var imgwidth = Number(width)*0.7;
	startingPoint = start;
    w2popup.open({
      title: 'Timelapse',
      body: '<div class="w2ui-centered"><img class="timg" id="timelapseimg" src="' + images[0] + '" style="max-width:' + imgwidth + 'px;max-height:' + imgheight + 'px;"></img></div>',
	  buttons: 'Time between Pictures in ms: <input name="tbp" type="text" value="<?php echo $DEFAULTTIMELAPSEVALUE ?>" size="5"> - <button class="mediabtn" title="Go back (when on pause)" onclick="advanceImage(-1);"><i class="fa fa-step-backward" aria-hidden="true"></i></button><button class="mediabtn" title="Stop Timelapse" onclick="stopTimelapse();"><i class="fa fa-stop" aria-hidden="true"></i></button><button class="mediabtn" title="Start Timelapse" id="timelapseStartPausebtn" onclick="startTimelapse(' + index + ' );"><i id="timelapseStartPausei" class="fa fa-play" aria-hidden="true"></i></button><button class="mediabtn" title="Go forward (when on pause)" onclick="advanceImage(1);"><i class="fa fa-step-forward" aria-hidden="true"></i></button> - <input type="radio" id="timelapseorder1" name="order" value="asc" checked> Ascending <input type="radio" id="timelapseorder2" name="order" value="des"> Descending',
	  width: width,
	  height: height
    });

}

</script>