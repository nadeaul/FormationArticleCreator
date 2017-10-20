<?php
if(isset($_FILES["file"]["type"])) // Si on a une image
{
    $validextensions = array("jpeg", "jpg", "png");
    $temporary = explode(".", $_FILES["file"]["name"]);
    $file_extension = end($temporary);
    if ((($_FILES["file"]["type"] == "image/png") || // Si c'est un png
        ($_FILES["file"]["type"] == "image/jpg") || // ou un jpg
        ($_FILES["file"]["type"] == "image/jpeg")) // on un jpeg
        && ($_FILES["file"]["size"] < 2000000) // que le fichier fais mois de ~2Mo
    && in_array(strtolower($file_extension), $validextensions)) { // Et que l'extension est valide
        if ($_FILES["file"]["error"] > 0) // Si il y a une erreur
        {
            echo "Error " . $_FILES["file"]["error"] . "<br/><br/>";
        }
        else
        {
            $name = $_FILES["file"]["name"]; // On stock le nom du fichier
            $extension = "";
            $extension_value = 1;
            $sourcePath = $_FILES['file']['tmp_name'];
            if (file_exists('upload')) // On vérifie et créé si besoin le dossier upload
            {
                if (!is_dir('upload'))
                {
                    unlink('upload');
                    mkdir('upload');
                }
            }
            else
            {
                mkdir('upload');
            }
            while (file_exists("upload/" . $name . $extension_value . $extension)) { // Si le fichier existe
                $extension_value++; // On incrémente un nombre pour changer le nom du fichier dynamiquement
            }
            $targetPath = "upload/" . $name . $extension_value . $extension;
            move_uploaded_file($sourcePath,$targetPath) ; // On sauvegarde le fichier
            echo $targetPath; // On retourne le lien vers l'image
        }
    }
    else
    {
        echo "<span id='invalid'>***Invalid file Size or Type***<span>";
    }
}
?>
