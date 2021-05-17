<?php

    require_once('includes/seccheck.php');

    function compressImage($source, $name, $quality, $filepath) {

    //Get image information
    $info = getimagesize($source);

    //Convert image into image object
    if ($info['mime'] == 'image/jpeg')
        $image = imagecreatefromjpeg($source);
    
    if ($info['mime'] == 'image/gif')
        $image = imagecreatefromgif($source);

    if ($info['mime'] == 'image/png')
        $image = imagecreatefrompng($source);

    //Scale image to 256x256px
    $image = imagescale($image, 256, 256, IMG_BICUBIC);
    //Create new jpeg image from source
    imagejpeg($image, ($filepath . $name), 90);

    }

    //Check the form was submitted
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        
        if(!isset($_POST['id']) || trim($_POST['id']) == '')
            die(header("location: ../404.php"));
        $agentID = trim($_POST['id']);

        //Check if the file was uploaded properly
        if(isset($_FILES["agentIcon"]) && $_FILES["agentIcon"]["error"] == 0){

            //Setup database connection
            require_once('../includes/config.php');
            require_once('../includes/db.php');

            $allowed = array("jpg", "jpeg", "gif", "png");
            $filename = $_FILES["agentIcon"]["name"];
            $filesize = $_FILES["agentIcon"]["size"];
            $agentName = trim($_POST['agentName']);
            $buildName = $agentID . '_' . $agentName . '.jpg';
            $destination = "../uploads/agents/";
            $filetype = pathinfo($filename, PATHINFO_EXTENSION);

            //Verify the file is under the max filesize limit
            $maxsize = 2 * 1024 * 1024;
            if ($filesize > $maxsize) die(header("location: edit-agent.php?id=" . $agentID . "&r=4"));

            //Verify MIME type
            if(in_array(strtolower($filetype), $allowed)){
                
                //Attempt to update database
                $sql = "UPDATE agent SET icon = :icon WHERE agent_ID = :agentID";

                if ($stmt = $pdo->prepare($sql)){
                    $stmt->bindParam(":icon", $param_icon);
                    $stmt->bindParam(":agentID", $param_ID);

                    $param_icon = $buildName;
                    $param_ID = $agentID;

                    //If we successfully update the database
                    if ($stmt->execute()){  
                        //If a file exists with the same name, delete it
                        if(file_exists("../uploads/agents/" . $buildName . ".jpg")) 
                            unlink("../uploads/agents/" . $buildName . ".jpg");
                        
                        //Compress and create our new 256x256 image
                        compressImage($_FILES['agentIcon']['tmp_name'], $buildName, 90, $destination);
        
                        //redirect back to the edit agent page
                        exit(header("location: edit-agent.php?id=" . $agentID . "&r=2"));
                    } else {

                        //redirect to edit page with an error message
                        die(header("location: edit-agent.php?id=" . $agentID . "&r=3"));
                    }
                    
                    unset($pdo);
                } else {
                    //redirect to edit page with an error message
                    die(header("location: edit-agent.php?id=" . $agentID . "&r=3"));
                }
                
            } else {
                //redirect to edit page with an error message
                die(header("location: edit-agent.php?id=" . $agentID . "&r=5" . $filetype));
            }

        } else {
            //redirect to edit page with an error message
            die(header("location: edit-agent.php?id=" . $agentID . "&r=3&e=" . $_FILES["agentIcon"]["error"]));
        }
    //If we didn't get a form POSTed (user is trying to access the page manually) redirect to 404
    } else { 
        die(header("location: ../404.php")); 
    }
?>