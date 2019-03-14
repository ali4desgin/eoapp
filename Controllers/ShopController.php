<?php
UseLibarary("LocalStorage");
UseLibarary("Encryption");
UseLibarary("Validation");

class Shop extends Controller {
	

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



    public function providers(){

        $prods = $this->db->DB_Fetch_Grid_With_Condition("user","WHERE type = 'provider'");
        

        $response["response"] = true;
        $response["providers"] = $prods;

        return $this->json($response);

    }
	

	
}
