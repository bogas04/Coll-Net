'use strict';

angular.module('collnet', [
		'ngCookies'
		,'ngResource'
		,'ngSanitize'
		,'ngRoute'
		,'ui.bootstrap'
		])
.config(function ($locationProvider, $routeProvider) {
	$routeProvider
	.when('/', {
		templateUrl: 'views/main.html',
		controller: 'MainCtrl'
	})
.when('/login', {
	templateUrl: 'views/login.html',
	controller: 'MainCtrl'
})
.when('/contact-us', {
	templateUrl: 'views/contact-us.html',
	controller: 'MainCtrl'
})
.when('/search', {
	templateUrl: 'views/search.html',
controller: 'MainCtrl'
})
.when('/profile/college/:collegeid', {
	templateUrl: 'views/college-profile.html',
controller: 'MainCtrl'
})
.when('/profile/college/:collegeid/students', {
	templateUrl: 'views/college-students.html',
controller: 'MainCtrl'
})
.when('/profile/user/:userid', {
	templateUrl: 'views/user-profile.html',
controller: 'MainCtrl'
})
.when('/profile/student/:studentid', {
	templateUrl: 'views/student-profile.html',
controller: 'MainCtrl'
})
.when('/profile/college/:collegeid/group', {
	templateUrl: 'views/college-group.html',
controller: 'MainCtrl'
});
// $locationProvider.html5Mode(true);
})
.controller('MainCtrl', function ($scope, $location) {
	$scope.message = "successful? I guess!";
	$scope.items = [{
		name: 'NSIT',
	rating: 4.5,
	cid:1,
	location: {country:'india',state:'delhi',zip:'110034'},
	image: "assets/img/1.jpg",
	description: 'Best college ever. Our college is the best college, we have no place for studies.'
	}, {
		name: 'DTU',
	rating: 2.49,
	cid:2,
	location: {country:'india',state:'delhi',zip:'110034'},
	image: "assets/img/2.jpg",
	description: 'Best engineering kaalege only after NSIT!'
	}];
	$scope.students= [{
		name : "Akanshi Mangla", 
	sid : 2,
	image: "assets/img/akanshi.jpg",
	location: {country:'us',state:'mountain view',zip:'110034'},
	description: 'I AM SEXY AND I KNOW IT',
	dob: new Date((new Date().getTime())-1000*60*60*24*365*20),
	educationHistory: [{name:"NSIT", from : new Date(new Date().getTime() - 1000*60*60*24*365*3), to : new Date(), degree : "B.E", discipline : "Computer Engineering"}],
	workHistory: [{company:"Google", title : "Software Developer Engineer Intern", from : new Date(), to : null, description : "Worked really hard as a peon. Known in the campus for great jhaadu maaring skills."}]
	}, 
		{
			name: 'AYUSH',
			image: "assets/img/ayush.jpg",
			sid : 5,
			description: 'ALLAH K NAAM PE SONE DE',
			location: {country:'england',state:'london',zip:'110034'},
			dob: new Date((new Date().getTime())-1000*60*60*24*365*70),
			educationHistory: [{name:"IIIT", from : new Date(new Date().getTime() - 1000*60*60*24*365*3), to : new Date(), degree : "B.E", discipline : "MS Computer Engineering"},{name:"NSIT", from : new Date(new Date().getTime() - 1000*60*60*24*365*4), to : new Date(), degree : "B.E", discipline : "Computer Engineering"}],
			workHistory: [{company:"facebook", title : "", from : new Date(), to : null, description : "Worked really 'hard' as a rapist. Known in the campus for great rape karing skills."}]
		},
		{
			name: 'CHITRA',
			sid : 7,
			image: "assets/img/chitra.jpg",
			description: 'BABAJI KI BUTTI',
			location: {country:'england',state:'delhi',zip:'110034'},
			educationHistory: [{name:"DTU", from : new Date(new Date().getTime() - 1000*60*60*24*365*3), to : new Date(), degree : "B.E", discipline : "Computer Engineering"}],
			workHistory: [{company:"Sumo Logic", title : "Developer", from : new Date(), to : null, description : "Worked really hard as a chapdasi. Known in the campus for great  skills."}]
		},
		{
			name: 'DIVJOT',
			sid : 8,
			image: "assets/img/divjot.jpg",
			description: 'SUCKER',
			location: {country:'india',state:'chandigarh',zip:'352014'},
			dob: new Date((new Date().getTime())-1000*60*60*24*365*100),
			educationHistory: [{name:"NSIT", from : new Date(new Date().getTime() - 1000*60*60*24*365*3), to : new Date(), degree : "B.E", discipline : "Computer Engineering"}],
			workHistory: [{company:"MICROSOFT", title : "Software Developer Engineer Intern", from : new Date(), to : null, description : "Worked really hard as a peon. Known in the campus for great tea serving skills."}]
		}];

	$scope.groupPosts=[{
		content:"oye what's up fellas, i am your  akanshi",
			pid: 1,
			name : "Akanshi",
			sid:2,
			upvotes:20,
			downvotes:2,
			commentCount: 10,
			comments: [{cid:1,comdesc:"hi akanshi", name : "Aman" },{cid:1,comdesc:"you are sexy", name : "blaah"},{cid:2,comdesc:"divjot sucks", name : "Astha" }]
	},
		{
			name : "Chitrasoma Singh",
			content:"oye what's up fellas, i am your  chitra",
			pid: 2,
			sid:7,
			upvotes:16,
			downvotes:1,
			commentCount: 0,
			comments: []	 
		},
		{
			name : "Divjot Singh",
			content:"oye what's up fellas, i am your  divjot",
			pid: 3,
			sid:8,
			upvotes:10,
			downvotes:9,
			commentCount: 5,
			comments : []
		},
		{
			content:"oye what's up fellas, i am your  ayush",
			name : "Ayush",
			pid: 4,
			sid:5,
			upvotes:10,
			downvotes:0,
			commentCount: 1,
			comments: [{cid:6,comdesc:"ayush", name : "Bhawna"},{cid:1,comdesc:"mehrauli", name : "Ayush"},{cid:7,comdesc:"chandigarh", name : "Divjot"}]	 
		}]
	$scope.searchColleges = function () {

		$location.path("search");
	}
	// $scope.currentStudent = $scope.students[0];
});
