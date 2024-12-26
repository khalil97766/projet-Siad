<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/technicien.css">
  <link rel="stylesheet" href="css/tab.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <title>Tableau de Bord Technicien</title>
</head>
<body>

  <h1>Tableau de Bord Technicien</h1>

  <!-- Menu Hamburger -->
  <input type="checkbox" id="menu-toggle">
 <label for="menu-toggle" class="menu-icon">&#9776;</label>
 
 <div class="sidebar">
    
     <nav>
    <a href="connexion.php">Déconnexion</a>
    <a href="technicien.php">Tableau de bord Technicien</a>
    </nav>
  </div>
  <?php
        include("PHP/config.PHP");
        $query=mysqli_query($con,"select * from `gestionutilisateurs`");
        $row=mysqli_fetch_array($query)
         
  ?>

  <form method="POST" action="technicien.php">
  <div class="main-content">
    <!-- Section Profil Technicien -->
    <section id="info-technicien">
      <h2>Informations du Technicien</h2>
      <p><strong>ID :</strong> <span id="idTechnicien"> 
      <input type="text" id="idUtilisateur" name="id" placeholder="id" required value="<?php echo $row['id'];?>"></span></p>
      <p><strong>Nom :</strong> <span id="nomTechnicien">  
      <input type="text" id="nomUtilisateur" name="Nom" placeholder="Nom" required value="<?php echo $row['Nom'];?>"></span></p>
      <p><strong>Prenom :</strong> <span id="prenomTechnicien">
      <input type="text" id="prenomUtilisateur" name="Prenom" placeholder="Prénom" required value="<?php echo $row['Prenom'];?>"></span></p>
    </section>
</form>
<style>
        /* Fond et police */
        body {
            background-image: url('../images/machine.png'); /* Remplacez par le chemin correct de l'image */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            font-family: Arial, sans-serif;
            color: #fff;
        }

        /* Titre principal */
        h1 {
            background-color: rgba(14, 54, 65, 0.8);
            color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Cartes des machines */
        .card {
            background-color: rgba(87, 113, 126, 0.9);
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        .card .card-title {
            font-weight: bold;
            color: #fff;
        }

        .card .badge {
            font-size: 0.9em;
        }

        .card .btn {
            background-color: #ffae42;
            border: none;
            color: #000;
        }

        .card .btn:hover {
            background-color: #ffc766;
        }

        /* Filtres */
        .card.mb-4 {
            background-color: rgba(87, 113, 126, 0.8);
            color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Tableaux et textes */
        label {
            font-weight: bold;
        }

        .form-select {
            background-color: #57717e;
            color: #fff;
            border: 1px solid #0e3641;
        }

        .form-select:focus {
            border-color: #ffae42;
            box-shadow: 0 0 5px rgba(255, 174, 66, 0.8);
        }
    </style>
<div class="container mt-5">
    <h1 class="text-center mb-4">Tableau de Bord Maintenance</h1>
    
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Filtres</h5>
            <div class="row">
                <div class="col-md-4">
                    <label for="riskFilter" class="form-label">Filtrer par Risque:</label>
                    <select id="riskFilter" class="form-select">
                        <option value="all">Tous</option>
                        <option value="high">Risque Élevé</option>
                        <option value="medium">Risque Moyen</option>
                        <option value="low">Risque Faible</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div id="machinesList" class="row">
        <!-- Machines will be dynamically populated here -->
    </div>
</div>

<script>
$(document).ready(function() {
    // Fetch machine predictions
    $.ajax({
        url: '/technicien/machines',
        type: 'GET',
        success: function(machines) {
            displayMachines(machines);
        },
        error: function() {
            $('#machinesList').html('<div class="alert alert-danger">Erreur de chargement des données</div>');
        }
    });

    function displayMachines(machines) {
        const machinesList = $('#machinesList');
        machinesList.empty();

        machines.forEach(machine => {
            // Determine risk level based on prediction
            const isRepairNeeded = machine["Prediction"];
            const riskLevel = isRepairNeeded ? 'high' : 'low'; // Adjust this logic as needed

            // Create a card for each machine dynamically
            const machineCard = `
                <div class="col-md-4 mb-3" data-risk="${riskLevel}">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">${machine["Product ID"] || "ID Inconnu"} 
                                <span class="badge ${isRepairNeeded ? 'bg-danger' : 'bg-success'} float-end">
                                    ${isRepairNeeded ? 'A reparer' : 'Pas besoin de reparation'}
                                </span>
                            </h5>
                            <p class="card-text">
                                ${Object.entries(machine).map(([key, value]) => `
                                    <strong>${key}:</strong> ${value !== undefined ? value : 'N/A'}<br>
                                `).join('')}
                            </p>
                            <button onclick="scheduleMaintenance('${machine["Product ID"] || "ID Inconnu"}')" class="btn btn-warning">
                                Programmer Maintenance
                            </button>
                        </div>
                    </div>
                </div>
            `;
            machinesList.append(machineCard);
        });
    }

    $('#riskFilter').on('change', function(event) {
        event.preventDefault(); // Prevent default behavior
        const selectedRisk = $(this).val();
        $('.col-md-4').show(); // Show all machines initially
        if (selectedRisk !== 'all') {
            $('.col-md-4').each(function() {
                const cardRisk = $(this).data('risk'); // Get the risk level from data attribute
                if (cardRisk !== selectedRisk) {
                    $(this).hide(); // Hide machines that don't match the selected risk
                }
            });
        }
    });
});
</script>
</head>
</body>
</html>