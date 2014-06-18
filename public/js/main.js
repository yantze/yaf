require.config({
    baseUrl: "../lib",
    paths: {
        "jquery": "jquery/jquery-1.11.1.min",
        "bootstrap": "yeti/js/bootstrap.min",
        "underscore": "underscore-min",
        "math": "../js/math",
        "login": "../js/login"
    },
    //对于那些非AMD规范的js需要定义它们的特征
    shim: {
        'underscore':{
            exports: '_'
        },
        'backbone': {
            deps: ['underscore', 'jquery'],
            exports: 'Backbone'
        }
    }
});



require(['jquery', 'underscore'], function ($, _){
});

require(['math'], function (math){
    console.log(math.add(3,3));
});

require(['jquery','login'], function ($, lg){
    lg.bind_click();
});
