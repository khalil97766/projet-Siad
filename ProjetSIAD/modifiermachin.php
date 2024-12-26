<?php
      include("PHP/config.PHP");
      $id=$_GET['idmm'];
      $query=mysqli_query($con,"select * from `gestionmachine` where id='$id'");
      $row=mysqli_fetch_array($query);
      
      if (isset($_POST["submit"])) {
        
        $type= $_POST["type"];
        $etatactual= $_POST["etatactual"];
        $datemintenance= $_POST["datemintenance"];
   
        mysqli_query($con,"update `gestionmachine` set type='$type',etatactual='$etatactual',datemintenance='$datemintenance' where id='$id'") ;
    }
  
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>modifier donne machine</title>
  <link rel="stylesheet" href="css/tab.css">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <form method="post" action="gestionmachin.php?idmm=<?php echo $id;?>">
<section id="gestion-utilisateurs">
    <h2>modifier donne machine</h2>

      <input type="text" id="type" name="type" placeholder="type" required value="<?php echo $row['type'];?>">
      <input type="text" id="etatactual" name="etatactual" placeholder="etatactual" required value="<?php echo $row['etatactual'];?>">
      <input type="date" id="datemintenance" name="datemintenance" placeholder="datemintenance" required value="<?php echo $row['datemintenance'];?>">
      <button type="submit" name="submit">modifier</button>
</form>
</body>
</head>
</html>