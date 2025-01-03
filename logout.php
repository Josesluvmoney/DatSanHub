<?php
session_start();
session_destroy();
header("Location: trangchu.php");
exit();
?> 