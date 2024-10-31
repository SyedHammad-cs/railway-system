

<?php
$servername = "localhost";
$username = "root";  
$password = "";  
$port = 4306;
$dbname = "railway";

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = $_POST['fullName'];
    $user = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phoneNumber'];
    $pass = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $gender = $_POST['gender'];

    $sql = "INSERT INTO signup (fullName, username, email, phoneNumber, password, gender)
            VALUES ('$fullName', '$user', '$email', '$phone', '$pass', '$gender')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
          // Redirect to index.html after successful signup
          header("Location: index.html");
          exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="signup.css">
</head>
<body>
    <header>
    <p class="RRS">RRS</p>
        <nav>
            <ul>
                <li><a href="index.html#home">Home</a></li>
                <li><a href="">Contact Us</a></li>
            </ul>
        </nav>
        <div class="account">
        <button class="btn"><a href="http://localhost/railway%20system/login.php">Login | Signup</a></button>
        </div>
    </header>
    <div class="container">
        <h1 class="form-title">Sign Up</h1>
        <form action="signup.php" method="POST">
            <div class="main-user-info">
                <div class="user-input-box">
                    <label for="fullName">Full Name</label>
                    <input type="text" id="fullName" name="fullName" placeholder="Enter Full Name" required>
                </div>
                <div class="user-input-box">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter Username" required>
                </div>
                <div class="user-input-box">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter Email" required>
                </div>
                <div class="user-input-box">
                    <label for="phoneNumber">Phone Number</label>
                    <input type="text" id="phoneNumber" name="phoneNumber" placeholder="Enter Phone Number" required>
                </div>
                <div class="user-input-box">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter Password" required>
                </div>
                <div class="user-input-box">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required>
                </div>
            </div>
            <div class="gender-details-box">
                <span class="gender-title">Gender</span>
                <div class="gender-category">
                    <input type="radio" name="gender" id="male" value="Male">
                    <label for="male">Male</label>
                    <input type="radio" name="gender" id="female" value="Female">
                    <label for="female">Female</label>
                    <input type="radio" name="gender" id="other" value="Other">
                    <label for="other">Other</label>
                </div>
            </div>
            <div class="form-submit-btn">
                <input type="submit" value="Sign Up">
            </div>
        </form>
    </div>
</body>
</html>

