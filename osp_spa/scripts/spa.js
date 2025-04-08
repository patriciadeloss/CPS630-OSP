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

    $scope.main_description = "Don't want to leave the house? No problem! We offer a variety of services to meet your needs. Now you can get your grocery shopping needs in one place, all from the convenience of a web browser. From browsing and shopping, to payments and delivery, we'll handle it all.";
    // Services Array
    $scope.services = [
        {
            title: "Order Management",
            description: "Manage and track your orders efficiently with features like placing, updating, and confirming your orders. Stay informed at every stage of your order's journey."
        },
        {
            title: "Inventory Management",
            description: "Efficiently track your products and manage stock levels. Our platform ensures that you always have accurate and real-time information about your inventory."
        },
        {
            title: "Delivery to Your Destination",
            description: "We offer convenient delivery services from your selected branch to your preferred destination, ensuring that your items reach you on time and in perfect condition."
        }
    ];
});

app.controller('ReviewsController', function($scope) {
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.type = 'text/css';
    link.href = '../css/reviews.css';
    $scope.css = link;
});

app.controller('AboutUsController', function($scope) {
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.type = 'text/css';
    link.href = '../css/about.css';  
    document.head.appendChild(link);

    // Team member profiles array
    $scope.profiles = [
        {
            name: "Patricia Delos Santos",
            quote: "Blah blah blah",
            email: "pdelos@torontomu.ca",
            image: "../img/pfp.svg"
        },
        {
            name: "Genevive Sanchez",
            quote: "Blah blah blah",
            email: "g1sanchez@torontomu.ca",
            image: "../img/pfp.svg"
        },
        {
            name: "Suboohi Sayeed",
            quote: "Blah blah blah",
            email: "suboohi.sayeed@torontomu.ca",
            image: "../img/pfp.svg"
        }
    ];
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

