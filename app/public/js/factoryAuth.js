collnetApp.factory("Auth", ['$http', function ($http) {
  var serviceBase = '../api/';
  return {
    isLoggedIn : function() {
      return $http.get(serviceBase + "?action=loginStatus").then(function (results) {
        return results.data;
      });
    },
    get: function (q) {
      return $http.get(serviceBase + "?action=" + q).then(function (results) {
        return results.data;
      });
    },
    post: function (q, object) {
      return $http.post(serviceBase + "?action=" + q, object).then(function (results) {
        return results.data;
      });
    },
    put: function (q, object) {
      return $http.post(serviceBase + "?action=" + q, object).then(function (results) {
        return results.data;
      });
    },
    delete:  function (q) {
      return $http.post(serviceBase + "?action=" + q).then(function (results) {
        return results.data;
      });
    } 
  };
}]);
