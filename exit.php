<?php 
    session_start();
	session_unset($_SESSION['xz_username']);

	header('location:login.php');
?>