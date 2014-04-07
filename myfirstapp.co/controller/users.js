function UsersLoginController($scope,$rootScope, $http, $location , MenuStatus){
	$scope.validate_user = function(){
		$http.post(getDir()+"customer?method=login", {
				username: $scope.username,
				password: $scope.password
			}).success(function(data) {
			      $scope.response = getResponseTag(data);
			      if($scope.response.status==true){
			      		$location.path("users/dashboard");	
			      }
			      else
			      {
			      		showError("Please check your credentials");
			      }
			      $rootScope.$broadcast('event:menu-success', $scope.response.status);
			  	}).error(function(data) {
			  		console.log("error en el servicio web");
			  	});
			  }
}

function UsersLogoutController($scope,$rootScope, $http, $location){
	$http.get(getDir()+"customer?method=logout").success(function(data) {
			      $scope.response = getResponseTag(data);
			      $rootScope.$broadcast('event:menu-success', false);
			      $location.path("");
			  	}).error(function(data) {
			  		console.log("error en el servicio web");
	});
}

function UsersDashboardController($scope, $http, $location){
	$http.get(getDir()+"customer?method=getUserData").success(function(data) {			      
			      $scope.response = getResponseTag(data);
			      $scope.shipping = $scope.response.primaryShippingAddress;
			      $scope.billing = $scope.response.primaryBillingAddress;
			      $scope.additionals = $scope.response.additionalAddresses;
			  	}).error(function(data) {
			  		console.log("error en el servicio web");
	});
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

function UsersAddress($scope, $http, $location){
	$http.get(getDir()+"index?method=getSettings").success(function(data) {
			      $scope.response = getResponseTag(data);
			      console.log($scope.response);
			  	}).error(function(data) {
			  		console.log("error en el servicio web");
	});

	$scope.addAddress = function(){
		country_tmp = $scope.country_select.value;
		
		if($scope.region_select)
			region_tmp = $scope.region_select.value;
		else
			region_tmp = "";

		$http.post(getDir()+"customer?method=saveAddress", {	
				firstName: $scope.firstName,
				lastName: $scope.lastName,
				company: $scope.company,
				telephone: $scope.telephone,
				fax: $scope.fax,
				street_address: $scope.street_address,
				street_address2: $scope.street_address2,
				zip: $scope.zip,
				country: country_tmp,
				region: region_tmp,
				city: $scope.city,
				billing_address: $scope.billing_address,
				shipping_address: $scope.shipping_address
				
		}).success(function(data) {
		      $scope.response = getResponseTag(data);
		      if($scope.response.status){
		      		$location.path("users/address");
		      }
		      else{
		      		showError("Please check your address info");
		      }
		  	}).error(function(data) {
		  		console.log("error en el servicio web");
		  	});
	}
}

function leftMenuController($scope, $http, $location) {
    $http.get(getDir()+"customer?method=isCustomerLogged").success(function(data) {		      
			      $scope.response = getResponseTag(data);
			      $scope.loggedIn = $scope.response.status;
			      if(!$scope.loggedIn)
			      {
			      	$location.path("");
			      }
			  	}).error(function(data) {
			  		console.log("error en el servicio web");
			  	});
}

function UsersDeleteAddress($scope, $http, $location, $routeParams){
	$http.get(getDir()+"customer?method=deleteAddress&id="+$routeParams.id).success(function(data) {
			      $scope.response = getResponseTag(data);
			      $scope.status = $scope.response.status;
			      if($scope.status){
			      	$location.path("users/address");
			      }
			      else{
			      	$location.path("users/address");
			      	showError("We can't delete the address at this moment, please try again in a few minutes");
			      }
			  	}).error(function(data) {
			  		console.log("error en el servicio web");
	});
}

function UsersEditAddress($scope, $http, $location, $routeParams){
	$http.get(getDir()+"index?method=getSettings").success(function(data) {
			      $scope.settings = getResponseTag(data);
			  	}).error(function(data) {
			  		console.log("error en el servicio web");
	});

	$http.get(getDir()+"customer?method=getAddressData&id="+$routeParams.id).success(function(data) {
			      $scope.response = getResponseTag(data);
			      $scope.addressId = $scope.response.addressId;
			      $scope.firstName = $scope.response.firstName;
			      $scope.lastName = $scope.response.lastName;
			      $scope.city = $scope.response.city;
			      $scope.country_select = $scope.response.country;
			      $scope.street_address = $scope.response.street1;
			      $scope.street_address2 = $scope.response.street2;
			      $scope.region_select = $scope.response.region;
			      $scope.company = $scope.response.company;
			      $scope.zip = $scope.response.zip;
			      $scope.telephone = $scope.response.telephone;
			      $scope.fax = $scope.response.fax;
			      $scope.shipping_address = $scope.response.isShippingAddress;
			      $scope.billing_address = $scope.response.isBillingAddress;

			  	}).error(function(data) {
			  		console.log("error en el servicio web");
	});


	$scope.editAddress = function(){
		country_tmp = $scope.country_select.value;
		
		if($scope.region_select === null)
			region_tmp = "";
		else
			region_tmp = $scope.region_select.value;

		$http.post(getDir()+"customer?method=saveAddress", {
				addressId: $scope.addressId,	
				firstName: $scope.firstName,
				lastName: $scope.lastName,
				company: $scope.company,
				telephone: $scope.telephone,
				fax: $scope.fax,
				street_address: $scope.street_address,
				street_address2: $scope.street_address2,
				zip: $scope.zip,
				country: country_tmp,
				region: region_tmp,
				city: $scope.city,
				billing_address: $scope.billing_address,
				shipping_address: $scope.shipping_address
				
		}).success(function(data) {
		      $scope.response = getResponseTag(data);
		      if($scope.response.status){
		      		$location.path("users/address");
		      }
		      else{
		      		showError("Please check your address info");
		      }
		  	}).error(function(data) {
		  		console.log("error en el servicio web");
		  	});
	}
}

function UsersPassword($scope, $http, $location, $routeParams){
	$scope.changePassword = function(){
		$http.post(getDir()+"customer?method=changePassword", {
				old_password: $scope.old_password,
				password: $scope.password
		}).success(function(data) {
		      $scope.response = getResponseTag(data);		      
		      if($scope.response.status)
		      		{ 		      		   
		      		   showSuccess("Your password has been changed");
		      		   //$location.path("users/dashboard");
		      		}
		      else
		      		showError("Please check your old password");
		      //$location.path("users/dashboard");
		  	}).error(function(data) {
		  		showError("Your personal data has not been updated, please try later :(");
		  		console.log("error en el servicio web");
		  	});
	};
}

function UsersEdit($scope, $http, $location, $routeParams){
	$http.get(getDir()+"customer?method=getUserData").success(function(data) {			      
			      $scope.response = getResponseTag(data);
			      $scope.firstName = $scope.response.firstName;
			      $scope.lastName = $scope.response.lastName;
			      $scope.email = $scope.response.email;
			  	}).error(function(data) {
			  		console.log("error en el servicio web");
	});

	$http.get(getDir()+"customer?method=getNewsletterStatus").success(function(data) {			      
			      $scope.response = getResponseTag(data);
			      $scope.isSubscribed = $scope.response.status;
			  	}).error(function(data) {
			  		console.log("error en el servicio web");
	});   

	$scope.editCustomer = function(){
		$http.post(getDir()+"customer?method=editCustomer", {
				firstName: $scope.firstName,
				lastName: $scope.lastName,
				email: $scope.email,
				isSubscribed: $scope.isSubscribed

		}).success(function(data) {
		      $scope.response = getResponseTag(data);
		      $location.path("users/dashboard");
		  	}).error(function(data) {
		  		showError("Your personal data has not been updated, please try later :(");
		  		console.log("error en el servicio web");
		  	});
	};
}