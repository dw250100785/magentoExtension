'use strict';

angular.module('myApp', [
  'ngRoute',
  'ngResource'
])
  .config(function ($routeProvider) {
    $routeProvider
      .when('/', {
        templateUrl: 'partials/main.html',
        controller: 'MainCtrl'
      })
      .when('/offices', {
        templateUrl: 'partials/offices/officesList.html',
        controller: 'OfficesController'
      })
      .when('/offices/:id', {
        templateUrl: 'partials/offices/officeData.html',
        controller: 'OfficeDataController'
      })
      .when('/offices/turn/:id', {
        templateUrl: 'partials/offices/officeTurn.html',
        controller: 'OfficeDataController'
      })
      .when('/users', {
        templateUrl: 'partials/users/login.html',
        controller: 'UsersLoginController'
      })
      .when('/users/forget', {
        templateUrl: 'partials/users/forget.html',
        controller: 'UsersForget'
      })
      .when('/users/signup', {
        templateUrl: 'partials/users/signup.html',
        controller: 'UsersSignUp'
      })
      .when('/organizations', {
        templateUrl: 'partials/organizations/main.html',
        controller: 'OrganizationsMain'
      })
      .when('/organizations/:url', {
            templateUrl: 'partials/organizations/main.html',
            controller: 'OrganizationsMain'
        })
      .when('/products', {
            templateUrl: 'partials/products/search.html',
            controller: 'ProductsController'
        })
      .otherwise({
        redirectTo: '/'
      });
      
  })