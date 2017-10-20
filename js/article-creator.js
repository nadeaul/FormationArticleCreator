/* ========================== VARIABLE GLOBAL ========================== */

// Objet contenant les valeurs par défaut des modules
var defaultModuleValues = {
    text: " Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi eu justo eget leo faucibus euismod. Donec vel est libero. Nulla sodales turpis felis, et vehicula nisi luctus nec. Aenean sit amet ligula dictum, tristique felis id, fermentum nunc. Nullam fermentum lorem eget lacus fringilla posuere. Integer lobortis est non tortor pharetra tincidunt. Donec congue aliquam hendrerit. Curabitur aliquam interdum nisi at mattis. Aliquam tellus elit, elementum id quam ut, tempor egesta. purus. Vivamus egestas tempus erat quis porttitor. Aenean blandit vehicula enim hendrerit dignissim. Duis feugiat nisl et facilisis scelerisque. Ut ut ligula dolor. Ut varius ligula egestas tempor mollis. Fusce non volutpat nisl. Donec vestibulum mi orci, vel sollicitudin nisl condimentum id. Phasellus ornare quis arcu suscipit convallis. Nullam sit amet elit ligula. Interdum et malesuada fames ac ante ipsum primis in faucibus. Sed ligula sem, egestas eu accumsan ac, fringilla rutrum tellus. ",
    image: "https://dummyimage.com/300x150/2c3e50/ecf0f1.png",
    youtube: "grMXBdq29DU",
    vimeo: "169846356",
    map: {
        lat: 44.832926,
        lon: -0.674089,
        zoom: 15
    }
}

// Valeurs contenant les templates des modules.
// Lors de la création des modules, on utilise les valeurs html puis on remplace #value  par la valeurs de l'image.
// La map n'est pas présente car elle requière d'avoir une logique de code
var defaultTemplate = {
    text: "<div class='edit-text-template'>#value</div>",
    image: "<img src='#value' class='img-editor' alt='Image'>",
    youtube: '<iframe width="560" height="315" src="https://www.youtube.com/embed/#value" frameborder="0" allowfullscreen></iframe>',
    vimeo: '<iframe src="https://player.vimeo.com/video/#value" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>',
    map: function() {
        return '<div id="map' + generateUniqueId() + '" class="map-preview" data-lat="' + defaultModuleValues.map.lat + '" data-lon="' + defaultModuleValues.map.lon + '" data-zoom="' + defaultModuleValues.map.zoom + '"></div>';
    }
}

// Objet contenant la configuration des modules
var config = {
    // Configuration Youtube
    youtube: {
        // URL affiché dans l'input de la modal d'édition
        baseUrl: 'https://www.youtube.com/watch?v=#value',
        // URL inséré à l'intérieur de l'iframe
        baseTemplateUrl: 'https://www.youtube.com/embed/#value'
    },
    // Configuration Vimeo
    vimeo: {
        // URL affiché dans l'input de la modal d'édition
        baseUrl: 'https://vimeo.com/#value',
        // URL inséré à l'intérieur de l'iframe
        baseTemplateUrl: 'https://player.vimeo.com/video/#value'
    },
    // Configuration Map
    map: {
        // Valuer qui passe à 'true' quand la map est initialisé
        active: false
    }
}
// Identifiant de l'objet en édition
var currentIdEdit = null;
// Objet à supprimer
var currentDeleteObject = null;
// Editeur de text
var modalTextEditor = null;
// Map de prévisualisation dans la modal
var previewMap = null;
// Marker de prévisualisation dans la modal
var previewMarker = null;

/* ========================== FONCTION UTILITAIRE ========================== */

function generateUniqueId() // Genere un id unique toutes les millisecondes.
{
    let ret = new Date().getTime();
    return ret;
}

function initMap() { // Fonction appellé quand la map est utilisable
    config.map.active = true; // On met que la map est activé
    updateDropArea(); // On met à jour
}

/* ============== INITIALISATION ET GESTION DU DRAG AND DROP =============== */

// Initialisation de la page
$(function() {
    $("#text-content-area").ckeditor(); // Création de l'éditeur dans la modal.
    modalTextEditor = $("#text-content-area"); // On stock cette éditeur dans une variable.
    $('[data-toggle="popover"]').popover(); // On initalise les popover de bootstrap
    $("#drop-area").sortable(); // On rend la drop-area sortable pour pouvoir changer l'ordre des colonnes

    $("#drop-area").droppable({ // On rend la drop-area droppable pour ajouter des colonnes
        accept: ".column", // On accepte que des colonnes
        drop: function(e, ui) {
            let data = ui.draggable[0].dataset['column']; // On récupère les données mis dans le li
            let columns = data.split(','); // On créé une array afin de récupérer les colonnes
            let newRow = $("<div class='row row-edit'></div>"); // On créé notre nouvelle ligne
            newRow.attr('id', generateUniqueId());
            newRow.append('<div class="toolbox-content"><button class="btn btn-danger deleteRow"><i class="fa fa-times"></i></button></div>')
            for (var key in columns) { // Pour chaque colonne à ajouter
                if (columns.hasOwnProperty(key)) {
                    let newCol = $('<div class="col-md-' + columns[key] + ' column-edit"></div>');
                    newCol.append('<div class="label-canvas">Colonnes</div>');
                    newRow.append(newCol); // On append
                }
            }
            $("#drop-area").append(newRow); // On append la ligne entiere
            updateDropArea(); // On met à jour toutes notre contenu dans la page
        }
    })

    $(".column").each(function() { //On récupère toute les colonnes, et on effectue les droppables
        $(this).draggable({
            revert: true,
            zIndex: 2500
        })
    })

    $(".module").each(function() { //On récupère toute les colonnes, et on effectue les droppables
        $(this).draggable({
            revert: true,
            zIndex: 2500
        })
    })

    $("#deleteValidation").click(function() { // Si on confirme la suppression
        currentDeleteObject.remove(); // On supprime
        updateDropArea(); // On rafraichi la drop-area
    })

    // On initialise les boutons pour la prévisualisation
    $("#button-return").click(edit);
    $("#button-preview").click(preview);
})

function updateDropArea()
{
    $("#drop-area").sortable();
    $(".deleteRow").each(function() { // Pour chaque bouton supprimer
        $(this).click(function() {
            currentDeleteObject = $(this).parent().parent(); // On stock l'objet à supprimer
            $("#delete-modal").modal('show'); // On affiche la modal de confirmation de suppréssion
        })
    })
    $(".column-edit").each(function() {
        $(this).sortable(); // On rend la colonne sortable
        $(this).droppable({
            accept: ".module", // On accepte que des modules
            drop: function(e, ui) {
                let data = ui.draggable[0].dataset['module']; // On récupère le type de module qui a été drop
                let newDiv = $('<div class="module-edit"></div>'); // On créé une nouvelle div qui sera notre module affiché
                newDiv.attr('data-module', data); // On ajoute notre nouvelle div le module qui lui ai associé.
                newDiv.attr('id', generateUniqueId()); // On génère un id unique au module
                // On ajoute au module un bouton d'édition et un bouton de suppréssion
                newDiv.append('<div class="toolbox-content"><button class="btn btn-info editModule"><i class="fa fa-edit"></i></button><button class="btn btn-danger deleteModule"><i class="fa fa-times"></i></button></div>');
                if (defaultTemplate[data] == 'function') // Si le template est une fonction
                {
                    newDiv.append(defaultTemplate[data]()); // On append le résultat de la fonction
                }
                else // Sinon
                {
                    let template = defaultTemplate[data]; // On récupère le template
                    let value = defaultModuleValues[data]; // On récupère la valeur par défaut du template
                    template = template.replace('#value', value); // On remplace #value par la valeur par défaut.
                    newDiv.append(template); // On append le résultat
                }
                $(this).append(newDiv); // On rajoute le module dans la colonne.
                updateDropArea(); // On met à jour la drop area
            }
        })
    });
    $(".editModule").each(function() { // Lors du clic sur le boutton éditer du module
        $(this).click(function() {
            currentIdEdit = $(this).parent().parent().attr('id'); // On récupère l'id du module
            currentDataEdit = $(this).parent().parent().attr('data-module'); // On récupère le type du module
            $("#" + currentDataEdit + "-modal").modal('show'); // On affiche le type du module dont l'id est {type}-modal
        })
    })
    $(".deleteModule").each(function() { // Lors du clic sur le bouton de suppression du module
        $(this).click(function() {
            currentDeleteObject = $(this).parent().parent(); // On stock l'objet à supprimer
            $("#delete-modal").modal('show'); // On affiche la modal de confirmation de suppréssion
        })
    })
    if (config.map.active) // Si la map peut être utilisé
    {
        $(".map-preview").each(function() { // Pour chaque map
            // On récupère les données stocker en dataset (Lat, Lon et Zoom) et l'id
            let lat = parseFloat($(this).attr('data-lat'));
            let lon = parseFloat($(this).attr('data-lon'));
            let zoom = parseInt($(this).attr('data-zoom'));
            let id = $(this).attr('id');
            var map = new GMaps({ // On initialise la map
                div: '#' + id,
                lat: lat,
                lng: lon,
                zoom: zoom,
                // On désactive l'interface de la map
                disableDefaultUI: true,
                draggable: false,
                zoomControl: false,
                scrollwheel: false,
                disableDoubleClickZoom: true
            })
            // On ajoute un marker sur la map au centre
            map.addMarker({
                lat: lat,
                lng: lon,
                title: 'Position'
            });
        })
    }
    $.ajax({ // On envoie les modifications via ajax dans la base de donnée
        url: 'send_modification.php',
        type: "POST",
        data: {
            id: globalId, // On envoie l'id
            content: generateSaveHTML() // et le code html de la page contenant l'interface d'édition
        }
    })
}

/* ================== FONCTION DE CONFIRMATION D'EDITION =================== */

function saveTextModule() // Fonction faite pour changer le texte d'un module text après l'édition
{
    // On récupère la div .edit-text-template du module en édition et on change son html
    $("#" + currentIdEdit).find('.edit-text-template').html(modalTextEditor.val());
    // On met à jour la page
    updateDropArea();
}

function saveYoutubeModule() // Fonction de sauvegarde d'édition de Youtube
{
    // On récupère l'iframe
    let iframeY = $("#" + currentIdEdit).find('iframe');
    let regex = /[?&]([^=#]+)=([^&#]*)/g;
    let url = $("#youtube-content-url").val(); // On récupère l'url envoyé
    let match;
    let value = '';
    while(match = regex.exec(url)) { // On récupère les arguments GET du lien envoyer avec Regex
        if (match[1] == 'v') // Si on a un argument v
        {
            value = match[2]; // On met à jour la valeur
            break;
        }
    }
    if (value == '') // Si on a pas encore trouvé de valeur
    {
        spUrl = url.split('/'); // On récupère l'identifiant de la vidéo via l'url en séparant par /
        value = spUrl[spUrl.length - 1];
    }
    iframeY.attr('src', config.youtube.baseTemplateUrl.replace('#value', value)); // On met à jour la vidéo
    updateDropArea(); // On met à jour la page
}

function saveVimeoModule() // Fonction de sauvegarde d'édition Vimeo (Comme youtube mais en plus simple)
{
    let iframeV = $("#" + currentIdEdit).find('iframe');
    let url = $("#vimeo-content-url").val();
    if (value == '')
    {
        spUrl = url.split('/');
        value = spUrl[spUrl.length - 1];
    }
    iframeV.attr('src', config.vimeo.baseTemplateUrl.replace('#value', value));
    updateDropArea();
}

function saveImageModule() // Fonction de sauvegarde d'image
{
    let img = $("#preview-image").attr('src'); // On récupère la source de l'image de prévisualisation
    let alt = $("#alt-text").val(); // On récupère le text alt
    $("#" + currentIdEdit).find('img').attr('alt', alt); // On set le text alt de l'image du module
    $("#" + currentIdEdit).find('img').attr('src', img); // On set la source de l'image du module
    updateDropArea();
}

function saveMapModule() // Fonction de sauvegarde de map
{
    let map = $("#" + currentIdEdit).find('.map-preview'); // On récupère la map
    // On met à jour les dataset par rapport au valeur rentré
    map.attr('data-lat', $("#map-content-lat").val());
    map.attr('data-lon', $("#map-content-lon").val());
    map.attr('data-zoom', $("#map-content-zoom").val());
    updateDropArea();
}

/* ==================== FONCTION DE GESTION DES IMAGES ===================== */

function loadImage() { // Fonction de chargement des images
    $("#loading-dir-image").show(); // La fonction peut être longue donc on affiche une alert de chargement
    $("#error-loading-dir").hide(); // On cache les erreurs
    $("#image-container").html(''); // On réinitialise le contener qui contient les images
    $.ajax({
        url: 'upload/', // On parcours le dossier upload
        success: function(data) {
            $(data).find("a").attr("href", function (i, val) { // Pour chaque lien dans le dossier upload
                if( val.match(/\.(jpe?g|png|gif)$/) ) { // Si c'est une image
                    // On ajoute l'image au contener
                    $("#image-container").append('<div class="col-xs-6 col-md-3 image-col"><a href="javascript:void(0)" class="thumbail image-sized"><img src="upload/' + val + '"></img></a></div>')
                }
            });
            // Pour chaque image
            $(".image-sized").each(function() {
                $(this).click(function() { // Quand on clique
                    let src = $(this).find('img').attr('src'); // On récupère la source de l'image
                    $("#preview-image").attr('src', src); // On change la source de l'image de prévisualisation
                })
            })
            $("#loading-dir-image").hide(); // On cache le chargement
        },
        error: function() { // Si il y a une erreur
            $("#loading-dir-image").hide(); // On cache le chargement
            $("#error-loading-dir").show(); // On affiche l'alert d'erreur
        }
    })
}

$("#new-image-form").submit(function(e) { // Envoie du formulaire d'upload des images
    e.preventDefault(); // On annule la fonction de base du formulaire
    // On cache les alert de succès et d'erreur ainsi que le formulaire
    $("#new-image-form").hide();
    $("#error-image").hide();
    $("#success-image").hide();
    $("#loading-image").show(); // On affiche le chargement
    $.ajax({
        url: 'upload-image.php',
        type: "POST",
        data: new FormData(this), // On envoie le formulaire directement comme il est envoyé normalement
        contentType: false,
        cache: false,
        processData:false,
        success: function(data) { // Si cela à marché
            if (data.startsWith('upload/')) // Si le lien renvoyé par la page php commence bien par upload/
            {
                $("#new-image-form").show(); // On affiche le formulaire
                $("#loading-image").hide(); // On cache le chargement
                $("#success-image").show(); // On affiche le message de succès
                loadImage(); // On recharge les images
            }
            else // Sinon
            {
                $("#new-image-form").show(); // On affiche le formulaire
                $("#loading-image").hide(); // On cache le chargement
                $("#error-image").show(); // On affiche le message d'erreur
            }
        },
        error: function() { // Si cela n'a pas marché
            $("#new-image-form").show(); // On affiche le formulaire
            $("#loading-image").hide(); // On cache le chargement
            $("#error-image").show(); // On affiche le message d'erreur
        }
    })
})

/* ==================== GESTION DES ACTIONS DES MODALS ===================== */

$("#text-modal").on('shown.bs.modal', function() {
    $("#text-content-area").focus();
    modalTextEditor.val($("#" + currentIdEdit).find('.edit-text-template').html());
})

$("#youtube-modal").on('shown.bs.modal', function() {
    $("#youtube-content-url").focus();
    let url = $("#" + currentIdEdit).find('iframe').attr('src');
    let spUrl = url.split('/');
    let value = spUrl[spUrl.length - 1];
    $("#youtube-content-url").val(config.youtube.baseUrl.replace('#value', value));
})

$("#vimeo-modal").on('shown.bs.modal', function() {
    $("#vimeo-content-url").focus();
    let url = $("#" + currentIdEdit).find('iframe').attr('src');
    let spUrl = url.split('/');
    let value = spUrl[spUrl.length - 1];
    $("#vimeo-content-url").val(config.vimeo.baseUrl.replace('#value', value));
})

$("#image-modal").on('shown.bs.modal', function() {
    $("#preview-image").attr('src', $("#" + currentIdEdit).find('img').attr('src'));
    $("#alt-text").val($("#" + currentIdEdit).find('img').attr('alt'));
    $("#new-image-form").show();
    $("#error-image").hide();
    $("#success-image").hide();
    $("#loading-image").hide();
    loadImage();
})

$("#map-modal").on('shown.bs.modal', function() {
    let map = $("#" + currentIdEdit).find('.map-preview');
    let lat = parseFloat(map.attr('data-lat'));
    let lon = parseFloat(map.attr('data-lon'));
    let zoom = parseFloat(map.attr('data-zoom'));
    $("#map-content-lat").val(lat);
    $("#map-content-lon").val(lon);
    $("#map-content-zoom").val(zoom);
    previewMap = new GMaps({
        div: '#preview-map',
        lat: lat,
        lng: lon,
        zoom: zoom,
        width: 568,
        height: 400,
        disableDefaultUI: true,
        draggable: false,
        zoomControl: false,
        scrollwheel: false,
        disableDoubleClickZoom: true
    })
    previewMarker = previewMap.addMarker({
        lat: lat,
        lng: lon,
        title: 'Position'
    });
})

// Si on met à jour la lattitude, longitude ou le zoom, on modifie la map et le marker
$("#map-content-lat").change(function() {
    previewMap.setCenter(parseFloat($("#map-content-lat").val()), parseFloat($("#map-content-lon").val()));
    previewMarker.setPosition({lat: parseFloat($("#map-content-lat").val()), lng: parseFloat($("#map-content-lon").val())})
    previewMap.refresh();
});

$("#map-content-lon").change(function() {
    previewMap.setCenter(parseFloat($("#map-content-lat").val()), parseFloat($("#map-content-lon").val()));
    previewMarker.setPosition({lat: parseFloat($("#map-content-lat").val()), lng: parseFloat($("#map-content-lon").val())})
    previewMap.refresh();
});

$("#map-content-zoom").change(function() {
    previewMap.setZoom(parseFloat($("#map-content-zoom").val()))
})

/* ======================= GESTION DE LA SAUVEGARDE ======================== */

function generateSaveHTML() // Genere le HTML à sauvegarder
{
    var htmlSave = $("#drop-area").html(); // On récupère le HTML
    let ret = $('<div>' + htmlSave + '</div>'); // On le met dans deux div pour pouvoir utiliser jquery
    ret.find('.label-drop').each(function() {$(this).remove()}); // On supprime tout les label-drop (label 'Contenu de la page')
    retHtml = ret.html(); // On récupère le HTML
    retHtml = retHtml.replace(/>[\n\t ]+</g, "><"); // On supprime tout les espaces entre deux balises
    retHtml = retHtml.replace(/[\n\t ]+</g, "<"); // On supprime tout les espaces au début du fichier
    return retHtml; // On retourne l'html
}

function generateHTML() // Genere le HTML à afficher en production
{
    var html = $("<div>" + generateSaveHTML() + "</div>"); // On met le HTML de sauvegarde entre div pour pouvoir utiliser jquery
    html.find('.toolbox-content').each(function() { // On supprimer tout les boutons (Qui sont dans des div qui on la class 'toolbox-content')
        $(this).remove();
    })
    html.find('.label-canvas').remove(); // On supprime tout les label
    // On enleve toutes les class de jqueryui
    html.find('.ui-sortable-handle').removeClass('ui-sortable-handle');
    html.find('.ui-sortable').removeClass('ui-sortable');
    html.find('.ui-droppable').removeClass('ui-droppable');

    html.find('.column-edit').removeClass('column-edit'); // On enleve la class column-edit
    // On enleve les class d'édition pour la remplacer par les class de style en production (CF: template/main.css)
    html.find('.module-edit').each(function() {
        $(this).removeClass('module-edit');
        $(this).addClass('ac-content');
    })
    html.find('.img-editor').addClass('ac-img');
    html.find('.img-editor').removeClass('img-editor');
    html.find('.map-preview').addClass('ac-map');
    html.find('.map-preview').removeClass('map-preview');
    // On ajoute des class différente pour chaque module
    html.find('[data-module="text"]').addClass('ac-module-text');
    html.find('[data-module="youtube"]').addClass('ac-module-youtube');
    html.find('[data-module="vimeo"]').addClass('ac-module-vimeo');
    html.find('[data-module="map"]').addClass('ac-module-map');
    html.find('[data-module="image"]').addClass('ac-module-image');
    return html.html(); // On retourne l'HTML
}

function preview() // Fonction qui gère la prévisualisation de l'article
{
    $("#button-return").show(); // On affiche le bouton retour
    $("#preview").show(); // On affiche la prévisualisation de l'article
    $("#preview").html(generateHTML()); // On genere puis insert l'html généré à l'intérieur de preview
    $("#edit-page").hide(); // On cache la partie édition
}

function edit() // Fonction qui cache la prévisualisation de l'article
{
    $("#button-return").hide(); // On cache le bouton retour
    $("#preview").hide(); // On cache la prévisualisation
    $("#edit-page").show(); // On affiche la page d'édition
}

$("#button-publish").click(function() { // Fonction qui gère la génération d'un fichier HTML pour l'article
    let html = generateHTML(); // On genere l'html
    $.ajax({
        url: 'generate_html.php',
        type: "POST",
        data: {
            'content': html, // On envoie l'html avec l'id de l'article au ficher php
            'id': globalId
        },
        success: function(data) {
            if (data == 'OK') // Si la génération a fonctionné
            {
                $("#success-publish").show(); // On affiche l'alert de succès
                $("#error-publish").hide(); // On cache l'alert d'erreur
            }
            else
            {
                $("#success-publish").hide(); // On cache l'alert de succès
                $("#error-publish").show(); // On affiche l'alert d'erreur
            }
        },
        error: function() { // Si le script na pas pu s'executer correctement
            $("#success-publish").hide(); // On cache l'alert de succès
            $("#error-publish").show(); // On affiche l'alert d'erreur
        }
    })
})
