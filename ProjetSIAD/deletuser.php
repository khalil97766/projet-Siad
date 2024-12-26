<?php
      include("PHP/config.PHP");
      $sql="DELETE FROM `gestionutilisateurs` WHERE id='". $_GET["ids"] ."'";
      if(mysqli_query($con,$sql,$sqle)){
            echo"delete succes";
      }      else{
            echo"nam".mysqli_error($con);
      }
      header("location: tab.php");
      ?>