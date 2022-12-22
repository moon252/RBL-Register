<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html');
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel=”stylesheet” href=”css/bootstrap-responsive.css”>


    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->
    <link rel="stylesheet" href="css/home_style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel=”stylesheet” href=”css/bootstrap-responsive.css”>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">




    <style>
        .wrapper {

            margin: auto;
            margin-top: 5px;
            max-width: 750px;
            background-color: lightgray;

        }

        table tr td:last-child {
            max-width: 600px;
        }
    </style>
    <script>
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</head>

<body class="loggedin m-auto">


    <nav class="navbar navbar-expand-lg navbar-dark bg-dark navbar-inverse">
        <div class="container-fluid">
            <a class=" navbar-brand font_title " href="#" onclick="reloadPage()">RBL Register</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="nav navbar-nav m-auto">
                    <li class="nav-item p-2">
                        <a href="form/create.php" class="nav-link "><i class="fab fa-wpforms"></i> Form</a>
                    </li> >

                    <li class="nav-item p-2">
                        <a href="profile.php" class="nav-link"><i class="fas fa-user-circle"></i> Profile</a>
                    </li>
                    <li class="nav-item p-2">
                        <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>



    <div class="content">
        <h2 class="p-2">Home Page</h2>
        <p>Welcome back, <?= $_SESSION['name'] ?>!</p>
    </div>

    <div>
        <div class="container-fluid px-2">
            <div class="row text-center">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">File Transfer Details</h2>
                        <a href="form/create.php" class="btn btn-dark pull-right"><i class="fa fa-plus"></i> Send New
                            File</a>
                    </div>
                    <?php
                    // Include config file
                    require_once "form/config.php";

                    // Attempt select query execution
                    $sql = "SELECT * FROM form";
                    if ($result = mysqli_query($link, $sql)) {
                        if (mysqli_num_rows($result) > 0) {
                            echo '<table class="table table-dark table-striped table-bordered  table-hover table-responsive-md">';
                            echo "<thead>";
                            echo "<tr>";
                            echo "<th>#</th>";
                            echo "<th>File Name</th>";
                            echo "<th>File Type</th>";
                            echo "<th>From</th>";
                            echo "<th>To</th>";
                            echo "<th>Date</th>";
                            echo "<th>Status</th>";
                            echo "<th>Action</th>";
                            echo "</tr>";
                            echo "</thead>";

                            echo "<tbody>";
                            while ($row = mysqli_fetch_array($result)) {
                                echo "<tr>";

                                echo "<td>" . $row['Id'] . "</td>";
                                echo "<td>" . $row['file_name'] . "</td>";
                                echo "<td>" . $row['file_type'] . "</td>";
                                echo "<td>" . $row['from_address'] . "</td>";
                                echo "<td>" . $row['to_address'] . "</td>";
                                echo "<td>" . $row['date'] . "</td>";
                                echo "<td>" . "</td>";
                                echo "<td>";

                                echo '<a href="form/update.php?id=' . $row['Id'] . '" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil" style="color:white;"></span></a>';
                                echo '<a href="form/delete.php?id=' . $row['Id'] . '" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash" style="color:white;"></span></a>';
                                echo "</td>";

                                echo "</tr>";
                            }
                            echo "</tbody>";

                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else {
                            echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                        }
                    } else {
                        echo "Oops! Something went wrong. Please try again later.";
                    }

                    // Close connection
                    mysqli_close($link);
                    ?>
                </div>
            </div>
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