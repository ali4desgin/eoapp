<?php


class DBase  {


    private $db_host;
    private $db_user;
    private $db_pass;
    private $db_driver;
    private $db_name;


    public $dbconn;

    function  __construct()
    {
        // connet to db
        $this->handel_db_vars();
        $this->connect();

    }


    function  __destruct()
    {
        // TODO: Implement __destruct() method.
        $this->close_connection();
    }




    private function handel_db_vars(){

        $this->db_host = env("DB_HOST");
        $this->db_user = env("DB_USER");
        $this->db_pass = env("DB_PASS");
        $this->db_driver = env("DB_DRIV");
        $this->db_name = env("DB_NAME");
    }



    private function connect(){


        $this->dbconn = new PDO($this->db_driver.':host='.$this->db_host.';dbname='.$this->db_name, $this->db_user, $this->db_pass);




        if($this->dbconn->errorCode()!=null){
            return false;
        }

        $this->dbconn->exec("set names utf8 ");

    }



    private  function  close_connection(){
        $this->dbconn = null;
    }




    public function DB_Query($sql){
        $stmt = $this->dbconn->prepare($sql);
        return $stmt->execute();
    }


    public  function DB_Insert_With_Last_Id($sql){
        $commit = $this->DB_Query($sql);

        if($commit)
            return $this->dbconn->lastInsertId();

            
        return 0;

    }



    public  function DB_Row_Count($sql){
        $stmt = $this->dbconn->prepare($sql);
        $stmt->execute();
        return $stmt->rowCount();
        

    }



    public function DB_Login_Query_Via_Email($table, $email,$password){
        $sql = "SELECT * FROM `{$table}` WHERE email = '{$email}'";
        $resposne = [];
        $stmt = $this->dbconn->prepare($sql);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $rowCount = $stmt->rowCount();

        if($rowCount<=0){
            $resposne["login"] = false;
            $resposne["message"] = "email not valid";
        }else{
            $data = $stmt->fetch();
            $current_password = $data["password"];
            if(password_verify($password,$current_password)){
                $resposne["login"] = true;
                $resposne["data"] = $data;
            }else{
                $resposne["login"] = false;
                $resposne["message"] = "password worng ";
            }


        }
        return $resposne;
    }









    public function DB_Delete($table,$val , $identy = "id" ){
        $sql = "DELETE FROM $table WHERE `{$identy}` = '{$val}' ";
        $query = $this->DB_Query($sql);
        return $query;
    }


  





    public function DB_Fetch_Row($table,$val,$identy = "id", $colums = "*"){
        $stmt = $this->dbconn->prepare("SELECT {$colums} FROM {$table} WHERE `{$identy}` = '{$val}'");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC); 
        $resposne = [];
        if($stmt->rowCount()>=1){
            $resposne = $stmt->fetch();
        }


        return $resposne;
    }




    public function DB_Fetch_Grid($table = "users", $orderBy = ["clm"=>"id","case"=>"desc"], $colums = "*"){



        $stmt = $this->dbconn->prepare("SELECT {$colums} FROM {$table} ORDER BY {$orderBy['clm']} {$orderBy['case']}");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $resposne = $stmt->fetchAll();


        return $resposne;
    }



    public function DB_Fetch_Grid_With_Condition($table = "users", $where = ""){



        $stmt = $this->dbconn->prepare("SELECT * FROM {$table} {$where} ");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $resposne = $stmt->fetchAll();

        return $resposne;
    }







    public function DB_Fetch_Grid_WHERE($table = "users", $where = "id",$ob = "=", $val = ""){
        $stmt = $this->dbconn->prepare("SELECT * FROM {$table} WHERE {$where} {$ob}  {$val} ORDER BY id DESC");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $resposne = $stmt->fetchAll();


        return $resposne;
    }

    
}
