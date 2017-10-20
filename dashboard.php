<html>
<head>
    <meta charset="utf-8">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body style="padding-top:70px;">

    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="#">Liste des articles</a></li>
                    <li class=""><a href="site/index.php">Site web</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="row" stype="padding: 8px;">
        <div class="col-xs-12">
            <div class="panel panel-primary" style="margin: 8px;">
                <div class="panel-heading">
                    <a href="desc-edit.php" class="btn btn-success btn-xs pull-right">Nouvelle article</a>
                    <h3 class="panel-title">Liste des articles</h3>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            require 'connexion.php';
                            // On récupère la liste des articles et on affiche cela dans un table
                            $q = $pdo->query('SELECT * FROM articles');
                            $articles = $q->fetchAll(PDO::FETCH_OBJ);
                            foreach ($articles as $key => $value) {
                                echo '<tr><th>' . $value->title . '</th><th>' . $value->description . '</th><th><a href="desc-edit.php?id=' . $value->id . '" class="btn btn-xs btn-warning">Editer la description</a>&nbsp;<a href="article-creator.php?id=' . $value->id . '" class="btn btn-xs btn-info">Editer le contenu</a>';
                                if ($value->has_data)
                                {
                                    // Si il y a des données déjà généré, alors on affiche les boutons de publication et de non-publication
                                    echo '<button class="btn btn-success btn-xs publish" data-id="' . $value->id . '" onclick="publish_article(' . $value->id . ')" ' . ($value->is_published ? 'style="display:none;"' : '') . '>Publier</button>';
                                    echo '<button class="btn btn-warning btn-xs unpublish" data-id="' . $value->id . '" onclick="disable_article(' . $value->id . ')" ' . (!$value->is_published ? 'style="display:none;"' : '') . '>Retirer de la publication</button>';
                                }
                                else
                                {
                                    // Sinon on affiche un warning
                                    echo '<br><span class="label label-warning">Le contenu n\'a pas été généré dans l\'éditeur de contenu.</span>';
                                }

                                echo '</th></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
    function publish_article(id) // Fonction permettant d'activer un article
    {
        $.ajax({
            url: 'active_article.php',
            type: 'POST',
            data: {
                id: id, // On envoie l'id de l'article et on met la variable published à 1
                published: 1
            },
            success: function(data) {
                if (data == 'OK')
                { // On inverse les boutons de publication et de suppression de publication
                    $(".publish[data-id=" + id + "]").hide();
                    $(".unpublish[data-id=" + id + "]").show();
                }
            }
        })
    }
    function disable_article(id) // Fonction permettant de désactiver un article (Inverse de publish_article(id))
    {
        $.ajax({
            url: 'active_article.php',
            type: 'POST',
            data: {
                id: id,
                published: 0
            },
            success: function(data) {
                if (data == 'OK')
                {
                    $(".publish[data-id=" + id + "]").show();
                    $(".unpublish[data-id=" + id + "]").hide();
                }
            }
        })
    }
    </script>

    <script src="https://cdn.ckeditor.com/ckeditor5/1.0.0-alpha.1/classic/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>
