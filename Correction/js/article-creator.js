$(".column").each(function() { //On récupère toute les colonnes, et on effectue les droppables
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
}

function initMap() { // Temporaire, fonction qui est appellé quand la map s'initialise.
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
