$("input").attr("size", 40);

function showResults(container_id)
{
	$("#"+container_id).fadeIn( "slow", function() { } );
}

function emptyQuery(container_id)
{
	var container = document.getElementById(container_id);
	container.value = "";
}

function find(container_id, container2_id, results_container_id){
	var container = container_id;
	var container2 = document.getElementById(container2_id);
	
	if(container.value == null)
		container2.innerHTML = "Show all";
	else
		container2.innerHTML = "Find";

	if(event.keyCode==13)
		showResults(results_container_id);
}

function validateUserExists(users, user_container){
	console.log("entro");
	var username = user_container.value;
	for(var i=0; i<users.lenght; i++){
		
	}
	
}

function getResponseTag(xml){
	xml = $.parseXML(xml);
	var response = xml.getElementsByTagName("response");
	response = response[0].childNodes[0].nodeValue;
	json = angular.fromJson(response);
	return json;
}

function getDir(){
	return "http://magento.ver/magento18/index.php/restext/";
}