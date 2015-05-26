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
  $scope.select = {
    disciplines: [
    {value: "BT", name : "Biology Technology"},
    {value: "CE", name : "Civil Engineering"},
    {value: "COE", name : "Computer Engineering"},
      {value: "CS", name : "Computer Science"},
      {value: "CSE", name : "Computer Science and Engineering"},
        {value: "ECE", name : "Electronics and Communication Engineering"},
        {value: "EE", name : "Electrical Engineering"},
          {value: "ICE", name : "Instrumentation and Control Engineering"},
          {value: "IT", name : "Information and Technology"},
            {value: "MPAE", name : "Manufacturing Processes and Automation Engineering"},
            {value: "ME", name : "Mechanical Engineering"},
              {value: "MAC", name : "Mathematics and Computing"}
    ],
    degrees : [
    {value: "BE", name : "Bachelors of Engineering"},
    {value: "BTech", name : "Bachelors of Technology"},
    {value: "BS", name : "Bachelors of Science"},
      {value: "MS", name : "Masters of Engineering"},
      {value: "MTech", name : "Masters of Technology"},
    ]
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
      $scope.addInstitute = { _id: $scope.currentInstitute._id, degree: $scope.select.degrees[0].value };
    });
  }
  $scope.studiedHere = function(instituteId) {
    var returnVal = false;
    if($scope.currentUser && $scope.currentUser.educationHistory) {
      var found = false;
      for(var i = 0; !found && i < $scope.currentUser.educationHistory.length; i++) {
        if($scope.currentUser.educationHistory[i]._id.$id === instituteId) {
          returnVal = found = true;
        }
      }
    }
    return returnVal;
  };
  $scope.addInstituteToProfile = function(details) {
    console.log(details);
    if(!details || !$scope.currentUser || !$scope.isLoggedIn) {
      $scope.updateProfileMessageType = 'alert-danger'; 
      $scope.updateProfileMessage = "Internal server error";
      return;
    }
    details.username = $scope.currentUser.username;
    details.fromDate = details.fromDate ? $scope.formatDate(details.fromDate) : "";
    details.toDate = details.toDate ? $scope.formatDate(details.toDate) : null;
    console.log(details);
    Auth.addInstitute(details).then(function(result) {
      $scope.updateProfileMessageType = result.error?'alert-danger':'alert-success'; 
      $scope.updateProfileMessage = result.message;
    });
  };
  // Company Related
  if($location.path().indexOf('profile/company') > -1) {
    Company.get($routeParams.companyId).then(function(results) {
      $scope.currentCompany = results.data;
      $scope.addCompany = { _id : $scope.currentCompany._id };
    });
  }
  $scope.workedHere = function(companyId) {
    var returnVal = false;
    if($scope.currentUser && $scope.currentUser.workHistory) {
      var found = false;
      for(var i = 0; !found && i < $scope.currentUser.workHistory.length; i++) {
        if($scope.currentUser.workHistory[i]._id.$id === companyId) {
          returnVal = found = true;
        }
      }
    }
    return returnVal;
  };
  $scope.addCompanyToProfile = function(details) {
    console.log(details);
    if(!details || !$scope.currentUser || !$scope.isLoggedIn) {
      $scope.updateProfileMessageType = 'alert-danger'; 
      $scope.updateProfileMessage = "Internal server error";
      return;
    }
    details.username = $scope.currentUser.username;
    details.fromDate = details.fromDate ? $scope.formatDate(details.fromDate) : "";
    details.toDate = details.toDate ? $scope.formatDate(details.toDate) : null;
    console.log(details);
    Auth.addCompany(details).then(function(result) {
      $scope.updateProfileMessageType = result.error?'alert-danger':'alert-success'; 
      $scope.updateProfileMessage = result.message;
    });
  };

  // User Related
  Auth.isLoggedIn().then(function(result) {
    $scope.isLoggedIn = !result.error;
    $scope.currentUser = result.data || null;
    $scope.fillDurations($scope.currentUser);
    $scope.redirectNicely();
  });
  $scope.login = function(details) {
    Auth.login(details).then(function(result) {
      $scope.loginMessageType = result.error?'alert-danger':'alert-success'; 
      $scope.loginMessage = result.message;
      $scope.isLoggedIn = !result.error;
      $scope.currentUser = result.data || null;
      $scope.fillDurations($scope.currentUser);
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
      $scope.updateProfileMessageType = result.error?'alert-danger':'alert-success'; 
      $scope.updateProfileMessage = result.message;
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

  // Misc Functions
  $scope.fillDurations = function(obj) {
    // adds new field 'duration' whereever fromDate and toDate exist
    if(obj) {
      if(obj.workHistory) {
        for(var i = 0; i < obj.workHistory.length; i++) {
          var fromDate = new Date(obj.workHistory[i].fromDate);
          var toDate = obj.workHistory[i].toDate? new Date(obj.workHistory[i].toDate) : "Present";

          obj.workHistory[i].duration = $scope.stringifyDate(fromDate) + ' - ' + $scope.stringifyDate(toDate);
        }
      }
      if(obj.educationHistory) {
        for(var i = 0; i < obj.educationHistory.length; i++) {
          var fromDate = new Date(obj.educationHistory[i].fromDate);
          var toDate = obj.educationHistory[i].toDate? new Date(obj.educationHistory[i].toDate) : "Present";

          obj.educationHistory[i].duration = $scope.stringifyDate(fromDate) + ' - ' + $scope.stringifyDate(toDate); 
        }
      }
    }
  };
  $scope.stringifyDate = function(dateObj) {
    var months = ['January', 'Feburary', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'Decemeber'];
    return (dateObj && dateObj instanceof Date) ? dateObj.getDate() + ', ' + months[dateObj.getMonth()] + ' ' + dateObj.getFullYear() : dateObj;
  };
  $scope.formatDate = function(dateString) {
    // Assumes format : DD-MM-YYYY
    dateString = dateString.split('-').map(function(i) { return parseInt(i); });
    return (new Date(Date.UTC(dateString[2], dateString[1] - 1, dateString[0])));
  };
});
