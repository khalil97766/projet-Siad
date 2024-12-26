<?php
      include("PHP/config.PHP");
      $sql="DELETE FROM `last_data` WHERE id='". $_GET["idm"] ."'";
      if(mysqli_query($con,$sql,$sqle)){
            echo"delete succes";
      }      else{
            echo"nam".mysqli_error($con);
      }
      header("location: technicien.php");
      ?>