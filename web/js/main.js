$(function(){	
	
	//slider on main page
	var swiper = new Swiper('.swiper-container', {
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
    });
	
    //open auth popup
    $(".links .popup").on("click", function(){
        $(".auth-popup").toggleClass("active")
    });
	
    //close auth popup
    $(document).mouseup(function (e){
		var div = $(".auth-popup");
		if (!div.is(e.target)
		    && div.has(e.target).length === 0) {
			div.removeClass("active");
		}
	});
    
	//open my accounts
    $(".user .ident.arrowed").on("click", function(){
        $(this).toggleClass("open");
        $(".user-menu .accounts").toggleClass("open");
    });
    
    //tabs
    $(".tab").not("input").on("click", function(){
        $(this).parent(".tab-block").find(".tab").removeClass("active")
        $(this).addClass("active");
        var num = $(this).index();
        $(this).parent(".tab-block").parent(".tabs").next(".tab-content").find(".tab-cont").removeClass("active")
        $(this).parent(".tab-block").parent(".tabs").next(".tab-content").children(".tab-cont").eq(num).addClass("active")
        $(this).parent(".tab-block").parent(".tabs").parent(".padd-block").next(".tab-content").find(".tab-cont").removeClass("active")
        $(this).parent(".tab-block").parent(".tabs").parent(".padd-block").next(".tab-content").children(".tab-cont").eq(num).addClass("active")
        if($(this).parent(".tab-block").parent(".tabs").next(".tab-content").hasClass("main")){
            $(".tab-cont.active .tabs.secondary").children(".tab-block").children(".tab").removeClass("active")
            $(".tab-cont.active .tabs.secondary").children(".tab-block").children(".tab").eq("0").addClass("active")
            $(".tab-cont.active .tab-content.secondary").children(".tab-cont").eq("0").addClass("active")
        }
    });
    
    //selects
    $(".change-options").on("change", function(){
        $("select.default").hide();
        $(".changed").removeClass("active");
        if($(".change-options option:selected").val() == "user"){
            $(".changed.user").addClass("active")
        }else{
            if($(".change-options option:selected").val() == "check"){
                $(".changed.check").addClass("active")
            }else{
                if($(".change-options option:selected").val() == "bonus"){
                    $(".changed.bonus").addClass("active")
                }else{
                    $("select.default").show();
                }
            }
        }
    });
    
    //change arrow
    $(".heading .cell.date").on("click", function(){
        if($(this).hasClass("up")){
            $(this).removeClass("up")
            $(this).addClass("down")
        }else{
            $(this).addClass("up")
            $(this).removeClass("down")
        }
    });
	
    //open next row
    $(".row").on("click", function(){
        if($(this).next(".row").hasClass("hidden")){
            $(this).next(".row.hidden").toggleClass("active")
        }
    });
    
    //activate input
    $(".input-log .checkbox").on("click", function(){
        $(".input-log .checkbox").removeClass("active")
        $(this).addClass("active")
        if($(this).hasClass("for-user")){
            $(this).next("input").removeClass("disabled")
            $(".input-log .theme select").removeClass("support")
            $(".input-log .theme input").removeClass("support")
            $(".input-log .theme select").addClass("user")
            $(".input-log .theme input").addClass("user")
        }
        if($(this).hasClass("support")){
            $(".input-log .checkbox.for-user").next("input").addClass("disabled")
            $(".input-log .theme select").removeClass("user")
            $(".input-log .theme input").removeClass("user")
            $(".input-log .theme select").addClass("support")
            $(".input-log .theme input").addClass("support")
        }
    });
    
    //open steps
    $(".start").on("click", function(){
        $(".steps-popup").toggleClass("active")
    });
    
    //close steps on btn
    $(".steps-popup .close").on("click", function(){
        $(".steps-popup").removeClass("active")
    });
    
    //close steps out block
    $(document).mouseup(function (e){
		var div = $(".steps-popup");
		if (!div.is(e.target)
		    && div.has(e.target).length === 0) {
			div.removeClass("active");
		}
	});
    
    //next step
    $(".steps-popup .next-step").on("click", function(){
        $(".steps-popup .step-cont").removeClass("active")
        $(this).parent(".string").parent(".step-cont").next(".step-cont").addClass("active")
        $(".steps-popup .steps").find(".step.active").removeClass("active").addClass("done").next(".arrow").next(".step").addClass("active")
    });
    
    //on resize    
    function resize(){
        if($(window).width() <= 1200){
            $(".links-block nav span").clone().addClass("added").prependTo(".links-block.mobile")
            $(".links-block nav span").remove()
            $(".tabs.main.active").removeClass("active")
            $(".padd-block").addClass("full")
            $(".tabs.main .hide-show .hide").removeClass("active").next(".show").addClass("active")
        }else{
            $(".links-block.mobile .links.added").clone().removeClass("added").prependTo(".links-block nav")
            $(".links-block.mobile .links.added").remove()
        }
    }
    resize();
    
    $(window).on("resize", function(){
        resize();
    })
    
    //open mobile menu
    $(".mobile-btn").on("click", function(){
        $(".links-block.mobile").toggleClass("active")
    });
    
    
    //close lk menu
    $(".tabs.main .hide-show>div").on("click", function(){
        $(".tabs.main").toggleClass("active")
        $(".padd-block").toggleClass("full")
        if($(this).hasClass("hide")){
            $(this).removeClass("active").next(".show").addClass("active")
        }
        if($(this).hasClass("show")){
            $(this).removeClass("active").prev(".hide").addClass("active")
        }
    });
    
    //sticky header
    var stickyNavTop = $(".tabs.main").offset().top+0;
    var stickyNav = function(){
        var scrollTop = $(window).scrollTop();
        if (scrollTop > stickyNavTop) { 
            $(".tabs.main").addClass("sticky");
            $(".to-top").addClass("active");
        } else {
            $(".to-top").removeClass("active");
            $(".tabs.main").removeClass("sticky"); 
        }
    };
    stickyNav();
    $(window).scroll(function() {
        stickyNav();
    });
    
    //scroll to-top
    $(".to-top").click(function() {
      $("html, body").animate({ scrollTop: 0 }, "500");
      return false;
    });

    //open folder
    $(".folder").on("click", function(){
      if($(this).parent(".string").next("div").hasClass("profile-block")){
        $(this).parent(".string").parent(".profile-block").toggleClass("mini");
      }
    });
    
    
    
    
    
    
    
    
});