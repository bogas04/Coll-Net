collnetApp.factory("Auth", ['$http', function ($http) {
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
    profile: function(details) {
      return $http.post(serviceBase + "?action=getUser" , details).then(function (results) {
        return results.data;
      });
    },
    update: function (details) {
      return $http.post(serviceBase + "?action=updateUser" , { 
        username: details.username, 
        password: details.password, 
        newDetails: details
      }).then(function (results) {
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
