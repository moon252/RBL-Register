<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html');
    exit;
}

require_once "config.php";


$stmt1 = $link->prepare('SELECT password, email, address FROM user WHERE id = ?');

$stmt1->bind_param('i', $_SESSION['id']);
$stmt1->execute();
$stmt1->bind_result($password, $email, $address);
$stmt1->fetch();
$stmt1->close();


$file_name = $file_type = $from_address = $to_address = $date = "";
$file_name_err = $file_type_err = $from_address_err = $to_address_err = $date_err = "";


// Processing form data when form is submitted
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    // Get hidden input value
    $id = $_POST["id"];
    // Validate name
    $input_file_name = trim($_POST["file_name"]);
    if (empty($input_file_name)) {
        $file_name_err = "Please enter the file name.";
    } elseif (!filter_var($input_file_name, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z\s]+$/")))) {
        $file_name_err = "Please enter a valid first name.";
    } else {
        $file_name = $input_file_name;
    }


    $input_file_type = trim($_POST["file_type"]);
    $file_type = $input_file_type;



    $input_from_address = trim($_POST["from_address"]);
    $from_address = $input_from_address;


    $input_to_address = trim($_POST["to_address"]);
    $to_address = $input_to_address;



    //validate date
    $input_date = trim($_POST["date"]);
    if (empty($input_date)) {
        $date_err = "Please enter date in dd/mm/yyyy";
    } elseif (!filter_var($input_date, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$/")))) {
        $date_err = "Please enter a valid date.";
    } else {
        $date = $input_date;
    }



    // Check input errors before inserting in database
    if (empty($file_name_err) && empty($file_type_err) && empty($from_address_err) && empty($to_address_err) && empty($date_err)) {

        $sql = "UPDATE form SET file_name=?, file_type=?,from_address=?, to_address=?,date=? WHERE Id=?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssi", $param_file_name, $param_file_type, $param_from_address, $param_to_address, $param_date, $param_id);

            $param_file_name = $file_name;
            $param_file_type = $file_type;
            $param_from_address = $from_address;
            $param_to_address = $to_address;
            $param_date = $date;
            $param_id = $id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records created successfully. Redirect to landing page
                header("location: ../home.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
} else {
    // Check existence of id parameter before processing further
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {

        $id = trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM form WHERE Id = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Set parameters
            $param_id = $id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $file_name = $row["file_name"];
                    $file_type = $row["file_type"];
                    $from_address = $row["from_address"];
                    $to_address = $row["to_address"];
                    $date = $row["date"];

                } else {
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }

            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

        // Close connection
        mysqli_close($link);
    } else {
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper {
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="wrapper mb-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">File Transfer Form</h2>
                    <p>Please fill this form and submit to send file info.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                        <div class="form-group">
                            <label class="font-weight-bold">File Name</label>
                            <input type="text" name="file_name"
                                class="form-control <?php echo (!empty($file_name_err)) ? 'is-invalid' : ''; ?>"
                                value="<?php echo $file_name; ?>">
                            <span class="invalid-feedback">
                                <?php echo $file_name_err; ?>
                            </span>
                        </div>



                        <div class="form-group">
                            <label class="font-weight-bold">Select File Type</label>

                            <select class="form-control" name="file_type">
                                <option>Letter</option>
                                <option>Notice</option>
                                <option>aaa</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">From</label>
                            <input class="form-control" type="text" name="from_address" value="<?= $address ?>"
                                readonly>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Select Where To Send </label>
                            <select class="form-control" name="to_address">
                                <option>ICT Division, Head Office, RBL</option>
                                <option>ICT peration, Head Office, RBL</option>
                                <option>HR, Head Office, RBL</option>
                            </select>
                        </div>



                        <div class="form-group">
                            <label class="font-weight-bold">Date</label>
                            <textarea name="date" placeholder="dd/mm/yyyy"
                                class="form-control <?php echo (!empty($date_err)) ? 'is-invalid' : ''; ?>"><?php echo $date; ?></textarea>
                            <span class="invalid-feedback">
                                <?php echo $date_err; ?>
                            </span>
                        </div>

                        <input type="hidden" name="id" value="<?php echo $id; ?>" />



                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="../home.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>