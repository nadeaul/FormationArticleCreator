<?php
require 'connexion.php';
// On récupère l'article via php
$req = $pdo->prepare('SELECT * FROM articles WHERE id=:id');
$req->bindValue(':id', intval($_GET['id']));
$req->execute();
$res = $req->fetch(PDO::FETCH_OBJ);
?>
<html>
<head>
    <meta charset="utf-8">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="css/article-creator.css" />
    <link rel="stylesheet" href="template/main.css" />
    <link rel="stylesheet" hred="css/scrollbar.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body style="padding-top:70px;overflow-x:hidden;">
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li><a href="dashboard.php">Liste des articles</a></li>
                    <li class=""><a href="site/index.php">Site web</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <button id="button-return" style="display: none;" class="btn btn-primary">Retour à l'édition</button>
    <div id="preview" style="display:none;" class="ac-container">

    </div>
    <div class="sidebar-custom scrollbar" id="style-2">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="toolbox-button">
                    <!-- On utilise le data-toogle de bootstrap pour collapse le panel. -->
                    <a data-toggle="collapse" href="#column-list"><i class="glyphicon glyphicon-plus" onclick="collapse"></i></a>
                </div>
                <h3 class="panel-title">Mise en page</h3>
            </div>
            <div class="panel-body collapse in" id="column-list">
                <ul class="list-group"> <!-- On défini chaque colonne avec un data-column (C'est un attribut personnalisé) qui défini, séparé par une virgule, les colonnes qui seront ajouté -->
                    <li class="list-group-item column" data-column="12"><span class="badge"><i class="glyphicon glyphicon-plus"></i></span> 1 colonne</li>
                    <li class="list-group-item column" data-column="6,6"><span class="badge"><i class="glyphicon glyphicon-plus"></i></span> 2 colonne</li>
                    <li class="list-group-item column" data-column="7,5"><span class="badge"><i class="glyphicon glyphicon-plus"></i></span> 2 colonne grand gauche</li>
                    <li class="list-group-item column" data-column="5,7"><span class="badge"><i class="glyphicon glyphicon-plus"></i></span> 2 colonne grand droit</li>
                    <li class="list-group-item column" data-column="8,4"><span class="badge"><i class="glyphicon glyphicon-plus"></i></span> 2 colonne petit gauche</li>
                    <li class="list-group-item column" data-column="4,8"><span class="badge"><i class="glyphicon glyphicon-plus"></i></span> 2 colonne petit droit</li>
                    <li class="list-group-item column" data-column="4,4,4"><span class="badge"><i class="glyphicon glyphicon-plus"></i></span> 3 colonne</li>
                    <li class="list-group-item column" data-column="3,3,3,3"><span class="badge"><i class="glyphicon glyphicon-plus"></i></span> 4 colonne</li>
                </ul>
            </div>
            <div class="panel-footer">
                <span class="help-block">
                    Effectuer votre mise en page avec vos colonnes.
                </span>
            </div>
        </div>
        <br>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="toolbox-button">
                    <a data-toggle="collapse" href="#module-list"><i class="glyphicon glyphicon-plus" onclick="collapse"></i></a>
                </div>
                <h3 class="panel-title">Modules</h3>
            </div>
            <div class="panel-body collapse in" id="module-list">
                <ul class="list-group">
                    <!-- On défini chaque colonne avec un data-module afin de pouvoir le récupérer dans Javascript -->
                    <li class="list-group-item module" data-module="text"><span class="badge"><i class="fa fa-file-text"></i></span> Paragraphe</li>
                    <li class="list-group-item module" data-module="image"><span class="badge"><i class="fa fa-picture-o"></i></span> Image</li>
                    <li class="list-group-item module" data-module="youtube"><span class="badge"><i class="fa fa-youtube-play"></i></span> Youtube Vidéo</li>
                    <li class="list-group-item module" data-module="vimeo"><span class="badge"><i class="fa fa-vimeo"></i></span> Viméo Vidéo</li>
                    <li class="list-group-item module" data-module="map"><span class="badge"><i class="fa fa-map"></i></span> Map</li>
                </ul>
            </div>
            <div class="panel-footer">
                <span class="help-block">
                    Ajoutez des modules à votre page.
                </span>
            </div>
        </div>
        <br>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="toolbox-button">
                    <a data-toggle="collapse" href="#action-list"><i class="glyphicon glyphicon-plus" onclick="collapse"></i></a>
                </div>
                <h3 class="panel-title">Actions</h3>
            </div>
            <div class="panel-body collapse in" id="action-list">
                <button class="btn btn-primary btn-block" id="button-preview">Prévisualiser</button>
                <button class="btn btn-success btn-block" id="button-publish">Publier</button>
                <div class="alert alert-info" role="alert" id="success-publish" style="display:none;">
                    <b>Succès ! </b> La publication est réussi !
                </div>
                <div class="alert alert-danger" role="alert" id="error-publish" style="display:none;">
                    <b>Erreur : </b> Impossible de générer la page. Veuillez rééssayer.
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="padding: 8px;padding-left:258px;" id="edit-page">
        <div class="col-md-12">
            <!-- La div qui contient le contenu de notre article -->
            <div class="canvas-article" id="drop-area">
                <div class="label-canvas label-drop">Contenu de la page</div>
                <?php
                if ($res) // Si l'article est chargé on affiche la sauvegarde de l'éditeur
                {
                    echo $res->save;
                }
                ?>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="text-modal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Modification du texte</h4>
                </div>
                <div class="modal-body">
                    <textarea id="text-content-area">

                    </textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="saveTextModule()">Sauvegarder</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="youtube-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Modification de youtube</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Url de la vidéo Youtube</label>
                        <input type="url" id="youtube-content-url" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="saveYoutubeModule()">Sauvegarder</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="vimeo-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Modification de Vimeo</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Url de la vidéo Vimeo</label>
                        <input type="url" id="vimeo-content-url" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="saveVimeoModule()">Sauvegarder</button>
                </div>
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

    <div class="modal fade" tabindex="-1" role="dialog" id="map-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Modification d'une map</h4>
                </div>
                <div class="modal-body">
                    <div class="map-preview" id="preview-map">

                    </div>
                    <div class="form-group">
                        <label>Latitude</label>
                        <input type="number" id="map-content-lat" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Longitude</label>
                        <input type="number" id="map-content-lon" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Zoom</label>
                        <input type="number" id="map-content-zoom" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="saveMapModule()">Sauvegarder</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="delete-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Suppression</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <b>Attention ! </b> Vous êtes sur le point de supprimer un élément. Etes vous sûr ?
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal" id="deleteValidation">Supprimer definitivement</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="plugins/ckeditor/ckeditor.js"></script>
    <script src="plugins/ckeditor/adapters/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCSGviqWFmjq6gjLveBMNdzAwpj11SkD_o&callback=initMap"></script>
    <script src="dist/js/gmaps.js"></script>
    <script src="js/article-creator.js"></script>
    <?php
    if ($res) // Si l'article est chargé on créé une variable globalId et on y met l'id de l'article. Puis on met à jour la page.
    {
        ?>
        <script>
        var globalId = <?php echo $res->id; ?>;
        updateDropArea();
        </script>
        <?php
    }
    ?>
</body>
</html>
