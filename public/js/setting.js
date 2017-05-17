	$(document).click(function(e) {
			e.stopPropagation();
			var target = e.target;

			if ( $(target).is(".hint") != true ) {
//						$('.hint').hide();
				if (!$(target).is('.glyphicon-question-sign') ) {
						$('.glyphicon-question-sign').find(".hint").hide();
				}
				if (!$(target).is('.glyphicon-menu-down')  ) {
						$('.glyphicon-menu-down').find(".hint").hide();
				}
			}
	});
		
  $(document).ready(function() {
		$('.tooltipPlugin').tooltipster({
				theme: 'tooltipster-noir',
				contentAsHTML: true,
				interactive:true,
		});
		$("#extra-time").hide();
    // $('.add-spin-comment').click(function(e){
		$( "body" ).on( "click", ".add-spin-comment", function(e) {
			e.preventDefault();
			var $select = $("#textarea-comments").selectize();
			var selectize = $select[0].selectize;
			selectize.addOption({value:$(this).text(),text:$(this).text()}); //option can be created manually or loaded using Ajax
			selectize.addItem($(this).text()); 			
			console.log($(this).text());
    });
    $('#button-package').click(function(e){
			if ($("#extra-time").is(":visible") ){
				$("#extra-time").hide();
				$("#normal-time").fadeIn(1000);
				$(this).html("Normal Time Package");
			} else 
			if ($("#normal-time").is(":visible") ){
				$("#normal-time").hide();
				$("#extra-time").fadeIn(1000);
				$(this).html("Extra Time Package");
			}
    });
		
    // $( "body" ).on( "click", ".button-action", function(e) {
    $('.button-action').click(function(e){
      e.preventDefault();
      action = "";
      if ($(this).val()=="Start") { action = "start"; }
      if ($(this).val()=="Stop") { action = "stop"; }
      call_action(action,$(this).attr("data-id"));
    });

    $("#alert").hide();

		$('#followButton').click(function(e){
			$("#status_follow").val("on");
			$("#status_unfollow").val("off");
			
			$("#activity").val("follow");
			$('#followButton').addClass('btn-success');
			$('#unfollowButton').removeClass('btn-success');
			if ($("#status_whitelist").val()==0) {
				$('#div-unfollow-whitelist').fadeOut(500);
			}

			$('.status-unfollow').fadeIn(500);
			if ( ( $("#select-follow-source").val() == "followers of username" ) || ( $("#select-follow-source").val() == "following of username" )) {
				$("#div-usernames").fadeIn(500);
			}
			
		});
		$('#unfollowButton').click(function(e){
			$("#status_follow").val("off");
			$("#status_unfollow").val("on");
			
			
			$("#activity").val("unfollow");
			$('#followButton').removeClass('btn-success');
			$('#unfollowButton').addClass('btn-success');
			if ($("#status_whitelist").val()==1) {
				$('#div-unfollow-whitelist').fadeIn(500);
			}

			$('.status-unfollow').fadeOut(500);
			$('#div-usernames').fadeOut(500);
			
		});
		


		
		/*status follow like comment (on off nya) */
		$('#statusFollowOnButton').click(function(e){
			$("#status_follow_unfollow").val("on");
			$('#statusFollowOnButton').addClass('btn-primary');
			$('#statusFollowOffButton').removeClass('btn-danger');
			$(".status-follow").fadeIn(500);
			
			if ($('#unfollowButton').hasClass("btn-success")) {
				if ($("#status_whitelist").val()==1) {
					$('#div-unfollow-whitelist').fadeIn(500);
				}
				
				$("#status_follow").val("off");
				$("#status_unfollow").val("on");
			}
			
			if ($('#followButton').hasClass("btn-success")) {
				$("#status_follow").val("on");
				$("#status_unfollow").val("off");
			}

			if ( $("#select-follow-source").val() == "hashtags" ) {
				$("#div-usernames").fadeOut(500);
				$("#div-hashtags").fadeIn(500);
			}
			if ( ( $("#select-follow-source").val() == "followers of username" ) || ( $("#select-follow-source").val() == "following of username" )) {
				$("#div-usernames").fadeIn(500);
				if (($('#statusLikeOffButton').hasClass("btn-danger")) && ($('#statusCommentOffButton').hasClass("btn-danger"))) {
					$('#div-hashtags').fadeOut(500);
				}
			}
		});
		$('#statusFollowOffButton').click(function(e){
			$("#status_follow").val("off");
			$("#status_unfollow").val("off");
				
			$("#status_follow_unfollow").val("off");
			$('#statusFollowOnButton').removeClass('btn-primary');
			$('#statusFollowOffButton').addClass('btn-danger');
			$(".status-follow").fadeOut(500);
			if ($("#status_whitelist").val()==0) {
				$('#div-unfollow-whitelist').fadeOut(500);
			}
			$("#div-usernames").fadeOut(500);

			if ( (!$('#statusFollowOffButton').hasClass("btn-danger") && ( ( $("#select-follow-source").val() == "followers of username" ) || ( $("#select-follow-source").val() == "following of username" )) ) 
				&& ($('#statusLikeOffButton').hasClass("btn-danger")) && ($('#statusCommentOffButton').hasClass("btn-danger"))  ) 
			{
				$('#div-hashtags').fadeOut(500);
			}
		});

		$('#statusLikeOnButton').click(function(e){
			$("#status_like").val("on");
			$('#statusLikeOnButton').addClass('btn-primary');
			$('#statusLikeOffButton').removeClass('btn-danger');
			$("#div-hashtags").fadeIn(500);
		});
		$('#statusLikeOffButton').click(function(e){
			$("#status_like").val("off");
			$('#statusLikeOnButton').removeClass('btn-primary');
			$('#statusLikeOffButton').addClass('btn-danger');
			
			if ( (!$('#statusFollowOffButton').hasClass("btn-danger") && ( ( $("#select-follow-source").val() == "followers of username" ) || ( $("#select-follow-source").val() == "following of username" )) ) 
				&& ($('#statusLikeOffButton').hasClass("btn-danger")) && ($('#statusCommentOffButton').hasClass("btn-danger"))  ) 
			{
				$('#div-hashtags').fadeOut(500);
			}
		});

		$('#statusCommentOnButton').click(function(e){
			$("#status_comment").val("on");
			$('#statusCommentOnButton').addClass('btn-primary');
			$('#statusCommentOffButton').removeClass('btn-danger');
			$('#div-comment').fadeIn(500);
			$("#div-hashtags").fadeIn(500);
		});
		$('#statusCommentOffButton').click(function(e){
			$("#status_comment").val("off");
			$('#statusCommentOnButton').removeClass('btn-primary');
			$('#statusCommentOffButton').addClass('btn-danger');
			$('#div-comment').fadeOut(500);
			
			if ( (!$('#statusFollowOffButton').hasClass("btn-danger") && ( ( $("#select-follow-source").val() == "followers of username" ) || ( $("#select-follow-source").val() == "following of username" )) ) 
				&& ($('#statusLikeOffButton').hasClass("btn-danger")) && ($('#statusCommentOffButton').hasClass("btn-danger"))  ) 
			{
				$('#div-hashtags').fadeOut(500);
			}
		});
		
		
		
		
		$('.hint').hide();

		// $('.hint').click(function(e){
			// e.preventDefault();
			// e.stopPropagation();
		// });
		
		// $('.glyphicon-menu-down').click(function(e){
			// $(this).find('.hint').slideToggle();
		// });

		// $('.glyphicon-question-sign').click(function(e){
			// $(this).find('.hint').slideToggle();
		// });
		
		$( "#select-follow-source" ).change(function() {
			if ( $( this ).val() == "hashtags" ) {
				$("#div-usernames").fadeOut(500);
				$("#div-hashtags").fadeIn(500);
			}
			if ( ( $( this ).val() == "followers of username" ) || ( $( this ).val() == "following of username" )) {
				$("#div-usernames").fadeIn(500);
				if (($('#statusLikeOffButton').hasClass("btn-danger")) && ($('#statusCommentOffButton').hasClass("btn-danger"))) {
					$("#div-hashtags").fadeOut(500);
				}
			}
		});




		
    $('.selectize-default').selectize({
      plugins:['remove_button'],
      delimiter: ';',
      persist: false,
			onChange: function(value) {
               // alert(value);
				// console.log($(this).parent());
      },
      create: function(input) {
        return {
          value: input,
          text: input
        }
      },
    });
		
		
		
	// show current input values
	$('textarea.selectize-default,select.selectize-default,input.selectize-default').each(function() {
		var $container = $('<div style="font-size:11px;">').addClass('value').html('Current count: ');
		var $value = $('<span>').appendTo($container);
		var $input = $(this);
		var update = function(e) { 
			// $value.text(JSON.stringify($input.val())); 

			var str,res;
			str = JSON.stringify($input.val());
			res = str.split(";");
			if ($input.val() == "") {
				$value.text("0"); 
			} else {
				$value.text(res.length); 
			}
			// console.log(res.length);
			// $container.insertAfter($input.next());
		}

		$(this).on('change', update);
		update();

		$container.insertAfter($input.next());
		
		// $container.insertAfter($input.next());
	});
	
	$('.button-copy').click(function(e){
		$("#textarea-copy").val($("#"+$(this).attr("data-text")).val());
	});
		
	// $('#button-ok-copy').click(function(e){
		// console.log("asd");
		// e.preventDefault();
		// copyToClipboard($("#textarea-copy"));
	// });
		
		
		$('#button-fullauto').click(function(e){
			e.preventDefault();
			$('#button-fullauto').addClass('gold-fullauto-setting');
			$('#button-advanced').removeClass('btn-info');
			$("#status_auto").val(1);
			$("#target-categories").show();
			
			$("#div-loading").show();
			$(".advanced-manual-setting").addClass("hide");
			setTimeout(function() {
				//your code to be executed after 1 second
				$("#div-loading").hide();
			}, 500);			
		});
		$('#button-advanced').click(function(e){
			e.preventDefault();
			$('#button-advanced').addClass('btn-info');
			$('#button-fullauto').removeClass('gold-fullauto-setting');
			$("#status_auto").val(0);
			$("#target-categories").hide();
			
			$("#div-loading").show();
			$(".advanced-manual-setting").removeClass("hide");
			setTimeout(function() {
				//your code to be executed after 1 second
				$("#div-loading").hide();
			}, 500);			
		});
		
		
		$('#AutoLikesOnButton').click(function(e){
			$("#is_auto_get_likes").val(1);
			$('#AutoLikesOnButton').addClass('btn-primary');
			$('#AutoLikesOffButton').removeClass('btn-danger');
		});
		$('#AutoLikesOffButton').click(function(e){
			$("#is_auto_get_likes").val(0);
			$('#AutoLikesOffButton').addClass('btn-danger');
			$('#AutoLikesOnButton').removeClass('btn-primary');
		});
		$('#AutoLikesFollowersOnButton').click(function(e){
			$("#is_like_followers").val(1);
			$('#AutoLikesFollowersOnButton').addClass('btn-primary');
			$('#AutoLikesFollowersOffButton').removeClass('btn-danger');
		});
		$('#AutoLikesFollowersOffButton').click(function(e){
			$("#is_like_followers").val(0);
			$('#AutoLikesFollowersOffButton').addClass('btn-danger');
			$('#AutoLikesFollowersOnButton').removeClass('btn-primary');
		});
		
		$('#BlacklistOnButton').click(function(e){
			e.preventDefault();
			$('#BlacklistOnButton').addClass('black-blacklist');
			$('#BlacklistOffButton').removeClass('black-blacklist');
			$("#status_blacklist").val(1);
			$("#div-blacklist").fadeIn(500);
		});
		$('#BlacklistOffButton').click(function(e){
			e.preventDefault();
			$('#BlacklistOnButton').removeClass('black-blacklist');
			$('#BlacklistOffButton').addClass('black-blacklist');
			$("#status_blacklist").val(0);
			$("#div-blacklist").fadeOut(500);
		});

		$('#WhitelistOnButton').click(function(e){
			e.preventDefault();
			$('#WhitelistOnButton').addClass('black-blacklist');
			$('#WhitelistOffButton').removeClass('black-blacklist');
			$("#status_whitelist").val(1);
			$("#div-unfollow-whitelist").fadeIn(500);
		});
		$('#WhitelistOffButton').click(function(e){
			e.preventDefault();
			$('#WhitelistOnButton').removeClass('black-blacklist');
			$('#WhitelistOffButton').addClass('black-blacklist');
			$("#status_whitelist").val(0);
			$("#div-unfollow-whitelist").fadeOut(500);
		});
		
		$('#DontFollowPUOnButton').click(function(e){
			e.preventDefault();
			$('#DontFollowPUOnButton').addClass('btn-primary');
			$('#DontFollowPUOffButton').removeClass('btn-danger');
			$("#dont_follow_pu").val(1);
		});
		$('#DontFollowPUOffButton').click(function(e){
			e.preventDefault();
			$('#DontFollowPUOnButton').removeClass('btn-primary');
			$('#DontFollowPUOffButton').addClass('btn-danger');
			$("#dont_follow_pu").val(0);
		});

		$('#DontFollowSUOnButton').click(function(e){
			e.preventDefault();
			$('#DontFollowSUOnButton').addClass('btn-primary');
			$('#DontFollowSUOffButton').removeClass('btn-danger');
			$("#dont_follow_su").val(1);
		});
		$('#DontFollowSUOffButton').click(function(e){
			e.preventDefault();
			$('#DontFollowSUOnButton').removeClass('btn-primary');
			$('#DontFollowSUOffButton').addClass('btn-danger');
			$("#dont_follow_su").val(0);
		});
		
		$('#button-mode-setting').click(function(e){
			$(".button-mode").removeClass("active");
			$(this).addClass("active");

			$(".div-mode").addClass("hide");
			$("#div-mode-setting").removeClass("hide");
		});
		$('#button-mode-comment').click(function(e){
			$(".button-mode").removeClass("active");
			$(this).addClass("active");
			
			$(".div-mode").addClass("hide");
			$("#div-mode-comment").removeClass("hide");
		});
		$('#button-mode-like').click(function(e){
			$(".button-mode").removeClass("active");
			$(this).addClass("active");
			
			$(".div-mode").addClass("hide");
			$("#div-mode-like").removeClass("hide");
		});
		$('#button-mode-mention').click(function(e){
			$(".button-mode").removeClass("active");
			$(this).addClass("active");
			
			$(".div-mode").addClass("hide");
			$("#div-mode-mention").removeClass("hide");
		});
		$('#button-mode-tagged').click(function(e){
			$(".button-mode").removeClass("active");
			$(this).addClass("active");
			
			$(".div-mode").addClass("hide");
			$("#div-mode-tagged").removeClass("hide");
		});
		$('#button-mode-follow').click(function(e){
			$(".button-mode").removeClass("active");
			$(this).addClass("active");
			
			$(".div-mode").addClass("hide");
			$("#div-mode-follow").removeClass("hide");
		});
  });
