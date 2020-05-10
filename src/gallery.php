<!--
##################################################
# Simple PHP Gallery
# Made by Callonz
# Version 1.3
# https://github.com/Courtsilius/Simple-PHP-Gallery/
##################################################
-->
<?php
include 'config/config.php';

function is_audio($filepath) {
	$allowed = array('audio/mpeg', 'audio/x-mpeg', 'audio/mpeg3', 'audio/x-mpeg-3', 'audio/aiff','audio/mid', 
		'audio/x-aiff', 'audio/x-mpequrl','audio/midi', 'audio/x-mid','audio/x-midi','audio/wav',
		'audio/x-wav','audio/xm','audio/x-aac','audio/basic','audio/flac','audio/mp4','audio/x-matroska',
		'audio/ogg','audio/s3m','audio/x-ms-wax','audio/xm' );
    // check REAL MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $type = finfo_file($finfo, $filepath );
    finfo_close($finfo);
    // check to see if REAL MIME type is inside $allowed array
    if( in_array($type, $allowed) ) {
        return true;
    } else {
        return false;
    }
}

function is_video($filepath) {
	$allowed = array('video/3gpp', 'video/3gpp2', 'video/h261', 'video/h263', 'video/h264', 'video/jpeg', 'video/jpm',
	    'video/mj2', 'video/mp4', 'video/mpeg', 'video/ogg', 'video/quicktime', 'video/vnd.dece.hd', 'video/vnd.dece.mobile', 
 	    'video/vnd.dece.pd', 'video/vnd.dece.sd', 'video/vnd.dece.video', 'video/vnd.dvb.file', 'video/vnd.fvt', 
	    'video/vnd.mpegurl', 'video/vnd.ms-playready.media.pyv', 'video/vnd.uvvu.mp4', 'video/vnd.vivo', 'video/webm', 
	    'video/x-f4v', 'video/x-fli', 'video/x-flv', 'video/x-m4v', 'video/x-matroska', 'video/x-mng', 'video/x-ms-asf', 
	    'video/x-ms-vob', 'video/x-ms-wm', 'video/x-ms-wmv', 'video/x-ms-wmx', 'video/x-ms-wvx', 'video/x-msvideo', 
	    'video/x-sgi-movie', 'video/x-smv');
    // check REAL MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $type = finfo_file($finfo, $filepath );
    finfo_close($finfo);
    // check to see if REAL MIME type is inside $allowed array
    if( in_array($type, $allowed) ) {
        return true;
    } else {
        return false;
    }
}

function is_image($filepath) {
	if (@is_array(getimagesize($filepath))) {
			return true;
	} else {
			return false;
	}
}

if(isset($_POST['delete']) && $ALLOWDELETION){
	del_file($_POST['delete']);
}
if(isset($_POST['amount'])){ //Checking if user has sorted, if not, uses default value from config
  $sort = $_POST['sortno'];
}else{
  $sort=$DEFAULTNUMBEROFFILES;
}
$arr_img = array();
$imagetest = array();
$totalsize = 0;
$folders = $FILEPATH;
if(isset($_POST["checkedfilePaths"])){
	$newFilepath = [];
	foreach($folders as $path){
		unset($folders);
		if (in_array($path,$_POST["checkedfilePaths"])){
			array_push($newFilepath,$path);
		}
	}
	$folders = $newFilepath;
}
foreach($folders as $dir){
	if ($handle = opendir($dir)) {
		array_push($DISALLOW,basename(__FILE__)); //adding self to list of disallowed items
		while (false !== ($entry = readdir($handle))) {
			if (!in_array($entry, $DISALLOW) && !is_dir($entry)) {			
				$arrayname = array($dir.$entry => filemtime($dir.$entry));
				array_push($imagetest,$dir.$entry);
				$arr_img += $arrayname;
				$totalsize += filesize($dir.$entry);
			}
		}
		closedir($handle);
	}
}
uasort($arr_img, 'cmp'); //Sorting the Array by Date


 ?>
<html>
<head>
<title><?php echo $TITLE ?></title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="config/gallery.css">
<?php
if($ENABLETIMELAPSE){
	$timelapseArray= array();
	foreach ($arr_img as $key => $value) {
		
		array_push($timelapseArray,$key);
	}
	include 'config/timelapse.php';
}
?>
</head>
<body>
<center>
<p>There are <?php echo sizeof($arr_img);?> items in this gallery, taking up <?php echo human_filesize($totalsize);?>.</p>
<form method="post">
<?php
if (sizeof($FILEPATH)>1){
	echo '<p>Only show files from these directories: ';
	if(isset($_POST["checkedfilePaths"])){
		foreach($FILEPATH as $path){
			if(in_array($path, $_POST["checkedfilePaths"])){
				echo '<input type="checkbox" name="checkedfilePaths[]" value="'.$path.'" checked>'.$path;
			}else{
				echo '<input type="checkbox" name="checkedfilePaths[]" value="'.$path.'">'.$path;
			}
		}
	}else{
		foreach($FILEPATH as $path){
			echo '<input type="checkbox" name="checkedfilePaths[]" value="'.$path.'" checked>'.$path;
		}
	}
	echo '<input type="checkbox" name="checkedfilePaths[]" value="" hidden checked></p>';
}
?>
<p>Number of items to display:


<select name = "sortno">
<?php 

foreach($NUMBEROFFILES as $limit){
	echo '<option ';
	if ($sort == $limit){echo "selected='selected'";} 
	echo ' value="'.$limit.'">'.$limit.'</option>';
} ?>
<option <?php if ($sort == "All" || $sort==0){echo "selected='selected'";}?> value="All">All</option>
</select>
<input type='submit' name='amount' value='Change'/>
<?php if($ENABLETIMELAPSE){ echo '<br><p><input class="btn popup_image"type="button" onClick="openTimelapse(0)" value="Timelapse"/></p>';}?>
</p>
</form>

<table>
  <tr><th>No.</th><th>Preview</th><th>Name</th><th>Date</th><th>Size</th><?php if($ALLOWDELETION){echo'<th>Actions</th>';}?></tr>
<?php

$sort_no=0;
foreach ($arr_img as $key => $value) {
	$filetype = explode(".", $key);
	echo "<tr><td>".($sort_no+1)."<td><a target='_blank' href='./".$key."'>";
	
	$arr_size = sizeof($filetype) -1;
	$file_ext =strtolower($filetype[$arr_size]);
	if(is_image($key)) {
		echo "<img class='gimg' src='".$key."'></img>";
	}else if (is_audio($key)) {
		echo "<audio controls><source src='".$key."'></source></audio>";
	}else if (is_video($key)) {
		echo "<video style='width:90%;' controls><source src='".$key."'></source></video>";
	}else{
		echo "<p>No preview available.</p>";
	}
	echo "</a></td><td><a target='_blank' href='./".$key."'>".$key."</a></td>
		<td>".date("F d Y H:i:s",$value)."</td><td>".human_filesize(filesize($key))."</td>";
	if($ALLOWDELETION ||$ALLOWIGNORE || $ENABLETIMELAPSE){
		echo "<td>";
		if($ALLOWDELETION){echo "<form class='delbtn' method='POST'><button class='gbtn' type='submit' title='Delete this file' name='delete' value='".$key."'/><i class='fa fa-trash-o'></i></button></form>";}
		if($ALLOWIGNORE){echo "<form class='delbtn' method='POST'><button class='gbtn' type='submit' title='Ignore this file' name='ignore' ><i class='fa fa-eye-slash' aria-hidden='true'></i></button></form>";}
		if($ENABLETIMELAPSE){ echo "<button title='Start timelapse from here' class='gbtn'><i class='fa fa-clock-o' aria-hidden='true'></i></button></td>";}
	}
	echo "</tr>";
    if($sort<>0 && $sort<>"All" && $sort_no>=($sort-1)){break;} //dirty method of stopping the loop at the wanted maximum 
    $sort_no++;
}
echo "</table>";
function del_file($file){
  if(file_exists($file)){
	   unlink($file);
  }
}
function cmp($a, $b) {
    if ($a == $b) {
        return 0;
    }
    return ($a > $b) ? -1 : 1;
}

function human_filesize($bytes, $decimals = 2) {
  $sz = 'BKMGTP';
  $factor = floor((strlen($bytes) - 1) / 3);
  return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}


?>
<p><a href="https://github.com/Courtsilius/Simple-PHP-Gallery" target="_blank">Simple PHP Gallery on GitHub</a></p>

</center>
</body>
</html>
