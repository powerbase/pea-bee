function pr(obj) {
	var properties = '';
	for (var prop in obj){
		properties += prop + "=" + obj[prop] + "\n";
	}
	alert(properties);
}

function array_key_exists(key, search) {
	if (!search || (search.constructor !== Array && search.constructor !== Object)) {
		return false;
	}
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

var shadow_overlay = "#shadow-overlay";

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
		$("#progresss-animation").fadeOut("normal");
		setTimeout(function(){
			$("#progresss-animation").fadeOut("normal");
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
var ua = navigator.userAgent;
var isIE6 = ua.match(/msie [6.]/i),
    isIE7 = ua.match(/msie [7.]/i),
    isIE8 = ua.match(/msie [8.]/i),
    isIE9 = ua.match(/msie [9.]/i),
    isIE10 = ua.match(/msie [10.]/i),
    isChrome = ua.match(/chrome/i),
    isSafari = ua.match(/safari/i),
    isfireFox = ua.match(/firefox/i),
    isOpera = ua.match(/opera/i);

var isIE = false;
if( ua.match(/MSIE/i) || ua.match(/Trident/i) ) {
    isIE = true;
}
