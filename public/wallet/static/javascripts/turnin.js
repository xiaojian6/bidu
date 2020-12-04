$(function(){
    var address = $("#user_info").data("address")
    if (address != ""){
        $.ajax({
            type: "POST",
            url: laravel_api + "/user/getuserbyaddress",
            data: {
                address:address
            },
            dataType: "json",
            success: function(data){
                if (data.type == "ok"){
                    console.log(data.message)
                    $("#account_number").val(data.message.account_number)
                    $("#address").val(data.message.address)
                    $("#ewm_png").attr("src","http://qr.liantu.com/api.php?&w=300&text="+data.message.address)
                } else{
                    layer_msg(data.message)
                    return false;
                }
            }
        });
    }

    var content = $("#address").val();
	var clipboard = new ClipboardJS('.trade_link', {
		text: function() {
			return content;
		}
	});
	clipboard.on('success', function(e) {
		console.log(e);
	});
 
	clipboard.on('error', function(e) {
		console.log(e);
	});

    
});