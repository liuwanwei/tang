
//弹出登陆窗口
function loginModal()
	{

		$(".modal-backdrop1").show();
		$("#myModal").slideDown(200);
		$(".close").one("click",function(){
			$(".modal-backdrop1").hide();
			$("#myModal").slideUp(100);

		});
	}