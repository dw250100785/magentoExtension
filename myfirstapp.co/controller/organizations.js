function OrganizationsMain($scope, $routeParams, $http){
	
	switch($routeParams.url)
        {
            case 'main':
                $scope.templateUrl = 'partials/organizations/main.html';
                break;
            case 'myoffices':
                $scope.templateUrl = 'partials/organizations/myoffices.html';
                break;
            case 'edit_office':
            	$scope.templateUrl = 'partials/organizations/edit_office.html';
            	$scope.controller = "edit_officeController"
                break;
            default:
                $scope.templateUrl = 'partials/organizations/welcome.html';
        }

    $http.get("/data/organizations/1.json").success( function (data){
    		$scope.organization = data;
    });

    $http.get("/data/organizations/offices1.json").success( function (data){
    		$scope.officesList = data;
    });	

}

function edit_officeController($scope, $routeParams, $http){
	var result;
	    var id =1;
	    $http.get("/data/offices/"+id+".json").success( function (data){
    			$scope.office = data;
    });
}