<!-- 
	Student Name: Qiaoqing Wu
	Due Date: 2023-04-10
	Section: CST8285 Lab section 313
	Description: a update page to update a exist record
-->
<?php
// Include employeeDAO file
require_once('./dao/employeeDAO.php');

// Define variables and initialize with empty values
$name = $birthdate = $address = $salary = $image = "";
$name_err = $birthdate_err = $address_err = $salary_err = $image_err = "";
$employeeDAO = new employeeDAO(); 

// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name, letters only.";
    } else{
        $name = $input_name;
    }
    
    // Validate birthdate
    $input_birthdate = trim($_POST["birthdate"]);
    $birthdateObject = new DateTime($input_birthdate);
    $compareDate = new DateTime('2005-01-01');
    if(empty($input_birthdate)){
        $birthdate_err = "Please enter a birthdate.";
    } elseif($compareDate < $birthdateObject){
        $birthdate_err = "Please enter a valid birthdate less than 2005-01-01.";
    } else{
        $birthdate = $input_birthdate;
    }

    // Validate address address
    $input_address = trim($_POST["address"]);
    if(empty($input_address)){
        $address_err = "Please enter an address. It should not be empty."; 
    } else{
        $address = $input_address;
    }
    
    // Validate salary
    $input_salary = trim($_POST["salary"]);
    $min_salary = 1000;
    $max_salary = 10000;
    if(empty($input_salary)){
        $salary_err = "Please enter the salary amount.";     
    } elseif(!ctype_digit($input_salary)){
        $salary_err = "Please enter a positive integer value.";
    } elseif ($input_salary < $min_salary || $input_salary > $max_salary) {
        $salary_err = "Please enter a salary between $min_salary and $max_salary.";
    } else{
        $salary = $input_salary;
    }
    
    // Validate image
    $old_image = $_POST["old_image"];
    $newImageUploaded = false;
    if (!empty($_FILES["profile_img"]["name"])) {
        $target_dir = "images/";
        $target_file = $target_dir . basename($_FILES["profile_img"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["profile_img"]["tmp_name"]);
    
        if ($check !== false) {
            if (move_uploaded_file($_FILES["profile_img"]["tmp_name"], $target_file)) {
                $image = basename($_FILES["profile_img"]["name"]);
                $newImageUploaded = true;
            } else {
                $image_err = "Sorry, there was an error uploading your file.";
            }
        } else {
            $image_err = "File is not an image.";
        }
    }
    // Check if a new image is uploaded, if not, keep the old image
    if (!$newImageUploaded) {
        $image = $old_image;
    }

    // Check input errors before inserting in database
    if(empty($name_err) && empty($birthdate_err) && empty($address_err) && empty($salary_err) && empty($image_err)){
        $employee = new Employee($id, $name, $birthdate, $address, $salary, $image);
        $result = $employeeDAO->updateEmployee($employee);        
		header("refresh:2; url=index.php");
		echo '<br><h6 style="text-align:center">' . $result . '</h6>';
        // Close connection
        $employeeDAO->getMysqli()->close();
    }

} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        $employee = $employeeDAO->getEmployee($id);
                
        if($employee){
            // Retrieve individual field value
            $name = $employee->getName();
            $birthdate = $employee->getBirthdate();
            $address = $employee->getAddress();
            $salary = $employee->getSalary();
            $image = $employee->getImage();
        } else{
            // URL doesn't contain valid id. Redirect to error page
            header("location: error.php");
            exit();
        }
    } else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
    // Close connection
    $employeeDAO->getMysqli()->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Qiaoqing Wu">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Update Record</h2>
                    <p>Please edit the input values and submit to update the employee record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Birthdate</label>
                            <input type="date" name="birthdate" class="form-control <?php echo (!empty($birthdate_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $birthdate; ?>">
                            <span class="invalid-feedback"><?php echo $birthdate_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>"><?php echo $address; ?></textarea>
                            <span class="invalid-feedback"><?php echo $address_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Salary</label>
                            <input type="text" name="salary" class="form-control <?php echo (!empty($salary_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $salary; ?>">
                            <span class="invalid-feedback"><?php echo $salary_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Profile Image</label>
                            <input type="file" accept="image/*" name="profile_img" class="form-control <?php echo (!empty($image_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $image; ?>">
                            <span class="invalid-feedback"><?php echo $image_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="hidden" name="old_image" value="<?php echo $image; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>