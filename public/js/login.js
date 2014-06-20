define(function (){
    var login = function() {
        $.post("/user/login" ,{
            username:$("#username").val(),
            password:$("#password").val(),
            type:'post'
        },function (data) {
            data = $.parseJSON(data);
            if(data.code==0)
                location.href="/index";
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

