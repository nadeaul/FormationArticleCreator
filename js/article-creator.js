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
    text: "<p>#value</p>",
    image: "<img src='#value'>",
    youtube: '<iframe width="560" height="315" src="https://www.youtube.com/embed/#value" frameborder="0" allowfullscreen></iframe>',
    vimeo: '<iframe src="https://player.vimeo.com/video/#value" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>',
}

var config = {
    youtube: {
        baseUrl: 'https://www.youtube.com/watch?v=#value',
        baseTemplateUrl: 'https://www.youtube.com/embed/#value'
    }
}

var currentIdEdit = null;

var modalTextEditor = null;

function generateUniqueId()
{
    let ret = new Date().getTime();
    console.log(ret);
    return ret;
}

$(".column").each(function() { //On récupère toute les colonnes, et on effectue les droppables
    $(this).draggable({
        revert: true
    })
})

$(".module").each(function() { //On récupère toute les colonnes, et on effectue les droppables
    $(this).draggable({
        revert: true
    })
})

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

function updateDropArea()
{
    $("#drop-area").sortable();
    $(".deleteRow").each(function() {
        $(this).click(function() {
            $(this).parent().parent().remove();
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
        })
    })
}

function saveTextModule()
{
    $("#" + currentIdEdit).find('p').remove();
    $("#" + currentIdEdit).append(modalTextEditor.getData());
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
}

function initMap() {
    var uluru = {lat: -25.363, lng: 131.044};
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 4,
        center: uluru
    });
    var marker = new google.maps.Marker({
        position: uluru,
        map: map
    });
}

ClassicEditor
.create( document.querySelector( '#text-content-area' ) )
.then( editor => {
    modalTextEditor = editor;
} )
.catch( error => {
    console.error( 'Probleme avec l\'editeur de texte' );
} );

$("#text-modal").on('shown.bs.modal', function() {
    $("#text-content-area").focus();
    modalTextEditor.setData('<p>' + $("#" + currentIdEdit).find('p').html() + '</p>');
})

$("#youtube-modal").on('shown.bs.modal', function() {
    $("#youtube-content-url").focus();
    let url = $("#" + currentIdEdit).find('iframe').attr('src');
    let spUrl = url.split('/');
    let value = spUrl[spUrl.length - 1];
    $("#youtube-content-url").val(config.youtube.baseUrl.replace('#value', value));
})
