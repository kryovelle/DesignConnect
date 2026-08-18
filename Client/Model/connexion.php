<?php

Class Connexion{

public $name = "designconnect";   // the DATABASE NAME
public $host = "localhost";    // the SERVER address where MySQL is running
public $user = "root";         // the MySQL USERNAME to log in with
public $password = "";         // the MySQL PASSWORD (empty here = no password)

protected function connecterBDD($nom, $host, $user, $password)
    {
        try {
            $dataBase = new PDO("mysql:host=$host;dbname=$nom;charset=utf8", $user, $password);
            $dataBase->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $error) {
            die("Erreur lors de la connexion à la base de données : " . $error->getMessage());
        }
        return $dataBase;
    }

    public function requete($BDD, $query)
    {
        $stmt = $BDD->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function deconnecterBDD(&$dataBase)
    {
        $dataBase = null;
    }


}


// ?>