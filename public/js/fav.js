define(function (){
    var del_item = function(name, sel ) {
        $.getJSON("/fav/del" ,{
            name:name,
            type:'post'
        },function (data) {
            alert(data.msg);
            //这里2代表的是删除成功
            if(data.code=='2'){
                $(sel).parent().remove();
            }
        });

        return false;
    };

    var add_cart = function (name, sel){
        $.getJSON("/order/add" ,{
            name:name
        },function (data) {
            alert(data.msg);
            if(data.code==0){
                $(sel).parent().remove();
            }
        });
        //这里是为了防止用户刷新的时候，重新添加一遍商品
        history.replaceState(null, null, "/fav/list");

        return false;
    };

    return {
        del_item: del_item,
        add_cart : add_cart
    };
});

