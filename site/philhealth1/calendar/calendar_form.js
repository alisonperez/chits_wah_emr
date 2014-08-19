// JavaScript Document

var ccWidth = 0;
var ccHeight = 0;

function setValue(){
	var f = document.calendarform;
	var date_selected = padString(f.selected_year.value, 4, "0") + "-" + padString(f.selected_month.value, 2, "0") + "-" + padString(f.selected_day.value, 2, "0");

	//not use for now
	//toggle = typeof(toggle) != 'undefined' ? toggle : true;

	window.parent.setValue(f.objname.value, date_selected);
}

function unsetValue(){
	var f = document.calendarform;
	f.selected_day.value = "00";
	f.selected_month.value = "00";
	f.selected_year.value = "0000";

	setValue();

	this.loading();
	//f.submit();
}

function restoreValue(){
	var f = document.calendarform;
	var date_selected = padString(f.selected_year.value, 4, "0") + "-" + padString(f.selected_month.value, 2, "0") + "-" + padString(f.selected_day.value, 2, "0");

	window.parent.updateValue(f.objname.value, date_selected);
}

function selectDay(d){
	var f = document.calendarform;
	f.selected_day.value = d.toString();
	f.selected_month.value = f.m[f.m.selectedIndex].value;
	f.selected_year.value = f.y[f.y.selectedIndex].value;

	setValue();

	this.loading();
	f.submit();

	submitNow(f.selected_day.value, f.selected_month.value, f.selected_year.value);
}

function hL(E, mo){
	//clear last selected
	if(document.getElementById("select")){
		var selectobj = document.getElementById("select");
		selectobj.Id = "";
	}

	while (E.tagName!="TD"){
		E=E.parentElement;
	}

	E.Id = "select";
}

function selectMonth(m){
	var f = document.calendarform;
	f.selected_month.value = m;
}

function selectYear(y){
	var f = document.calendarform;
	f.selected_year.value = y;
}

function move(m, y){
	var f = document.calendarform;
	f.m.value = m;
	f.y.value = y;

	this.loading();
	f.submit();
}

function today(){
	var f = document.calendarform;
	f.m.value = this.today_month;
	f.y.value = this.today_year;

	this.loading();
	f.submit();
}

function closeMe(){
	window.parent.toggleCalendar(this.obj_name);
}

function padString(stringToPad, padLength, padString) {
	if (stringToPad.length < padLength) {
		while (stringToPad.length < padLength) {
			stringToPad = padString + stringToPad;
		}
	}else {}
/*
	if (stringToPad.length > padLength) {
		stringToPad = stringToPad.substring((stringToPad.length - padLength), padLength);
	} else {}
*/
	return stringToPad;
}

function loading(){
	if(this.ccWidth > 0 && this.ccHeight > 0){
		var ccobj = getObject('calendar-container');

		ccobj.style.width = this.ccWidth+'px';
		ccobj.style.height = this.ccHeight+'px';
	}

	document.getElementById('calendar-container').innerHTML = "<div id=\"calendar-body\"><div class=\"refresh\"><div align=\"center\" class=\"txt-container\">Refreshing Calendar...</div></div></div>";
	adjustContainer();
}

function submitCalendar(){
	this.loading();
	document.calendarform.submit();
}

function getObject(item){
	if( window.mmIsOpera ) return(document.getElementById(item));
	if(document.all) return(document.all[item]);
	if(document.getElementById) return(document.getElementById(item));
	if(document.layers) return(document.layers[item]);
	return(false);
}

function adjustContainer(){
	var tc_obj = getObject('calendar-page');
	//var tc_obj = frm_obj.contentWindow.getObject('calendar-page');
	if(tc_obj != null){
		var div_obj = window.parent.document.getElementById('div_'+obj_name);

		if(tc_obj.offsetWidth > 0 && tc_obj.offsetHeight > 0){
			div_obj.style.width = tc_obj.offsetWidth+'px';
			div_obj.style.height = tc_obj.offsetHeight+'px';
			//alert(div_obj.style.width+','+div_obj.style.height);

			var ccsize = getObject('calendar-container');
			this.ccWidth = ccsize.offsetWidth;
			this.ccHeight = ccsize.offsetHeight;
		}
	}
}

window.onload = function(){
	adjustContainer();
	setTimeout("adjustContainer()", 1000);
	restoreValue();
};