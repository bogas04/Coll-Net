'use strict';

collnetApp.controller('MainCtrl', function ($scope, $location, $rootScope, Auth) {
  $scope.isLoggedIn = $rootScope.authenticated;
  $scope.currentUser = $rootScope.currentUser;
  if(!$scope.isLoggedIn) {
    Auth.isLoggedIn().then(function(result) {
      $scope.isLoggedIn = result.data._id ? true : false;
      $scope.currentUser = result.data || null;
    });
  }
  
  
  
  
  
  
  
  
  
  
  
  
  
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
