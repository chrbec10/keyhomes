<?php

$title = "Edit Property"; //The Page Title
require_once('../includes/layouts/header.php'); //Gets the header
require_once('../includes/db.php'); //Connect to database

//Defining our variables
$saleType = $price = $description = $bedrooms = $bathrooms = $garage = $agent_ID = $streetNum = $street = $city = $postcode = '';
$saleType_err = $price_err = $description_err = $bedrooms_err = $bathrooms_err = $garage_err = $agent_ID_err = $streetNum_err = $street_err = $city_err  = $postcode_err = '';

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
    //validate price
    complexValidateInput($input_price, $price_err, $price, "Please enter a price for the property", "Please enter a valid price", "/^[0-9]*$/");

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
                header("location: index.php");
                exit();

            } else {
                echo "Oops! Something went wrong.";
            }
        }
        //Close statement
        unset($stmt);
    }
    //Close connection
    //unset($pdo);

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
        //unset($stmt);

        //Close connection
        //unset($pdo);
    } else {
        //We weren't given an ID
        header("location: ../404.php");
        exit();
    }

}
?>

<div class="content-top-padding pb-4 bg-light">
    <div class="container mt-4">
        <?php
            //If we can retrieve our agent list
            $sql = "SELECT agent_ID, fname, lname FROM agent";
            if ($result = $pdo->query($sql)){
                if ($result->rowCount() > 0){
                    
                } else {
                    echo "No agents to retrieve. Try creating one first.";
                }
            } else {
                echo "Unable to retrieve agents. Something went wrong. Please try again later.";
            }
            //unset($pdo);
        ?>
        <!--Notify user which property is being changed-->
        <div class="container"><h2>Editing listing for <?php echo $row['streetNum'] . ' ' . $row['street'] . ', ' . $row['city'] . ' ' . $row['postcode'] ?></h2></div>
        <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
            <div class="row">
                <div class="form-group col">
                    <label for="agent" style="display:block">Assigned Agent</label>
                    <select name="agent" id="agent" class="form-control <?php echo (!empty($agent_ID_err)) ? 'is-invalid' : ''; ?>">
                        <?php
                        //Generate dropdown options from agents table
                        while ($agentrow = $result->fetch()){
                            if ($agentrow['agent_ID'] == $row['agent_ID']){
                                echo '<option selected value="' . $agentrow['agent_ID'] . '">' . $agentrow['fname'] . ' ' . $agentrow['lname'] . '</option>';
                            } else {
                            echo '<option value="' . $agentrow['agent_ID'] . '">' . $agentrow['fname'] . ' ' . $agentrow['lname'] . '</option>';
                            }
                        }
                        //unset($result);
                        ?>
                    </select>
                    <span class="invalid-feedback"><?php echo $agent_ID_err;?></span>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="form-group col-md-2">
                    <label for="streetNum">Number</label>
                    <input type="text" id="streetNum" name="streetNum" class="form-control <?php echo (!empty($streetNum_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $row['streetNum'] ?>">
                    <span class="invalid-feedback"><?php echo $streetNum_err;?></span>
                </div>
                <div class="form-group col">
                    <label for="street">Street</label>
                    <input type="text"  id="street" name="street" maxlength="100" class="form-control <?php echo (!empty($street_err)) ? 'is-invalid' : ''; ?>"value="<?php echo $row['street'] ?>">
                    <span class="invalid-feedback"><?php echo $street_err;?></span>
                </div>
                <div class="form-group col-md-3">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" maxlength="100" class="form-control <?php echo (!empty($city_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $row['city'] ?>">
                    <span class="invalid-feedback"><?php echo $city_err;?></span>
                </div>
                <div class="form-group col-md-2">
                    <label for="postcode">Postcode</label>
                    <input type="text" id="postcode" name="postcode" maxlength="4" class="form-control <?php echo (!empty($postcode_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $row['postcode'] ?>">
                    <span class="invalid-feedback"><?php echo $postcode_err;?></span>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="form-group col-md">
                    <label for="bedrooms">Bedrooms</label>
                    <input type="text" id="bedrooms" name="bedrooms" maxlength="2" class="form-control <?php echo (!empty($bedrooms_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $row['bedrooms'] ?>">
                    <span class="invalid-feedback"><?php echo $bedrooms_err;?></span>
                </div>
                <div class="form-group col-md">
                    <label for="bathrooms">Bathrooms</label>
                    <input type="text" id="bathrooms" name="bathrooms" maxlength="2" class="form-control <?php echo (!empty($bathrooms_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $row['bathrooms'] ?>">
                    <span class="invalid-feedback"><?php echo $bathrooms_err;?></span>
                </div>
                <div class="form-group col-md">
                    <label for="garage">Parking</label>
                    <input type="text" id="garage" name="garage" maxlength="2" class="form-control <?php echo (!empty($garage_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $row['garage'] ?>">
                    <span class="invalid-feedback"><?php echo $garage_err;?></span>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="form-group col-md">
                    <label for="saleType">Sale Type</label>
                    <input type="text" id="saleType" name="saleType" maxlength="20" class="form-control <?php echo (!empty($saleType_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $row['saleType'] ?>">
                    <span class="invalid-feedback"><?php echo $saleType_err;?></span>
                </div>
                <div class="form-group col-md">
                    <label for="price">Price</label>
                    <input type="text" id="price" name="price" class="form-control <?php echo (!empty($price_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $row['price'] ?>">
                    <span class="invalid-feedback"><?php echo $price_err;?></span>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>" name="description" id="description"><?php echo $row['description']; ?></textarea>
                    <span class="invalid-feedback"><?php echo $description_err;?></span>
                </div>
            </div>
            <br>
            <input type="hidden" id="id" name="id" value="<?php echo $property_ID; ?>"/>
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php require_once('../includes/layouts/footer.php'); ?>