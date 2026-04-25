<?php
session_start();

// Clear all session data
session_unset();
session_destroy();

header("Location: /FinalProject/login");
exit;