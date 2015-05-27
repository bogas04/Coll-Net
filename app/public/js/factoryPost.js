collnetApp.factory("Post", ['$http', function ($http) {
  var serviceBase = '../api/';
  return {
    getPost: function(q) {
      return $http.get(serviceBase + "?action=getPost&_id=" + q).then(function (results) {
        return results.data;
      });
    },
    getPostsOf: function(details) {
      return $http.get(serviceBase + "?action=getPostsOf", { params : details }).then(function (results) {
        return results.data;
      });
    }
  };
}]);
