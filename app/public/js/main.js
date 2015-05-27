'use strict';

collnetApp.controller('MainCtrl', function (
      $scope 
      ,$routeParams
      ,$location
      ,$rootScope
      ,User
      ,Institute
      ,Company
      ,Post) {
  $scope.includeCompanies = true;
  $scope.filterBy = "i";
  $scope.placeholder = {
    profileImage : 'assets/img/user.jpg',
    instituteImage : 'assets/img/institute.jpg',
    instituteBanner : 'assets/img/instituteBanner.jpg',
    companyImage : 'assets/img/company.jpg',
    companyBanner : 'assets/img/companyBanner.jpg'
  };
  $scope.addThisUser = { sex: (new Array('m', 'f', 'o'))[parseInt((Math.random()*10)%3)] };

  // Search Related
  if($location.path().indexOf('search') > -1) {
    Institute.getAll().then(function(results) { $scope.institutes = results.data; });
    Company.getAll().then(function(results) { $scope.companies = results.data; });
  } 
  // Institute Related 
  if($location.path().indexOf('profile/institute') > -1) {
    Institute.get($routeParams.instituteId).then(function(results) {
      if(results.data._id) {
        $scope.currentInstitute = results.data;
        $scope.addInstitute = { 
          _id: results.data._id, 
          discipline : results.data.disciplines[0].value, 
          section : results.data.disciplines[0].sections[0]
        };
        if($location.path().indexOf('students') > -1) {
          Institute.getStudentsOf($routeParams.instituteId).then(function(results) {
            $scope.currentInstitute.students = results.data || [];
          });
        }
        if($location.path().indexOf('posts') > -1) {
          Post.getPostsOf({ filters : { instituteId : $routeParams.instituteId }}).then(function(results) {
            $scope.currentInstitute.posts = results.data || [];
            $scope.addThisPost = { postForType : "dys" };
            $scope.currentEducationHistory = $scope.getEducationHistory($scope.currentUser, $routeParams.instituteId);
          });
        }
      }
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
    if(!details || !$scope.currentUser || !$scope.isLoggedIn) {
      $scope.updateProfileMessageType = 'alert-danger'; 
      $scope.updateProfileMessage = "Internal server error";
      return;
    }
    details.username = $scope.currentUser.username;
    details.fromDate = details.fromDate ? $scope.formatDate(details.fromDate) : "";
    if(details.fromDate.getFullYear() < $scope.currentInstitute.yearOfEstablishment) {
      $scope.updateProfileMessageType = 'alert-danger'; 
      $scope.updateProfileMessage = "Year of joining can not be before year of establishment!";
      return; 
    }
    details.toDate = details.toDate ? $scope.formatDate(details.toDate) : null;
    User.addInstitute(details).then(function(result) {
      $scope.updateProfileMessageType = result.error?'alert-danger':'alert-success'; 
      $scope.updateProfileMessage = result.message;
    });
  };
  // Company Related
  if($location.path().indexOf('profile/company') > -1) {
    Company.get($routeParams.companyId).then(function(results) {
      $scope.currentCompany = results.data;
      $scope.addCompany = { _id : $scope.currentCompany._id };
      if($location.path().indexOf('employees') > -1) {
        Company.getEmployeesOf($routeParams.companyId).then(function(results) {
          $scope.currentCompany.employees = results.data || [];
        });
      }
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
    User.addCompany(details).then(function(result) {
      $scope.updateProfileMessageType = result.error?'alert-danger':'alert-success'; 
      $scope.updateProfileMessage = result.message;
    });
  };

  // User Related
  if($location.path().indexOf('profile/user') > -1) {
    User.getProfile($routeParams.username).then(function(results) {
      $scope.selectedUser = results.data;
      $scope.fillDurations($scope.selectedUser);
    });
  }
  User.isLoggedIn().then(function(result) {
    $scope.isLoggedIn = !result.error;
    $scope.currentUser = result.data || null;
    $scope.fillDurations($scope.currentUser);
    $scope.redirectNicely();
  });
  $scope.login = function(details) {
    User.login(details).then(function(result) {
      $scope.loginMessageType = result.error?'alert-danger':'alert-success'; 
      $scope.loginMessage = result.message;
      $scope.isLoggedIn = !result.error;
      $scope.currentUser = result.data || null;
      $scope.fillDurations($scope.currentUser);
      $scope.redirectNicely();
    });
  };
  $scope.logout = function() {
    User.logout().then(function(result) {
      $scope.isLoggedIn = false;
      $scope.currentUser = null;
      $location.path('/');
    });
  };
  $scope.register = function(details) {
    User.signup(details).then(function(result) {
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
    User.update(details).then(function(result) {
      $scope.updateProfileMessageType = result.error?'alert-danger':'alert-success'; 
      $scope.updateProfileMessage = result.message;
    });
  };

  // Post Related
  $scope.filterPostsBy = function(filterBy) {
    if(filterBy) {
      var filters = { instituteId : $routeParams.instituteId };
      var educationHistory = $scope.getEducationHistory($scope.currentUser, $scope.currentInstitute._id); 
      var year = $scope.toDate(educationHistory.fromDate).getFullYear();
      switch(filterBy) {
        case "y" : filters.batchYear = year; break;
        case "d" : filters.discipline = educationHistory.discipline; break;
        case "dy" : filters.batchYear = year; filters.discipline = educationHistory.discipline; break;
        case "dys" : filters.batchYear = year; filters.discipline = educationHistory.discipline; filters.section = educationHistory.section; break;
      }
      Post.getPostsOf({ filters : filters }).then(function(results) { $scope.currentInstitute.posts = results.data || []; });
    }
  };
  $scope.addPost = function(instituteId, postDetails) {
    var educationHistory = $scope.getEducationHistory($scope.currentUser, $scope.currentInstitute._id); 
    var year = $scope.toDate(educationHistory.fromDate).getFullYear();
    postDetails.timestamp = new Date();
    postDetails.postFor = { instituteId: instituteId };
    switch(postDetails.postForType) {
      case "y" : 
        postDetails.postFor.batchYear = year; 
        break;
      case "d" : 
        postDetails.postFor.discipline = educationHistory.discipline;
        break;
      case "dy" : 
        postDetails.postFor.batchYear = year;
        postDetails.postFor.discipline = educationHistory.discipline;
        break;
      case "dys" : 
        postDetails.postFor.batchYear = year; 
        postDetails.postFor.discipline = educationHistory.discipline; 
        postDetails.postFor.section = educationHistory.section; 
        break;
    }
    delete postDetails.postForType;
    User.addPost(postDetails).then(function(result) {
      $scope.addPostMessageType = result.error?'alert-danger':'alert-success'; 
      $scope.addPostMessage = result.message;
      postDetails = null;
    });
  };
  $scope.addComment = function(postId, commentDetails) {
    commentDetails.timestamp = new Date();
    commentDetails.postId = postId;
    User.addComment(commentDetails).then(function(result) {
      $scope.addCommentMessageType = result.error?'alert-danger':'alert-success'; 
      $scope.addCommentMessage = result.message;
      commentDetails = null;
    });
  };

  // Misc Functions
  $scope.getSections = function(disciplineCode, disciplines) {
    var sectionArray = [1, 2, 3]; // Default
    var found = false;
    if(disciplines && disciplineCode) {
      for(var i = 0; !found && i < disciplines.length; i++) {
        if(disciplines[i].value === disciplineCode) {
          found = true;
          sectionArray = disciplines[i].sections;
        }
      } 
    }
    return sectionArray;
  }
  $scope.getEducationHistory = function(user, instituteId) {
    // returns the object of education from educationHistory of user having same instituteId
    var obj = {};
    var found = false;
    if(instituteId && user && user.educationHistory) {
      for(var i = 0; !found && i < user.educationHistory.length; i++) {
        if(user.educationHistory[i]._id.$id === instituteId) {
          found = true;
          obj = user.educationHistory[i];
        }
      }
    }
    return obj;
  };
  $scope.getEducationDescription = function(user, instituteId) {
    // returns title and duration of education
    $scope.fillDurations(user);
    var obj = $scope.getEducationHistory(user, instituteId);
    return obj ? (obj.discipline + ', ' + obj.duration) : "";
  };
  $scope.getWorkHistory = function(user, companyId) {
    // returns the object of work from workHistory of user having same companyId
    var obj = {};
    var found = false;
    if(companyId && user && user.workHistory) {
      for(var i = 0; !found && i < user.workHistory.length; i++) {
        if(user.workHistory[i]._id.$id === companyId) {
          found = true;
          obj = user.workHistory[i];
        }
      }
    }
    return obj;
  };
  $scope.getWorkDescription = function(user, companyId) {
    // returns title and duration of work
    $scope.fillDurations(user);
    var obj = $scope.getWorkHistory(user, companyId);
    return obj ? (obj.title + ', ' + obj.duration) : "";
  };
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
  $scope.toDate = function(dateString) {
    // converts dateString into dateObj
    return new Date(dateString); 
  }
  $scope.formatDate = function(dateString) {
    // Assumes format : DD-MM-YYYY
    dateString = dateString.split('-').map(function(i) { return parseInt(i); });
    return (new Date(Date.UTC(dateString[2], dateString[1] - 1, dateString[0])));
  };
});
