'use strict';

collnetApp.controller('MainCtrl', function ($scope, $location, $rootScope, Auth) {
  $scope.placeholder = {
    profileImage : 'assets/img/placeholder.jpg'
  };
  $scope.addThisUser = {
    name: '',
    email: '',
    username: '',
    sex: (new Array('m', 'f', 'o'))[parseInt((Math.random()*10)%3)],
    password: ''
  };

  Auth.isLoggedIn().then(function(result) {
    $scope.isLoggedIn = !result.error;
    $scope.currentUser = result.data || null;
    $scope.redirectNicely();
  });
  $scope.login = function(details) {
    Auth.login(details).then(function(result) {
      $scope.isLoggedIn = !result.error;
      $scope.currentUser = result.data || null;
      $scope.redirectNicely();
    });
  };
  $scope.logout = function() {
    Auth.logout().then(function(result) {
      $scope.isLoggedIn = false;
      $scope.currentUser = null;
      $scope.redirectNicely();
    });
  };
  $scope.register = function(details) {
    Auth.signup(details);
  };
  $scope.redirectNicely = function() {
    if($location.path().indexOf('profile/self') > -1) {
      if($scope.isLoggedIn === false) {
        $location.path('/login');
      }
    }
    if($location.path().indexOf('login') > -1) {
      if($scope.isLoggedIn === true) {
        $location.path('/profile/self');
      }
    }
  };
  $scope.upvote = function(pid, uid) {
    // $http.post('...');
    for(var i = 0; i < $scope.groupPosts.length; i++) {
      if($scope.groupPosts[i].pid === pid) {
        $scope.groupPosts[i].upvotes++;
      }
    }
  };
  $scope.downvote = function(pid, uid) {
    // $http.post('...');
    for(var i = 0; i < $scope.groupPosts.length; i++) {
      if($scope.groupPosts[i].pid === pid) {
        $scope.groupPosts[i].downvotes--;
      }
    }
  };
});
