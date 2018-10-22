<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 25/07/2018
 * Time: 14:28
 */


$conn = ldap_connect("SERVDC1") or die("Impossible de se connecter au serveur LDAP.");
ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option($conn, LDAP_OPT_REFERRALS, 0);


if ($conn) {

    // Authentification anonyme
    $ldapbind = ldap_bind($conn, "******@domaine.lan", "******"); // ANONYME   => BLOQUER le CAS VIDE SINON ANONYME !!!

    $login_rdn = "dc=domaine,dc=lan";

    if ($ldapbind) {
        echo "Connexion LDAP Réussie";
    } else {
        echo "Connexion LDAP Echouée...";
    }

    $read = @ldap_search($conn, 'dc=domaine,dc=lan', "(samaccountname=jdelaunay)", ['mail', 'samaccountname','cn','sn','c','l','title','postalcode','telephonenumber','givenname','company','streetaddress','name'] );
    $data = @ldap_get_entries($conn, $read);
    var_dump($data );


}

