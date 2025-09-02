<?php
echo "<h1>Docker Test Page</h1>";
echo "<p>PHP is working!</p>";

// Check if config directory exists
if (is_dir('config')) {
    echo "<p>✅ Config directory exists</p>";
    
    // Check if database.php exists
    if (file_exists('config/database.php')) {
        echo "<p>✅ config/database.php exists</p>";
        
        // Try to include it
        try {
            require_once 'config/database.php';
            echo "<p>✅ config/database.php loaded successfully</p>";
        } catch (Exception $e) {
            echo "<p>❌ Error loading config/database.php: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p>❌ config/database.php does not exist</p>";
    }
} else {
    echo "<p>❌ Config directory does not exist</p>";
}

// List files in current directory
echo "<h2>Files in current directory:</h2>";
echo "<ul>";
$files = scandir('.');
foreach ($files as $file) {
    if ($file != '.' && $file != '..') {
        echo "<li>$file</li>";
    }
}
echo "</ul>";

// List files in config directory if it exists
if (is_dir('config')) {
    echo "<h2>Files in config directory:</h2>";
    echo "<ul>";
    $configFiles = scandir('config');
    foreach ($configFiles as $file) {
        if ($file != '.' && $file != '..') {
            echo "<li>$file</li>";
        }
    }
    echo "</ul>";
}
?>
