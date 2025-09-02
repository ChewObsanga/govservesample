<?php
echo "<h1>Simple PHP Test</h1>";
echo "<p>PHP is working!</p>";
echo "<p>Current directory: " . getcwd() . "</p>";
echo "<p>Current time: " . date('Y-m-d H:i:s') . "</p>";

// Check if we can read files
if (file_exists('config/database.php')) {
    echo "<p>✅ config/database.php exists</p>";
    echo "<p>File size: " . filesize('config/database.php') . " bytes</p>";
} else {
    echo "<p>❌ config/database.php does not exist</p>";
}

// List current directory
echo "<h2>Current directory files:</h2>";
$files = scandir('.');
foreach ($files as $file) {
    if ($file != '.' && $file != '..' && !is_dir($file)) {
        echo "<p>$file</p>";
    }
}

// List config directory if it exists
if (is_dir('config')) {
    echo "<h2>Config directory files:</h2>";
    $configFiles = scandir('config');
    foreach ($configFiles as $file) {
        if ($file != '.' && $file != '..') {
            echo "<p>$file</p>";
        }
    }
} else {
    echo "<p>❌ Config directory does not exist</p>";
}
?>
