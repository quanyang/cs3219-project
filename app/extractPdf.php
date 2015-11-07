<?php
function extractPDF($file_path) {
	$file_path = convertPath($file_path);
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
		return shell_exec("java 'parser $file_path'");
	} else {
    	return shell_exec("java parser $file_path");
	}
}

function convertPath($path) {
  $path = str_replace('\\','/', $path);
  $path = str_replace(' ','\ ', $path);
  if(strrpos($path, "'")) {
  	$path = str_replace("'","\'", $path);
  }
  if(strrpos($path, ":")) {
  	$pieces = explode(":", $path, 2);
  	$path = "/".strtolower($pieces[0]).$pieces[1];
  }

  return $path;
}
?>
