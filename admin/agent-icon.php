<?php



    function compressImage($source, $name, $quality) {
    //Set destination path
    $destination = "../uploads/agents/";

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
    imagejpeg($image, ($destination . $name), 90);

    }

    //Check the form was submitted
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        
        $agentID = trim($_POST['id']);

        //Check if the file was uploaded properly
        if(isset($_FILES["agentIcon"]) && $_FILES["agentIcon"]["error"] == 0){

            //Setup database connection
            require_once('../includes/config.php');
            require_once('../includes/db.php');

            $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
            $filename = $_FILES["agentIcon"]["name"];
            $filetype = $_FILES["agentIcon"]["type"];
            $filesize = $_FILES["agentIcon"]["size"];
            $agentID = trim($_POST['id']);
            $agentName = trim($_POST['agentName']);
            $buildName = $agentID . '_' . $agentName . '.jpg';

            //Verify that the file extension is allowed
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if(!array_key_exists($ext, $allowed)) die(header("location: edit-agent.php?id=" . $agentID . "&r=5"));

            //Verify the file is under the max filesize limit
            $maxsize = 2 * 1024 * 1024;
            if ($filesize > $maxsize) die(header("location: edit-agent.php?id=" . $agentID . "&r=4"));

            //Verify MIME type
            if(in_array($filetype, $allowed)){
                
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
                        if(file_exists("../uploads/agents/" . $buildName . ".jpg")) unlink("../uploads/agents/" . $buildName . ".jpg");
                        
                        //Compress and create our new 256x256 image
                        compressImage($_FILES['agentIcon']['tmp_name'], $buildName, 90);
        
                        //redirect back to the edit agent page
                        header("location: edit-agent.php?id=" . $agentID . "&r=2");
                    } else {
                        header("location: edit-agent.php?id=" . $agentID . "&r=3");
                    }
                    
                    unset($pdo);
                } else {
                    header("location: edit-agent.php?id=" . $agentID . "&r=3");
                }
                
            } else {
                header("location: edit-agent.php?id=" . $agentID . "&r=3&e=" . $_FILES["agentIcon"]["error"]);
            }

        } else {
            header("location: edit-agent.php?id=" . $agentID . "&r=3&e=" . $_FILES["agentIcon"]["error"]);
        }
    //If we didn't get a form (user is trying to access the page manually) redirect to 404
    } else { 
        header("location: ../404.php"); 
    }
?>