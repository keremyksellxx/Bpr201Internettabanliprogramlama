<?php
session_start();
session_unset();
session_destroy();
header("Location: editor_login.php");
exit();
?>
