<!-- 
	Student Name: Qiaoqing Wu
	Due Date: 2023-04-10
	Section: CST8285 Lab section 313
	Description: a create page to submit a new record
-->
<?php
// Include employeeDAO file
require_once('./dao/employeeDAO.php');

// Define variables and initialize with empty values
$name = $birthdate = $address = $salary = $image = "";
$name_err = $birthdate_err = $address_err = $salary_err = $image_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
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

    // Validate address
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
    if (!empty($_FILES["profile_img"]["name"])) {
        $target_dir = "images/";
        $target_file = $target_dir . basename($_FILES["profile_img"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["profile_img"]["tmp_name"]);
    
        if ($check !== false) {
            if (move_uploaded_file($_FILES["profile_img"]["tmp_name"], $target_file)) {
                $image = basename($_FILES["profile_img"]["name"]);
            } else {
                $image_err = "Sorry, there was an error uploading your file.";
            }
        } else {
            $image_err = "File is not an image.";
        }
    } else {
        $image_err = "Please upload a profile image.";
    }

    // Check input errors before inserting in database
    if(empty($name_err) && empty($birthdate_err) && empty($address_err) && empty($salary_err) && empty($image_err)){
        $employeeDAO = new employeeDAO();    
        $employee = new Employee(0, $name, $birthdate, $address, $salary, $image);
        $addResult = $employeeDAO->addEmployee($employee);        
        header( "refresh:2; url=index.php" ); 
		echo '<br><h6 style="text-align:center">' . $addResult . '</h6>';   
        // Close connection
        $employeeDAO->getMysqli()->close();
        }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Qiaoqing Wu">
    <title>Create Record</title>
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
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add employee record to the database.</p>
					
					<!--the following form action, will send the submitted form data to the page itself ($_SERVER["PHP_SELF"]), instead of jumping to a different page.-->
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
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
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
        <?include 'footer.php';?>
    </div>
</body>
</html>