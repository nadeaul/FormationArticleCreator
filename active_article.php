<?php
// Page qui permet de publier un article

require 'connexion.php';
if(isset($_POST['id']))
{
    try
    {
        $req = $pdo->prepare('SELECT id, date FROM articles WHERE id=:id');
        $req->bindValue(':id', $_POST['id']);
        $req->execute();
        $res = $req->fetch(PDO::FETCH_OBJ);
        if ($res->id == $_POST['id'])
        {
            $req;
            if ($res->date == null && $_POST['published'] == 1) // Si c'est la première publication
            {
                $now = new DateTime(); // On met la date d'aujourd'hui en publication
                $req = $pdo->prepare('UPDATE articles SET is_published=:published, date=:date WHERE id=:id');
                $req->bindValue(':date', $now->format('Y-m-d H:i:s'));
            }
            else
            {
                $req = $pdo->prepare('UPDATE articles SET is_published=:published WHERE id=:id');
            }
            $req->bindValue(':id', $res->id);
            $req->bindValue(':published', ($_POST['published'] == 1 ? 1 : 0), PDO::PARAM_INT); // On met 1 ou 0 suivant si c'est publié ou pas
            $req->execute();
            echo 'OK';
            exit();
        }
    }
    catch(Exception $e)
    {
        echo $e->getMessage();
    }
}
echo 'KO';
?>
