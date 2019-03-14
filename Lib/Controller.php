<?php


class Controller  {
	

    protected  $db;




	function __construct(){
		$this->create_db_connection();
	}
    // this will create connection with the database
    // store the connect pdo object in the db protected var
    protected function  create_db_connection(){
		$this->db = new DBase();
		
    }






	protected function json($json_string = null){
		
		if($json_string==null)
			return null;

		echo  json_encode($json_string);
		exit();
	}


	protected function getParams($method = "*"){

		if($method=="self"){
			$method = $_SERVER['REQUEST_METHOD'];
		}



		if($method=="*") {
			return $_REQUEST;		
		}else if($method=="POST"){
			return $_POST;
		}else if($method=="GET"){
			return $_GET;
		}else if($method=="PUT"){
			return $_PUT;
		}else if($method=="DELETE"){
			return $_DELETE;
		}


		return null;
	}


	protected function getParam($key  , $method = "*",$isRequired=false){
		if($method=="self"){
			$method = $_SERVER['REQUEST_METHOD'];
		}

		if($method=="*") {
			$param =  isset($_REQUEST[$key]) ? $_REQUEST[$key] : null ;		
		}else if($method=="POST"){
			$param = isset($_POST[$key]) ? $_POST[$key] : null ;
		}else if($method=="GET"){
			$param =  isset($_GET[$key]) ? $_GET[$key] : null ;

		}else if($method=="PUT"){
			$param =  isset($_PUT[$key]) ? $_PUT[$key] : null ;

		}else if($method=="DELETE"){
			$param =  isset($_DELETE[$key]) ? $_DELETE[$key] : null ;

		}



		if($isRequired){
			if($param == null){
				printError("0010","($key) required");
			}
		}
		
		return $param;
	}


}


