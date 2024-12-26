<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion des Machines</title>
  <link rel="stylesheet" href="css/tab.css">
  <link rel="stylesheet" href="css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<?php
$conn = mysqli_connect("localhost","root","","projetsaid");// Ensure this file initializes $con

if (isset($_POST["submit"])) {

    $type= $_POST["type"];
    $etatactual= $_POST["etatactual"];
    $datemintenance= $_POST["datemintenance"];

	mysqli_query($conn,"INSERT INTO gestionmachine(type,etatactual,datemintenance) VALUES ('$type','$etatactual','$datemintenance')") ;
}

// Close statement and connection
$conn->close()
?>
  <h1>Tableau de Bord Administrateur</h1>
 <!-- Menu Hamburger -->

 <input type="checkbox" id="menu-toggle">
 <label for="menu-toggle" class="menu-icon">&#9776;</label>
 
 <div class="sidebar">
     <div class="logo">Mon Logo</div>
     <nav>
         <a href="index.php">Accueil</a>
         <a href="gestionmachin.php">gestion de machine</a>
         <a href="tab.php">gestion d'utilisateur</a> <!-- Vérifie que ce fichier existe -->
         <a href="connexion.php">Déconnexion</a>
     </nav>
 </div>

  
  <section id="gestion-utilisateurs">
    <h2>Gestion des Machines</h2>
    <table id="tableauUtilisateurs">
      <thead>
        <tr>
          <th>ID</th>
          <th>TYPE</th>
          <th>etatactual</th>
          <th>datemintenance</th>

          <th>Modifier</th>
          <th>Supprimer</th>
        </tr>
      </thead>
      <tbody>
      <?php
        include("PHP/config.PHP");
        $query=mysqli_query($con,"select * from `gestionmachine`");
        while($row=mysqli_fetch_array($query)){
        ?>
        <tr>

          <td><?php echo $row['id'];?></td>
          <td><?php echo $row['type'];?></td>
          <td><?php echo $row['etatactual'];?></td>
          <td><?php echo $row['datemintenance'];?></td>

          <td><a class="btn-connect" href="modifiermachin.php?idmm=<?php echo $row ['id'];?>">modifier</td>
          <td><a class="btn-connect" type="submit" href="deletmachin.php ? ids=<?php echo $row ['id'];?>">supprimer</td>
        </tr>
        <?php
        
      }
        ?>
      </tbody>
    </table>
    <form id="formulaireUtilisateur" method="post" action="gestionmachin.php">

      <input type="text" id="type" name="type" placeholder="type" required>
      <input type="text" id="etatactual" name="etatactual" placeholder="etatactual" required>
      <input type="date" id="datemintenance" name="datemintenance" placeholder="datemintenance" required>

      <button type="submit" name="submit" onclick="ajouterMachine()">Ajouter machine</button>
    </form>
  </section>

  
    <style>
        /* Général */
        body {
            background-image: url("../images/machine.png");
            /* Chemin vers l'image de fond */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            font-family: Arial, sans-serif;
            color: #fff;
            /* Couleur de texte pour une meilleure lisibilité sur le fond */
        }

        /* Titre principal */
        h1 {
            text-align: center;
            padding: 20px;
            background-color: #0e3641;
            /* Fond semi-transparent */
            color: #fff;
            margin: 20px;
            border-radius: 8px;
        }

        /* Conteneur principal */
        .container {
            max-width: 900px;
            margin: 20px auto;
        }

        /* Sections */
        .card {
            background-color: #57717ede;
            /* Fond semi-transparent */
            border: none;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .card-title {
            font-weight: bold;
        }

        /* Tableaux */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table,
        th,
        td {
            border: 1px solid #fff;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #0e3641;
        }

        button {
            padding: 10px 15px !important;
            border: none !important;
            border-radius: 4px !important;
            background-color: #0e3641 !important;
            color: white !important;
            cursor: pointer !important;
        }

        button:hover {
            background-color: #57717e !important;
        }


        /* Barre latérale */
        #menu-toggle {
            display: none;
        }

        .menu-icon {
            font-size: 2em;
            position: fixed;
            top: 20px;
            left: 20px;
            cursor: pointer;
            color: #fff;
            z-index: 2;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: -250px;
            width: 250px;
            height: 100%;
            background-color: #57717e;
            transition: left 0.3s ease;
            padding-top: 120px;
            z-index: 1;
        }

        #menu-toggle:checked+.menu-icon+.sidebar {
            left: 0;
        }

        .sidebar nav a {
            display: block;
            padding: 15px 20px;
            font-size: 1.2em;
            color: #fff;
            text-decoration: none;
        }

        .sidebar nav a:hover {
            background-color: #0e3641;
        }
    </style>
</head>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Maintenance Prédictive</h1>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Charger votre dataset</h5>
                <div class="mb-3">
                    <label for="csvFile" class="form-label">Choisir fichier Csv</label>
                    <input type="file" id="csvFile" accept=".csv" class="form-control" title="Upload your CSV dataset">
                </div>
                <button onclick="uploadDataset()" class="btn btn-primary">Charger</button>
                <div id="uploadResponse" class="mt-3 text-success"></div>
            </div>
        </div>

        <div id="columnSelection" class="card mb-4" style="display:none;">
            <div class="card-body">
                <h5 class="card-title">Sélectionner colonnes:</h5>
                <div class="row">
                    <div class="col-md-6">
                        <h6>Sélectionner attributs</h6>
                        <div id="featureColumns" class="overflow-auto" style="max-height: 300px;"></div>
                    </div>
                    <div class="col-md-6">
                        <h6>Sélectionner Target</h6>
                        <div id="targetColumns" class="overflow-auto" style="max-height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div id="modelSelection" class="card mb-4" style="display:none;">
            <div class="card-body">
                <h5 class="card-title">Configuration du modèle</h5>
                <div class="row">
                    <div class="col-md-4">
                        <label for="problemType" class="form-label">Type de Problème:</label>
                        <select id="problemType" class="form-select" title="Choisir le type de problème">
                            <option value="classification">Classification</option>
                            <option value="regression">Régression</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="modelType" class="form-label">Modèle:</label>
                        <select id="modelType" class="form-select" title="Sélectionner le modèle">
                            <option value="RandomForest">Random Forest</option>
                            <option value="LogisticRegression">Régression Logistique</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button onclick="trainModel()" class="btn btn-success mt-4">Entraîner modèle</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Résultats</h5>
                <div id="trainingResults" class="mb-3"></div>
                <div id="confusionMatrix" class="mt-4"></div>
                <div id="featureImportance" class="mt-4"></div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Prédictions</h5>
                <button onclick="makePredictions()" class="btn btn-info">Prédire</button>
                <div id="predictions" class="mt-3 prediction-results"></div>
            </div>
        </div>
    </div>

    <script>
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
                success: function (response) {
                    if (response.columns) {
                        $('#uploadResponse').html(`Dataset chargé: ${file.name}`);
                        populateColumnSelections(response.columns);
                        $('#columnSelection').show();
                        $('#modelSelection').show();
                    } else {
                        $('#uploadResponse').html(response.message).addClass('text-danger');
                    }
                },
                error: function () {
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
            var features = $('#featureColumns input:checked').map(function () {
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
                success: function (response) {
                    $('#trainingResults').html(response.message);
                    if (response.cm_image) {
                        $('#confusionMatrix').html(`<img src="data:image/png;base64,${response.cm_image}" alt="Matrice de Confusion" class="img-fluid"/>`);
                    }
                    if (response.feature_importance_image) {
                        $('#featureImportance').html(`<img src="data:image/png;base64,${response.feature_importance_image}" alt="Importance des Caractéristiques" class="img-fluid"/>`);
                    }
                },
                error: function () {
                    $('#trainingResults').html('Échec de l\'entraînement du modèle').addClass('text-danger');
                }
            });
        }

        function makePredictions() {
            $.ajax({
                url: '/predict',
                type: 'POST',
                success: function (response) {
                    if (response.message) {
                        $('#predictions').html(response.message);
                    } else {
                        $('#predictions').html('<pre>' + JSON.stringify(response, null, 2) + '</pre>');
                    }
                },
                error: function () {
                    $('#predictions').html('Échec de la prédiction').addClass('text-danger');
                }
            });
        }
    </script>
  

  <script src="prediction.js"></script>
</body>
</html>
