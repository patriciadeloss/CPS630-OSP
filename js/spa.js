var app = angular.module('myApp', ['ngRoute']);

/* Define Components */

app.component('home', {
    templateUrl: '../spa-pages/home.php',
    controller: function() {
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.type = 'text/css';
        link.href = '../css/index.css';  
        document.head.appendChild(link);
    }
});

app.component('services', {
    templateUrl: '../spa-pages/services.html',
    controller: function($scope) {
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.type = 'text/css';
        link.href = '../css/services.css';  
        document.head.appendChild(link);
    }
});

app.component('reviews', {
    templateUrl: '../spa-pages/reviews.php', 
    controller: function($scope) {
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.type = 'text/css';
        link.href = '../css/reviews.css';  
        document.head.appendChild(link);
    }
});

app.component('about', {
    templateUrl: '../spa-pages/about.html', 
    controller: function($scope) {
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.type = 'text/css';
        link.href = '../css/about.css';  
        document.head.appendChild(link);
    }
});

app.component('signin', {
    templateUrl: '../spa-pages/signin.php', 
    controller: function($scope, $location, $http) {
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.type = 'text/css';
        link.href = '../css/forms.css';  
        document.head.appendChild(link);
    }
});

app.component('logout', {
    templateUrl: '../spa-pages/logout.php', 

    /* This is scuffed :D */
    controller: function($scope, $location, $http, $window, $route) {
        // Check if logout has already been processed to prevent re-triggering
        if (sessionStorage.getItem('logoutProcessed')) { return; } // Do nothing if logout is already processed

        // Mark that logout is processed to avoid re-triggering the action
        sessionStorage.setItem('logoutProcessed', 'true');

        // Perform the logout process when the component is loaded
        $http.get('../spa-pages/logout.php').then(function(response) {
            // After logout, reroute to the login page
            $location.path('/signin');  
        }).catch(function(error) {
            console.error('Error during logout:', error); // debugging TT
        });
    }
});

/* Configure Router */

app.config(function($routeProvider) {
  $routeProvider
    .when('/', {
        template: '<home/>'
    })
    .when('/services', {
        template: '<services/>'
    })
    .when('/reviews', {
        template: '<reviews/>' 
    })
    .when('/about', {
        template: '<about/>' 
    })
    .when('/cart', {
        /* NON-OPERABLE */
    })
    .when('/signin', {
        template: '<signin/>' 
    })
    .when('/logout', {
        template: '<logout/>'
        /* OPERATES-ISH */
    })
    .otherwise({
        redirectTo: '/'
    });
});