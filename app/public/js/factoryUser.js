collnetApp.factory("User", ['$http', function ($http) {
  var serviceBase = '../api/';
  return {
    isLoggedIn: function() {
      return $http.get(serviceBase + "?action=loginStatus").then(function (results) {
        return results.data;
      });
    },
    login: function(details) {
      return $http.post(serviceBase + "?action=login" , details).then(function (results) {
        return results.data;
      });
    },
    logout: function() {
      return $http.get(serviceBase + "?action=logout").then(function (results) {
        return results.data;
      });
    },
    signup: function(details) {
      return $http.post(serviceBase + "?action=signup" , details).then(function (results) {
        return results.data;
      });
    },
    getProfile: function(q) {
      return $http.post(serviceBase + "?action=getUser&username=" + q).then(function (results) {
        return results.data;
      });
    },
    addPost: function (details) {
      return $http.post(serviceBase + "?action=addPost" , details).then(function (results) {
        return results.data;
      });
    },
    addComment: function (details) {
      return $http.post(serviceBase + "?action=addComment" , details).then(function (results) {
        return results.data;
      });
    },
    addInstitute: function (details) {
      var obj = {
        username: details.username,
        password: details.password
      };
      delete details.username;
      delete details.password;
      obj.newDetails = details;
      return $http.post(serviceBase + "?action=addInstitute" , obj).then(function (results) {
        return results.data;
      });
    },
    addCompany: function (details) {
      var obj = {
        username: details.username,
        password: details.password
      };
      delete details.username;
      delete details.password;
      obj.newDetails = details;
      return $http.post(serviceBase + "?action=addCompany" , obj).then(function (results) {
        return results.data;
      });
    },
    update: function (details) {
      var obj = {
        username: details.username,
        password: details.password
      };
      delete details.username;
      delete details.password;
      obj.newDetails = details;
      return $http.post(serviceBase + "?action=updateUser" , obj).then(function (results) {
        return results.data;
      });
    },
    delete:  function (details) {
      return $http.post(serviceBase + "?action=deleteUser" , details).then(function (results) {
        return results.data;
      });
    } 
  };
}]);
