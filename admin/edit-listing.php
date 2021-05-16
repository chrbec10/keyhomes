<?php
$title = "Edit Listing"; //The Page Title
require_once('../includes/layouts/header.php'); //Gets the header
require_once('../includes/db.php'); //Connect to database

//Defining our variables
$saleType = $price = $description = $bedrooms = $bathrooms = $garage = $agent_ID = $streetNum = $street = $city = $postcode = '';
$saleType_err = $price_err = $description_err = $bedrooms_err = $bathrooms_err = $garage_err = $agent_ID_err = $streetNum_err = $street_err = $city_err  = $postcode_err = '';


//Set alert banner text and colour
if (isset($_GET['r']) && ($_GET['r'] != '')){
    $r = trim($_GET['r']);

    if (isset($_GET['e']))
        $e = trim($_GET['e']);

    if (isset($_GET['n']))
        $n = trim($_GET['n']);
    
    switch($r){
        case 1:
            $response_div = 'alert-success';
            $response_txt = 'New listing created successfully. Remember to upload your gallery images before continuing.';
            break;
        
        case 2:
            $response_div = 'alert-success';
            $response_txt = 'New gallery images uploaded successfully';
            break;

        case 3:
            $response_div = 'alert-danger';
            $response_txt = 'There was a problem uploading your files. Please try again later.';
            if (isset($e) && $e != ''){
                if($e == 4){
                    $response_div = 'alert-warning';
                    $response_txt = 'Please select at least one image to be uploaded.';
                } else if($e == 2){
                    $response_div = 'alert-warning';
                    $response_txt = 'Please select files that are each under 5MB in size.';
                        if(isset($n) && $n != '')
                        $response_txt .= ' File ' . $n . ' is too large.';
                } else
                    $response_txt = $response_txt . ' Error code: ' . $e;
            }
            break;

        case 4:
            $response_div = 'alert-warning';
            $response_txt = 'Please select files that are each under 5MB in size.';
            if(isset($n) && $n != '')
                $response_txt .= ' File ' . $n . ' is too large.';
            break;

        case 5:
            $response_div = 'alert-warning';
            $response_txt = 'Please select only .jpg, .jpeg, .png, or .gif files.';
            if(isset($n) && $n != '')
                $response_txt .=  ' File ' . $n . ' is the wrong file type.';
            break;

        case 6:
            $response_div = 'alert-success';
            $response_txt = 'Image deleted successfully';
            break;

        case 7:
            $response_div = 'alert-danger';
            $response_txt = 'Something went wrong. Please try again later.';
            break;

        case 8:
            $response_div = 'alert-danger';
            $response_txt = 'Image not found.';
            if (isset($e) && $e != ''){
                $response_txt = 'Image with ID <strong>' . $e . '</strong> not found.';
            }
            break;

        default:
            $response_div = 'd-none';
            $response_txt = '';
            break;
    }
} else {
    $response_div = 'd-none';
    $response_txt = '';
}

//Validate input, passing important variables by reference
function validateInput($input = '', &$err = '', &$output = '', $errMsg = '') {
    if (empty($input)){
        $err = $errMsg;

    } else {
        $output = $input;
    }
}

//Validate input with regular expression check, passing important variables by reference
function complexValidateInput($input = '', &$err = '', &$output = '', $errMsg = '', $errInvalid = '', $regex = '') {
    if (empty($input)){
        $err = $errMsg;

    } elseif (!filter_var($input, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>$regex)))){
        $err = $errInvalid;

    } else {
        $output = $input;
    }
}


//Process form data on submit
if (isset($_POST['id']) && !empty(trim($_POST['id']))){

    //Grab ID of property to be edited
    $property_ID = trim($_POST["id"]);

    $input_agent = trim($_POST["agent"]);
    //Validate assigned agent
    complexValidateInput($input_agent, $agent_ID_err, $agent_ID, "Please select an agent to be assigned to this listing", "Please select an agent to be assigned to this listing", "/^[0-9]*$/");
    
    $input_streetNum = trim($_POST["streetNum"]);
    //Validate street number
    complexValidateInput($input_streetNum, $streetNum_err, $streetNum, "Please enter a street number", "Please enter a valid street number", "/^[a-zA-Z0-9]*$/");

    $input_street = trim($_POST["street"]);
    //Validate street
    complexValidateInput($input_street, $street_err, $street, "Please enter a street name", "Please enter a valid street name", "/^[a-zA-Z-' ]*$/");

    $input_city = trim($_POST["city"]);
    //Validate city
    complexValidateInput($input_city, $city_err, $city, "Please enter a city", "Please enter a valid city", "/^[a-zA-Z-' ]*$/");

    $input_postcode = trim($_POST["postcode"]);
    //Validate postcode
    complexValidateInput($input_postcode, $postcode_err, $postcode, "Please enter a postcode", "Please enter a valid postcode", "/^[0-9]*$/");

    $input_bedrooms = trim($_POST["bedrooms"]);
    //validate bedrooms
    complexValidateInput($input_bedrooms, $bedrooms_err, $bedrooms, "Please enter the number of bedrooms", "Please enter a number from 0-99", "/^[0-9]*$/");

    $input_bathrooms = trim($_POST["bathrooms"]);
    //validate bedrooms
    complexValidateInput($input_bathrooms, $bathrooms_err, $bathrooms, "Please enter the number of bathrooms", "Please enter a number from 0-99", "/^[0-9]*$/");

    $input_garage = trim($_POST["garage"]);
    //validate bedrooms
    complexValidateInput($input_garage, $garage_err, $garage, "Please enter the number of parking spaces", "Please enter a number from 0-99", "/^[0-9]*$/");

    $input_saleType = trim($_POST["saleType"]);
    //validate sale type
    validateInput($input_saleType, $saleType_err, $saleType, "Please enter the type of sale");

    $input_price = trim($_POST["price"]);

    //validate price (0 is considered 'empty' so using isset instead)
    if (!isset($input_price)){
        $price_err = "Please enter a price for the property";

    } else {
        $price = $input_price;
    }


    $input_description = trim($_POST["description"]);
    //validate description
    validateInput($input_description, $description_err, $description, "Please enter a description for the property");


    //Check for input errors before trying to insert into database
    if(empty($saleType_err) && empty($price_err) && empty($description_err) && empty($bedrooms_err) && empty($bathrooms_err) 
    && empty($garage_err) && empty($agent_ID_err) && empty($streetNum_err) && empty($street_err) && empty($city_err) && empty($postcode_err)){
        //Prepare an UPDATE statement
        $sql = "UPDATE property SET saleType=:saleType, price=:price, description=:description, bedrooms=:bedrooms, bathrooms=:bathrooms, garage=:garage, 
        agent_ID=:agent_ID, streetNum=:streetNum, street=:street, city=:city, postcode=:postcode WHERE property_ID=:property_ID";

        if ($stmt = $pdo->prepare($sql)){
            //Bind variables to the prepared statement as parameters
            $stmt->bindParam(":saleType", $param_saleType);
            $stmt->bindParam(":price", $param_price);
            $stmt->bindParam(":description", $param_description);
            $stmt->bindParam(":bedrooms", $param_bedrooms);
            $stmt->bindParam(":bathrooms", $param_bathrooms);
            $stmt->bindParam(":garage", $param_garage);
            $stmt->bindParam(":agent_ID", $param_agent_ID);
            $stmt->bindParam(":streetNum", $param_streetNum);
            $stmt->bindParam(":street", $param_street);
            $stmt->bindParam(":city", $param_city);
            $stmt->bindParam(":postcode", $param_postcode);
            $stmt->bindParam(":property_ID", $param_property_ID);

            //Set parameters
            $param_saleType = $saleType;
            $param_price = $price;
            $param_description = $description;
            $param_bedrooms = $bedrooms;
            $param_bathrooms = $bathrooms;
            $param_garage = $garage;
            $param_agent_ID = $agent_ID;
            $param_streetNum = $streetNum;
            $param_street = $street;
            $param_city = $city;
            $param_postcode = $postcode;
            $param_property_ID = $property_ID;

            //If successful
            if ($stmt->execute()){
                header("location: success.php");
                exit();

            } else {
                echo "Oops! Something went wrong.";
            }
        }
        //Close statement
        unset($stmt);
    }
    //Close connection
    unset($pdo);

} else {
    //Check whether we were given an ID before continuing
    if (isset($_GET['id']) && !empty(trim($_GET['id']))){
        //Get our ID parameter
        $property_ID = trim($_GET['id']);

        //Prepare a select statement
        $sql = "SELECT * FROM property WHERE property_ID = :property_ID";
        if ($stmt = $pdo->prepare($sql)){

            //Bind variables to the select statement
            $stmt->bindParam(":property_ID", $param_property_ID);

            //Set parameter
            $param_property_ID = $property_ID;
            
            //Attempt the select statement
            if($stmt->execute()){
                //Check that we get exactly 1 row back
                if ($stmt->rowCount() == 1){
                    //Fetch as an associative array since we're getting only one row back
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    //Pull values from row
                    $saleType = $row['saleType'];
                    $price = $row['price'];
                    $description = $row['description'];
                    $bedrooms = $row['bedrooms'];
                    $bathrooms = $row['bathrooms'];
                    $garage = $row['garage'];
                    $agent_ID = $row['agent_ID'];
                    $streetNum = $row['streetNum'];
                    $street = $row['street'];
                    $city = $row['city'];
                    $postcode = $row['postcode'];

                } else {
                    //URL doesn't contain a valid ID
                    header("location: ../404.php");
                    exit();
                }
            } else {
                echo "Oops! Something went wrong.";
            }
        }
        //Close statement
        unset($stmt);
    } else {
        //We weren't given an ID
        header("location: ../404.php");
        exit();
    }

}
?>

<div class="content-top-padding pb-4 bg-light">
    <div class="container mt-4">
        <div class="alert <?php echo $response_div; ?>"><?php echo $response_txt; ?></div>
        <?php
            //If we can retrieve our agent list
            $sql = "SELECT agent_ID, fname, lname FROM agent";
            if ($result = $pdo->query($sql)){
                if (!($result->rowCount() > 0)){
                    echo "No agents to retrieve. Try creating one first.";
                }
            } else {
                echo "Unable to retrieve agents. Something went wrong. Please try again later.";
            }
        ?>
        <!--Notify user which property is being changed-->
        <h2>Currently editing <?php echo $streetNum . ' ' . $street . ', ' . $city . ' ' . $postcode ?></h2>
        <br>
        <ul id="gallery" class="row mx-0 px-0" style="list-style-type: none;">
        <!--Create gallery out of image locations in database-->
            <?php
            $sql = "SELECT * FROM gallery WHERE property_ID = :property_ID";
            if ($stmt = $pdo->prepare($sql)){

                //Bind variables to the select statement
                $stmt->bindParam(":property_ID", $param_property_ID);
    
                //Set parameter
                $param_property_ID = $property_ID;

                if($stmt->execute()){
                    if ($stmt->rowCount() > 0){
                        while ($image = $stmt->fetch()){
                            if(file_exists("../uploads/properties/thumb_" . $image['image'])){
                                echo "<li class='col-6 col-sm-4 col-lg-2 my-3'>
                                        <div style='height:100px; width:150px;
                                        background-image: url(\"../uploads/properties/thumb_" . $image['image'] . "?=" . filemtime('../uploads/properties/' . $image['image']) . "\");' 
                                        class='edit-gallery-img mx-auto'>
                                        <div><a style='font-size:20px;' class='btn btn-danger' href='delete-image.php?id=" . $image['image_ID'] . "&pid=" . $property_ID . "'>&times;</a></div>
                                        </div>
                                    </li>";
                            }
                        }
                    }
                }
            } else {
                echo "There was a problem retrieving the gallery images. Please try again later.";
            }
            unset($stmt);
            unset($pdo);
            ?>
        </ul>
        <form action="listing-gallery.php" method="post" enctype="multipart/form-data">
            <h4 class="text-center">Gallery</h4>
            <input type="hidden" name="MAX_FILE_SIZE" value="5242880">
            <input type="file" name="gallery[]" id="gallery" class="form-control" multiple>
            <p><strong>Note:</strong> Maximum size of 5MB. Allowed formats: .jpg, .jpeg, .gif, or .png.</strong></p>
            <input type="hidden" id="id" name="id" value="<?php echo $property_ID; ?>"/>
            <button tpye="submit" class="btn btn-primary">Upload</button>
        </form>
        <br>
        <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
            <h4 class="text-center">Property details</h4>
            <div class="row">
                <div class="form-group col">
                    <label for="agent" style="display:block">Assigned Agent</label>
                    <select name="agent" id="agent" class="form-control <?php echo (!empty($agent_ID_err)) ? 'is-invalid' : ''; ?>">
                        <?php
                        //Generate dropdown options from agents table
                        if ($result->rowCount() > 0){
                            while ($agentrow = $result->fetch()){
                                if ($agentrow['agent_ID'] == $agent_ID){
                                    echo '<option selected value="' . $agentrow['agent_ID'] . '">' . $agentrow['fname'] . ' ' . $agentrow['lname'] . '</option>';
                                } else {
                                echo '<option value="' . $agentrow['agent_ID'] . '">' . $agentrow['fname'] . ' ' . $agentrow['lname'] . '</option>';
                                }
                            }
                        }
                        ?>
                    </select>
                    <span class="invalid-feedback"><?php echo $agent_ID_err;?></span>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="form-group col-md-2">
                    <label for="streetNum">Number</label>
                    <input type="text" id="streetNum" name="streetNum" class="form-control <?php echo (!empty($streetNum_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $streetNum ?>">
                    <span class="invalid-feedback"><?php echo $streetNum_err;?></span>
                </div>
                <div class="form-group col">
                    <label for="street">Street</label>
                    <input type="text"  id="street" name="street" maxlength="100" class="form-control <?php echo (!empty($street_err)) ? 'is-invalid' : ''; ?>"value="<?php echo $street ?>">
                    <span class="invalid-feedback"><?php echo $street_err;?></span>
                </div>
                <div class="form-group col-md-3">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" maxlength="100" class="form-control <?php echo (!empty($city_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $city ?>">
                    <span class="invalid-feedback"><?php echo $city_err;?></span>
                </div>
                <div class="form-group col-md-2">
                    <label for="postcode">Postcode</label>
                    <input type="text" id="postcode" name="postcode" maxlength="4" class="form-control <?php echo (!empty($postcode_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $postcode ?>">
                    <span class="invalid-feedback"><?php echo $postcode_err;?></span>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="form-group col-md">
                    <label for="bedrooms">Bedrooms</label>
                    <input type="text" id="bedrooms" name="bedrooms" maxlength="2" class="form-control <?php echo (!empty($bedrooms_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $bedrooms ?>">
                    <span class="invalid-feedback"><?php echo $bedrooms_err;?></span>
                </div>
                <div class="form-group col-md">
                    <label for="bathrooms">Bathrooms</label>
                    <input type="text" id="bathrooms" name="bathrooms" maxlength="2" class="form-control <?php echo (!empty($bathrooms_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $bathrooms ?>">
                    <span class="invalid-feedback"><?php echo $bathrooms_err;?></span>
                </div>
                <div class="form-group col-md">
                    <label for="garage">Parking</label>
                    <input type="text" id="garage" name="garage" maxlength="2" class="form-control <?php echo (!empty($garage_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $garage ?>">
                    <span class="invalid-feedback"><?php echo $garage_err;?></span>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="form-group col-md">
                    <label for="saleType">Sale Type</label>
                    <input type="text" id="saleType" name="saleType" maxlength="20" class="form-control <?php echo (!empty($saleType_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $saleType ?>">
                    <span class="invalid-feedback"><?php echo $saleType_err;?></span>
                </div>
                <div class="form-group col-md">
                    <label for="price">Price</label>
                    <input type="number" id="price" name="price" class="form-control <?php echo (!empty($price_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $price ?>">
                    <span class="invalid-feedback"><?php echo $price_err;?></span>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>" name="description" id="description"><?php echo $description; ?></textarea>
                    <span class="invalid-feedback"><?php echo $description_err;?></span>
                </div>
            </div>
            <br>
            <input type="hidden" id="id" name="id" value="<?php echo $property_ID; ?>"/>
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="./" class="btn btn-secondary">Cancel</a>
            <a href="delete-listing.php?id=<?php echo $property_ID; ?>" class="btn btn-danger float-end">Delete Listing</a>
        </form>
    </div>
</div>

<?php require_once('../includes/layouts/footer.php'); ?>