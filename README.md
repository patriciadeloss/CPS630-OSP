## CPS630-OSP
Summary of functionality: there are currently a number of components and controllers but I've only gotten the Sign In form to work. The logout button partiallyyy works --- the only problem is when u click it, it only switches back to the sign in button after a manual page refresh 😭

# js/spa.js

A snippet of how one of the components work
```
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
```

Name the component 'services'
```
app.component('services', {
    ...
    }
});
```
