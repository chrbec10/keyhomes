<?php
    //Check the form was submitted
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        
        //Check if the file was uploaded properly
        if(isset($_FILES["agentIcon"]) && $_FILES["agentIcon"]["error"] == 0){
            $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
            $filename = $_FILES["agentIcon"]["name"];
            $filetype = $_FILES["agentIcon"]["type"];
            $filesize = $_FILES["agentIcon"]["size"];
            $agentID = trim($_POST['id']);
            $agentName = trim($_POST['agentName']);

            //Verify that the file extension is allowed
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if(!array_key_exists($ext, $allowed)) die("Error: Please select a .jpg, .jpeg, .gif, or .png file");

            //Verify the file is under the max filesize limit
            $maxsize = 2 * 1024 * 1024;
            if ($filesize > $maxsize) die("Error: File is larger than the size limit (2MB)");

            //Verify MYME type
            if(in_array($filetype, $allowed)){

                //Delete old icon and shift our new one in from temporary location
                unlink("../uploads/agents/" . $agentName . ".jpg");
                move_uploaded_file($_FILES["agentIcon"]["tmp_name"], "../uploads/agents/" . $agentName . ".jpg" );
                header("refresh: 0; url=edit-agent.php?id=" . $agentID . "?u=cur");

            } else {
                echo "Error: There was a problem uploading your file.";
            }

        } else {
            echo "Error: " . $_FILES["agentIcon"]["error"];
        }
    }
?>