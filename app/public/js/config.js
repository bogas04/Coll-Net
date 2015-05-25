'use strict';

collnetApp.config(function ($locationProvider, $routeProvider) {
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
  .when('/profile/college/:collegeid/students', {
    templateUrl: 'views/college-students.html',
    controller: 'MainCtrl'
  })
  .when('/profile/self', {
    templateUrl: 'views/profile-self.html',
    controller: 'MainCtrl'
  })
  .when('/profile/edit', {
    templateUrl: 'views/edit-profile.html',
    controller: 'MainCtrl'
  })
  .when('/profile/user/:userid', {
    templateUrl: 'views/student-profile.html',
    controller: 'MainCtrl'
  })
  .when('/profile/student/:studentid', {
    templateUrl: 'views/student-profile.html',
    controller: 'MainCtrl'
  })
  .when('/profile/college/:collegeid/group', {
    templateUrl: 'views/college-group.html',
    controller: 'MainCtrl'
  })
  .when('/profile/college/:collegeid/group/:courseid/students', {
    templateUrl: 'views/students.html',
    controller: 'MainCtrl'
  });
  // $locationProvider.html5Mode(true);
})
