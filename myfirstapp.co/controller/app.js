'use strict';

var myApp = angular.module('myApp', [
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
      .when('/users/logout', {
        templateUrl: 'partials/users/logout.html',
        controller: 'UsersLogoutController'
      })
      .when('/users/dashboard', {
        templateUrl: 'partials/users/dashboard.html',
        controller: 'UsersDashboardController'
      })
      .when('/users/address', {
        templateUrl: 'partials/users/address.html',
        controller: 'UsersDashboardController'
      })
      .when('/users/newAddress', {
        templateUrl: 'partials/users/newAddress.html',
        controller: 'UsersAddress'
      })
      .when('/users/editAddress/:id', {
        templateUrl: 'partials/users/editAddress.html',
        controller: 'UsersEditAddress'
      })
      .when('/users/edit', {
        templateUrl: 'partials/users/edit.html',
        controller: 'UsersEdit'
      })
      .when('/users/password', {
        templateUrl: 'partials/users/password.html',
        controller: 'UsersPassword'
      })
      .when('/users/deleteAddress/:id', {
        templateUrl: 'partials/users/address.html',
        controller: 'UsersDeleteAddress'
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
      
  });

myApp.directive("leftmenu", function() {
  return {
    restrict: "E",        // directive is an Element (not Attribute)
    scope: {              // set up directive's isolated scope
      name: "@",          // name var passed by value (string, one-way)
      amount: "=",        // amount var passed by reference (two-way)
      save: "&"           // save action
    },
    templateUrl: 'partials/users/left_menu.html',
    controller: "leftMenuController",
    replace: true,        // replace original markup with template
    transclude: false,    // do not copy original HTML content
  }
});   