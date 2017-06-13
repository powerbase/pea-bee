function pr(obj) {
	var properties = '';
	for (var prop in obj){
		properties += prop + "=" + obj[prop] + "\n";
	}
	alert(properties);
}

function array_key_exists(key, search) {
	if (!search || (search.constructor !== Array && search.constructor !== Object)) return false;
	return key in search;
}

function request(p) {
	if (!array_key_exists('type', p)) p.type = 'GET';
	if (!array_key_exists('async', p)) p.async = true;
	if (!array_key_exists('dataType', p)) p.dataType = "text";
	if (!array_key_exists('cache', p)) p.cache = false;
	if (!array_key_exists('error', p)) p.error = 
		function(xhr, textStatus, errorThrown) {
			alert(errorThrown);
		};
	p.beforeSend = function(){
		progress(true);
	};
	p.complete = function(){
		progress();
	};
	$.ajax(p);
}

function smkIndicateError(input, label, message) {
	$(input).css("border-color", '#a94442');
	$(label).css("color", '#a94442');
	$(input).after("<span style='color: #a94442; display: block; position: absolute; right: 15px; font-size: 12px; margin-top: 0; margin-bottom: 0;'>"+message+"</span>");
}

var flash_message = "#flash-message";
var shadow_overlay = "#shadow-overlay";

var flashMessage = function(level, message) {
	var blink = true;
	$("#progresss-animation").hide();
	$(flash_message).addClass('alert-' + level);
	$(flash_message).html(message);
	$(flash_message).fadeIn(100, function(){
		if (blink) for(var i = 0; i < 2; i++) $(flash_message).fadeTo('normal', 0.3).fadeTo('normal', 1.0);
		$(flash_message).fadeOut(2000);
	});
};

function progress(mode) {
	if (!$(shadow_overlay).get(0)) return;
	var d = new $.Deferred;
	if (mode) {
		$(shadow_overlay).show();
		setTimeout(function(){
			$("#progresss-animation").fadeIn("fast");
			//d.resolve();
		}, 100);
		return d.promise();
	} else {
		$(shadow_overlay).hide();
		$("#progresss-animation").fadeOut("fast");
		setTimeout(function(){
			$("#progresss-animation").fadeOut("fast");
			//d.resolve();
		}, 100);
		return d.promise();
	}
}

$(function() {
	if ($(shadow_overlay).get(0)) {
		$(shadow_overlay).css({
			opacity: '0.4',
			display: 'none',
			position: 'absolute',
			top: '0',
			left: '0',
			width: '100%',
			height: '100%',
			background: '#eee',
			zIndex: '1'
		});
	}
});

window.onbeforeunload = function(){
	progress(true);
};
window.onload = function(){
	if (!$(shadow_overlay).get(0)) return;
	$("#shadow-overlay").css('display', 'none');
	if (window.opener && window.opener.$("shadow_overlay").get(0)) {
		window.opener.$("shadow_overlay").css('display', 'none');
	}
};

$(document).click(function(e) {
	if ($(flash_message).get(0)) $(flash_message).css('display', 'none');
	e.stopPropagation();
});
