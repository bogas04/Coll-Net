'use strict';

var collnetApp = angular.module('collnet', [
    'ngCookies'
    ,'ngResource'
    ,'ngSanitize'
    ,'ngRoute'
    ,'ui.bootstrap'
])
.run(function ($rootScope, $location, Auth, Institute) {
});
