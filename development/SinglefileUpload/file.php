<?php
/*
 *Code By Abhishek R. Kaushik
 * Downloaded from http://devzone.co.in
 */
$upload_dir = "uploads/";
if (isset($_FILES["myfile"])) {
    if ($_FILES["myfile"]["error"] > 0) {
        echo "Error: " . $_FILES["file"]["error"] . "<br>";
    } else {
        move_uploaded_file($_FILES["myfile"]["tmp_name"], $upload_dir . $_FILES["myfile"]["name"]);
        //echo "Uploaded File :" . $_FILES["myfile"]["name"];
        echo "<pre>";
        print_r($_POST);
        print_r($_FILES);
    }
}
?>