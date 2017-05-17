if (typeof jQuery === "undefined") {
    throw new Error("jQuery plugins need to be before this file");
}




function activateNouislide(){
	var rating_slider = document.getElementById('rating_slider');


// Range for the slider
var rating_range = {
  'min': [25],
  'max': [100]
};


//check speed 
start_value = 100;
if ($('#activity-speed').val() == "slow") {
	$('#rating_slider > div.noUi-pips.noUi-pips-horizontal > div').removeClass('text-primary');
	$('#rating_slider > div.noUi-pips.noUi-pips-horizontal > div:nth-child(2)').addClass('text-primary');
	start_value = 25;
} else if ($('#activity-speed').val() == "normal") {
	$('#rating_slider > div.noUi-pips.noUi-pips-horizontal > div').removeClass('text-primary');
	$('#rating_slider > div.noUi-pips.noUi-pips-horizontal > div:nth-child(4)').addClass('text-primary');
	start_value = 50;
} else if ($('#activity-speed').val() == "fast") {
	$('#rating_slider > div.noUi-pips.noUi-pips-horizontal > div').removeClass('text-primary');
	$('#rating_slider > div.noUi-pips.noUi-pips-horizontal > div:nth-child(6)').addClass('text-primary');
	start_value = 75;
} else if ($('#activity-speed').val() == "turbo") {
	$('#rating_slider > div.noUi-pips.noUi-pips-horizontal > div').removeClass('text-primary');
	$('#rating_slider > div.noUi-pips.noUi-pips-horizontal > div:nth-child(9)').addClass('text-primary');
	start_value = 100;
} 


// Initialize the slider
noUiSlider.create(rating_slider, {
  range: rating_range,
  start:start_value,
  step: 25,
  connect: 'lower',
  pips: {
    mode: 'range',
    density: 16.66,
    stepped: true
  }
});


// Alter the pip labels (and modify/add the middle one)
$('#rating_slider > div.noUi-pips.noUi-pips-horizontal').css({'padding':'0'});
$('#rating_slider > div.noUi-pips.noUi-pips-horizontal > div:first').removeClass('noUi-marker noUi-marker-horizontal noUi-marker-normal');
$('#rating_slider > div.noUi-pips.noUi-pips-horizontal > div:nth-child(2)').removeClass('noUi-marker noUi-marker-horizontal noUi-marker-normal');
$('#rating_slider > div.noUi-pips.noUi-pips-horizontal > div:nth-child(3)').removeClass('noUi-marker noUi-marker-horizontal noUi-marker-normal');
$('#rating_slider > div.noUi-pips.noUi-pips-horizontal > div:nth-child(4)').removeClass('noUi-marker noUi-marker-horizontal noUi-marker-normal');
$('#rating_slider > div.noUi-pips.noUi-pips-horizontal > div:nth-child(5)').removeClass('noUi-marker noUi-marker-horizontal noUi-marker-normal');
$('#rating_slider > div.noUi-pips.noUi-pips-horizontal > div:nth-child(6)').removeClass('noUi-marker noUi-marker-horizontal noUi-marker-normal');
$('#rating_slider > div.noUi-pips.noUi-pips-horizontal > div:nth-child(7)').removeClass('noUi-marker noUi-marker-horizontal noUi-marker-normal');
$('#rating_slider > div.noUi-pips.noUi-pips-horizontal > div:nth-child(8)').removeClass('noUi-marker noUi-marker-horizontal noUi-marker-normal');
$('#rating_slider > div.noUi-pips.noUi-pips-horizontal > div:nth-child(9)').removeClass('noUi-marker noUi-marker-horizontal noUi-marker-normal');
$('#rating_slider > div.noUi-pips.noUi-pips-horizontal > div:nth-child(2)').html('<small><b>Slow</b></small>');
$('#rating_slider > div.noUi-pips.noUi-pips-horizontal > div:nth-child(4)')
  .removeClass('noUi-marker-normal')
  <!-- .addClass('txt-b') -->
  .append('<div class="noUi-value noUi-value-horizontal noUi-value-large" style="left: 33.33333%"><small><b>Normal</b></small></div>');
$('#rating_slider > div.noUi-pips.noUi-pips-horizontal > div:nth-child(6)')
  .removeClass('noUi-marker-normal')
  <!-- .addClass('txt-b') -->
  .append('<div class="noUi-value noUi-value-horizontal noUi-value-large" style="left: 66.66667%"><small><b>Fast</b></small></div>');
$('#rating_slider > div.noUi-pips.noUi-pips-horizontal > div:nth-child(9)').html('<small style="margin-right:34px;"><b>Turbo</b></small>');

//add class
if ($('#activity-speed').val() == "slow") {
	$('#rating_slider > div.noUi-pips.noUi-pips-horizontal > div').removeClass('text-primary');
	$('#rating_slider > div.noUi-pips.noUi-pips-horizontal > div:nth-child(2)').addClass('text-primary');
} else if ($('#activity-speed').val() == "normal") {
	$('#rating_slider > div.noUi-pips.noUi-pips-horizontal > div').removeClass('text-primary');
	$('#rating_slider > div.noUi-pips.noUi-pips-horizontal > div:nth-child(4)').addClass('text-primary');
} else if ($('#activity-speed').val() == "fast") {
	$('#rating_slider > div.noUi-pips.noUi-pips-horizontal > div').removeClass('text-primary');
	$('#rating_slider > div.noUi-pips.noUi-pips-horizontal > div:nth-child(6)').addClass('text-primary');
} else if ($('#activity-speed').val() == "turbo") {
	$('#rating_slider > div.noUi-pips.noUi-pips-horizontal > div').removeClass('text-primary');
	$('#rating_slider > div.noUi-pips.noUi-pips-horizontal > div:nth-child(9)').addClass('text-primary');
} 



// Slider event handlers
rating_slider.noUiSlider.on('slide', function (values, handle) {
  // Array of score names
  var rating_names = ['Slow', 'Normal', 'Fast', 'Turbo'];


  // Get rounded value
  var value = Math.round(values[handle]);


  // Get score name to show in tool-tip
  var text = rating_names[value - 1];


  // Set hidden field to value
  // $('#rating_id').val(value);
	// console.log(value);

  // Show score name in tool-tip
  $('#rating-tip').text(text).removeClass('hidden');
  $('#rating_slider > div.noUi-pips.noUi-pips-horizontal > div').removeClass('text-primary');
  if(value >= 0  && value <= 25 ){
		$('#rating_slider > div.noUi-pips.noUi-pips-horizontal > div').removeClass('text-primary');
		$('#rating_slider > div.noUi-pips.noUi-pips-horizontal > div:nth-child(2)').addClass('text-primary');
		$('#activity-speed').val("slow");
  }else if(value >= 26 && value <= 50 ){
		$('#rating_slider > div.noUi-pips.noUi-pips-horizontal > div').removeClass('text-primary');
		$('#rating_slider > div.noUi-pips.noUi-pips-horizontal > div:nth-child(4)').addClass('text-primary');
		$('#activity-speed').val("normal");
  }else if(value >= 51 && value <= 75){
		$('#rating_slider > div.noUi-pips.noUi-pips-horizontal > div').removeClass('text-primary');
		$('#rating_slider > div.noUi-pips.noUi-pips-horizontal > div:nth-child(6)').addClass('text-primary');
		$('#activity-speed').val("fast");
  }else if(value >= 76 && value <= 100){
		$('#rating_slider > div.noUi-pips.noUi-pips-horizontal > div').removeClass('text-primary');
		$('#rating_slider > div.noUi-pips.noUi-pips-horizontal > div:nth-child(9)').addClass('text-primary');
		$('#activity-speed').val("turbo");
		alert("Disclaimer : Menggunakan speed Turbo akan meningkatkan resiko akun anda di flag oleh Instagram");
  }
});


// Function to hide the tool-tip again after a timeout
rating_slider.noUiSlider.on('change', function (values, handle) {
  setTimeout(function () {
    $('#rating-tip').addClass('hidden');
  }, 500);
});
}
$(document).on('click','button',function(){
	var play = '<i class="fa fa-play"></i>&nbsp;<span>Start</span>';
	var stop = '<i class="fa fa-stop"></i>&nbsp;<span>Stop</span>';
	var imgNotif = $(this).closest('.body').find('.startStopArea img');
	var txtNotif = $(this).closest('.body').find('.startStopArea span');
	if($(this).hasClass('btnStop')){
		$(this).removeClass('btnStop');
		$(this).addClass('btnStart');
		$(this).empty();$(this).html()
		$(this).html(play)
		$(this).removeClass('bg-red');$(this).addClass('bgGreenLight');
		imgNotif.attr('src','../celebGramme/images/stopIcon.png');
		txtNotif.removeClass('text-success col-teal');
		txtNotif.addClass('text-danger col-pink');
		txtNotif.html('Stoped');
	}else if($(this).hasClass('btnStart')){
		$(this).removeClass('btnStart');
		$(this).addClass('btnStop');
		$(this).empty();$(this).html()
		$(this).html(stop)
		$(this).removeClass('bgGreenLight');$(this).addClass('bg-red');
		imgNotif.attr('src','../celebGramme/images/startIcon.png');
		txtNotif.removeClass('text-danger col-pink');
		txtNotif.addClass('text-success col-teal');
		txtNotif.html('Start');
	}else if($(this).hasClass('btnSetting')){
		getPage('userSetting');
		$('#jqueryMain').remove();
		$('#navPhone').removeClass('js-right-sidebar');
	}else if($(this).hasClass('btnGeneral')){
		$(this).removeClass('bg-grey');
		$(this).addClass('bg-cyan');
		$(this).closest('.tabButton').find('.btnMessage').addClass('bg-grey');
	}else if($(this).hasClass('btnMessage')){
		$(this).removeClass('bg-grey');
		$(this).addClass('bg-cyan');
		$(this).closest('.tabButton').find('.btnGeneral').addClass('bg-grey');
	}else if($(this).attr('id') == "btnToDashboard"){
		// getPage('dashboard');
		$('.list').find('li').removeClass('active');
		$(".menu .list li").each(function(){
			if($(this).attr('id') == "dashboad"){
				$(this).addClass('active')
			}
		});
	}else if($(this).hasClass('btnOn')){
		$(this).removeClass('bg-grey').addClass('bg-cyan');
		$(this).closest('.btnGroupOO').find('.btnOff').removeClass('bg-cyan').addClass('bg-grey');
	}else if($(this).hasClass('btnOff')){
		$(this).removeClass('bg-grey').addClass('bg-cyan');
		$(this).closest('.btnGroupOO').find('.btnOn').removeClass('bg-cyan').addClass('bg-grey');
	}else if($(this).hasClass('btnDmIn')){
		$(this).addClass('bg-cyan').removeClass('bg-grey'); 
		$(this).closest('.btnTab').find('.btnDmRe').removeClass('bg-cyan').addClass('bg-grey');
		$(this).closest('.btnTab').find('.btnDmAu').removeClass('bg-cyan').addClass('bg-grey');
	}else if($(this).hasClass('btnDmRe')){
		$(this).addClass('bg-cyan').removeClass('bg-grey'); 
		$(this).closest('.btnTab').find('.btnDmIn').removeClass('bg-cyan').addClass('bg-grey');
		$(this).closest('.btnTab').find('.btnDmAu').removeClass('bg-cyan').addClass('bg-grey');
	}else if($(this).hasClass('btnDmAu')){
		$(this).addClass('bg-cyan').removeClass('bg-grey'); 
		$(this).closest('.btnTab').find('.btnDmIn').removeClass('bg-cyan').addClass('bg-grey');
		$(this).closest('.btnTab').find('.btnDmRe').removeClass('bg-cyan').addClass('bg-grey');
	}
})
$(document).on('click','#headingOne_1 h4 a',function(){
	var href = $(this).attr('href');
	<!-- $(this).find($('i .fa-chevron-down')).addClass('fa-chevron-up'); -->
	if($(this).closest('.panel').find($('.btn-circle .fa-chevron-down')).length > 0){
		$(this).closest('.panel').find($('.fa')).removeClass('fa-chevron-down');
		$(this).closest('.panel').find($('.fa')).addClass('fa-chevron-up')
	}else{
		$(this).closest('.panel').find($('.fa')).removeClass('fa-chevron-up');
		$(this).closest('.panel').find($('.fa')).addClass('fa-chevron-down')
	}
	
})
$(document).on('click','#navPhone',function(){
	var me = jQuery(this);
	if ( me.data('requestRunning') ) {
		return;
	}
	me.data('requestRunning', true);
	$('.overlay').show();
	$('#right-sidebar').addClass('open');
	me.data('requestRunning', false);
	return false;
});
/*
$(document).on('click','.menu .list a',function(){
	var filter = $(this).data('identity');
	// if(filter == 'home'){window.location = window.location.href}
	$('.list').find('li').removeClass('active');
	$(this).closest('li').addClass('active');
	var filterElement = $(this).closest('li').attr('id');
	$(".menu .list li").each(function(){
		if($(this).attr('id') == filterElement){
			$(this).addClass('active')
		}
	});
	if(filter != undefined){
		getPage(filter);
	}
	if($(this).closest('#rightsidebar').hasClass('open')){
		$('.js-right-sidebar').click();
		$('.overlay').hide();
		$(this).closest('#rightsidebar').removeClass('open');
	}
});
*/
function getPage(param){
	var path = 'pages';
	if(param !=''){
			$.ajax({
				url: window.location.protocol+"/celebGramme/"+path+"/"+param+".html",
				success: 
					function(result)
						{	
							$('.content').empty();
							$('.content').html();
							$('.content').html(result);
						}
			});
	}
}

/*
$(function(){
	$('.menu .list a').attr('href',"javascript:void(0);");
});
*/
$.AdminBSB = {};
$.AdminBSB.options = {
    colors: {
        red: '#F44336',
        pink: '#E91E63',
        purple: '#9C27B0',
        deepPurple: '#673AB7',
        indigo: '#3F51B5',
        blue: '#2196F3',
        lightBlue: '#03A9F4',
        cyan: '#00BCD4',
        teal: '#009688',
        green: '#4CAF50',
        lightGreen: '#8BC34A',
        lime: '#CDDC39',
        yellow: '#ffe821',
        amber: '#FFC107',
        orange: '#FF9800',
        deepOrange: '#FF5722',
        brown: '#795548',
        grey: '#9E9E9E',
        blueGrey: '#607D8B',
        black: '#000000',
        white: '#ffffff'
    },
    leftSideBar: {
        scrollColor: 'rgba(0,0,0,0.5)',
        scrollWidth: '4px',
        scrollAlwaysVisible: false,
        scrollBorderRadius: '0',
        scrollRailBorderRadius: '0',
        scrollActiveItemWhenPageLoad: true,
        breakpointWidth: 1170
    },
    dropdownMenu: {
        effectIn: 'fadeIn',
        effectOut: 'fadeOut'
    }
}

/* Left Sidebar - Function =================================================================================================
*  You can manage the left sidebar menu options
*  
*/
$.AdminBSB.leftSideBar = {
    activate: function () {
        var _this = this;
        var $body = $('body');
        var $overlay = $('.overlay');

        //Close sidebar
        $(window).click(function (e) {
            var $target = $(e.target);
            if (e.target.nodeName.toLowerCase() === 'i') { $target = $(e.target).parent(); }

            if (!$target.hasClass('bars') && _this.isOpen() && $target.parents('#leftsidebar').length === 0) {
                if (!$target.hasClass('js-right-sidebar')) $overlay.fadeOut();
                $body.removeClass('overlay-open');
            }
        });

        $.each($('.menu-toggle.toggled'), function (i, val) {
            $(val).next().slideToggle(0);
        });

        //When page load
        $.each($('.menu .list li.active'), function (i, val) {
            var $activeAnchors = $(val).find('a:eq(0)');

            $activeAnchors.addClass('toggled');
            $activeAnchors.next().show();
        });

        //Collapse or Expand Menu
        $('.menu-toggle').on('click', function (e) {
            var $this = $(this);
            var $content = $this.next();

            if ($($this.parents('ul')[0]).hasClass('list')) {
                var $not = $(e.target).hasClass('menu-toggle') ? e.target : $(e.target).parents('.menu-toggle');

                $.each($('.menu-toggle.toggled').not($not).next(), function (i, val) {
                    if ($(val).is(':visible')) {
                        $(val).prev().toggleClass('toggled');
                        $(val).slideUp();
                    }
                });
            }

            $this.toggleClass('toggled');
            $content.slideToggle(320);
        });

        //Set menu height
        _this.setMenuHeight();
        _this.checkStatuForResize(true);
        $(window).resize(function () {
            _this.setMenuHeight();
            _this.checkStatuForResize(false);
        });

        //Set Waves
        Waves.attach('.menu .list a', ['waves-block']);
        Waves.init();
    },
    setMenuHeight: function (isFirstTime) {
        if (typeof $.fn.slimScroll != 'undefined') {
            var configs = $.AdminBSB.options.leftSideBar;
            var height = ($(window).height() - ($('.legal').outerHeight() + $('.user-info').outerHeight() + $('.navbar').innerHeight()));
            var $el = $('.list');

            $el.slimscroll({
                height: height + "px",
                color: configs.scrollColor,
                size: configs.scrollWidth,
                alwaysVisible: configs.scrollAlwaysVisible,
                borderRadius: configs.scrollBorderRadius,
                railBorderRadius: configs.scrollRailBorderRadius
            });

            //Scroll active menu item when page load, if option set = true
/*            if ($.AdminBSB.options.leftSideBar.scrollActiveItemWhenPageLoad) {
                var activeItemOffsetTop = $('.menu .list li.active')[0].offsetTop
                if (activeItemOffsetTop > 150) $el.slimscroll({ scrollTo: activeItemOffsetTop + 'px' });
            }*/
        }
    },
    checkStatuForResize: function (firstTime) {
        var $body = $('body');
        var $openCloseBar = $('.navbar .navbar-header .bars');
        var width = $body.width();

        if (firstTime) {
            $body.find('.content, .sidebar').addClass('no-animate').delay(1000).queue(function () {
                $(this).removeClass('no-animate').dequeue();
            });
        }
	
        if (width < 1170) {
            $body.addClass('ls-closed');
            // $openCloseBar.fadeIn();
            $openCloseBar.fadeOut();
        }
        else {
            $body.removeClass('ls-closed');
            $openCloseBar.fadeOut();
        }
    },
    isOpen: function () {
        return $('body').hasClass('overlay-open');
    }
};
//==========================================================================================================================

/* Right Sidebar - Function ================================================================================================
*  You can manage the right sidebar menu options
*  
*/
$.AdminBSB.rightSideBar = {
    activate: function () {
        var _this = this;
        var $sidebar = $('#rightsidebar');
        var $overlay = $('.overlay');

        //Close sidebar
        $(window).click(function (e) {
            var $target = $(e.target);
            if (e.target.nodeName.toLowerCase() === 'i') { $target = $(e.target).parent(); }

            if (!$target.hasClass('js-right-sidebar') && _this.isOpen() && $target.parents('#rightsidebar').length === 0) {
                if (!$target.hasClass('bars')) $overlay.fadeOut();
                $sidebar.removeClass('open');
            }
        });

        $('.js-right-sidebar').on('click', function () {
            $sidebar.toggleClass('open');
            if (_this.isOpen()) { $overlay.fadeIn(); $('#menuRight').css({'margin':'0px;','padding':'0px'});$sidebar.css({'padding':'0px;'});} else { $overlay.fadeOut(); }
        });
    },
    isOpen: function () {
        return $('.right-sidebar').hasClass('open');
    }
}
//==========================================================================================================================

/* Searchbar - Function ================================================================================================
*  You can manage the search bar
*  
*/
var $searchBar = $('.search-bar');
$.AdminBSB.search = {
    activate: function () {
        var _this = this;

        //Search button click event
        $('.js-search').on('click', function () {
            _this.showSearchBar();
        });

        //Close search click event
        $searchBar.find('.close-search').on('click', function () {
            _this.hideSearchBar();
        });

        //ESC key on pressed
        $searchBar.find('input[type="text"]').on('keyup', function (e) {
            if (e.keyCode == 27) {
                _this.hideSearchBar();
            }
        });
    },
    showSearchBar: function () {
        $searchBar.addClass('open');
        $searchBar.find('input[type="text"]').focus();
    },
    hideSearchBar: function () {
        $searchBar.removeClass('open');
        $searchBar.find('input[type="text"]').val('');
    }
}
//==========================================================================================================================

/* Navbar - Function =======================================================================================================
*  You can manage the navbar
*  
*/
$.AdminBSB.navbar = {
    activate: function () {
        var $body = $('body');
        var $overlay = $('.overlay');

        //Open left sidebar panel
        $('.bars').on('click', function () {
            $body.toggleClass('overlay-open');
            if ($body.hasClass('overlay-open')) { $overlay.fadeIn(); } else { $overlay.fadeOut(); }
        });

        //Close collapse bar on click event
        $('.nav [data-close="true"]').on('click', function () {
            var isVisible = $('.navbar-toggle').is(':visible');
            var $navbarCollapse = $('.navbar-collapse');

            if (isVisible) {
                $navbarCollapse.slideUp(function () {
                    $navbarCollapse.removeClass('in').removeAttr('style');
                });
            }
        });
    }
}
//==========================================================================================================================

/* Input - Function ========================================================================================================
*  You can manage the inputs(also textareas) with name of class 'form-control'
*  
*/
$.AdminBSB.input = {
    activate: function () {
        //On focus event
        $('.form-control').focus(function () {
            $(this).parent().addClass('focused');
        });

        //On focusout event
        $('.form-control').focusout(function () {
            var $this = $(this);
            if ($this.parents('.form-group').hasClass('form-float')) {
                if ($this.val() == '') { $this.parents('.form-line').removeClass('focused'); }
            }
            else {
                $this.parents('.form-line').removeClass('focused');
            }
        });

        //On label click
        $('body').on('click', '.form-float .form-line .form-label', function () {
            $(this).parent().find('input').focus();
        });

        //Not blank form
        $('.form-control').each(function () {
            if ($(this).val() !== '') {
                $(this).parents('.form-line').addClass('focused');
            }
        });
    }
}
//==========================================================================================================================

/* Form - Select - Function ================================================================================================
*  You can manage the 'select' of form elements
*  
*/
$.AdminBSB.select = {
    activate: function () {
        if ($.fn.selectpicker) { $('select:not(.ms)').selectpicker(); }
    }
}
//==========================================================================================================================

/* DropdownMenu - Function =================================================================================================
*  You can manage the dropdown menu
*  
*/

$.AdminBSB.dropdownMenu = {
    activate: function () {
        var _this = this;

        $('.dropdown, .dropup, .btn-group').on({
            "show.bs.dropdown": function () {
                var dropdown = _this.dropdownEffect(this);
                _this.dropdownEffectStart(dropdown, dropdown.effectIn);
            },
            "shown.bs.dropdown": function () {
                var dropdown = _this.dropdownEffect(this);
                if (dropdown.effectIn && dropdown.effectOut) {
                    _this.dropdownEffectEnd(dropdown, function () { });
                }
            },
            "hide.bs.dropdown": function (e) {
                var dropdown = _this.dropdownEffect(this);
                if (dropdown.effectOut) {
                    e.preventDefault();
                    _this.dropdownEffectStart(dropdown, dropdown.effectOut);
                    _this.dropdownEffectEnd(dropdown, function () {
                        dropdown.dropdown.removeClass('open');
                    });
                }
            }
        });

        //Set Waves
        Waves.attach('.dropdown-menu li a', ['waves-block']);
        Waves.init();
    },
    dropdownEffect: function (target) {
        var effectIn = $.AdminBSB.options.dropdownMenu.effectIn, effectOut = $.AdminBSB.options.dropdownMenu.effectOut;
        var dropdown = $(target), dropdownMenu = $('.dropdown-menu', target);

        if (dropdown.length > 0) {
            var udEffectIn = dropdown.data('effect-in');
            var udEffectOut = dropdown.data('effect-out');
            if (udEffectIn !== undefined) { effectIn = udEffectIn; }
            if (udEffectOut !== undefined) { effectOut = udEffectOut; }
        }

        return {
            target: target,
            dropdown: dropdown,
            dropdownMenu: dropdownMenu,
            effectIn: effectIn,
            effectOut: effectOut
        };
    },
    dropdownEffectStart: function (data, effectToStart) {
        if (effectToStart) {
            data.dropdown.addClass('dropdown-animating');
            data.dropdownMenu.addClass('animated dropdown-animated');
            data.dropdownMenu.addClass(effectToStart);
        }
    },
    dropdownEffectEnd: function (data, callback) {
        var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
        data.dropdown.one(animationEnd, function () {
            data.dropdown.removeClass('dropdown-animating');
            data.dropdownMenu.removeClass('animated dropdown-animated');
            data.dropdownMenu.removeClass(data.effectIn);
            data.dropdownMenu.removeClass(data.effectOut);

            if (typeof callback == 'function') {
                callback();
            }
        });
    }
}
//==========================================================================================================================

/* Browser - Function ======================================================================================================
*  You can manage browser
*  
*/
var edge = 'Microsoft Edge';
var ie10 = 'Internet Explorer 10';
var ie11 = 'Internet Explorer 11';
var opera = 'Opera';
var firefox = 'Mozilla Firefox';
var chrome = 'Google Chrome';
var safari = 'Safari';

$.AdminBSB.browser = {
    activate: function () {
        var _this = this;
        var className = _this.getClassName();

        if (className !== '') $('html').addClass(_this.getClassName());
    },
    getBrowser: function () {
        var userAgent = navigator.userAgent.toLowerCase();

        if (/edge/i.test(userAgent)) {
            return edge;
        } else if (/rv:11/i.test(userAgent)) {
            return ie11;
        } else if (/msie 10/i.test(userAgent)) {
            return ie10;
        } else if (/opr/i.test(userAgent)) {
            return opera;
        } else if (/chrome/i.test(userAgent)) {
            return chrome;
        } else if (/firefox/i.test(userAgent)) {
            return firefox;
        } else if (!!navigator.userAgent.match(/Version\/[\d\.]+.*Safari/)) {
            return safari;
        }

        return undefined;
    },
    getClassName: function () {
        var browser = this.getBrowser();

        if (browser === edge) {
            return 'edge';
        } else if (browser === ie11) {
            return 'ie11';
        } else if (browser === ie10) {
            return 'ie10';
        } else if (browser === opera) {
            return 'opera';
        } else if (browser === chrome) {
            return 'chrome';
        } else if (browser === firefox) {
            return 'firefox';
        } else if (browser === safari) {
            return 'safari';
        } else {
            return '';
        }
    }
}
//==========================================================================================================================

$(function () {
    $.AdminBSB.browser.activate();
    $.AdminBSB.leftSideBar.activate();
    $.AdminBSB.rightSideBar.activate();
    $.AdminBSB.navbar.activate();
    $.AdminBSB.dropdownMenu.activate();
    $.AdminBSB.input.activate();
    $.AdminBSB.select.activate();
    $.AdminBSB.search.activate();

    setTimeout(function () { $('.page-loader-wrapper').fadeOut(); }, 50);
});


$(document).on('click','#button-package-normal',function(){
	$("#button-package-normal").addClass("bg-cyan");
	$("#button-package-normal").removeClass("bgBlueGreen");
	$("#button-package-extra").removeClass("bg-cyan");
	$("#button-package-extra").addClass("bgBlueGreen");
});
$(document).on('click','#button-package-extra',function(){
	$("#button-package-normal").removeClass("bg-cyan");
	$("#button-package-normal").addClass("bgBlueGreen");
	$("#button-package-extra").addClass("bg-cyan");
	$("#button-package-extra").removeClass("bgBlueGreen");
});
