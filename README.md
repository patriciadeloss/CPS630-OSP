# CPS630-OSP
Summary of functionality: there are currently a number of components and controllers but I've only gotten the Sign In form to work. The logout button partiallyyy works --- the only problem is when u click it, it only switches back to the sign in button after a manual page refresh 😭

## js/spa.js

A snippet of how one of the components work
```javascript
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
.
.
.
/* Configure Router */

app.config(function($routeProvider) {
  $routeProvider
    ...
    .when('/services', {
        template: '<services/>'
    })
    ...
});
```

**1. Name the component 'services'**
```javascript
app.component('services', {
    ...
    }
});
```

**2. Link its HTML template**
```javascript
templateUrl: '../spa-pages/services.html'
```

**3. Define the functionality of the controller**
```javascript
controller: function($scope) {
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.type = 'text/css';
    link.href = '../css/services.css';  
    document.head.appendChild(link);
}
```

The controller links the corresponding stylesheet. 
The ```<head>``` tag in pages/index.php contains the links css/base-style.css & script/spa.js. This tag is fixed and isn't updated dynamically through AngularJS meaning it isn't re-rendered to add new styles. Hence why I had to manually add the stylesheets for each page (or component) in spa.js.

For example, when a user navigates to the 'services' section, I add the corresponding stylesheet for that component via scripts/spa.js to ensure the styles are applied properly.

**4. Configure the router**
```javascript
app.config(function($routeProvider) {
  $routeProvider
    ...
    .when('/services', {
        template: '<services/>'
    })
    ...
});
```

When "Services" is clicked on the header, ```<main ng-view></main>```in pages/index.php will render as ```<main ng-view><services></services></main>```. The ```<services>``` tag contains the HTML template pages/services.html with styling applied from css/services.css.
