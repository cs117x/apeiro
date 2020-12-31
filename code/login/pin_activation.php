<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<link rel="stylesheet" href="../lib/mdl/material.min.css">
	<script src="../lib/mdl/material.min.js"></script>
	<link rel="stylesheet" type="text/css" href="login.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<body>
	<h3> Verifying... </h3>

<?php
/*

	Resources:

	-	https://www.w3schools.com/php/php_form_url_email.asp
	-	https://regex101.com 
	-	https://phpdelusions.net/pdo
	-	https://stackoverflow.com

*/

require '../includes/database.php';

#Required to connect to database, variable needs to be in the global scope.
global $conn;

function redirect($time, $location) {
	if ($location == 'login') {
		echo "
  		<script> 	
  			setTimeout(function () {
  				window.location.replace(\"https://apeiro.network/login\");
  			}, $time);
  		</script>
  		";
	}

	else {
		echo "
  		<script> 	
  			setTimeout(function () {
  				window.location.replace(\"https://apeiro.network/$location\");
  			}, $time);
  		</script>
  		";
	}
}


function activate_pin()
{

    $employee_number = strval($_POST["employee_number"]);
    $password = $_POST["password"];

    #var_dump($employee_number, $password);

    /*/
    
    Regex Definition
    
    |/^[0-9]{8}/| = {Integers only, length(8)}
    |/^[[:word:]]{8,36}/| = {Characters, length(8-36)}
    
    /*/

    if (!preg_match('/^[0-9]{8}/', $employee_number) || !preg_match('/^[[:word:]]{8,36}/', $password))
    {
    	#Data is invalid, explain to user then redirect.
        echo "<p> Validation error </p> <il> Your employee number needs to be: <br> <li> 8 Numbers long </li> <br> <il> Your password needs to be: </il> <br> <li> Letters and Symbols </li> <li> Between 8 and 36 characters long </li>";
        redirect(8500, 'login');
    }
    else
    {
        #Data is valid, query the database
        #echo "This data is valid \n";

        #Required to access the database
        global $conn;

        #Using prepared statements to prevent attacks and code clarity
        $stm_find_employee = $conn->prepare("SELECT * FROM users WHERE employee_number = :employee_number");
        $stm_activate_pin = $conn->prepare("UPDATE users SET pin_active = 1 WHERE employee_number = :employee_number");
        $stm_failed_attempt = $conn->prepare("UPDATE users SET login_attempts = login_attempts + 1 WHERE employee_number = :employee_number");
        $stm_reset_attempts = $conn->prepare("UPDATE users SET login_attempts = 0 WHERE employee_number = :employee_number");

        #Bind parameter ':employee_number' to corresponding variable $employee_number
        #Execute statement
        $stm_find_employee->execute(array(
            ':employee_number' => $employee_number
        ));

       	#Define the output and print.
        $output = $stm_find_employee->fetchAll();
        $attempts = $output[0]["login_attempts"];

        if ($password == $output[0]["password"] && $attempts < 5)
        {

           #Password is correct, activate pin and reset login attempts
            $stm_activate_pin->execute(array(
                ':employee_number' => $employee_number
            ));
            $stm_reset_attempts->execute(array(
                'employee_number' => $employee_number
            ));

            #Successful login, redirect to dashboard
            echo "<p> Successful Login, pin is now active. Redirecting.... </p>";
            redirect(2500, 'index.php');

        }
        else
        {

            #Password is incorrect, increment login_attempts by 1
            $stm_failed_attempt->execute(array(
                ':employee_number' => $employee_number
            ));


            if ($attempts >= 5)
            {
                #Too many attempts made, account is now locked 
                echo "<p> This account is locked. </br> There have been too many attempts made to login to this account, A  reset from your manager is needed </p>";
                redirect(6500, "login");

            }
            else
            {

                $attempts_left = 5 - $attempts;

                #Password is incorrect, 'you have x tries left'
                echo "<p> Incorrect password, you only have $attempts_left attempts left to login. Redirecting... </p>";
                redirect(6500, "login");

            }
        }
    }
}

activate_pin()

?>



</body>
</html>
