<?php
// Start the session to store login information
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $valid_username = 'Admin';
    $valid_password = '1234';

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['logged_in'] = true;
        header('Location: index.php');
        exit();
    } else {
        $error_message = 'Invalid username or password.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - IT Asset Management</title>
    <style>
        body {
            font-family: 'Roboto', Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background-color: #121212;
            color: #ffffff;
            overflow: hidden;
        }

        .matrix-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            grid-template-rows: 1fr auto 1fr;
            pointer-events: none;
            z-index: -1;
        }

        .matrix-row {
            display: flex;
            justify-content: space-around;
            font-family: monospace;
            color: #444444;
            animation: flicker 3s infinite;
        }

        .matrix-row span {
            font-size: 12px;
            white-space: nowrap;
        }

        @keyframes flicker {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .main-header {
            font-size: 24px;
            font-weight: bold;
            color: #4CAF50;
            margin-bottom: 20px;
        }

        .login-box {
            background-color: #1f1f1f;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
            width: 320px;
            text-align: center;
            z-index: 1;
        }

        .login-box h2 {
            margin-bottom: 20px;
            color: #4CAF50;
        }

        .login-box input[type="text"],
        .login-box input[type="password"] {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            background-color: #1e1e1e;
            border: 1px solid #333333;
            border-radius: 4px;
            color: #ffffff;
        }

        .login-box input::placeholder {
            color: #bbbbbb;
        }

        .login-box button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }

        .login-box button:hover {
            background-color: #3e8e41;
        }

        .error {
            color: #ff4f4f;
            margin-top: 10px;
        }
    </style>
</head>
<body>


    <!-- Page Header -->
    <div class="main-header">Inventory Management System Access</div>

    <!-- Login Form -->
    <div class="login-box">
        <h2>Login</h2>
        <form method="POST" action="login.php">
            <input type="text" name="username" placeholder="Username" required>
            <br>
            <input type="password" name="password" placeholder="Password" required>
            <br>
            <button type="submit">Login</button>
        </form>
        <?php
        if (isset($error_message)) {
            echo '<div class="error">' . $error_message . '</div>';
        }
        ?>
    </div>
</body>
</html>
