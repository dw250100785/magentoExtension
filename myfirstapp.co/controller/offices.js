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
	
	$scope.getProductSearch = function()
		{	$http.post("http://magento.ver/magento18/restext/catalog?method=getProductSearch", {
				query: $scope.search_term
			}).success(function(data) {
			      console.log(getResponseTag(data));
			      $scope.response = getResponseTag(data);
			  	}).error(function(data) {
			  		console.log("error en el servicio web");
			  	}
			  	);
		}
}