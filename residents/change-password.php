<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in and is resident
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'resident') {
    header('Location: ../login.php');
    exit();
}

$success = '';
$error = '';

try {
    $pdo = getDBConnection();
    
    // Get resident user info
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND status = 'active'");
    $stmt->execute([$_SESSION['user_id']]);
    $resident = $stmt->fetch();
    
    if (!$resident) {
        session_destroy();
        header('Location: ../login.php');
        exit();
    }
    
    // Handle password change
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Validation
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $error = 'All fields are required.';
        } elseif ($newPassword !== $confirmPassword) {
            $error = 'New passwords do not match.';
        } elseif (strlen($newPassword) < 8) {
            $error = 'New password must be at least 8 characters long.';
        } elseif (!password_verify($currentPassword, $resident['password_hash'])) {
            $error = 'Current password is incorrect.';
        } else {
            try {
                // Hash new password
                $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                
                // Update password
                $stmt = $pdo->prepare("UPDATE users SET password_hash = ?, updated_at = NOW() WHERE id = ?");
                $stmt->execute([$newPasswordHash, $_SESSION['user_id']]);
                
                $success = 'Password changed successfully!';
                
            } catch (Exception $e) {
                $error = 'Failed to change password. Please try again.';
                error_log('Password change error: ' . $e->getMessage());
            }
        }
    }
    
} catch (Exception $e) {
    error_log('Change password page error: ' . $e->getMessage());
    $error = 'System error. Please try again later.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - Caloocan City Social Services</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#ff6600',
                        secondary: '#ff8829',
                        accent: '#ffb366',
                        success: '#a5c90f',
                        dark: '#6f9c3d'
                    },
                    fontFamily: {
                        'sans': ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 font-sans">
    <!-- Navigation Bar -->
    <nav class="bg-primary shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo and City Name -->
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <img src="../caloocan-seal.png" alt="Caloocan City Seal" class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 object-contain">
                    <div>
                        <h1 class="text-sm sm:text-lg md:text-xl font-bold text-white">Caloocan City</h1>
                        <p class="text-xs sm:text-sm text-white opacity-90">Social Services</p>
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="md:hidden text-white p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                <!-- Right Side: Navigation Links, User Menu, and Time/Date -->
                <div class="hidden md:flex items-center space-x-4 lg:space-x-6">
                    <!-- Navigation Links -->
                    <div class="flex items-center space-x-4 lg:space-x-6">
                        <a href="services.php" class="text-white hover:text-gray-200 font-medium transition-colors duration-200">Mga Serbisyo</a>
                        <a href="index.php" class="text-white hover:text-gray-200 font-medium transition-colors duration-200">Home</a>
                    </div>

                    <!-- User Menu -->
                    <div class="relative">
                        <button id="user-menu-btn" class="flex items-center space-x-2 text-white hover:text-gray-200">
                            <div class="w-8 h-8 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                                <span class="text-white font-medium text-sm">
                                    <?php echo strtoupper(substr($resident['first_name'], 0, 1) . substr($resident['last_name'], 0, 1)); ?>
                                </span>
                            </div>
                            <span class="hidden lg:block"><?php echo htmlspecialchars($resident['first_name']); ?></span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <!-- User Dropdown Menu -->
                        <div id="user-menu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden">
                            <a href="profile.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile Settings</a>
                            <a href="applications.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Applications</a>
                            <hr class="my-1">
                            <a href="../logout.php" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Logout</a>
                        </div>
                    </div>

                    <!-- Live Time and Date - removed -->
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="md:hidden hidden pb-4">
                <div class="flex flex-col space-y-4">
                    <a href="services.php" class="text-white hover:text-gray-200 font-medium transition-colors duration-200">Mga Serbisyo</a>
                    <a href="index.php" class="text-white hover:text-gray-200 font-medium transition-colors duration-200">Home</a>
                    <hr class="border-white border-opacity-20">
                    <a href="applications.php" class="text-white hover:text-gray-200 font-medium transition-colors duration-200">My Applications</a>
                    <a href="../logout.php" class="text-red-300 hover:text-red-200 font-medium transition-colors duration-200">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Change Password</h1>
            <p class="text-gray-600">Update your account password for security.</p>
        </div>

        <!-- Success/Error Messages -->
        <?php if ($success): ?>
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- Password Change Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form method="POST" class="space-y-6">
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password *</label>
                    <input type="password" id="current_password" name="current_password" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                
                <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">New Password *</label>
                    <input type="password" id="new_password" name="new_password" required minlength="8"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    <p class="text-sm text-gray-500 mt-1">Password must be at least 8 characters long.</p>
                </div>
                
                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password *</label>
                    <input type="password" id="confirm_password" name="confirm_password" required minlength="8"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                
                <div class="flex justify-end space-x-3">
                    <a href="profile.php" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors duration-200">
                        Cancel
                    </a>
                    <button type="submit" class="bg-primary hover:bg-secondary text-white px-6 py-2 rounded-md font-medium transition-colors duration-200">
                        Change Password
                    </button>
                </div>
            </form>
        </div>

        <!-- Password Requirements -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6">
            <h3 class="text-sm font-medium text-blue-800 mb-2">Password Requirements:</h3>
            <ul class="text-sm text-blue-700 space-y-1">
                <li>• At least 8 characters long</li>
                <li>• Use a combination of letters, numbers, and symbols</li>
                <li>• Avoid using personal information</li>
                <li>• Don't reuse passwords from other accounts</li>
            </ul>
        </div>
    </div>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });

        // User menu toggle
        document.getElementById('user-menu-btn').addEventListener('click', function() {
            document.getElementById('user-menu').classList.toggle('hidden');
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('#user-menu-btn')) {
                document.getElementById('user-menu').classList.add('hidden');
            }
        });

        // Live time and date - removed
    </script>
</body>
</html>
