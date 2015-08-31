<? 
/*
    Copyright (C) 2013-2015 xtr4nge [_AT_] gmail.com

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/ 
?>
<?
include "../../../login_check.php";
include "../../../config/config.php";
include "../_info_.php";
include "../../../functions.php";

// Checking POST & GET variables...
if ($regex == 1) {
    regex_standard($_GET["service"], "../msg.php", $regex_extra);
    regex_standard($_GET["action"], "../msg.php", $regex_extra);
    regex_standard($_GET["page"], "../msg.php", $regex_extra);
    regex_standard($_GET["install"], "../msg.php", $regex_extra);
	regex_standard($_POST["upload"], "../msg.php", $regex_extra);
}

$service = $_GET['service'];
$action = $_GET['action'];
$page = $_GET['page'];
$install = $_GET['install'];
$upload = $_POST['upload'];

if ($service != "") {
	
    if ($action == "start") {
	
		// COPY LOG
        if ( 0 < filesize( $mod_logs ) ) {
            $exec = "$bin_cp $mod_logs $mod_logs_history/".gmdate("Ymd-H-i-s").".log";
            exec_fruitywifi($exec);
            
			$exec = "$bin_chown fruitywifi:fruitywifi $mod_logs_history/*";
			exec_fruitywifi($exec);
			
            $exec = "$bin_echo '' > $mod_logs";
            exec_fruitywifi($exec);
        }
	
        //$exec = "openvpn --config client.ovpn --log $mod_logs --daemon > /dev/null &";
		$exec = "$bin_openvpn --config client.ovpn --log $mod_logs --daemon openvpn-fruitywifi";
        exec_fruitywifi($exec);
        
		$exec = "$bin_chown fruitywifi:fruitywifi $mod_logs";
        exec_fruitywifi($exec);
		
        $wait = 1;
	    
    } else if ($action == "stop") {
    
        $exec = "ps aux|grep -E 'openvpn --config' | grep -v grep | awk '{print $2}'";
		exec($exec,$output);
		
		$exec = "kill " . $output[0];
		exec_fruitywifi($exec);
		
		// COPY LOG
        if ( 0 < filesize( $mod_logs ) ) {
            $exec = "$bin_cp $mod_logs $mod_logs_history/".gmdate("Ymd-H-i-s").".log";
            exec_fruitywifi($exec);
            
			$exec = "$bin_chown fruitywifi:fruitywifi $mod_logs_history/*";
			exec_fruitywifi($exec);
			
            $exec = "$bin_echo '' > $mod_logs";
            exec_fruitywifi($exec);
        }
    
    }
}

// UPLOAD FILE [.OVPN]
if ($upload == "upload") {
	$target_dir_final = "./";
	$target_dir = "/tmp/";
	$fileExtension = pathinfo($_FILES["fileToUpload"]["name"],PATHINFO_EXTENSION);
	//$target_file = $target_dir . "payload.$fileExtension";
	$target_file = $target_dir . "client.ovpn";
	$uploadOk = 1;
	$fileType = pathinfo($target_file,PATHINFO_EXTENSION);
	// Check if image file is a actual image or fake image
	if(isset($_POST["submit"])) {
		$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
	}
	// Allow certain file formats
	if ($fileType != "ovpn") {
		echo "FileType not allowed...";
		$uploadOk = 0;
	}
	
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		echo "Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
	} else {
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
			$exec = "mv $target_file $target_dir_final";
			exec_fruitywifi($exec);
		} else {
			echo "Sorry, there was an error uploading your file.";
		}
	}

	header("Location: ../index.php?tab=1");
	exit;
}

if ($install == "install_$mod_name") {

    $exec = "$bin_chmod 755 install.sh";
    exec_fruitywifi($exec);

    $exec = "$bin_sudo ./install.sh > $log_path/install.txt &";
    exec_fruitywifi($exec);

    header('Location: ../../install.php?module='.$mod_name);
    exit;
}

if ($page == "status") {
    header('Location: ../../../action.php?wait='.$wait);
} else if ($page == "config") {
    header('Location: ../../../page_config.php');
} else {
    header('Location: ../../action.php?page='.$mod_name.'&wait='.$wait);
}

?>
