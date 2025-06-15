<?php
session_start();
$message = "";

// Database connection with your credentials
$host = 'localhost';
$db   = 'travel_booking';
$user = 'root';
$pass = 'Shapy_tot1';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
];

$messageType = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];

    // Basic validation
    if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
        $message = "Please fill in all fields.";
        $messageType = "error";
    } elseif ($password !== $confirmPassword) {
        $message = "Passwords do not match.";
        $messageType = "error";
    } elseif (strlen($password) < 6) {
        $message = "Password must be at least 6 characters long.";
        $messageType = "error";
    } else {
        try {
            $pdo = new PDO($dsn, $user, $pass, $options);

            // Check if email already exists
            $sql = "SELECT email FROM User WHERE email = :email LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':email' => $email]);
            $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existingUser) {
                $message = "This email address is already registered.";
                $messageType = "error";
            } else {
                // Insert new user (storing password as plain text to match your system)
                $sql = "INSERT INTO User (name, email, password) VALUES (:name, :email, :password)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':name' => $name,
                    ':email' => $email,
                    ':password' => $password
                ]);

                $message = "Account created successfully! Welcome " . htmlspecialchars($name) . "!";
                $messageType = "success";
                
                // Auto-login the new user
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['user_name'] = $name;
                
                // Redirect to homepage after 2 seconds
                header("refresh:5;url=index.html");
            }
        } catch (PDOException $e) {
            $message = "Database connection error: " . htmlspecialchars($e->getMessage());
            $messageType = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - EasyTravel</title>
    <style>
        body {
            margin: 0;
            font-family: 'Georgia', serif;
            background: linear-gradient(135deg, #f8f5f2 0%, #e8ddd4 100%);
            color: #3b2f2f;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header {
            background-color: #fff8f4;
            padding: 1.2rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e4dcd4;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .logo {
            font-family: 'Georgia', serif;
            font-size: 1.8rem;
            font-weight: bold;
            color: #8b3e2f;
        }

        .nav-links a {
            margin-left: 1rem;
            text-decoration: none;
            color: #5a3b2e;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: #8b3e2f;
        }

        .register-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }

        .register-card {
            background: #fff8f4;
            padding: 3rem 2.5rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(139, 62, 47, 0.1);
            width: 100%;
            max-width: 450px;
            text-align: center;
        }

        .register-card h1 {
            font-family: 'Georgia', serif;
            font-size: 2.2rem;
            color: #7c4229;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }

        .register-card p {
            color: #5a3b2e;
            margin-bottom: 2rem;
            font-size: 1.1rem;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #5a3b2e;
            font-weight: 500;
            font-size: 1rem;
        }

        .form-group input {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 2px solid #e4dcd4;
            border-radius: 10px;
            font-size: 1rem;
            font-family: 'Georgia', serif;
            background-color: #f8f5f2;
            color: #3b2f2f;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        .form-group input:focus {
            outline: none;
            border-color: #7c4229;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(124, 66, 41, 0.1);
        }

        .register-btn {
            width: 100%;
            background: linear-gradient(135deg, #8b3e2f, #7c4229);
            color: white;
            border: none;
            padding: 0.9rem 1.5rem;
            border-radius: 10px;
            font-size: 1.1rem;
            font-family: 'Georgia', serif;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
        }

        .register-btn:hover {
            background: linear-gradient(135deg, #7c4229, #6b3423);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(139, 62, 47, 0.3);
        }

        .register-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .login-link {
            color: #7c4229;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .login-link:hover {
            color: #8b3e2f;
            text-decoration: underline;
        }

        .message {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-weight: 500;
            animation: fadeIn 0.5s ease-in;
            text-align: left;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .back-btn {
            background-color: #e4dcd4;
            color: #5a3b2e;
            border: none;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            margin-top: 1rem;
            transition: all 0.3s ease;
            font-weight: 500;
            font-family: 'Georgia', serif;
        }

        .back-btn:hover {
            background-color: #d4c7b8;
            transform: translateY(-1px);
        }

        footer {
            text-align: center;
            padding: 1.5rem;
            font-size: 0.9rem;
            background-color: #fff8f4;
            color: #7a5a4e;
            border-top: 1px solid #e4dcd4;
        }

        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s ease-in-out infinite;
            margin-left: 10px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .password-requirements {
            font-size: 0.85rem;
            color: #7a5a4e;
            margin-top: 0.3rem;
            font-style: italic;
        }

        @media (max-width: 600px) {
            .register-card {
                padding: 2rem 1.5rem;
                margin: 1rem;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">EasyTravel</div>
        <div class="nav-links">
            <a href="index.html">Home</a>
            <a href="inspiration.html">Inspirations</a>
            <a href="login.php">Log In</a>
        </div>
    </header>

    <div class="register-container">
        <div class="register-card">
            <h1>Create Account</h1>
            <p>Join EasyTravel and start planning your perfect journey today!</p>

            <?php if ($message): ?>
                <div class="message <?php echo $messageType; ?>">
                    <?php echo htmlspecialchars($message); ?>
                    <?php if ($messageType === 'success'): ?>
                        <span class="loading"></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" id="registerForm">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" required 
                           value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"
                           placeholder="Enter your full name">
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required 
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                           placeholder="Enter your email address">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required
                               placeholder="Create password">
                        <div class="password-requirements">At least 6 characters</div>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required
                               placeholder="Confirm password">
                    </div>
                </div>

                <button type="submit" class="register-btn" id="submitBtn">Create Account</button>
            </form>

            <p>Already have an account? 
               <a href="login.php" class="login-link">Log In</a>
            </p>

            <a href="index.html" class="back-btn">← Back to Home</a>
        </div>
    </div>

    <footer>
        © 2025 EasyTravel — All rights reserved.
    </footer>

    <script>
        // Auto-focus on name field when page loads
        window.onload = function() {
            document.getElementById('name').focus();
        };

        // Password confirmation validation
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (password !== confirmPassword && confirmPassword !== '') {
                this.style.borderColor = '#dc3545';
                this.style.backgroundColor = '#fff5f5';
            } else {
                this.style.borderColor = '#e4dcd4';
                this.style.backgroundColor = '#f8f5f2';
            }
        });

        // Add loading state to button on form submission
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                return;
            }
            
            const btn = document.getElementById('submitBtn');
            btn.innerHTML = 'Creating Account... <span class="loading"></span>';
            btn.disabled = true;
        });

        // Real-time password strength indicator
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const requirements = document.querySelector('.password-requirements');
            
            if (password.length >= 6) {
                requirements.style.color = '#28a745';
                requirements.innerHTML = '✓ Password meets requirements';
            } else {
                requirements.style.color = '#7a5a4e';
                requirements.innerHTML = 'At least 6 characters';
            }
        });
    </script>
</body>
</html>
