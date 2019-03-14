<?php
UseLibarary("LocalStorage");
UseLibarary("Encryption");
UseLibarary("Validation");

class Users extends Controller {
	

	private $upk = null;
	function __construct(){
		parent::__construct();
		// constrcut function
		// will run at the same time this class is initz
	}

	function __destruct(){
		// __destruct function
		// use to kill unneeded proccess like db connection 

	
	}


	/*entiry point for processes 
	the app start here 
	we proccess every thing starting from here	
	*/
	public function run(){		
		/* this startup function for any controller */
		
		//$data = $this->db->DB_Fetch_Grid("users");
		//return  $this->json($data);

		return $this->json(["test"=>"done","response"=>2244234,"route"=>"users"]);
	
	}

	





    public function login(){
		

	
		$email = $this->getParam("email","self",true);
		$password = $this->getParam("password","self",true);
		$response = [];


		// validation
		if(Validation::isValidEmail($email) == false){
			$response["response"] = false; 
			$response["message"] = "please enter valid email address";
		}else{


			if(Validation::isValidPassword($password) == false){
				$response["response"] = false; 
				$response["message"] = "please enter password more than 6 chars";
			}else{

				// sql query to database
				$res = $this->db->DB_Login_Query_Via_Email("user",$email,$password);

				if($res["login"] == true){

					$user = $res["data"];
					if($user["type"]=="provider"){
						$subdata = $this->db->DB_Fetch_Row("provider",$user['id'],"user_id");
					}else{
						$subdata = $this->db->DB_Fetch_Row("client",$user['id'],"user_id");
					}


					

					$response["response"] = true; 
					$response["message"] = "user successfuly login";
					$response["data"] = $res["data"];
					$response["subdata"] = $subdata;





				}else{
					$response["response"] = false; 
					$response["message"] = $res["message"];
				}

			}




		}



		return $this->json($response);
		
		
	}
	



	public function info(){
		$user_id 			= $this->getParam("user_id","self",true);
		$user = $this->db->DB_Fetch_Row("user",$user_id);

		$type = $user['type'];
		if($type=="client")
{
	$user["client"] = $this->db->DB_Fetch_Row("client",$user_id,"user_id");

}else{
	$user["provider"] = $this->db->DB_Fetch_Row("provider",$user_id,"user_id");
}

		
		$response["response"] = true;
		$response["user"] = $user;
		return $this->json($response);
	}




	public function register(){


		$email 			= $this->getParam("email","self",true);
		$password 		= $this->getParam("password","self",true);
		$phone_number 	= $this->getParam("phone_number","self",true);
		$city 			= $this->getParam("city","self",true);
		$type 			= $this->getParam("type","self",true);
	





		if($type  == "client"){

			$firstname 			= $this->getParam("firstname","self",true);
			$lastname 			= $this->getParam("lastname","self",true);
			

		}else{

			$name 			= $this->getParam("name","self",true);
			$work 			= $this->getParam("work","self",true);
			$license 			= $this->getParam("license","self",true);
		}



		if(Validation::isValidEmail($email) == false){
			$response["response"] = false; 
			$response["message"] = "please enter valid email address";
		}else{


			if(Validation::isValidPassword($password) == false){
				$response["response"] = false; 
				$response["message"] = "please enter password more than 6 chars";
			}else{

					//Validation::isValidPhone($phone_number) == false
				if(false){
					$response["response"] = false; 
					$response["message"] = "please enter phone number";
				}else{


					if(in_array($type,	["client","provider"])){





						$sql_email = "SELECT id FROM user WHERE email = '{$email}'";

						$email_count = $this->db->DB_Row_Count($sql_email);

						if($email_count>=1){
							$response["response"] = false; 
							$response["message"] = "this email address is already used";
						}else{

							$sql_phone = "SELECT id FROM user WHERE `phone_number` = '{$phone_number}'";

							$phone_count = $this->db->DB_Row_Count($sql_phone);
							if($phone_count>=1){
								$response["response"] = false; 
								$response["message"] = "this phone number  is already used";
							}else{



									$hash_password = Encryption::generateEncryptedPassword($password);





									$sql = "INSERT INTO user(
										`email`,
										`password`,
										`phone_number`,
										`city`,
										`type`
										)VALUES(
											'{$email}',
											'{$hash_password}',
											'{$phone_number}',
											'{$city}',
											'{$type}'
										)";



												// user  ,  client = >user_id

											// 900
									$inserted =  $this->db->DB_Insert_With_Last_Id($sql);

									if($inserted>=1){


										if($type=="client"){
											$sql2 = "INSERT INTO client(`user_id`,`firstname`,`lastname`)VALUE('{$inserted}'
											,'{$firstname}',
											'{$lastname}'
											)";
											$this->db->DB_Insert_With_Last_Id($sql2);

										}else{

											$sql2 = "INSERT INTO provider(`user_id`,`name`,`work`,`license`)VALUE('{$inserted}'
											,'{$name}',
											'{$work}',
											'{$license}',
											)";
											$this->db->DB_Insert_With_Last_Id($sql2);
										}

										$response["response"] = true; 
										$response["user_id"] = $inserted; 
										$response["message"] = "register sccessfuly";
									}else{
										$response["response"] = false; 
										$response["message"] = "register fail please try later";
									}


									
							}


						}
						
						//



					}else{
						$response["response"] = false; 
						$response["message"] = "type must be provider or client only";

					}
					


				}


			}

		}
	



		return $this->json($response);

	}





	public function providers(){

		$sql = "SELECT * FROM user WHERE type = 'provider'";
		$providers = $this->db->DB_Fetch_Grid_WHERE("user","type", "=",  "");

		return $this->json($providers);
	}
	
}
