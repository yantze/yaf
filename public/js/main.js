require.config({
    baseUrl: "../lib",
    paths: {
        "jquery": "jquery/jquery-1.11.1.min",
        "bootstrap": "yeti/js/bootstrap.min",
        "underscore": "underscore-min",
        "math": "../js/math",
        "login": "../js/login",
        "cart": "../js/cart",
        "fav": "../js/fav"
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

//这里是加载各种模块
require(['math'], function (math){
    console.log(math.add(3,3));
});

require(['jquery'], function ($){
    $('.js-search-btn').on('click', function(){
        name = $('.js-search-name').val();
        location.href="/item/search?name="+name;
    });
});

//login 
require(['jquery','login'], function ($, lg){
    lg.bind_click();
});

//cart
var cart = {};
require(['jquery','cart'], function ($, ct){
    cart = ct;
});

//fav
var fav = {};
require(['jquery','fav'], function ($, fv){
    fav = fv;
});
