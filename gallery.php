<html>
<head>
<title>Simple PHP Gallery</title>
<style>
td, th {
    border: 1px solid #ddd;
    padding: 8px;
}

tr:nth-child(even){background-color: #f2f2f2;}

tr:hover {background-color: #ddd;}

th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #797a79;
    color: white;
}
img{
    max-width:200;
    max-height:200px;
}
</style>
</head>
<body>
<?php
if($_GET){
    if(isset($_GET['delete'])){
		del_file($_GET['delete']);
	}
}
	echo "<table><tr>
	<th>Preview</th>
    <th>Name</th>
    <th>Date</th>
	<th>Size</th>
    <th>Delete</th>
  </tr>";
if ($handle = opendir('.')) {
	$arr_img = array();
    while (false !== ($entry = readdir($handle))) {
		if ($entry != "." && $entry != ".." && $entry != basename(__FILE__) && !is_dir($entry)) {
			$arrayname = array($entry => filemtime($entry));
			$arr_img += $arrayname;
			}
		}
		closedir($handle);
}
uasort($arr_img, 'cmp'); //Sorting the Array by Date
	
foreach ($arr_img as $key => $value) {
	$filetype = explode(".", $key);
	echo "<tr><td><a target='_blank' href='./".$key."'>";
	$preview_extensions = array('png', 'jpg', 'jpeg', 'gif');
	$arr_size = sizeof($filetype) -1;
	$file_ext =strtolower($filetype[$arr_size]);
	if(in_array($file_ext, $preview_extensions)) {
		echo "<img src=".$key."></img>";
	}else{
		echo "<p>No preview available.</p>";
	}
	echo "</a></td><td><a target='_blank' href='./".$key."'>".$key."</a></td>
		<td>".date("F d Y H:i:s",$value)."</td><td>".human_filesize(filesize($key))."</td><td><form><button type='submit' name='delete' value='".$key."'/>Delete</button>
		</form></td></tr>";
}
echo "</table>";
function del_file($file){
	unlink($file);
	$self = $_SERVER['PHP_SELF'];
	header("Refresh:0; url=$self");
}
function cmp($a, $b) {
    if ($a == $b) {
        return 0;
    }
    return ($a > $b) ? -1 : 1;
}
function makeprivate($file){
	//TODO
}
function human_filesize($bytes, $decimals = 2) {
  $sz = 'BKMGTP';
  $factor = floor((strlen($bytes) - 1) / 3);
  return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}
?>
</body>
</html>
