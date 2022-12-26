<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html');
    exit;
}

// Include config file
require_once "form/config.php";

// We don't have the password or email info stored in sessions so instead we can get the results from the database.
$stmt1 = $link->prepare('SELECT password, email, address FROM user WHERE id = ?');

$stmt1->bind_param('i', $_SESSION['id']);
$stmt1->execute();
$stmt1->bind_result($password, $email, $address);
$stmt1->fetch();
$stmt1->close();

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->

    <link href="css/user_details.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
</head>

<body class="loggedin">
    <nav class="navbar navbar-expand-lg navbar  navbar-dark" style="background-color: rgb(37, 59, 49);"">
        <div class=" container-fluid">

        <a class=" navbar-brand font_title" href="#" onclick="reloadPage()">RBL Register</a>


        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="nav navbar-nav m-auto">


                <li class="nav-item p-2">
                    <a href="home.php" class="nav-link "><i class="fa fa-home"></i> Home</a>
                </li> >
                <li class="nav-item p-2">
                    <a href="form/create.php" class="nav-link "><i class="fab fa-wpforms"></i> Form</a>
                </li>
                <li class="nav-item p-2">
                    <a href="profile.php" class="nav-link "><i class="fas fa-user-circle"></i> Profile</a>
                </li>
                <li class="nav-item p-2">
                    <a href="logout.php" class="nav-link "><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
            </ul>
        </div>
        </div>
    </nav>
    <div class="content">
        <h2 class="m-auto p-2">Profile Page</h2>
        <div>
            <p class="m-auto">Your account details are below:</p>
            <table>
                <tr>
                    <td>Username:</td>
                    <td>
                        <?= $_SESSION['name'] ?>
                    </td>
                </tr>
                <tr>
                    <td>Password:</td>
                    <td>
                        <?= $password ?>
                    </td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td>
                        <?= $email ?>
                    </td>
                </tr>
                <tr>
                    <td>Address:</td>
                    <td>
                        <?= $address ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function reloadPage() {
            location.reload(true);
        }
    </script>
</body>

</html>