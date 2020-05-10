<?php
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
?>
