<?php
//Database connection
require_once('../includes/config.php');
require_once('../includes/db.php');

//if we got IDs for image and property
if (isset($_GET['id']) && !empty(trim($_GET['id'])) && isset($_GET['pid']) && !empty(trim($_GET['pid']))){

    $imageID = trim($_GET['id']);
    $propertyID = trim($_GET['pid']);

    //Grab image filepaths
    $sql = "SELECT image, property_ID FROM gallery WHERE image_ID = :id";

    if($stmt = $pdo->prepare($sql)){

        $stmt->bindParam(":id", $param_imageID);
        $param_imageID = $imageID;

        if($stmt->execute()){
            //If we got an assigned image
            if($stmt->rowCount() > 0){
                $row = $stmt->fetch();
                $imageName = $row['image'];
                $propertyID = $row['property_ID'];
                //Delete all matching files from the server uploads folder
                foreach(glob('../uploads/properties/*' . $imageName) as $image){
                    unlink($image);
                }
                $param_imageID = '';

                //Delete the gallery entry from gallery table
                $sql = "DELETE FROM gallery WHERE image_ID = :id";
                if($stmt = $pdo->prepare($sql)){
                    $stmt->bindParam(":id", $param_imageID);
                    $param_imageID = $imageID;
                    //If all goes well, direct back to edit page with success message
                    if($stmt->execute()){
                        header("location: edit-listing.php?id=" . $propertyID . "&r=6");
                        unset($pdo);
                        unset($stmt);

                    //else direct back to edit page with error message
                    } else {
                        header("location: edit-listing.php?id=" . $propertyID . "&r=7");
                    }
                } else {
                    header("location: edit-listing.php?id=" . $propertyID . "&r=7");
                }
            } else {
                header("location: edit-listing.php?id=" . $propertyID . "&r=8&e=" . $imageID);
            }
        } else {
            header("location: edit-listing.php?id=" . $propertyID . "&r=7");
        }
    } else {
        header("location: edit-listing.php?id=" . $propertyID . "&r=7");
    }

} else {
    header("location: ../404.php", 404);
}

?>