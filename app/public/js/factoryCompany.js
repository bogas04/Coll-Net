collnetApp.factory("Company", ['$http', function($http) {
  var serviceBase = '../api/';
  return {
    get: function (q) {
      return $http.get(serviceBase + "?action=getCompany&_id=" + q).then(function (results) {
        return results.data;
      });
    },
    getAll: function () {
      return $http.get(serviceBase + "?action=getAllCompanies").then(function (results) {
        return results.data;
      });
    },
    getEmployeesOf : function(q) {
      return $http.get(serviceBase + "?action=employeesOf&_id=" + q).then(function (results) {
        return results.data;
      });
    },
    search: function (q) {
      return $http.get(serviceBase + "?action=getCompany&keyword=" + q).then(function (results) {
        return results.data;
      });
    },
    join: function (object) {
      return $http.post(serviceBase + "?action=join", object).then(function (results) {
        return results.data;
      });
    },
    leave: function(object) {
      return $http.post(serviceBase + "?action=leave", object).then(function (results) {
        return results.data;
      });
    } 
  };
}]);
