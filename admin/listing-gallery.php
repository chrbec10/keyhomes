<?php 

//Include database access
require_once('../includes/config.php');
require_once('../includes/db.php');

function compressImage($source, $name, $quality) {
    //Set destination path
    $destination = "../uploads/properties/"

    //Get image information
    $info = getimagesize($source);

    //Convert image into image object
    if ($info['mime'] == 'image/jpeg')
        $image = imagecreatefromjpeg($source);
    
    if ($info['mime'] == 'image/gif')
        $image = imagecreatefromgif($source);

    if ($info['mime'] == 'image/png')
        $image = imagecreatefrompng($source);

    //Save our original image as full-size compressed jpeg
    imagejpeg($image, ($destination . $name), $quality);

    //Scale image to 1500px width
    $image_hi = imagescale($image, 1500, IMG_BICUBIC);
    //Save image as high-quality jpeg
    imagejpeg($image_hi, ($destination . "hi_" $name), $quality);

    //Scale image to 750px width
    $image_med = imagescale($image, 750, IMG_BICUBIC);
    //Save image as medium-quality jpeg
    imagejpeg($image_med, ($destination . "med_" $name), $quality);

    //Scale image down to 150px width
    $image_th = imagescale($image, 150, IMG_BICUBIC);
    //Save image as thumbnail
    imagejpeg($image_th, ($destination . "thumb_" . $name), $quality);
}


//Check the form was submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
        
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
        if(!array_key_exists($ext, $allowed)) die("Error: Please select a .jpg, .jpeg, .gif, or .png file");

        //Verify the file is under the max filesize limit
        $maxsize = 2 * 1024 * 1024;
        if ($filesize > $maxsize) die("Error: File is larger than the size limit (2MB)");

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
                    header("location: edit-agent.php?id=" . $agentID);
                }
                unset($pdo);
            }
            
        } else {
            echo "Error: There was a problem uploading your file.";
        }

    } else {
        echo "Error: " . $_FILES["agentIcon"]["error"];
    }
} else { header("location: ../404.php"); }

?>
