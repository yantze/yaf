define(function (){
    var login = function() {
        $.post("/user/login" ,{
            username:$("#username").val(),
            password:$("#password").val(),
            type:'post'
        },function (data) {
            data = $.parseJSON(data);
            if(data.code==0)
                //如果网址是来自其他页面的跳转，则会自动跳转到原来用户请求的页面
                if(location.search==""){
                    location.href="/index";
                }else{
                    location.href=location.href;
                }
            else if(data.code==1)
                $(".js-badge").css("display","inline");

            return true;
        });

        return false;
    };

    var bind_click = function (){
        $(".js-login-btn").on('click', function() {
            login();
        });
    };

    return {
        login: login,
        bind_click: bind_click
    };
});

