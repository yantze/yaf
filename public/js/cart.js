define(function (){
    var del_item = function(name, sel ) {
        $.getJSON("/order/del" ,{
            name:name,
            type:'post'
        },function (data) {
            alert(data.msg);
            if(data.code==0){
                $(sel).parent().remove();
            }
        });

        return false;
    };

    var add_fav = function (name, sel){
        $.getJSON("/fav/add" ,{
            name:name,
            type:'post'
        },function (data) {
            alert(data.msg);
            if(data.code==0){
                $(sel).parent().remove();
                //收藏完毕后要删除购物车中的内容
                del_item(name, sel);
            }
        });
        //这里是为了防止用户刷新的时候，重新添加一遍商品
        history.replaceState(null, null, "/order/list");

        return false;
    };

    return {
        del_item: del_item,
        add_fav : add_fav
    };
});

