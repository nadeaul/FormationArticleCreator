<?php
if(isset($_FILES["file"]["type"]))
{
    $validextensions = array("jpeg", "jpg", "png");
    $temporary = explode(".", $_FILES["file"]["name"]);
    $file_extension = end($temporary);
    if ((($_FILES["file"]["type"] == "image/png") ||
        ($_FILES["file"]["type"] == "image/jpg") ||
        ($_FILES["file"]["type"] == "image/jpeg"))
        && ($_FILES["file"]["size"] < 2000000)
    && in_array($file_extension, $validextensions)) {
        if ($_FILES["file"]["error"] > 0)
        {
            echo "Error " . $_FILES["file"]["error"] . "<br/><br/>";
        }
        else
        {
            $name = $_FILES["file"]["name"];
            $extension = "";
            $extension_value = 1;
            while (file_exists("upload/" . $name . $extension)) {
                $extension = $extension_value;
                $extension_value++;
            }
            $sourcePath = $_FILES['file']['tmp_name'];
            $targetPath = "upload/" . $name . $extension;
            move_uploaded_file($sourcePath,$targetPath) ;
            echo $targetPath;
        }
    }
    else
    {
        echo "<span id='invalid'>***Invalid file Size or Type***<span>";
    }
}
?>
