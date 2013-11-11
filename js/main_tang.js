
//弹出登陆窗口
function loginModal()
	{

		$(".modal-backdrop").show();
		$("#myModal").slideDown(200);
		$(".close").one("click",function(){
			$(".modal-backdrop").hide();
			$("#myModal").slideUp(100);

		});
	}