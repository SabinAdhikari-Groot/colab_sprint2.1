<?php
// Start or resume a session
session_start();

// Print the entire session array
echo "Session Data:";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
?>
