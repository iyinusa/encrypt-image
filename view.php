<?php
	$db_host = "localhost";
	$db_username = "root";
	$db_pass = "root";
	$db_name = "transferdb";
	
	mysql_connect("$db_host","$db_username","$db_pass") or die(mysql_error());
	mysql_select_db("$db_name") or die("Database Connection Error");
	
	if(isset($_POST['decrypt'])){
		$img_id = $_POST['img_id'];
		$pass = $_POST['pass'];
		
		if(!$img_id){
			$msg = '<div class="alert alert-info">Invalid Image Request! - <a href="index.php"><< Back</a></div>';
		} else if(!$pass) {
			$msg = '<div class="alert alert-info">Password is required</div>';
		} else {
			//check if password is correct
			$chk = mysql_query("SELECT * FROM file WHERE pass='$pass' AND id='$img_id' LIMIT 1");
			if(mysql_num_rows($chk) <= 0){
				$msg = '<div class="alert alert-danger">Not Permitted!!! - Invalid Password Key</div>';
				$img_file = '<h2 class="text-muted">Image File Encrypted</h2>';
			} else {
				while($chkr = mysql_fetch_assoc($chk)){
					$img_file = '<img alt="" src="'.$chkr['path'].'" />';	
				}
				$msg = '<div class="alert alert-success">Decryption Successfull!!!</div>';
			}
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
        <form action="view.php" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-lg-6">
                    <fieldset>
                        <legend>Open Encrypted Image</legend>
                        <b>Enter Password To Decrypt Image</b><br />
                        <input type="hidden" name="img_id" value="<?php if(isset($_GET['img'])){echo $_GET['img'];} ?>" />
                        <input type="password" name="pass" class="form-control" placeholder="Password" /><br />
                        <a href="index.php" class="btn btn-warning"><< Cancel</a>
                        <input type="submit" name="decrypt" value="Decrypt Image!" class="btn btn-success" />
                        <br />
                    </fieldset>
                </div>
            </div>
            
            <hr />
            
            <div class="row">
            	<?php if(!empty($img_file)){echo $img_file;} ?>
            </div>
            
            <br /><br />
        </form>
    </div>
    
    <footer class="text-center">
    	Copyright &copy; 2015
    </footer>
</body>
</html>