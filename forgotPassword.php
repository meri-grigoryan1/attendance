<?php
include 'Includes/dbcon.php'; 
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Forgot password page">
  <meta name="author" content="Admin">
  <link href="img/logo/attnlg.jpg" rel="icon">
  <title>Forgot Password</title>
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-login">
  <div class="container-login">
    <div class="row justify-content-center">
      <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card shadow-sm my-5">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-lg-12">
                <div class="login-form">
                  <div class="text-center">
                    <img src="img/logo/attnlg.jpg" style="width:100px;height:100px">
                    <br><br>
                    <h1 class="h4 text-gray-900 mb-4">Forgot Password</h1>
                  </div>
                  <form class="user" method="POST" action="forgotPassword.php">
                    <div class="form-group">
                      <input type="email" class="form-control" required name="email" placeholder="Enter Email Address">
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary btn-block" value="Submit" name="submit" />
                    </div>
                  </form>

                  <?php
                  if (isset($_POST['submit'])) {
                      $email = $_POST['email'];

                      // Validate email format
                      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                          echo "<div class='alert alert-danger'>Invalid email format.</div>";
                      } else {
                          // Prepare the SQL statement to avoid SQL injection
                          $stmt = $conn->prepare("SELECT * FROM tbladmin WHERE emailAddress = ?");
                          $stmt->bind_param("s", $email);
                          $stmt->execute();
                          $result = $stmt->get_result();

                          if ($result->num_rows > 0) {
                              // User exists, generate a secure token
                              $token = bin2hex(random_bytes(50));
                              $expireTime = date("Y-m-d H:i:s", strtotime('+1 hour'));

                              // Store token and expiration time in the database
                              $updateStmt = $conn->prepare("UPDATE tbladmin SET reset_token=?, token_expire=? WHERE emailAddress=?");
                              $updateStmt->bind_param("sss", $token, $expireTime, $email);
                              $updateStmt->execute();

                              // Generate the password reset link
                              $resetLink = "http://localhost/attendance/resetPassword.php?token=" . $token;

                              // Send email
                              $subject = "Password Reset Request";
                              $message = "Click the following link to reset your password: " . $resetLink;
                              $headers = "From: no-reply@yourwebsite.com";

                              if (mail($email, $subject, $message, $headers)) {
                                  echo "<div class='alert alert-success'>A password reset link has been sent to your email.</div>";
                              } else {
                                  echo "<div class='alert alert-danger'>Failed to send the reset email.</div>";
                              }
                          } else {
                              echo "<div class='alert alert-danger'>This email address is not registered.</div>";
                          }
                      }
                  }
                  ?>

                  <hr>
                  <div class="text-center">
                    <a class="font-weight-bold small" href="memberSetup.php">Create a Member Account!</a>
                    <a class="font-weight-bold small" href="organizationSetup.php">Setup Cooperative Account!</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap and JS libraries -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
</body>
</html>
