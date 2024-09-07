<?php
session_start();
include 'config.php'; // Make sure this includes the connection to your database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Replace this with your actual query to validate the user
    $stmt = $conn->prepare("SELECT user_id, username, password_hash, role FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $db_username, $db_password_hash, $role);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $db_password_hash)) {
            // Successful login
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $db_username;
            $_SESSION['role'] = $role;
            
            // Redirect to the original page the user was trying to access
            if (isset($_SESSION['redirect_url'])) {
                $redirect_url = $_SESSION['redirect_url'];
                unset($_SESSION['redirect_url']); // Clear the session variable
                header("Location: $redirect_url");
            } else {
                header("Location: index.php"); // Default redirect
            }
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Invalid username or password.";
    }
    $stmt->close();
}
 elseif (isset($_POST['register'])) {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $role = $_POST['role'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

    // Check if the username already exists
    $query = "SELECT * FROM UserAccount WHERE username = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $register_error = "Username already exists. Please choose a different one.";
    } else {
        // Insert into UserAccount table
        $query = "INSERT INTO UserAccount (username, password, role) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sss', $username, $password, $role);
        $stmt->execute();

        $user_id = $stmt->insert_id; // Get the last inserted ID

        // Insert into the appropriate role-specific table
        if ($role == 'Farmer') {
            $query = "INSERT INTO Farmer (name, mobile_phone) VALUES (?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ss', $name, $contact);
        } elseif ($role == 'ExtensionOfficer') {
            $query = "INSERT INTO ExtensionOfficer (name, contact_info) VALUES (?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ss', $name, $contact);
        } elseif ($role == 'Administrator') {
            $query = "INSERT INTO Administrator (name, contact_info) VALUES (?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ss', $name, $contact);
        }

        if ($stmt->execute()) {
            // Registration success
            header("Location: index.php"); // Redirect to the home page or any other page
            exit();
        } else {
            $register_error = "Registration failed. Please try again.";
        }
    }
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <style>
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

.container {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    width: 300px;
}

h2 {
    margin-top: 0;
    color: #333;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    color: #555;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

.form-group input:focus,
.form-group select:focus {
    border-color: #007BFF;
}

.btn {
    width: 100%;
    padding: 10px;
    border: none;
    border-radius: 4px;
    background-color: #007BFF;
    color: white;
    font-size: 16px;
    cursor: pointer;
}

.btn:hover {
    background-color: #0056b3;
}

.link {
    display: block;
    margin-top: 10px;
    text-align: center;
    color: #007BFF;
    text-decoration: none;
}

.link:hover {
    text-decoration: underline;
}

.tabs {
    display: flex;
    justify-content: space-around;
    margin-bottom: 20px;
}

.tabs button {
    background: none;
    border: none;
    font-size: 16px;
    cursor: pointer;
    padding: 10px;
    color: #007BFF;
}

.tabs button.active {
    border-bottom: 2px solid #007BFF;
}
    </style>
</head>
<body>
    <div class="container">
        <div class="tabs">
            <button class="active" onclick="showForm('login')">Login</button>
            <button onclick="showForm('register')">Register</button>
            <button onclick="showForm('recover')">Recover</button>
        </div>
        
        <?php if (isset($login_error)) echo "<div class='error'>$login_error</div>"; ?>
        <form id="loginForm" method="POST">
            <h2>Login</h2>
            <div class="form-group">
                <label for="loginUsername">Username</label>
                <input type="text" id="loginUsername" name="username" required>
            </div>
            <div class="form-group">
                <label for="loginPassword">Password</label>
                <input type="password" id="loginPassword" name="password" required>
            </div>
            <button type="submit" name="login" class="btn">Login</button>
            <a href="javascript:void(0);" onclick="showForm('recover')" class="link">Forgot password?</a>
        </form>

        <?php if (isset($register_error)) echo "<div class='error'>$register_error</div>"; ?>
        <form id="registerForm" method="POST" style="display:none;">
            <h2>Register</h2>
            <div class="form-group">
                <label for="registerName">Name</label>
                <input type="text" id="registerName" name="name" required>
            </div>
            <div class="form-group">
                <label for="registerContact">Contact Information</label>
                <input type="text" id="registerContact" name="contact" required>
            </div>
			<div class="form-group">
				<label for="registerRole">Role</label>
				<select id="registerRole" name="role" required>
					<option value="Farmer">Farmer</option>
					<option value="ExtensionOfficer">Extension Officer</option>
					<option value="Administrator">Administrator</option>
				</select>
			</div>
            <div class="form-group">
                <label for="registerUsername">Username</label>
                <input type="text" id="registerUsername" name="username" required>
            </div>
            <div class="form-group">
                <label for="registerPassword">Password</label>
                <input type="password" id="registerPassword" name="password" required>
            </div>
            <button type="submit" name="register" class="btn">Register</button>
        </form>

        <form id="recoverForm" method="POST" style="display:none;">
            <h2>Password Recovery</h2>
            <div class="form-group">
                <label for="recoverContact">Enter your email or phone number</label>
                <input type="text" id="recoverContact" name="contact" required>
            </div>
            <button type="submit" name="recover" class="btn">Recover Password</button>
        </form>
    </div>

    <script>
        function showForm(form) {
            document.getElementById('loginForm').style.display = 'none';
            document.getElementById('registerForm').style.display = 'none';
            document.getElementById('recoverForm').style.display = 'none';
            
            document.getElementById(form + 'Form').style.display = 'block';

            const tabs = document.querySelectorAll('.tabs button');
            tabs.forEach(tab => tab.classList.remove('active'));
            document.querySelector(`.tabs button[onclick="showForm('${form}')"]`).classList.add('active');
        }
    </script>
</body>
</html>
