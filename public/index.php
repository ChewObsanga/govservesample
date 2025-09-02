<?php
// Check if the residents directory exists
if (file_exists('../residents/index.php')) {
    // Redirect to the main application
    header('Location: ../residents/index.php');
    exit();
} else {
    // Fallback - show a simple message
    echo '<h1>PWD Registration System</h1>';
    echo '<p>The application is starting up. Please wait a moment and refresh the page.</p>';
    echo '<p>If the problem persists, please check the Render logs.</p>';
}
?>
