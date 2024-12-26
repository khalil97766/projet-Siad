<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="css/connexion.css">
</head>
<body>
<?php
		include("php/config.php");
		if (isset($_POST['submit'])) {
			 
			$Username = $_POST['Username'];
			$Password = $_POST['Password'];

			$querye = "SELECT * FROM connexion WHERE Username = '$Username' AND Password = '$Password'";
			$result = mysqli_query($con,$querye);
            $row=mysqli_fetch_array($result);
			
				if($row["Usertype"]=="admin"){

			                  header("location: tab.php");
			                   exit();
				}
                elseif ($row["Usertype"]=="user") {

                    header("location: technicien.php");
                     exit();
                }
                else{
                    
                    header("location: connexion.php");
                    exit();
                }
		   $con->close(); 
		}
		?>
    <div class="login-container">
        <h2>Connexion</h2>
        <form  method="POST" action="connexion.php">
        <form  method="POST" action="technicien.php">
            <div class="input-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="Username" name="Username" required>
            </div>
            <div class="input-group">
			
                <label for="password">Mot de passe</label>
                <input type="password" id="Password" name="Password" required>
            </div>
            <button type="submit" class="login-button" name ="submit" href="technicien.php?idt=<?php echo $row ['Username'];?>" >Se connecter</button>
        </form>
    </form>
    </div>
</body>
</html>	