// Internet Explorer 6-11
var isIE = /*@cc_on!@*/false || !!document.documentMode;

// Chrome 1 - 79
var isChrome = !!window.chrome && (!!window.chrome.webstore || !!window.chrome.runtime);

if (isIE) {
	document.write("<link rel='stylesheet' href='css/fixie11.css' />");
}