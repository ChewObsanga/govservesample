<?php
/**
 * Admin Account Creation Script
 * 
 * IMPORTANT: This script should only be accessible to authorized personnel
 * and should be removed or secured after initial setup.
 * 
 * Usage: Run this script once to create the first admin account
 */

session_start();
require_once '../config/database.php';

// Basic security - you can enhance this with additional checks
$authorized = false;

// Option 1: Check if running from command line (recommended for initial setup)
if (php_sapi_name() === 'cli') {
    $authorized = true;
}

// Option 2: Check for a secret key (less secure, but useful for web access)
if (isset($_GET['key']) && $_GET['key'] === 'caloocan2025') {
    $authorized = true;
}

// Option 3: Check if user is already logged in as admin
if (isset($_SESSION['user_id']) && isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin') {
    $authorized = true;
}

if (!$authorized) {
    die('Access denied. This script is for authorized personnel only.');
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $userType = $_POST['user_type'] ?? 'staff';
    
    // Validation
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword) || empty($firstName) || empty($lastName)) {
        $error = 'All required fields must be filled.';
    } elseif (strlen($username) < 3 || strlen($username) > 50) {
        $error = 'Username must be between 3 and 50 characters.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters long.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])/', $password)) {
        $error = 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.';
    } elseif (!in_array($userType, ['staff', 'admin'])) {
        $error = 'Invalid user type.';
    } else {
        try {
            $pdo = getDBConnection();
            
            // Check if username already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetch()) {
                $error = 'Username already exists. Please choose a different one.';
            } else {
                // Check if email already exists
                $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    $error = 'Email already exists. Please use a different email address.';
                } else {
                    // Create new admin/staff user
                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                    
                    $stmt = $pdo->prepare("
                        INSERT INTO users (username, email, password_hash, first_name, last_name, user_type, status, email_verified, created_at)
                        VALUES (?, ?, ?, ?, ?, ?, 'active', TRUE, NOW())
                    ");
                    
                    $stmt->execute([
                        $username,
                        $email,
                        $passwordHash,
                        $firstName,
                        $lastName,
                        $userType
                    ]);
                    
                    $userId = $pdo->lastInsertId();
                    
                    // Log user creation
                    $stmt = $pdo->prepare("INSERT INTO audit_log (user_id, action, table_name, record_id, new_values, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([
                        $userId,
                        'admin_user_created',
                        'users',
                        $userId,
                        json_encode(['username' => $username, 'email' => $email, 'user_type' => $userType]),
                        $_SERVER['REMOTE_ADDR'] ?? '',
                        $_SERVER['HTTP_USER_AGENT'] ?? ''
                    ]);
                    
                    $message = "Successfully created {$userType} account for {$firstName} {$lastName}.";
                    
                    // Clear form data
                    $_POST = array();
                }
            }
        } catch (Exception $e) {
            $error = 'System error. Please try again later.';
            error_log('Admin creation error: ' . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Admin/Staff Account - Caloocan City Social Services</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Create Admin/Staff Account</h2>
                <p class="text-gray-600">Create accounts for authorized personnel</p>
                <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="text-sm text-yellow-800">
                        <strong>Security Notice:</strong> This script should be removed or secured after initial setup.
                    </p>
                </div>
            </div>

            <?php if ($message): ?>
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form class="mt-8 space-y-6" method="POST" action="">
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                First Name *
                            </label>
                            <input 
                                id="first_name" 
                                name="first_name" 
                                type="text" 
                                required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="First name"
                                value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>"
                            >
                        </div>
                        
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Last Name *
                            </label>
                            <input 
                                id="last_name" 
                                name="last_name" 
                                type="text" 
                                required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Last name"
                                value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>"
                            >
                        </div>
                    </div>
                    
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                            Username *
                        </label>
                        <input 
                            id="username" 
                            name="username" 
                            type="text" 
                            required 
                            minlength="3"
                            maxlength="50"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Choose a username"
                            value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                        >
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address *
                        </label>
                        <input 
                            id="email" 
                            name="email" 
                            type="email" 
                            required 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Enter email address"
                            value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                        >
                    </div>
                    
                    <div>
                        <label for="user_type" class="block text-sm font-medium text-gray-700 mb-2">
                            User Type *
                        </label>
                        <select 
                            id="user_type" 
                            name="user_type" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                            <option value="staff" <?php echo ($_POST['user_type'] ?? '') === 'staff' ? 'selected' : ''; ?>>Staff</option>
                            <option value="admin" <?php echo ($_POST['user_type'] ?? '') === 'admin' ? 'selected' : ''; ?>>Administrator</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password *
                        </label>
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            required 
                            minlength="8"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Create a password"
                        >
                        <p class="text-xs text-gray-500 mt-1">Minimum 8 characters with uppercase, lowercase, number, and special character</p>
                    </div>
                    
                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirm Password *
                        </label>
                        <input 
                            id="confirm_password" 
                            name="confirm_password" 
                            type="password" 
                            required 
                            minlength="8"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Confirm your password"
                        >
                    </div>
                </div>

                <div>
                    <button 
                        type="submit" 
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                        Create Account
                    </button>
                </div>
            </form>

            <div class="text-center">
                <a href="../index.php" class="text-blue-600 hover:text-blue-500 text-sm">
                    ← Back to Home
                </a>
            </div>
        </div>
    </div>
</body>
</html>
