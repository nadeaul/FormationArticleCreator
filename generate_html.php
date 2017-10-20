<?php
// Page qui génère l'html d'un article dans les dossiers du site

require 'connexion.php';
if(isset($_POST['id']))
{
    try
    {
        $req = $pdo->prepare('SELECT id FROM articles WHERE id=:id');
        $req->bindValue(':id', $_POST['id']);
        $req->execute();
        $res = $req->fetch(PDO::FETCH_OBJ);
        if ($res->id == $_POST['id']) // On vérifie l'id envoyé
        {
            $urlArticles = 'articles/' . $res->id . '.html';
            if (file_exists('articles')) // On vérifié que le dossier articles est bien présent
            {
                if (!is_dir('articles'))
                {
                    unlink('articles');
                    mkdir('articles');
                }
            }
            else
            {
                mkdir('articles');
            }
            if (file_exists($urlArticles)) // On remplace le fichier
            {
                unlink($urlArticles);
            }
            file_put_contents($urlArticles, $_POST['content']); // On met l'html à jour
            $req = $pdo->prepare('UPDATE articles SET has_data=1 WHERE id=:id'); // On met à jour l'article dans la base de donnée pour lui dire qu'il a des données
            $req->bindValue(':id', $res->id);
            $req->execute();
            echo 'OK';
            exit();
        }
        else
        {
            echo 'DIFF KO';
        }
    }
    catch(Exception $e)
    {
        echo 'SQL KO';
    }
}
echo 'KO';
?>
