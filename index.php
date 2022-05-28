<?php
	//start session
	session_start();
	
	$db_host = "localhost";
	$db_username = "root";
	$db_pass = "root";
	$db_name = "transferdb";
	
	mysql_connect("$db_host","$db_username","$db_pass") or die(mysql_error());
	mysql_select_db("$db_name") or die("Database Connection Error");
	
	function findexts ($filename) 
	 { 
		 $filename = strtolower($filename) ; 
		 $exts = split("[/\\.]", $filename) ; 
		 $n = count($exts)-1; 
		 $exts = $exts[$n]; 
		 return $exts; 
	 }
	 
	///////////////// DELETE ////////////////////////
	if(isset($_GET['del'])){
		$del = $_GET['del'];
		if($del){
			if(mysql_query("DELETE FROM file WHERE id='$del' LIMIT 1")){
				$msg = '<div class="alert alert-success">File deleted</div>';
			}
		}
	}
	
	//////// HOST A Code ///////////////////////////
	if(isset($_POST['aBtn'])){
		$ahost = $_POST['ahost'];
		$apass = $_POST['apass'];
		
		$from_host = 'Host A';
		
		if(!$apass){
			$msg = "<div class='alert alert-info'>Password is required</div>";
		} else {
			//passport upload
			$f_name=$_FILES['afile']['name'];
			$f_type=$_FILES['afile']['type'];
			$f_size=$_FILES['afile']['size'];
			$f_temp=$_FILES['afile']['tmp_name'];
			$f_error=$_FILES['afile']['error'];
			
			// Picture Image Contraints
			if(!$f_temp==true){
				$msg = "<div class='alert alert-info'>No Selected Image File, Please Select File</div>";
			} elseif($f_size > 2097152){
				$msg = "<div class='alert alert-info'>File must be less than 2MB</div>";
			} elseif(!preg_match("/\.(gif|jpg|png|bmp)$/i", $f_name)){
				$msg = "<div class='alert alert-info'>Image must be in .gif, .jpeg, or .png format</div>";
			} elseif($f_error==1){
				$msg = "<div class='alert alert-danger'>Failed to upload image file</div>";
			} else {
				$ext = findexts ($f_name) ;
				$tm = time();
				$tm .= ".";
				
				//change file name
				$f_name = $tm.$ext;
				
				//resize pics
				if($ext == "jpg" || $ext == "jpeg")
				{
					$src = imagecreatefromjpeg($f_temp);
				} else if($ext == "png")
				{
					$src = imagecreatefrompng($f_temp);
				} else
				{
					$src = imagecreatefrompng($f_temp);
				}
				
				list($width,$height)=getimagesize($f_temp);
				$newwidth=200;
				$newheight=($height/$width)*$newwidth;
				$tmp=imagecreatetruecolor($newwidth,$newheight);
				
				//prepare to save file on server
				imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
				
				$fpath = "images/".$f_name;
								
				imagejpeg($tmp,$fpath,100);
				
				imagedestroy($src);
				imagedestroy($tmp);
				
				$img_path = "images/".$f_name;
				
				$encrpt = md5($img_path);
			
				//save record to database
				$query = mysql_query("INSERT INTO file (from_host,to_host,encrypt,path,pass) VALUES ('$from_host','$ahost','$encrpt','$img_path','$apass')");
				if($query){
					$msg = "<div class='alert alert-success'>Encrypted Image Transfered from ".$from_host." to ".$ahost."</div>";
				} else {
					$msg = "<div class='alert alert-danger'>Error!</div>";
				}
			}
			//end passport upload
		}
	}
	
	//////// HOST B Code ///////////////////////////
	if(isset($_POST['bBtn'])){
		$bhost = $_POST['bhost'];
		$bpass = $_POST['bpass'];
		
		$from_host = 'Host B';
		
		if(!$bpass){
			$msg = "<div class='alert alert-info'>Password is required</div>";
		} else {
			//passport upload
			$f_name=$_FILES['bfile']['name'];
			$f_type=$_FILES['bfile']['type'];
			$f_size=$_FILES['bfile']['size'];
			$f_temp=$_FILES['bfile']['tmp_name'];
			$f_error=$_FILES['bfile']['error'];
			
			// Picture Image Contraints
			if(!$f_temp==true){
				$msg = "<div class='alert alert-info'>No Selected Image File, Please Select File</div>";
			} elseif($f_size > 2097152){
				$msg = "<div class='alert alert-info'>File must be less than 2MB</div>";
			} elseif(!preg_match("/\.(gif|jpg|png|bmp)$/i", $f_name)){
				$msg = "<div class='alert alert-info'>Image must be in .gif, .jpeg, or .png format</div>";
			} elseif($f_error==1){
				$msg = "<div class='alert alert-danger'>Failed to upload image file</div>";
			} else {
				$ext = findexts ($f_name) ;
				$tm = time();
				$tm .= ".";
				
				//change file name
				$f_name = $tm.$ext;
				
				//resize pics
				if($ext == "jpg" || $ext == "jpeg")
				{
					$src = imagecreatefromjpeg($f_temp);
				} else if($ext == "png")
				{
					$src = imagecreatefrompng($f_temp);
				} else
				{
					$src = imagecreatefrompng($f_temp);
				}
				
				list($width,$height)=getimagesize($f_temp);
				$newwidth=200;
				$newheight=($height/$width)*$newwidth;
				$tmp=imagecreatetruecolor($newwidth,$newheight);
				
				//prepare to save file on server
				imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
				
				$fpath = "images/".$f_name;
								
				imagejpeg($tmp,$fpath,100);
				
				imagedestroy($src);
				imagedestroy($tmp);
				
				$img_path = "images/".$f_name;
				
				$encrpt = md5($img_path);
			
				//save record to database
				$query = mysql_query("INSERT INTO file (from_host,to_host,encrypt,path,pass) VALUES ('$from_host','$bhost','$encrpt','$img_path','$bpass')");
				if($query){
					$msg = "<div class='alert alert-success'>Encrypted Image Transfered from ".$from_host." to ".$bhost."</div>";
				} else {
					$msg = "<div class='alert alert-danger'>Error!</div>";
				}
			}
			//end passport upload
		}
	}
	
	//////// HOST C Code ///////////////////////////
	if(isset($_POST['cBtn'])){
		$chost = $_POST['chost'];
		$cpass = $_POST['cpass'];
		
		$from_host = 'Host C';
		
		if(!$cpass){
			$msg = "<div class='alert alert-info'>Password is required</div>";
		} else {
			//passport upload
			$f_name=$_FILES['cfile']['name'];
			$f_type=$_FILES['cfile']['type'];
			$f_size=$_FILES['cfile']['size'];
			$f_temp=$_FILES['cfile']['tmp_name'];
			$f_error=$_FILES['cfile']['error'];
			
			// Picture Image Contraints
			if(!$f_temp==true){
				$msg = "<div class='alert alert-info'>No Selected Image File, Please Select File</div>";
			} elseif($f_size > 2097152){
				$msg = "<div class='alert alert-info'>File must be less than 2MB</div>";
			} elseif(!preg_match("/\.(gif|jpg|png|bmp)$/i", $f_name)){
				$msg = "<div class='alert alert-info'>Image must be in .gif, .jpeg, or .png format</div>";
			} elseif($f_error==1){
				$msg = "<div class='alert alert-danger'>Failed to upload image file</div>";
			} else {
				$ext = findexts ($f_name) ;
				$tm = time();
				$tm .= ".";
				
				//change file name
				$f_name = $tm.$ext;
				
				//resize pics
				if($ext == "jpg" || $ext == "jpeg")
				{
					$src = imagecreatefromjpeg($f_temp);
				} else if($ext == "png")
				{
					$src = imagecreatefrompng($f_temp);
				} else
				{
					$src = imagecreatefrompng($f_temp);
				}
				
				list($width,$height)=getimagesize($f_temp);
				$newwidth=200;
				$newheight=($height/$width)*$newwidth;
				$tmp=imagecreatetruecolor($newwidth,$newheight);
				
				//prepare to save file on server
				imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
				
				$fpath = "images/".$f_name;
								
				imagejpeg($tmp,$fpath,100);
				
				imagedestroy($src);
				imagedestroy($tmp);
				
				$img_path = "images/".$f_name;
				
				$encrpt = md5($img_path);
			
				//save record to database
				$query = mysql_query("INSERT INTO file (from_host,to_host,encrypt,path,pass) VALUES ('$from_host','$chost','$encrpt','$img_path','$cpass')");
				if($query){
					$msg = "<div class='alert alert-success'>Encrypted Image Transfered from ".$from_host." to ".$chost."</div>";
				} else {
					$msg = "<div class='alert alert-danger'>Error!</div>";
				}
			}
			//end passport upload
		}
	}
	
	//////// HOST D Code ///////////////////////////
	if(isset($_POST['dBtn'])){
		$dhost = $_POST['dhost'];
		$dpass = $_POST['dpass'];
		
		$from_host = 'Host D';
		
		if(!$dpass){
			$msg = "<div class='alert alert-info'>Password is required</div>";
		} else {
			//passport upload
			$f_name=$_FILES['dfile']['name'];
			$f_type=$_FILES['dfile']['type'];
			$f_size=$_FILES['dfile']['size'];
			$f_temp=$_FILES['dfile']['tmp_name'];
			$f_error=$_FILES['dfile']['error'];
			
			// Picture Image Contraints
			if(!$f_temp==true){
				$msg = "<div class='alert alert-info'>No Selected Image File, Please Select File</div>";
			} elseif($f_size > 2097152){
				$msg = "<div class='alert alert-info'>File must be less than 2MB</div>";
			} elseif(!preg_match("/\.(gif|jpg|png|bmp)$/i", $f_name)){
				$msg = "<div class='alert alert-info'>Image must be in .gif, .jpeg, or .png format</div>";
			} elseif($f_error==1){
				$msg = "<div class='alert alert-danger'>Failed to upload image file</div>";
			} else {
				$ext = findexts ($f_name) ;
				$tm = time();
				$tm .= ".";
				
				//change file name
				$f_name = $tm.$ext;
				
				//resize pics
				if($ext == "jpg" || $ext == "jpeg")
				{
					$src = imagecreatefromjpeg($f_temp);
				} else if($ext == "png")
				{
					$src = imagecreatefrompng($f_temp);
				} else
				{
					$src = imagecreatefrompng($f_temp);
				}
				
				list($width,$height)=getimagesize($f_temp);
				$newwidth=200;
				$newheight=($height/$width)*$newwidth;
				$tmp=imagecreatetruecolor($newwidth,$newheight);
				
				//prepare to save file on server
				imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
				
				$fpath = "images/".$f_name;
								
				imagejpeg($tmp,$fpath,100);
				
				imagedestroy($src);
				imagedestroy($tmp);
				
				$img_path = "images/".$f_name;
				
				$encrpt = md5($img_path);
			
				//save record to database
				$query = mysql_query("INSERT INTO file (from_host,to_host,encrypt,path,pass) VALUES ('$from_host','$dhost','$encrpt','$img_path','$dpass')");
				if($query){
					$msg = "<div class='alert alert-success'>Encrypted Image Transfered form ".$from_host." to ".$dhost."</div>";
				} else {
					$msg = "<div class='alert alert-danger'>Error!</div>";
				}
			}
			//end passport upload
		}
	}
	
	/////////////// Query Host A Records /////////////////////
	$hosta_list = '';
	$geta = mysql_query("SELECT * FROM file WHERE to_host='Host A'");
	if(mysql_num_rows($geta) > 0){
		while($getar = mysql_fetch_assoc($geta)){
			$hosta_list .= '
				<tr>
					<td>'.$getar['from_host'].'</td>
					<td>'.$getar['encrypt'].'</td>
					<td>
						<a href="view.php?img='.$getar['id'].'" class="btn btn-info btn-xs">View</a>
						<a href="index.php?del='.$getar['id'].'" class="btn btn-danger btn-xs">X</a>
					</td>
				</tr>
			';
		}
	}
	
	/////////////// Query Host B Records /////////////////////
	$hostb_list = '';
	$getb = mysql_query("SELECT * FROM file WHERE to_host='Host B'");
	if(mysql_num_rows($getb) > 0){
		while($getbr = mysql_fetch_assoc($getb)){
			$hostb_list .= '
				<tr>
					<td>'.$getbr['from_host'].'</td>
					<td>'.$getbr['encrypt'].'</td>
					<td>
						<a href="view.php?img='.$getbr['id'].'" class="btn btn-warning btn-xs">View</a>
						<a href="index.php?del='.$getbr['id'].'" class="btn btn-danger btn-xs">X</a>
					</td>
				</tr>
			';
		}
	}
	
	/////////////// Query Host C Records /////////////////////
	$hostc_list = '';
	$getc = mysql_query("SELECT * FROM file WHERE to_host='Host C'");
	if(mysql_num_rows($getc) > 0){
		while($getcr = mysql_fetch_assoc($getc)){
			$hostc_list .= '
				<tr>
					<td>'.$getcr['from_host'].'</td>
					<td>'.$getcr['encrypt'].'</td>
					<td>
						<a href="view.php?img='.$getcr['id'].'" class="btn btn-success btn-xs">View</a>
						<a href="index.php?del='.$getcr['id'].'" class="btn btn-danger btn-xs">X</a>
					</td>
				</tr>
			';
		}
	}
	
	/////////////// Query Host D Records /////////////////////
	$hostd_list = '';
	$getd = mysql_query("SELECT * FROM file WHERE to_host='Host D'");
	if(mysql_num_rows($getd) > 0){
		while($getdr = mysql_fetch_assoc($getd)){
			$hostd_list .= '
				<tr>
					<td>'.$getdr['from_host'].'</td>
					<td>'.$getdr['encrypt'].'</td>
					<td>
						<a href="view.php?img='.$getdr['id'].'" class="btn btn-primary btn-xs">View</a>
						<a href="index.php?del='.$getdr['id'].'" class="btn btn-danger btn-xs">X</a>
					</td>
				</tr>
			';
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Encryption Image Transfer</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    
    <style>
		header, footer{padding:15px; border:1px solid #eee;}
		.content{padding:0px 15px; font-size:small;}
		.tb{width:100%;}
		.tb .top{background-color:#eee; font-weight:bold;}
		.tb td{padding:5px; border:1px solid #ddd;}
	</style>
</head>

<body style="width:800px; margin:auto;">
	<header>
    	<h3>Encryption Image Transfer In Wide Area Network</h3>
    </header>
    
    <div class="content">
        <?php if(!empty($msg)){echo $msg;} ?>
        <form action="index.php" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-lg-6 bg-info">
                    <fieldset>
                        <legend>Host A</legend>
                        <b>Send Image To</b><br />
                        <select name="ahost" class="form-control">
                        	<option class="Host B">Host B</option>
                            <option class="Host C">Host C</option>
                            <option class="Host D">Host D</option>
                        </select><br />
                        <input type="file" name="afile" class="form-control" /><br />
                        <input type="password" name="apass" class="form-control" placeholder="Password" /><br />
                        <input type="submit" name="aBtn" value="Send >>" class="btn btn-info" />
                    </fieldset>
                    <div style="font-size:small;">
                        <br /><i><b>Received Images</b></i><br />
                        <table class="tb">
                        	<tr class="top">
                            	<td>From</td>
                                <td>Encrypted Image</td>
                                <td>Action</td>
                            </tr>
                            <?php echo $hosta_list; ?>
                        </table><br />
                    </div>
                </div>
                
                <div class="col-lg-6 bg-warning">
                    <fieldset>
                        <legend>Host B</legend>
                        <b>Send Image To</b><br />
                        <select name="bhost" class="form-control">
                        	<option class="Host A">Host A</option>
                            <option class="Host C">Host C</option>
                            <option class="Host D">Host D</option>
                        </select><br />
                        <input type="file" name="bfile" class="form-control" /><br />
                        <input type="password" name="bpass" class="form-control" placeholder="Password" /><br />
                        <input type="submit" name="bBtn" value="Send >>" class="btn btn-warning" />
                    </fieldset>
                    <div style="font-size:small;">
                        <br /><i><b>Received Images</b></i><br />
                        <table class="tb">
                        	<tr class="top">
                            	<td>From</td>
                                <td>Encrypted Image</td>
                                <td>Action</td>
                            </tr>
                            <?php echo $hostb_list; ?>
                        </table><br />
                    </div>
                </div>
            </div>
            
            <br />
            
            <div class="row">
                <div class="col-lg-6 bg-success">
                    <fieldset>
                        <legend>Host C</legend>
                        <b>Send Image To</b><br />
                        <select name="chost" class="form-control">
                        	<option class="Host A">Host A</option>
                            <option class="Host B">Host B</option>
                            <option class="Host D">Host D</option>
                        </select><br />
                        <input type="file" name="cfile" class="form-control" /><br />
                        <input type="password" name="cpass" class="form-control" placeholder="Password" /><br />
                        <input type="submit" name="cBtn" value="Send >>" class="btn btn-success" />
                    </fieldset>
                    <div style="font-size:small;">
                        <br /><i><b>Received Images</b></i><br />
                        <table class="tb">
                        	<tr class="top">
                            	<td>From</td>
                                <td>Encrypted Image</td>
                                <td>Action</td>
                            </tr>
                            <?php echo $hostc_list; ?>
                        </table><br />
                    </div>
                </div>
                
                <div class="col-lg-6 bg-primary">
                    <fieldset>
                        <legend><span style="color:#fff;">Host D</span></legend>
                        <b>Send Image To</b><br />
                        <select name="dhost" class="form-control">
                        	<option class="Host A">Host A</option>
                            <option class="Host B">Host B</option>
                            <option class="Host C">Host C</option>
                        </select><br />
                        <input type="file" name="dfile" class="form-control" /><br />
                        <input type="password" name="dpass" class="form-control" placeholder="Password" /><br />
                        <input type="submit" name="dBtn" value="Send >>" class="btn btn-primary" />
                    </fieldset>
                    <div style="font-size:small;">
                        <br /><i><b>Received Images</b></i><br />
                        <table class="tb">
                        	<tr class="top" style="color:#000;">
                            	<td>From</td>
                                <td>Encrypted Image</td>
                                <td>Action</td>
                            </tr>
                            <?php echo $hostd_list; ?>
                        </table><br />
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    <footer class="text-center">
    	Copyright &copy; 2015
    </footer>
</body>
</html>