<?php
// Met le HTML dans la partie save de l'article

require 'connexion.php';

$id = intval($_POST['id']);
$content = $_POST['content'];

try
{
    $req = $pdo->prepare('UPDATE articles SET save=:content WHERE id = :id');
    $req->bindValue(':content', $content, PDO::PARAM_STR);
    $req->bindValue(':id', $id);
    $req->execute();
    echo 'OK';
}
catch(Exception $e)
{
    echo 'KO ' . $e->getMessage();
}

?>
