/* 说明:
 * 这是requirejs的入口文件
 * 修改里面的module时,由于被浏览器缓存机制requirejs
 * 没有显式的引用module,导致浏览器强制刷新的时候,没有重新下载module
 */
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
    $(".js-search-name").on('keypress', function(event) {
        if ( event.which == 13 ) {
            event.preventDefault();
            name = $('.js-search-name').val();
            location.href="/item/search?name="+name;
        }
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
