<!DOCTYPE html>
<html lang="en">
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css " rel=stylesheet>
<head>
    <meta charset="UTF-8">
    <style>
    nav { background-image: url("back4.jpg"); }
</style>
    <nav class="navbar navbar-default">
  <div class="page-header">
    
      <h1 style="text-align: center;"><img src="msv_transparente.png" alt="..." class="img-rounded"></h1>
    
  </div>
</nav>
</head> 

<?php
$ldapconfig['host'] = 'localhost';
$ldapconfig['port'] = 389; // es el 389 però amb NULL l'agafa per defecte
$ldapconfig['basedn'] = 'ou=usuaris,dc=sergio,dc=lizana';
$ldapconfig['authrealm'] = 'My Realm';

if((isset($_POST['usuari'])) && (isset($_POST['password']))){
    $username = $_POST['usuari'];
    $passw = $_POST['password'];
}
 
echo "Inici...</br>";
 
$ds = ldap_connect($ldapconfig['host'],$ldapconfig['port']);
 
if( !$ds ) {
    echo "Error en la connexio</br>";
    exit(0);
}

if((isset($_POST['usuari'])) && (isset($_POST['password']))){
    echo "buscant usuari... <b>".$username."</b><br>\n";
    $r = ldap_search( $ds, $ldapconfig['basedn'], 'uid=' . $username );
     
    if ($r) {
        $result = ldap_get_entries( $ds, $r);
        //var_dump($result);
        //echo "\n<br><br>\n";
        if (count($result)>0) {
            if($result[0]) {
                echo "usuari trobat: <b>".$username."</b><br>\n";
                // Eps! si no, no va!
                ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
                // check passw
                if (ldap_bind( $ds, $result[0]['dn'], $passw) ) {
                    echo "Contrasenya OK<br>\n";
                } else {
                    echo "Contrasenya ERRONIA<br>\n";
                }
            } else {
    		echo "Usuari <b>".$username."</b> no trobat";
    	   }
        }
    } else {
    	echo "Error accedint a LDAP";
    }
}
?>
<body>
    <div class="container register">
    <div class="panel panel-default">
    <h1 class="text-center">Login</h1>
    <div class="panel-body">
    <form method="post">
    <p class="text-center">
        <label>Usuari:</label><br>
        <input type="text" name="usuari" size="32"><br>
    </p>
    <p class="text-center">
        <label>Password:</label><br>
        <input type="password" name="password" size="32"><br>
    </p>
    <p class="text-center">
        <input type="submit" name="entrar" value="Entra"/>
    </p>
        
    </form> 

    </div>
    </div>
    </div>


</body>
</html>