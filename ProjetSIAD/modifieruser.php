<?php
      include("PHP/config.PHP");
      $id=$_GET['idm'];
      $query=mysqli_query($con,"select * from `gestionutilisateurs` where id='$id'");
      $row=mysqli_fetch_array($query);
      
      if (isset($_POST["submit"])) {
        $Nom = $_POST["Nom"];
        $Prenom = $_POST["Prenom"];
        $Role = $_POST["Role"];
        $Username = $_POST["Username"];
        $Password = $_POST["Password"];
   
        mysqli_query($con,"update `gestionutilisateurs` set  Nom='$Nom',Prenom='$Prenom',Role='$Role',Username='$Username'
        ,Password='$Password' where id='$id'") ;
        mysqli_query($con,"update `connexion`  set Username='$Username',Password='$Password' where id='$id'") ;
    }
  
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>modifier donne user</title>
  <link rel="stylesheet" href="css/tab.css">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <form method="POST" action="tab.php?idm=<?php echo $id;?>">
<section id="gestion-utilisateurs">
    <h2>modifier donne user</h2>
      <input type="text" id="nomUtilisateur" name="Nom" placeholder="Nom" required value="<?php echo $row['Nom'];?>">
      <input type="text" id="prenomUtilisateur" name="Prenom" placeholder="Prénom" required value="<?php echo $row['Prenom'];?>">
      <input type="text" id="roleUtilisateur" name="Role" placeholder="Rôle" required value="<?php echo $row['Role'];?>">
      <input type="text" id="Username" name="Username" placeholder="Username" required value="<?php echo $row['Username'];?>">
      <input type="text" id="Password" name="Password" placeholder="Password" required value="<?php echo $row['Password'];?>">
      <button type="submit" name="submit">modifier</button>
</form>
</body>
</head>
</html>