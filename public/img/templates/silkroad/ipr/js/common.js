/**
 * Created by Administrator on 2017/2/20.
 */
$(function () {
    state = true;
    $(document).on('click','#select>a',function () {
        var _this=$("#select");
        _this.addClass("on").siblings().removeClass("on");
        if (state) {
            if (!(_this.is(":animated"))) {
                $(document).find('#option').slideDown();
            } else {
                $(document).find('#option').css("display", "none");
            }
            state = false;
        } else {
            if (!(_this.is(":animated"))) {
                $(document).find('#option').slideUp();
            }
            state = true;
        }
    });

	$(document).on('click','#option a',function () {
		$(this).parents("dd").addClass("curr").siblings().removeClass("curr");
		//return false;
    });


})