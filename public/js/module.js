var module1 = (function(){
    var _count = 0;
    var m1 = function(){
        console.log("m1");
    };
    var m2 = function(){
        console.log("m2");
    };
    return {
        m1 : m1,
        m2 : m2
    };
})();

var module1 = (function(mod){
    mod.m3 = function(){
        console.log("m3");
    };

    return mod;

})(window.module1 || {});

