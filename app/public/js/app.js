'use strict';

var collnetApp = angular.module('collnet', [
    'ngCookies'
    ,'ngResource'
    ,'ngSanitize'
    ,'ngRoute'
    ,'ui.bootstrap'
])
.run(function ($rootScope, $location, Auth) {
  $rootScope.$on("$routeChangeStart", function (event, next, current) {
    Auth.isLoggedIn().then(function (results) {
      if (results.data._id) {
        $rootScope.authenticated = true;
        $rootScope.currentUser = results.data;
      } else {
        $location.path("/login");
      }
    });
  });
});
