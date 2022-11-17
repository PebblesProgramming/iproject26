<?php

require(__DIR__ ."/../interfaces/IUser.php");

abstract class Database
{
    protected const TABLE_NAME = null;
    protected const PRIMARY_KEY = null;

    private const DB_LOGIN = "iproject26";
    private const DB_HOST = "sql.ip.aimsites.nl";
    private const DB_DATABASE = "iproject26";

    // private const DB_LOGIN = "sa";
    // private const DB_HOST = "database_server";
    // private const DB_DATABASE = "eenmaalandermaal";

    private const FETCH_METHOD = PDO::FETCH_CLASS;


    private static $verbinding;

    public function __construct() {
        $driver_options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];

        try {
            $password = "/6GZXKR/3StXuDcit205dceRlBqRe+R3";
            // $password = "Bam1schijf";
            self::$verbinding = new PDO(
                "sqlsrv:Server=" . self::DB_HOST . ";Database= " . self::DB_DATABASE, self::DB_LOGIN, $password,
                $driver_options
            );
            // Bewaar het wachtwoord niet langer onnodig in het geheugen van PHP.
            unset($wachtwoord);
        } catch (Exception $e) {
            print_r($e->getMessage());
        }
    }

    public function getVerbinding() {
        return self::$verbinding;
    }

    public function findOne($index) {
        if(static::TABLE_NAME == null
           || static::PRIMARY_KEY == null ) {
            throw new \Exception("protected const TABLE_NAME or protected const PRIMARY_KEY is not set");
        }
        try {
            $tableName = static::TABLE_NAME;
            $primaryKey = static::PRIMARY_KEY;
            $stmt = self::$verbinding->prepare("SELECT * FROM $tableName WHERE $primaryKey = ?");
            $stmt->setFetchMode(PDO::FETCH_CLASS, static::class);
            $stmt->execute([$index]);
            return $stmt->fetch();
        } catch(\PDOException $e) {
            throw new DatabaseError("No records found", static::class, $e);
        }
    }

    // Used for selecting all
    public function query(string $sql)
    {
        $stmt = self::$verbinding->query($sql);
        return $stmt->fetchAll(self::FETCH_METHOD, get_class($this));
    }

    public function select(string $sql, array $queryParams) {
        $stmt = self::$verbinding->prepare($sql);
        foreach($queryParams as $key=>$queryParam) {
            $stmt->bindParam($key, $queryParam);
        }
        $stmt->execute();
        return $stmt->fetchAll(self::FETCH_METHOD, get_class($this));
    }

    public function simpleSelect(string $sql, array $queryParams) {
        $stmt = self::$verbinding->prepare($sql);
        $stmt->execute($queryParams);
        return $stmt->fetchAll(self::FETCH_METHOD, get_class($this));
    }

    public function innerJoin(string $sql, array $queryParams) {
        $stmt = self::$verbinding->prepare($sql);
        foreach($queryParams as $key=>$queryParam) {
            $stmt->bindParam($key, $queryParam);
        }
        $stmt->execute();
        return $stmt->fetchAll(self::FETCH_METHOD);
    }

    public function save() {
        // $stmt = self::$verbinding->query();
        $tablename = static::TABLE_NAME;
        $sql = "INSERT INTO $tablename VALUES(";
        foreach($this as $key=>$_val) {
            $sql .= ":$key, ";
        }
        $sql = substr($sql, 0, -2);
        $sql .= ");";
        $stmt = self::$verbinding->prepare($sql);

        $paramArray = [];
        foreach($this as $key=>$val) {
            if(preg_match("/\d{4}-\d{2}-\d{2}/", $val)) {
                $val = date('Y-m-d', strtotime($val));
            }
            $paramArray[$key] = $val;
        }
        $stmt->execute($paramArray);
    }

    public function update() {
        // $stmt = self::$verbinding->query();
        $tablename = static::TABLE_NAME;
        $primaryKey = static::PRIMARY_KEY;
        $sql = "UPDATE $tablename SET ";
        foreach($this as $key=>$_val) {
            if($key === "save") {
                continue;
            }
            $sql .= "$key = :$key, ";
        }
        $sql = substr($sql, 0, -2);
        $sql .= " WHERE $primaryKey = :{$primaryKey}where;";
        $stmt = self::$verbinding->prepare($sql);

        $paramArray = [];
        foreach($this as $key=>$val) {
            if(preg_match("/\d{4}-\d{2}-\d{2}/", $val)) {
                $val = date('Y-m-d', strtotime($val));
            }
            $paramArray[$key] = $val;
        }

        $paramArray[$primaryKey."where"] = $paramArray[$primaryKey];
        $stmt->execute($paramArray);
    }

    public function echo(string $columnName) {
        if (in_array($columnName, array_keys(get_class_vars(static::class)))) {
            echo $this->$columnName;
        }
    }

    public function checkFields(): bool {
        if($this instanceof IUser) {
            if($this->validate($_POST)) {
                return true;
            }
        }
        return false;
    }
}
