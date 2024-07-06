<?php
require_once('includes/init.php');
session_start();
session_destroy();
redirect_to("login.php");
?>