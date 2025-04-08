var app = angular.module('myApp', ['ngRoute']);

/* Define Controllers */

app.controller('HomeController', function() {
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.type = 'text/css';
    link.href = '../css/index.css';  
    document.head.appendChild(link);
});

app.controller('ServicesController', function($scope) {
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.type = 'text/css';
    link.href = '../css/services.css';  
    document.head.appendChild(link);
});

app.controller('ReviewsController', function($scope) {
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.type = 'text/css';
    link.href = '../css/reviews.css';  
    console.log(link);
    $scope.csslink = link;
    $scope.css = "reviews.css";
});

app.controller('AboutUsController', function($scope) {
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.type = 'text/css';
    link.href = '../css/about.css';  
    document.head.appendChild(link);
});


app.controller('SignInController', function($scope, $location, $http) {
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.type = 'text/css';
    link.href = '../css/forms.css';  
    document.head.appendChild(link);
});

app.controller('SignUpController', function($scope, $location, $http) {
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.type = 'text/css';
    link.href = '../css/forms.css';  
    document.head.appendChild(link);
});

app.controller('LogoutController', function($scope, $location, $http, $window, $route) {
    // Check if logout has already been processed to prevent re-triggering
    if (sessionStorage.getItem('logoutProcessed')) { return; } // Do nothing if logout is already processed

    // Mark that logout is processed to avoid re-triggering the action
    sessionStorage.setItem('logoutProcessed', 'true');

    // Perform the logout process when the component is loaded
    $http.get('scripts/logout.php').then(function(response) {
        // After logout, reroute to the login page
        $location.path('/signin');  
    }).catch(function(error) {
        console.error('Error during logout:', error); // debugging TT
    });
});

/* Configure Router */

app.config(function($routeProvider) {
    //routeProvider: used to provide routes to services
    $routeProvider
    //change controller and template based on route
    .when('/home', {
    //templateUrl to link to ID of template
    templateUrl : 'home',
    controller: 'HomeController'})

    .when('/aboutus', {
    templateUrl : 'aboutus',
    controller: 'AboutUsController'})

    .when('/reviews', {
    templateUrl : 'reviews',
    controller: 'ReviewsController'})

    .when('/services', {
    templateUrl : 'services',
    controller: 'ServicesController'})

    .when('/signup', {
    templateUrl : 'signup',
    controller: 'SignUpController'})

    .when('/signin', {
    templateUrl : 'signin',
    controller: 'SignInController'})

    .when('/logout', {
    templateUrl : 'logout',
    controller: 'LogoutController'})

    .when('/search', {
    templateUrl : 'search'})

    .when('/cart', {
    templateUrl : 'cart'})

    .when('/map', {
    templateUrl : 'map'})

    .when('/payments', {
    templateUrl : 'payments'})

    .when('/confirmation', {
    templateUrl : 'confirmation'})

    .when('/logout', {
        template: '<logout/>',
        controller: 'LogoutController'})

    .otherwise({redirectTo: '/home'});
});

