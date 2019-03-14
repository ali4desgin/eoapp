<?php
/*
	you must sign your route here 
	with the access method for it like 
	home => ['GET']
	that mean only get request can access to the home route
*/
$routes = 
[
	"index" => ["GET"],
	"users" => ["GET","POST"], // insert /  update => post 
	"shop"	=> ["GET","POST"] ,
	"category" => ["GET"],
	"event" => ["POST","GET"],
	"chat"=>["GET","POST"]
	
];




// the main route should be registered in the routes  array
// keep remmeber that everything will  be handled before we call it
$routes_sub_routes = [
	"users" => [
		"register" => ["POST"],
		"login" => ["POST"],
		"info"=>["GET"]
	]
	,

	"shop"=> [
		"providers"=>["GET"]
	],
	"category"=>[
		"list"=>["GET"],
		"offers"=>["GET"]
	]

	,
	"event"=>[
		"insert" => ["POST"],
		"mylist" => ["GET"],
		"offers" => ["GET"],
		"details"=>["GET"],
		"delete"=>["GET"],
		"acceptoffer"=>['POST']
	],
	"chat"=>[
		"list"=>["GET"],
		"messages"=>["GET"],
		"sendmessage"=>["POST"]
	]

]



?>
