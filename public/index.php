<?php
// Check if the login.php exists in the parent directory
if (file_exists('../login.php')) {
    // Redirect to the login page
    header('Location: ../login.php');
    exit();
} else {
    // Fallback - show a simple message
    echo '<h1>PWD Registration System</h1>';
    echo '<p>The application is starting up. Please wait a moment and refresh the page.</p>';
    echo '<p>If the problem persists, please check the Render logs.</p>';
}
?>
