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

var defaultTemplate = {
    text: "<div class='edit-text-template'>#value</div>",
    image: "<img src='#value' class='img-editor' alt='Image'>",
    youtube: '<iframe width="560" height="315" src="https://www.youtube.com/embed/#value" frameborder="0" allowfullscreen></iframe>',
    vimeo: '<iframe src="https://player.vimeo.com/video/#value" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>',
}

var config = {
    youtube: {
        baseUrl: 'https://www.youtube.com/watch?v=#value',
        baseTemplateUrl: 'https://www.youtube.com/embed/#value'
    },
    vimeo: {
        baseUrl: 'https://vimeo.com/#value',
        baseTemplateUrl: 'https://player.vimeo.com/video/#value'
    },
    map: {
        active: false
    }
}

var currentIdEdit = null;

var modalTextEditor = null;

var previewMap = null;
var previewMarker = null;

function generateUniqueId()
{
    let ret = new Date().getTime();
    return ret;
}

$("#drop-area").sortable();

$("#drop-area").droppable({
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
        updateDropArea();
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

function updateDropArea()
{
    $("#drop-area").sortable();
    $(".deleteRow").each(function() {
        $(this).click(function() {
            $(this).parent().parent().remove();
            updateDropArea();
        })
    })
    $(".column-edit").each(function() {
        $(this).sortable();
        $(this).droppable({
            accept: ".module", // On accepte que des modules
            drop: function(e, ui) {
                let data = ui.draggable[0].dataset['module'];
                let newDiv = $('<div class="module-edit"></div>');
                newDiv.attr('data-module', data);
                newDiv.attr('id', generateUniqueId());
                newDiv.append('<div class="toolbox-content"><button class="btn btn-info editModule"><i class="fa fa-edit"></i></button><button class="btn btn-danger deleteModule"><i class="fa fa-times"></i></button></div>');
                if (data == 'map')
                {
                    newDiv.append('<div id="map' + generateUniqueId() + '" class="map-preview" data-lat="' + defaultModuleValues.map.lat + '" data-lon="' + defaultModuleValues.map.lon + '" data-zoom="' + defaultModuleValues.map.zoom + '"></div>')
                    $(this).append(newDiv);
                }
                else
                {
                    let template = defaultTemplate[data];
                    let value = defaultModuleValues[data];
                    template = template.replace('#value', value);
                    newDiv.append(template);
                    $(this).append(newDiv);
                }
                updateDropArea();
            }
        })
    });
    $(".editModule").each(function() {
        $(this).click(function() {
            currentIdEdit = $(this).parent().parent().attr('id');
            currentDataEdit = $(this).parent().parent().attr('data-module');
            $("#" + currentDataEdit + "-modal").modal('show');
        })
    })
    $(".deleteModule").each(function() {
        $(this).click(function() {
            $(this).parent().parent().remove();
            updateDropArea();
        })
    })
    if (config.map.active)
    {
        $(".map-preview").each(function() {
            let lat = parseFloat($(this).attr('data-lat'));
            let lon = parseFloat($(this).attr('data-lon'));
            let zoom = parseInt($(this).attr('data-zoom'));
            let id = $(this).attr('id');
            var map = new GMaps({
                div: '#' + id,
                lat: lat,
                lng: lon,
                zoom: zoom,
                disableDefaultUI: true,
                draggable: false,
                zoomControl: false,
                scrollwheel: false,
                disableDoubleClickZoom: true
            })
            console.log('marker position');
            map.addMarker({
                lat: lat,
                lng: lon,
                title: 'Position'
            });
        })
    }
    $.ajax({
        url: 'send_modification.php',
        type: "POST",
        data: {
            id: globalId,
            content: generateSaveHTML()
        },
        success: function(data) {
            console.log(data);
        },
        error: function(data) {
            console.log(data);
        }
    })
}

function saveTextModule()
{
    console.log(modalTextEditor.val());
    $("#" + currentIdEdit).find('.edit-text-template').html(modalTextEditor.val());
    updateDropArea();
}

function saveYoutubeModule()
{
    let iframeY = $("#" + currentIdEdit).find('iframe');
    let regex = /[?&]([^=#]+)=([^&#]*)/g;
    let url = $("#youtube-content-url").val();
    let match;
    let value = '';
    while(match = regex.exec(url)) {
        if (match[1] == 'v')
        {
            value = match[2];
            break;
        }
    }
    if (value == '')
    {
        spUrl = url.split('/');
        value = spUrl[spUrl.length - 1];
    }
    iframeY.attr('src', config.youtube.baseTemplateUrl.replace('#value', value));
    updateDropArea();
}

function saveVimeoModule()
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

function saveImageModule()
{
    let img = $("#preview-image").attr('src');
    let alt = $("#alt-text").val();
    $("#" + currentIdEdit).find('img').attr('alt', alt);
    $("#" + currentIdEdit).find('img').attr('src', img);
    updateDropArea();
}

function saveMapModule()
{
    let map = $("#" + currentIdEdit).find('map-preview');
    map.attr('data-lat'. $("#map-content-lat").val());
    map.attr('data-lon'. $("#map-content-lon").val());
    map.attr('data-zoom'. $("#map-content-zoom").val());
    updateDropArea();
}

function initMap() {
    config.map.active = true;
    updateDropArea();
}

$(function() {
    $("#text-content-area").ckeditor();
    modalTextEditor = $("#text-content-area");
    $('[data-toggle="popover"]').popover();
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

$("#map-content-lat").change(function() {
    previewMap.setCenter(parseFloat($("#map-content-lat").val()), parseFloat($("#map-content-lon").val()));
    previewMarker.setPosition({lat: parseFloat($("#map-content-lat").val()), lng: parseFloat($("#map-content-lon").val())})
    previewMap.refresh();
    console.log(previewMap);
});

$("#map-content-lon").change(function() {
    previewMap.setCenter(parseFloat($("#map-content-lat").val()), parseFloat($("#map-content-lon").val()));
});

$("#map-content-zoom").change(function() {
    previewMap.setZoom(parseFloat($("#map-content-zoom").val()))
})

function generateSaveHTML()
{
    var htmlSave = $("#drop-area").html();
    let ret = $('<div>' + htmlSave + '</div>');
    console.log(ret);
    ret.find('.label-drop').each(function() {$(this).remove()});
    retHtml = ret.html();
    retHtml = retHtml.replace(/>[\n\t ]+</g, "><");
    retHtml = retHtml.replace(/[\n\t ]+</g, "<")
    console.log(retHtml);
    return retHtml;
}

function generateHTML()
{
    var html = $("<div>" + generateSaveHTML() + "</div>");
    console.log(html.html());
    html.find('.toolbox-content').each(function() {
        $(this).remove();
    })
    html.find('.label-canvas').remove();
    html.find('.ui-sortable-handle').removeClass('ui-sortable-handle');
    html.find('.ui-sortable').removeClass('ui-sortable');
    html.find('.ui-droppable').removeClass('ui-droppable');
    html.find('.column-edit').removeClass('column-edit');
    html.find('.module-edit').each(function() {
        $(this).removeClass('module-edit');
        $(this).addClass('ac-content');
    })
    html.find('.img-editor').addClass('ac-img');
    html.find('.img-editor').removeClass('img-editor');
    html.find('.map-preview').addClass('ac-map');
    html.find('.map-preview').removeClass('map-preview');
    html.find('[data-module="text"]').addClass('ac-module-text');
    html.find('[data-module="youtube"]').addClass('ac-module-youtube');
    html.find('[data-module="vimeo"]').addClass('ac-module-vimeo');
    html.find('[data-module="map"]').addClass('ac-module-map');
    html.find('[data-module="image"]').addClass('ac-module-image');
    return html.html();
}

$("#button-return").click(edit);
$("#button-preview").click(preview);

function preview()
{
    $("#button-return").show();
    $("#preview").show();
    $("#preview").html(generateHTML());
    $("#edit-page").hide();
}

function edit()
{
    $("#button-return").hide();
    $("#preview").hide();
    $("#edit-page").show();
}

$("#button-publish").click(function() {
    let html = generateHTML();
    $.ajax({
        url: 'generate_html.php',
        type: "POST",
        data: {
            'content': html,
            'id': globalId
        },
        success: function(data) {
            if (data == 'OK')
            {
                $("#success-publish").show();
                $("#error-publish").hide();
            }
            else
            {
                $("#success-publish").hide();
                $("#error-publish").show();
            }
        },
        error: function() {

        }
    })
})
