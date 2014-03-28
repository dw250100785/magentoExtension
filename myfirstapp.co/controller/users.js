function UsersLoginController($scope, $http){
	$scope.validate_user = function(){
		$http.post(getDir()+"customer?method=login", {
				username: $scope.username,
				password: $scope.password
			}).success(function(data) {
			      console.log(getResponseTag(data));
			      $scope.response = getResponseTag(data);
			  	}).error(function(data) {
			  		console.log("error en el servicio web");
			  	});
			  }

	$scope.logout = function(){
		$http.get(getDir()+"customer?method=logout").success(function(data) {
			      console.log(getResponseTag(data));
			      $scope.response = getResponseTag(data);
			  	}).error(function(data) {
			  		console.log("error en el servicio web");
			  	});
			  }
}

function UsersForget($scope, $http){
	$scope.forgotPassword = function(){
	$http.post(getDir()+"customer?method=forgotPassword", {
				email: $scope.email
		}).success(function(data) {
		      console.log(getResponseTag(data));
		      $scope.response = getResponseTag(data);
		  	}).error(function(data) {
		  		console.log("error en el servicio web");
		  	});
	};

	$scope.resetPassword = function(){
		$http.post(getDir()+"customer?method=resetPassword", {
				id: "2",
				token: "82d27b471487824c329530c1ebcdb903",
				password: $scope.new_password
		}).success(function(data) {
		      console.log(getResponseTag(data));
		      $scope.response = getResponseTag(data);
		  	}).error(function(data) {
		  		console.log("error en el servicio web");
		  	});
	}
}

function UsersSignUp($scope, $http){

	$scope.createCustomer = function(){
		$http.post(getDir()+"customer?method=createCustomer", {
				
				firstName: $scope.firstName,
				lastName: $scope.lastName,
				email: $scope.email,
				password: $scope.password,
				isSubscribed: $scope.isSubscribed

		}).success(function(data) {
		      console.log(getResponseTag(data));
		      $scope.response = getResponseTag(data);
		  	}).error(function(data) {
		  		console.log("error en el servicio web");
		  	});
	}
}