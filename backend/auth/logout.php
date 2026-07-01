<?php
session_start();
session_destroy();
header("Location: /GIA/index.html");
exit();
?>
