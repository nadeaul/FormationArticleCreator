<?php
require 'connexion.php';
if(isset($_POST['id']))
{
    try
    {
        $req = $pdo->prepare('SELECT id FROM articles WHERE id=:id');
        $req->bindValue(':id', $_POST['id']);
        $req->execute();
        $res = $req->fetch(PDO::FETCH_OBJ);
        if ($res->id == $_POST['id'])
        {
            $urlArticles = 'articles/' . $res->id . '.html';
            if (file_exists('articles'))
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
            if (file_exists($urlArticles))
            {
                unlink($urlArticles);
            }
            file_put_contents($urlArticles, $_POST['content']);
            $req = $pdo->prepare('UPDATE articles SET has_data=1 WHERE id=:id');
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
