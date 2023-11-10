<?php
declare(strict_types=1);


class ConnectionFactory{

    private static $ini_array;

    static function setConfig($file){
        if (file_exists($file)){
            $array = parse_ini_file($file);
            if (isset($array['host']) && isset($array['username']) && isset($array['password']) && isset($array['database_name'])){
                ConnectionFactory::$ini_array=$array;
            }else{
                echo "ERROR: contenu fichier non valide";
            }
        }else{
            echo "ERROR: fichier de configuration n'existe pas";
        }
    }

    static function makeConnection(): PDO{
        $db = null;
        if (isset(self::$ini_array)){
            $dsn = "mysql:host=".self::$ini_array['host'].";dbname=".self::$ini_array['database_name'];
            $db = new PDO($dsn, self::$ini_array['username'], self::$ini_array['password'], [
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_STRINGIFY_FETCHES => false,
            ]);
        }
        $db->prepare('SET NAMES \'UTF8\'')->execute();
        return $db;
    }
}