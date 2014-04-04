function IndexController($scope,$rootScope, $http , MenuStatus){
	$scope.loggedIn = false;
	$scope.$on('event:menu-success', function (event, args) {
        $scope.loggedIn = args;
    });
}