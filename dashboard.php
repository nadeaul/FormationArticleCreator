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
                            $q = $pdo->query('SELECT * FROM articles');
                            $articles = $q->fetchAll(PDO::FETCH_OBJ);
                            foreach ($articles as $key => $value) {
                                echo '<tr><th>' . $value->title . '</th><th>' . $value->description . '</th><th><a href="desc-edit.php?id=' . $value->id . '" class="btn btn-xs btn-warning">Editer la description</a>&nbsp;<a href="article-creator.php?id=' . $value->id . '" class="btn btn-xs btn-info">Editer le contenu</a></th></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.ckeditor.com/ckeditor5/1.0.0-alpha.1/classic/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCSGviqWFmjq6gjLveBMNdzAwpj11SkD_o&callback=initMap"></script>
    <script src="dist/js/gmaps.js"></script>
    <script src="js/article-creator.js"></script>
</body>
</html>
