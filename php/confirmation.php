<?php

session_start();
include('dbcon.php');

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $verify_query = "SELECT reference, confirmation FROM users WHERE reference='$token' LIMIT 1";
    $verify_query_run = mysqli_query($con, $verify_query);

    if (mysqli_num_rows($verify_query_run) > 0) {
        $row = mysqli_fetch_array($verify_query_run);
        
        if ($row['confirmation'] == 0) {
            $clicked_token = $row['reference'];
            $update_query = "UPDATE users SET confirmation='1' WHERE reference='$clicked_token' LIMIT 1";
            $update_query_run = mysqli_query($con, $update_query);

            if ($update_query_run) {
                $_SESSION['status'] = "Application Confirmed.";
                $_SESSION['status_code'] = "success";
                header("Location: emailtester.php");
                exit(0);
            }
            
            else {
                $_SESSION['status'] = "Confirmation Error.";
                $_SESSION['status_code'] = "error";
                header("Location: emailtester.php");
                exit(0);
            }
        }

        else {
            $_SESSION['status'] = "Already confirmed.";
            $_SESSION['status_code'] = "error";
            header("Location: emailtester.php");
            exit(0);
        }
    }

    else {
        $_SESSION['status'] = "Invalid token.";
        $_SESSION['status_code'] = "error";
        header("Location: emailtester.php");
        exit(0);
    }
}

else {
    $_SESSION['status'] = "Not allowed.";
    $_SESSION['status_code'] = "error";
    header("Location: emailtester.php");
    exit(0);
}

?>