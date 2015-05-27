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
  $scope.placeholder = {
    profileImage : 'assets/img/user.jpg',
    instituteImage : 'assets/img/institute.jpg',
    instituteBanner : 'assets/img/instituteBanner.jpg',
    companyImage : 'assets/img/company.jpg',
    companyBanner : 'assets/img/companyBanner.jpg'
  };
  $scope.select = {
    disciplines: [
    { value: "BT", name : "Biology Technology" },
    { value: "CE", name : "Civil Engineering" },
    { value: "COE", name : "Computer Engineering" },
      { value: "CS", name : "Computer Science" },
      { value: "CSE", name : "Computer Science and Engineering" },
        { value: "ECE", name : "Electronics and Communication Engineering" },
        { value: "EE", name : "Electrical Engineering" },
          { value: "ICE", name : "Instrumentation and Control Engineering" },
          { value: "IT", name : "Information and Technology" },
            { value: "MPAE", name : "Manufacturing Processes and Automation Engineering" },
            { value: "ME", name : "Mechanical Engineering" },
              { value: "MAC", name : "Mathematics and Computing" }
    ],
    degrees : [
    { value: "BE", name : "Bachelors of Engineering" },
    { value: "BTech", name : "Bachelors of Technology" },
    { value: "BS", name : "Bachelors of Science" },
      { value: "MS", name : "Masters of Engineering" },
      { value: "MTech", name : "Masters of Technology" },
    ]
  };
  $scope.addThisUser = { sex: (new Array('m', 'f', 'o'))[parseInt((Math.random()*10)%3)] };

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
      if($location.path().indexOf('students') > -1) {
        Institute.getStudentsOf($routeParams.instituteId).then(function(results) {
          $scope.currentInstitute.students = results.data || [];
        });
      }
      if($location.path().indexOf('posts') > -1) {
        Post.getPostsOf({ instituteId : $routeParams.instituteId }).then(function(results) {
          $scope.currentInstitute.posts = results.data || [];
        });
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
  $scope.addPost = function(postDetails) {
    postDetails.timestamp = new Date();
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
    return obj ? (obj.degree + ' in ' + obj.discipline + ', ' + obj.duration) : "";
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
