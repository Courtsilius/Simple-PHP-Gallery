<?php

$TITLE  = "Simple PHP Gallery"; //title of tab
// Enables/Disables the ability to delete files through a delete button next to the entry. Set to disable by default.
$ALLOWDELETION = false;

// Current directory by default, allows for multiple paths to be iterated on
// Keep in mind that the path needs to be accessible by the webservice and by extension the browser, e.g. sub directories 
$FILEPATH = ["./"]; //Filepath needs to have a slash / at the end
$NUMBEROFFILES = [25,50,100,150,200,250]; //Dropdownmenu for number of files to show
$DEFAULTNUMBEROFFILES = 25;
$DISALLOW = [".","..","index.php",basename(__FILE__)]; //Add items that should be excluded from the gallery in this array3
$PREVIEWEXTENSIONS = array('png', 'jpg', 'jpeg', 'gif'); //allowed filetypes for preview
?>