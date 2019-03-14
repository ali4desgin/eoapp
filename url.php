<?php

// $server_port               = $_SERVER['SERVER_PORT'];
// $server_user_ip            = $_SERVER['SERVER_ADDR'];
// $http_user_browser         = $_SERVER['HTTP_USER_AGENT'];
// $user_remote_port   	   = $_SERVER['REMOTE_PORT'];


$request_uri        	   = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));






$api_keyword_enpoint =  strpos($request_uri,$startup_dir) + strlen($startup_dir);
$destination_uri_string  =  substr($request_uri,$api_keyword_enpoint);
$destionation_uri_array = explode(DS,$destination_uri_string);


if($destionation_uri_array[0]==""){
	$route = "index";
}else{
	$route = $destionation_uri_array[0];
}



if(array_key_exists($route,$routes)){
	if(in_array($_SERVER['REQUEST_METHOD'],$routes[$route])){

		$class	= ucwords($route);
		if(class_exists($class)){

			$object = new $class;

			if(count($destionation_uri_array)>=2){

				$method = $destionation_uri_array[1];
				
				if(array_key_exists($method,$routes_sub_routes[$route])){
					if(in_array($_SERVER['REQUEST_METHOD'],$routes_sub_routes[$route][$method]))
					{
						if(method_exists($class,$method)){
							http_response_code(200);
							$object->$method();
						}else{
							// internal error throw here
							http_response_code(404);
							printError("004","({$method}) method not available in your class");
						}

					}else{
						http_response_code(404);
						printError("004","request method no allowd for this route ");
					}

				}else{
					http_response_code(404);
					printError("004","there is no sub route with this name ");
				}

	
				
			
			}else{

				if(method_exists($class,'run')){
					http_response_code(200);
					$object->run();
				}else{
					// internal error throw here
					http_response_code(404);
					printError("004","run method not available in your class");
				}

			}





		}else{
			http_response_code(404);
			printError("003","there is no controller with this name");
		}


	}else{
		http_response_code(404);
		printError("0002","request method not allowd for this route");

	}
}else{
	http_response_code(404);
	printError("0001","route not exists");


}





?>