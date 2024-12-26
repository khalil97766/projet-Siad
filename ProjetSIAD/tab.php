<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion des Utilisateurs</title>
  <link rel="stylesheet" href="css/tab.css">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php
include("PHP/config.PHP"); // Ensure this file initializes $con

if (isset($_POST["submit"])) {

    $Nom= $_POST["Nom"];
    $Prenom= $_POST["Prenom"];
    $Role= $_POST["Role"];
    $Username= $_POST["Username"];
    $Password= $_POST["Password"];

	mysqli_query($con,"INSERT INTO gestionutilisateurs(Nom,Prenom,Role,Username,Password) 
  VALUES ('$Nom','$Prenom','$Role','$Username','$Password')") ;
	mysqli_query($con,"INSERT INTO connexion(Username,Password) VALUES ('$Username','$Password')") ;
}

// Close statement and connection
$con->close()
?>
  <h1>Tableau de Bord Administrateur</h1>
 <!-- Menu Hamburger -->

 <input type="checkbox" id="menu-toggle">
 <label for="menu-toggle" class="menu-icon">&#9776;</label>
 
 <div class="sidebar">
    
     <nav>
         <a href="index.php">Accueil</a>
         <a href="gestionmachin.php">gestion de machine</a>
         <a href="tab.php">gestion d'utilisateur</a> <!-- Vérifie que ce fichier existe -->
         <a href="connexion.php">Déconnexion</a>
     </nav>
 </div>

  
  <section id="gestion-utilisateurs">
    <h2>Gestion des Utilisateurs</h2>
    <table id="tableauUtilisateurs">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nom</th>
          <th>Prénom</th>
          <th>Rôle</th>
          <th>Username</th>
          <th>Password</th>
          <th>Modifier</th>
          <th>Supprimer</th>
        </tr>
      </thead>
      <tbody>
        
      <?php
        include("PHP/config.PHP");
        $query=mysqli_query($con,"select * from `gestionutilisateurs`");
        while($row=mysqli_fetch_array($query)){
        ?>
        <tr>

          <td><?php echo $row['id'];?></td>
          <td><?php echo $row['Nom'];?></td>
          <td><?php echo $row['Prenom'];?></td>
          <td><?php echo $row['Role'];?></td>
          <td><?php echo $row['Password'];?></td>
          <td><?php echo $row['Username'];?></td>
          <td><a class="btn-connect" href="modifieruser.php ? idm=<?php echo $row ['id'];?>">modifier</td>
          <td><a class="btn-connect" type="submit" href="deletuser.php?ids=<?php echo $row ['id'];?>">supprimer</td>
        </tr>
        <?php
        
      }
        ?>
      
      </tbody>
    </table>
    <form id="formulaireUtilisateur" method="post" action="tab.php">
      
      <input type="text" id="nomUtilisateur" name="Nom" placeholder="Nom" required>
      <input type="text" id="prenomUtilisateur" name="Prenom" placeholder="Prénom" required>
      <input type="text" id="roleUtilisateur" name="Role" placeholder="Rôle" required>
      <input type="text" id="Username" name="Username" placeholder="Username" required>
      <input type="text" id="Password" name="Password" placeholder="Password" required>
      <button type="submit" name="submit" onclick="ajouterUtilisateur()">Ajouter Utilisateur</button>
    </form>
  </section>

  <!-- gestion des machines -->
  

  <script src="tab.js"></script>
</body>
</html>