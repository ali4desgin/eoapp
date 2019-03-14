<?php
UseLibarary("LocalStorage");
UseLibarary("Encryption");
UseLibarary("Validation");

class Chat extends Controller {
	

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

		return $this->json(["test"=>"done","response"=>200]);
	
    }
    


    

    public function list(){
        
        $client_id = $this->getParam("client_id","self",true);


		$events =  $this->db->DB_Fetch_Grid_With_Condition( "event",  "WHERE `client_id` = {$client_id} and isAccept='yes'");
       
        $response["response"] = true;
        $response["events"] = $events;

        return $this->json($response);

	}
    
    

    public function messages(){
        
        
        $event_id = $this->getParam("event_id","self",true);


		$event =  $this->db->DB_Fetch_Row( "event",$event_id);
		$messages =  $this->db->DB_Fetch_Grid_With_Condition( "messages",  "WHERE `offer_id` = {$event['accepted_offer']}");
       
        $response["response"] = true;
        $response["messages"] = $messages;

        return $this->json($response);

    }
    


    public function sendmessage(){
        
        $event_id = $this->getParam("event_id","self",true);
        $isClient = $this->getParam("isClient","self",true);
        $message = $this->getParam("message","self",true);


		$event =  $this->db->DB_Fetch_Row( "event",$event_id);
       
       
       $sql = "INSERT  INTO messages(
			`offer_id`,
			`isClient`,
			`message`
		)VALUE(
			'{$event['accepted_offer']}',
				'{$isClient}',
					'{$message}'
                    )";

		
		$inserted =  $this->db->DB_Insert_With_Last_Id($sql);
		$response = [];

		if($inserted>=1){
			$response["reponse"] = true;
			$response["message"] = "messages sent";
		}else{
			$response["reponse"] = false;
			$response["message"] = "try later";
		}
        



        return $this->json($response);

	}
}
