<?php
require 'connexion.php';

if (isset($_POST['submit']) && $_POST['submit'] == 'true')
{
    try
    {
        $req = $pdo->prepare('UPDATE articles SET title=:title, description=:description, alt_image=:alt, image=:image WHERE id=:id');
        $req->bindValue(':title', $_POST['title'], PDO::PARAM_STR);
        $req->bindValue(':description', $_POST['description'], PDO::PARAM_STR);
        $req->bindValue(':alt', $_POST['alt'], PDO::PARAM_STR);
        $req->bindValue(':image', $_POST['image'], PDO::PARAM_STR);
        $req->bindValue(':id', $_POST['id']);
        $req->execute();
        header('Location: dashboard.php');
    }
    catch(Exception $e)
    {
        echo $e->getMessage();
    }

}

$req = $pdo->prepare('SELECT * FROM articles WHERE id = :id');
if (isset($_GET['id'])) // Si l'article existe on le récupère
{
    $req->bindValue(':id', $_GET['id']);
    $req->execute();
    $res = $req->fetch(PDO::FETCH_OBJ);
}
else // Sinon on le créé et on le récupère
{
    $newRq = $pdo->query('INSERT INTO articles (title, description) VALUES ("Nouvelle article", "Description")');
    $newRq->execute();
    $id = $pdo->lastInsertId();
    $req->bindValue(':id', $id);
    $req->execute();
}
?>
<html>
<head>
    <meta charset="utf-8">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/app.css">
</head>
<body style="padding-top:70px;">

    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li ><a href="dashboard.php">Liste des articles</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="row" stype="padding: 8px;">
        <div class="col-xs-12">
            <div class="panel panel-primary" style="margin: 8px;">
                <div class="panel-heading">
                    <h3 class="panel-title">Modification d'un article</h3>
                </div>
                <form method="post">
                    <div class="panel-body">
                        <div class="form-group">
                            <label>Titre de l'article</label>
                            <input class="form-control" value="<?php echo $res->title ?>" name="title">
                        </div>
                        <div class="form-group">
                            <label>Description de l'article</label>
                            <textarea name="description" id="description" rows="3">
                                <?php echo $res->description ?>
                            </textarea>
                        </div>
                        <div>
                            <h4>Image</h4>
                            <img id="db-image" src="<?php echo $res->image; ?>" alt="<?php echo $res->alt_image; ?>" />
                            <h4>Alt de l'image</h4>
                            <span id="db-alt" class="label label-primary"><?php echo $res->alt_image; ?></span><br><br><hr>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#image-modal">Modifier l'image</button>
                            <input id="alt-form" type="hidden" value="<?php echo $res->alt_image; ?>" name="alt" />
                            <input id="image-form" type="hidden" value="<?php echo $res->image; ?>" name="image" />
                        </div>
                        <input name="submit" type="hidden" value="true">
                        <input name="id" type="hidden" value="<?php echo $res->id; ?>">
                    </div>
                    <div class="panel-footer">
                        <button class="btn btn-primary">Valider</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="image-modal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Modification d'image</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h3>Liste d'image</h3>
                            <div class="alert alert-info" role="alert" id="loading-dir-image" style="display:none;">
                                <b>Chargement... </b> Nous chargeons les images. Veuillez patientez.
                            </div>
                            <div class="alert alert-danger" role="alert" id="error-loading-dir">
                                <b>Erreur : </b>Impossible de récupérer les images.
                            </div>
                            <div class="image-container">
                                <div class="row" id="image-container">

                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h3>Image actuelle</h3>
                            <img src="" id="preview-image" />
                            <h3>Envoyer une image</h3>
                            <form id="new-image-form">
                                <input type="file" name="file" id="file" required />
                                <input type="submit" value="Envoyer" class="submit" class="btn btn-info"/>
                            </form>
                            <div class="alert alert-info" role="alert" id="loading-image" style="display:none;">
                                <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><b> Envoi de l'image en cours...</b>
                            </div>
                            <div class="alert alert-success" role="alert" id="success-image" style="display:none;">
                                <b>Succès ! </b> Votre image a bien été envoyée
                            </div>
                            <div class="alert alert-danger" role="alert" id="error-image" style="display:none;">
                                <b>Erreur ! </b> Votre image n'a pas pu être envoyée. Veuillez rééssayer.
                            </div>
                            <h3>Changer les paramètres de l'image</h3>
                            <div class="form-group">
                                <label>Alt de l'image <i class="fa fa-question-circle" data-toggle="popover" data-placement="right" title="Alt" data-content="Texte qui sert à définir l'image quand elle ne peut pas être affichée. Utile pour le référencement."></i></label>
                                <input type="text" id="alt-text" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="saveImageModule()">Sauvegarder</button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="plugins/ckeditor/ckeditor.js"></script>
    <script src="plugins/ckeditor/adapters/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script>
    // Identique à article-creator, gestion des popover, ckeditor et image
    $(function() {
        $('[data-toggle="popover"]').popover();
        $("#description").ckeditor();
    })
    function loadImage() {
        $("#loading-dir-image").show();
        $("#error-loading-dir").hide();
        $("#image-container").html('');
        $.ajax({
            url: 'upload/',
            success: function(data) {
                $("#loading-dir-image").hide();
                $(data).find("a").attr("href", function (i, val) {
                    if( val.match(/\.(jpe?g|png|gif)$/) ) {
                        $("#image-container").append('<div class="col-xs-6 col-md-3 image-col"><a href="javascript:void(0)" class="thumbail image-sized"><img src="upload/' + val + '"></img></a></div>')
                    }
                });
                $(".image-sized").each(function() {
                    $(this).click(function() {
                        let src = $(this).find('img').attr('src');
                        $("#preview-image").attr('src', src);
                    })
                })
            },
            error: function() {
                $("#loading-dir-image").hide();
                $("#error-loading-dir").show();
            }
        })
    }
    $("#new-image-form").submit(function(e) {
        e.preventDefault();
        $("#new-image-form").hide();
        $("#error-image").hide();
        $("#success-image").hide();
        $("#loading-image").show();
        $.ajax({
            url: 'upload-image.php',
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData:false,
            success: function(data) {
                console.log(data);
                if (data.startsWith('upload/'))
                {
                    $("#new-image-form").show();
                    $("#loading-image").hide();
                    $("#success-image").show();
                    loadImage();
                }
                else
                {
                    $("#new-image-form").show();
                    $("#loading-image").hide();
                    $("#error-image").show();
                }
            },
            error: function() {
                $("#new-image-form").show();
                $("#loading-image").hide();
                $("#error-image").show();
            }
        })
    })

    $("#image-modal").on('shown.bs.modal', function() {
        $("#preview-image").attr('src', $('#image-form').val());
        $("#alt-text").val($("#alt-form").val());
        $("#new-image-form").show();
        $("#error-image").hide();
        $("#success-image").hide();
        $("#loading-image").hide();
        loadImage();
    })

    function saveImageModule()
    {
        let img = $("#preview-image").attr('src');
        let alt = $("#alt-text").val();
        $("#db-image").attr('src', img);
        $("#db-image").attr('alt', alt);
        $("#db-alt").html(alt);
        $("#alt-form").val(alt);
        $("#image-form").val(img);
    }
    </script>
</body>
</html>
