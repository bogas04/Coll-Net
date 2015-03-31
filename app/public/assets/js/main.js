'use strict';

angular.module('collnet', [
    'ngCookies'
    ,'ngResource'
    ,'ngSanitize'
    ,'ngRoute'
    ,'ui.bootstrap'
])
.config(function ($locationProvider, $routeProvider) {
  $routeProvider
    .when('/', {
      templateUrl: 'views/main.html',
      controller: 'MainCtrl'
    })
  .when('/login', {
    templateUrl: 'views/login.html',
    controller: 'MainCtrl'
  })
  .when('/contact-us', {
    templateUrl: 'views/contact-us.html',
    controller: 'MainCtrl'
  })
  .when('/search', {
    templateUrl: 'views/search.html',
    controller: 'MainCtrl'
  })
  .when('/profile/college/:collegeid', {
    templateUrl: 'views/college-profile.html',
    controller: 'MainCtrl'
  })
  .when('/profile/user/:userid', {
    templateUrl: 'views/user-profile.html',
    controller: 'MainCtrl'
  })
  .when('/group/:collegeid', {
    templateUrl: 'views/college-group.html',
    controller: 'MainCtrl'
  });
  //$locationProvider.html5Mode(true);
})
.controller('MainCtrl', function ($scope) {
  $scope.message = "successful? I guess!";
  $scope.items = [{
    name: 'NSIT',
    rating: 4.5,
    image: "assets/img/1.jpg",
    description: 'Best college ever. Our college is the best college, we have no place for studies.'
  }, {
    name: 'DTU',
    rating: 2.49,
    image: "assets/img/2.jpg",
    description: 'Best engineering kaalege only after NSIT!'
  }];
  $scope.students= [{
    name: 'AKANSHI',
    image: "assets/img/1.jpg",
    description: 'I AM SEXY AND I KNOW IT'
  }, {
    name: 'AYUSH',
    image: "assets/img/2.jpg",
    description: 'ALLAH K NAAM PE SONE DE'
  },
  {
    name: 'CHITRA',
    image: "assets/img/3.jpg",
    description: 'BABAJI KI BUTTI'
  }];
});
