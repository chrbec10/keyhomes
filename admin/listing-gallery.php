<?php 

//Include database access
require_once('../includes/config.php');
require_once('../includes/db.php');

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


    //Scale image to 1500px width and save as high quality
    $image_hi = imagescale($image, 1500, -1, IMG_BICUBIC);
    //Save image as high-quality jpeg
    imagejpeg($image_hi, ($filepath . $name), $quality);

    //Scale image to 750px width
    $image_med = imagescale($image, 750, -1, IMG_BICUBIC);
    //Save image as medium-quality jpeg
    imagejpeg($image_med, ($filepath . "med_" . $name), $quality);

    //Scale image down to 150px width
    $image_th = imagescale($image, 150, -1, IMG_BICUBIC);
    //Save image as thumbnail
    imagejpeg($image_th, ($filepath . "thumb_" . $name), $quality);
}


//Check the form was submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Check we got a property ID from the form
    if(!isset($_POST['id']) || trim($_POST['id']) == '')
        //If we didn't, direct to 404
        //die(header("location: ../404.php"));
        die("no id");

    //If we did, set property id variable for redirects
    $propertyID = trim($_POST['id']);

    //If we had files uploaded
    if(isset($_FILES["gallery"]) && !empty(array_filter($_FILES["gallery"]["name"]))){
        //Setup constraints
        $allowed = array("jpg", "jpeg", "gif", "png");
        $maxsize = 5 * 1024 * 1024;

        //Setup destination for images
        $destination = "../uploads/properties/";

        //Check each file meets our constraints
        foreach($_FILES["gallery"]["tmp_name"] as $key => $value) {
            $filesize = $_FILES["gallery"]["size"][$key];
            $filetype = pathinfo($_FILES["gallery"]["name"][$key], PATHINFO_EXTENSION);
            $filename = $_FILES["gallery"]["name"][$key];
            $error = $_FILES["gallery"]["error"][$key];

            if($error != 0)
                die(header("location: edit-listing.php?id=" . $propertyID . "&r=3&e=" . $error . "&n=" . $filename));
            if($filesize > $maxsize)
                die(header("location: edit-listing.php?id=" . $propertyID . "&r=4&n=" . $filename));
            if(!in_array(strtolower($filetype), $allowed))
                die(header("location: edit-listing.php?id=" . $propertyID . "&r=5&n=" . $filename));
        }

        //compress and create files, then add a gallery entry
        foreach($_FILES["gallery"]["tmp_name"] as $key => $value) {
            $image = $_FILES["gallery"]["name"][$key];
            $image_name = $propertyID . '_' . hash_file('sha1', $image) . ".jpg";
            $quality = 90;
            compressImage($image, $image_name, $quality, $destination);
            $sql = "INSERT INTO gallery (image, property_ID) VALUES (:name, :propertyID)";
            if ($stmt = $pdo->prepare($sql)){

                $stmt->bindParam(":name", $param_image_name);
                $stmt->bindParam(":propertyID", $param_propertyID);

                $param_image_name = $image_name;
                $param_propertyID = $propertyID;
                if($stmt->execute()){}
                else{
                    die(header("location: edit-listing.php?id=" . $propertyID . "&r=3"));
                }
            } else {
                die(header("location: edit-listing.php?id=" . $propertyID . "&r=3"));
            }

        }

        exit(header("location: edit-listing.php?id=" . $propertyID . "&r=2"));

    } else {

        die(header("location: edit-listing.php?id=" . $propertyID . "&r=3&e=4"));
    }



//Else user tried to navigate to page manually, redirect to 404
} else { header("location: ../404.php"); }

?>
