<?php
UseLibarary("LocalStorage");
UseLibarary("Encryption");
UseLibarary("Validation");

class Event extends Controller {
	

	function __construct(){
		parent::__construct();
		// constrcut function
		// will run at the same time this class is initz
	}

	/*entiry point for processes 
	the app start here 
	we proccess every thing starting from here	
	*/
	public function run(){		
		/* this startup function for any controller */
		
		//$data = $this->db->DB_Fetch_Grid("users");
		//return  $this->json($data);

		return $this->json(["test"=>"done","response"=>200]);
	
    }
	
	

	public function details(){

		$event_id = $this->getParam("event_id","self",true);


		$event =  $this->db->DB_Fetch_Row( "event",$event_id);
	   
		if(!empty($event)){
			$category =  $this->db->DB_Fetch_Row( "category",$event['party_type']);
			$offers = $this->db->DB_Row_Count("SELECT * FROM `event_offers` WHERE 
			`event_id` = '{$event_id}'");
			$event['type'] = $category['name'];
			$event['offer_count'] = $offers;
		}
        $response["response"] = true;
        $response["event"] = $event;

        return $this->json($response);

	}


	public function mylist(){

		$client_id = $this->getParam("client_id","self",true);


		$events =  $this->db->DB_Fetch_Grid_With_Condition( "event",  "WHERE `client_id` = {$client_id} and isAccept='no'");
       
        $response["response"] = true;
        $response["events"] = $events;

        return $this->json($response);

	}


	public function delete(){

		$event_id = $this->getParam("event_id","self",true);

		$this->db->DB_Delete("event",$event_id);

        $response["response"] = true;
        $response["events"] = "event successufuly delete";

        return $this->json($response);

	}





	



	public function offers(){
		$event_id = $this->getParam("event_id","self",true);
		$offerss =  $this->db->DB_Fetch_Grid_With_Condition( "`event_offers`",  "WHERE `event_id` = {$event_id}");
		$offers = [];
		foreach($offerss as $offer){

			$provider =  $this->db->DB_Fetch_Row( "user",$offer['provider_id']);
			$offer["provider_email"] =  $provider['email'];
			$offers[] = $offer;

		}


		
		return $this->json(["reponse"=>true,"offers"=>$offers,"message"=>"offers list"]);
	}


    public function insert(){

        $type = $this->getParam("type","self",true);
        $gender = $this->getParam("gender","self",true);
		$name = $this->getParam("name","self",false);
		$idea = $this->getParam("idea","self",false);
		$location = $this->getParam("location","self",false);
		$client_id = $this->getParam("client_id","self",true);
        $guests = $this->getParam("guests","self",true);
        $budget = $this->getParam("budget","self",true);
        $date = $this->getParam("date","self",true);




		$sql = "INSERT  INTO event(
			`client_id`,
			`gender`,
			`party_type`,
			`name`,
			`number_of_guest`,
			`date`,
			`party_location`,
			`idea`,
			`budget`
		)VALUE(
			'{$client_id}',
				'{$gender}',
					'{$type}',
						'{$name}',
							'{$guests}',
								'{$date}',
									'{$location}',
										'{$idea}',
											'{$budget}'
											)";

		
		$inserted =  $this->db->DB_Insert_With_Last_Id($sql);
		$response = [];

		if($inserted>=1){
			$response["reponse"] = true;
			$response["message"] = "event successfuly created";
		}else{
			$response["reponse"] = false;
			$response["message"] = "try later";
		}
        


		return $this->json($response);




	}
	

	public function acceptoffer(){

        $event_id = $this->getParam("event_id","self",true);
        $offer_id = $this->getParam("offer_id","self",true);
		$message = $this->getParam("message","self",false);
		


		$sql = "INSERT  INTO messages(
			`offer_id`,
			`isClient`,
			`message`
		)VALUE(
			'{$offer_id}',
				'yes',
					'{$message}'
							)";

		
		$inserted =  $this->db->DB_Insert_With_Last_Id($sql);


	$sql = "UPDATE event SET isAccept = 'yes' , `accepted_offer` = {$offer_id} WHERE 
	
	id = {$event_id} ";


		$this->db->DB_Query($sql );


		$response["reponse"] = true;
		$response["message"] = "offert accespted";
	
        


		return $this->json($response);




    }


	

	
}
