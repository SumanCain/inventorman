<?php
session_start(); // Start the session


// Finally, destroy the session
session_destroy();

// Redirect to login page (or home page)
header("Location: index.html");
exit;
?>
