function uploadDataset() {
    var fileInput = document.getElementById('csvFile');
    var file = fileInput.files[0];
    var formData = new FormData();
    formData.append('file', file);

    $.ajax({
        url: '/upload',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.columns) {
                $('#uploadResponse').html(`Dataset chargé: ${file.name}`);
                populateColumnSelections(response.columns);
                $('#columnSelection').show();
                $('#modelSelection').show();
            } else {
                $('#uploadResponse').html(response.message).addClass('text-danger');
            }
        },
        error: function() {
            $('#uploadResponse').html('Échec du téléchargement').addClass('text-danger');
        }
    });
}

function populateColumnSelections(columns) {
    var featuresHtml = columns.map(col => 
        `<div class="form-check">
            <input class="form-check-input" type="checkbox" value="${col}" id="feature_${col}">
            <label class="form-check-label" for="feature_${col}">${col}</label>
        </div>`
    ).join('');
    $('#featureColumns').html(featuresHtml);

    var targetHtml = columns.map(col => 
        `<div class="form-check">
            <input class="form-check-input" type="radio" name="target" value="${col}" id="target_${col}">
            <label class="form-check-label" for="target_${col}">${col}</label>
        </div>`
    ).join('');
    $('#targetColumns').html(targetHtml);
}

function trainModel() {
    var features = $('#featureColumns input:checked').map(function() {
        return this.value;
    }).get();

    var target = $('#targetColumns input:checked').val();

    if (features.length === 0 || !target) {
        alert('Veuillez sélectionner des attributs et une colonne cible');
        return;
    }

    $.ajax({
        url: '/train',
        type: 'POST',
        data: {
            features: features,
            target: target,
            model: $('#modelType').val(),
            problem_type: $('#problemType').val()
        },
        success: function(response) {
            $('#trainingResults').html(response.message);
            if (response.cm_image) {
                $('#confusionMatrix').html(`<img src="data:image/png;base64,${response.cm_image}" alt="Matrice de Confusion" class="img-fluid"/>`);
            }
            if (response.feature_importance_image) {
                $('#featureImportance').html(`<img src="data:image/png;base64,${response.feature_importance_image}" alt="Importance des Caractéristiques" class="img-fluid"/>`);
            }
        },
        error: function() {
            $('#trainingResults').html('Échec de l\'entraînement du modèle').addClass('text-danger');
        }
    });
}

function makePredictions() {
    $.ajax({
        url: '/predict',
        type: 'POST',
        success: function(response) {
            if (response.message) {
                $('#predictions').html(response.message);
            } else {
                $('#predictions').html('<pre>' + JSON.stringify(response, null, 2) + '</pre>');
            }
        },
        error: function() {
            $('#predictions').html('Échec de la prédiction').addClass('text-danger');
        }
    });
}