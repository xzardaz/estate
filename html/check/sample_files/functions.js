form_name='';
ctl_name='';

function confirm_delete(name) {
  	return confirm('Потвърдете изтриването!');
}

function ca_selected(arr) {
	eval("document."+form_name+"."+ctl_name+".value = '" + unescape(arr["organization_name"])+"'");
	eval("document."+form_name+"."+ctl_name+"_id.value = '" + unescape(arr["ca_id"])+"'");
}

function co_selected(arr) {
	eval("document."+form_name+"."+ctl_name+".value = '" + unescape(arr["organization_name"])+"'");
	eval("document."+form_name+"."+ctl_name+"_id.value = '" + unescape(arr["contractor_id"])+"'");
}

function doc_types_selected(in_str)
{
	oper = "document." + form_name + "." + ctl_name + ".value = '" +
		unescape(in_str) + "'";
	eval(oper);
}

function legal_reasons_selected(in_str) {
	oper = "document." + form_name + "." + ctl_name + ".value = '" +
		unescape(in_str) + "'";
	eval(oper);
}

//calculate subcontract_percent or subcontract_price by contract_price
//type -> 1 - percent by price, 2 - price by percent, 3 - contract price changed
function percent_price(type) {
	cprice = parseFloat(document.frm.contract_price.value);
	if (cprice != "NaN") {
		prc = parseFloat(document.frm.subcontract_price.value) + "";
		if ((type == 1) && (cprice > 0) && (prc != "NaN") && (prc <= cprice)) {
			document.frm.subcontract_percent.value = Math.round(10000*prc/cprice)/100;
			document.frm.subcontract_price.value = prc;
		}
		
		per = parseFloat(document.frm.subcontract_percent.value) + "";
		if ((type == 2) && (per != "NaN") && (per <= 100)) {
			document.frm.subcontract_price.value = Math.round(cprice*per)/100;
			document.frm.subcontract_percent.value = per;
		}
	}
	if (type == 3) {
		percent_price(1);
		percent_price(2);
		//document.frm.subcontract_percent.value = "";
		//document.frm.subcontract_price.value = "";
	}
}

function uncheck_radio(name) {	
	for(j = 0; j < document.forms.length; j++){
		f = document.forms[j];
		try {
			f[name].checked = false;
		} catch (e) {}
		try {
			for (i = 0; i < f[name].length; i++) 
				f[name][i].checked = false;
		} catch (e) {}
	}
}

function textCounter(field, maxlimit)
{
    if (field.value.length > maxlimit) {
	field.value = field.value.substring(0, maxlimit-1);
    }
}

function go2tab(tab)
{
    document.frm.next.value = tab;
    document.frm.tab_click.value = 1;
    submit_();
}
