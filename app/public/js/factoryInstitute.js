collegeApp.factory("Institute", ['$http', function($http) {
  var serviceBase = '../api/';
  return {
    get: function (q) {
      return $http.get(serviceBase + "?action=" + q).then(function (results) {
        return results.data;
      });
    },
    enroll: function (q, object) {
      return $http.post(serviceBase + "?action=" + q, object).then(function (results) {
        return results.data;
      });
    },
    unenroll: function(q, object) {
      return $http.post(serviceBase + "?action=" + q, object).then(function (results) {
        return results.data;
      });
    } 
  };
}]);
