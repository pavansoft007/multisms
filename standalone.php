<?php
// This is a standalone PHP file that doesn't rely on the CodeIgniter framework
echo '<!DOCTYPE html>
<html>
<head>
    <title>Standalone Page</title>
</head>
<body>
    <h1>Standalone Page</h1>
    <p>This is a standalone page that doesn\'t rely on the CodeIgniter framework.</p>
    <p>PHP Version: ' . phpversion() . '</p>
    <p>Current Time: ' . date('Y-m-d H:i:s') . '</p>
</body>
</html>';
?>