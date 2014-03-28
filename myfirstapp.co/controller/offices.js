function OfficesController($scope, $http){
	$http.get('data/offices.json').success(function(data) {
      $scope.offices = data;
  });

	$scope.orderProp = 'organization';
}

function OfficeDataController($scope, $http, $routeParams){
	$http.get('data/offices/'+$routeParams.id+'.json').success(function(data) {
      $scope.office = data;
  	});
}

function ProductsController($scope, $http){
	$scope.products = [];

	$scope.getProductSearch = function()
		{	$http.post(getDir()+"catalog?method=getProductSearch", {
				query: $scope.search_term
			}).success(function(data) {
			     //console.log(getResponseTag(data));
			      $scope.products = getResponseTag(data);
			      console.log($scope.products)
			  	}).error(function(data) {
			  		console.log("web service error");
			  	}
			  	);
		}
}