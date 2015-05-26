'use strict';

collnetApp.controller('MainCtrl', function (
      $scope 
      ,$routeParams
      ,$location
      ,$rootScope
      ,Auth
      ,Institute
      ,Company) {
  $scope.includeCompanies = true;
  $scope.placeholder = {
    profileImage : 'assets/img/user.jpg',
    instituteImage : 'assets/img/institute.jpg',
    instituteBanner : 'assets/img/instituteBanner.jpg',
    companyImage : 'assets/img/company.jpg',
    companyBanner : 'assets/img/companyBanner.jpg'
  };
  $scope.addThisUser = {
    name: '',
    email: '',
    username: '',
    sex: (new Array('m', 'f', 'o'))[parseInt((Math.random()*10)%3)],
    password: ''
  };

  // Search Related
  if($location.path().indexOf('search') > -1) {
    Institute.getAll().then(function(results) {
      $scope.institutes = results.data;
    });
    Company.getAll().then(function(results) {
      $scope.companies = results.data;
    });
  }
  // Institute Related 
  if($location.path().indexOf('profile/institute') > -1) {
    Institute.get($routeParams.instituteId).then(function(results) {
      $scope.currentInstitute = results.data;
    });
  }
  $scope.studiedHere = function(instituteId) {
    var returnVal = false;
    if($scope.currentUser && $scope.currentUser.educationHistory) {
      var found = false;
      for(var i = 0; !found && i < $scope.currentUser.educationHistory.length; i++) {
        if($scope.currentUser.educationHistory[i].instituteId === instituteId) {
          returnVal = found = true;
        }
      }
    }
    return returnVal;
  }
  // Company Related
  if($location.path().indexOf('profile/company') > -1) {
    Company.get($routeParams.companyId).then(function(results) {
      $scope.currentCompany = results.data;
    });
  }
  $scope.workedHere = function(companyId) {
    var returnVal = false;
    if($scope.currentUser && $scope.currentUser.workHistory) {
      var found = false;
      for(var i = 0; !found && i < $scope.currentUser.workHistory.length; i++) {
        if($scope.currentUser.workHistory[i].companyId === companyId) {
          returnVal = found = true;
        }
      }
    }
    return returnVal;
  }

  // User Related
  Auth.isLoggedIn().then(function(result) {
    $scope.isLoggedIn = !result.error;
    $scope.currentUser = result.data || null;
    $scope.redirectNicely();
  });
  $scope.login = function(details) {
    Auth.login(details).then(function(result) {
      $scope.loginMessageType = result.error?'alert-danger':'alert-success'; 
      $scope.loginMessage = result.message;
      $scope.isLoggedIn = !result.error;
      $scope.currentUser = result.data || null;
      $scope.redirectNicely();
    });
  };
  $scope.logout = function() {
    Auth.logout().then(function(result) {
      $scope.isLoggedIn = false;
      $scope.currentUser = null;
      $location.path('/');
    });
  };
  $scope.register = function(details) {
    Auth.signup(details).then(function(result) {
      details.showMessage = true;
      $scope.registerMessageType = result.error?'alert-danger':'alert-success'; 
      $scope.registerMessage = result.message;
    });
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
  $scope.editProfile = function(details) {
    Auth.update(details).then(function(result) {
      console.log(result);
    });
  };

  // Post Related
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
