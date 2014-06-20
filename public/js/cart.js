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

        return false;
    };

    return {
        del_item: del_item,
        add_fav : add_fav
    };
});

