<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "task27";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inputUsername = $_POST['username'];
    $inputPassword = $_POST['password'];

    // Fetch the user from the database
    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $inputUsername);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($storedPassword);
        $stmt->fetch();

        // Verify the password (without hashing)
        if ($inputPassword === $storedPassword) {
            // Password is correct, set session variables
            $_SESSION['logged_in'] = true;
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Invalid username or password.";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* General Body Styling */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #00c6ff, #0072ff);
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #fff;
        }

        /* Container Styling */
        .container {
            width: 100%;
            max-width: 400px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            padding: 40px;
            margin: 20px auto;
            position: relative;
            overflow: hidden;
            text-align: center;
        }

        /* Header Styling */
        h2 {
            color: #333;
            font-size: 30px;
            margin-bottom: 20px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        /* Form Styling */
        form input[type="text"],
        form input[type="password"] {
            width: 100%;
            padding: 12px 20px;
            margin: 10px 0;
            border: 2px solid #0072ff;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px;
            background-color: #f9f9f9;
            outline: none;
            transition: border-color 0.3s ease;
        }

        form input[type="text"]:focus,
        form input[type="password"]:focus {
            border-color: #00c6ff;
        }

        form input[type="submit"] {
            background-color: #0072ff;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            width: 100%;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        form input[type="submit"]:hover {
            background-color: #00c6ff;
            transform: scale(1.05);
        }

        form p {
            margin-top: 15px;
            color: red;
            font-size: 14px;
            text-align: center;
        }

        /* Responsive Layout */
        @media (max-width: 768px) {
            .container {
                padding: 30px;
            }

            form input[type="text"],
            form input[type="password"],
            form input[type="submit"] {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login Bre</h2>
        <form action="" method="POST">
            <input type="text" id="username" name="username" placeholder="Username" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <input type="submit" value="Login">
            <?php if (isset($error)) echo "<p>$error</p>"; ?>
        </form>
    </div>
</body>
</html>
