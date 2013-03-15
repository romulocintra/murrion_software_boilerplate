var numbers = "1234567890";	// for checking of valid card and cvn characters
var formSubmitted = false;		// prevent multiple form submissions
var w3c=(document.getElementById)?true:false;
var ie=(document.all)?true:false;
var N=-1;

function createBar(w,h,bgc,brdW,brdC,blkC,speed,blocks,count,action){
if(ie||w3c){
var t='<div id="_xpbar'+(++N)+'" style="visibility:visible; position:relative; overflow:hidden; width:'+w+'px; height:'+h+'px; background-color:'+bgc+'; border-color:'+brdC+'; border-width:'+brdW+'px; border-style:solid; font-size:1px;">';
t+='<span id="blocks'+N+'" style="left:-'+(h*2+1)+'px; position:absolute; font-size:1px">';
for(i=0;i<blocks;i++){
t+='<span style="background-color:'+blkC+'; left:-'+((h*i)+i)+'px; font-size:1px; position:absolute; width:'+h+'px; height:'+h+'px; '
t+=(ie)?'filter:alpha(opacity='+(100-i*(100/blocks))+')':'-Moz-opacity:'+((100-i*(100/blocks))/100);
t+='"></span>';
}
t+='</span></div>';
document.write(t);
var bA=(ie)?document.all['blocks'+N]:document.getElementById('blocks'+N);
bA.bar=(ie)?document.all['_xpbar'+N]:document.getElementById('_xpbar'+N);
bA.blocks=blocks;
bA.N=N;
bA.w=w;
bA.h=h;
bA.speed=speed;
bA.ctr=0;
bA.count=count;
bA.action=action;
bA.togglePause=togglePause;
bA.showBar=function(){
this.bar.style.visibility="visible";
}
bA.hideBar=function(){
this.bar.style.visibility="hidden";
}
bA.tid=setInterval('startBar('+N+')',speed);
return bA;
}}

function startBar(bn){
var t=(ie)?document.all['blocks'+bn]:document.getElementById('blocks'+bn);
if(parseInt(t.style.left)+t.h+1-(t.blocks*t.h+t.blocks)>t.w){
t.style.left=-(t.h*2+1)+'px';
t.ctr++;
if(t.ctr>=t.count){
eval(t.action);
t.ctr=0;
}}else t.style.left=(parseInt(t.style.left)+t.h+1)+'px';
}

function togglePause(){
if(this.tid==0){
this.tid=setInterval('startBar('+this.N+')',this.speed);
}else{
clearInterval(this.tid);
this.tid=0;
}}

function togglePause(){
if(this.tid==0){
this.tid=setInterval('startBar('+this.N+')',this.speed);
}else{
clearInterval(this.tid);
this.tid=0;
}}

function initialize() {
	formSubmitted = false;	// this handles Firefox forward-back caching when called by onPageShow
	if (document.ccform.PAYMENTBUTTON.value == "true"){
		document.ccform.button.value = "  Next  ";
	} else {
		document.ccform.button.value = "  Pay Now  ";
	}
	return(true);
}

function onPageShow() {
	return(initialize());
}

/* 
 * Function to be used by the confirmation template - like the validate function below, this
 * method will ensure that the payment is only confirmed once.
 */
function confirmPayment() {
	if (formSubmitted) {
		alert("Your request is already being processed. Please wait.");
		return(false);
	}

	formSubmitted = true;
}

function validate() {
	if (formSubmitted) {
		alert("Your request is already being processed. Please wait.");
		return(false);
	}

	if (!checkCardType()) {
		return(false);
	}

	if (!checkCardNumber()) {
		return(false);
	}

	if (!checkCardName()) {
		return(false);
	}

	if (!checkCVV()) {
		return(false);
	}

	if (!checkLUHN()) {
		return(false);
	}

	if (!checkDate()) {
		return(false);
	}

	if (!checkTerms()) {
		return(false);
	}
	
	formSubmitted = true;
	document.ccform.button.value = "  Processing...  ";
	return(true);
}


// this code looks like a throwback to when we presumably had radio buttons or checkboxes for
// selection of credit card type. I can't see any other reason why it would have looked like this.
// cleaned it up anyway, and moved it into this function.
//
// --andrewh 13/7/06
function checkCardType() {
	var cctype = document.ccform.pas_cctype[document.ccform.pas_cctype.selectedIndex].value;

	if (cctype == "") {
		alert("Please choose a credit card type");
		return(false);
	}

	return(true);
}


function checkCardNumber() {
	var cctype = document.ccform.pas_cctype[document.ccform.pas_cctype.selectedIndex].value;
	var ccnum = document.ccform.pas_ccnum.value;
	ccnum = "" + ccnum;

	if ((ccnum.length < 12) || (ccnum.length > 19)) {
		alert("Invalid length for credit card number");
		return(false);
	}	

	// does it contain only digits?
	for (i=0; i < ccnum.length; i++) {
		var c = ccnum.charAt(i);
		if(numbers.indexOf(c) == -1) {
			alert("Please enter only digits in the credit card number\n(No spaces or dashes)");
			return(false);
		}
	}

	// does the first digit correspond to the correct card type?
	if ((cctype == "VISA") && (ccnum.substring(0,1) != "4")) {
		alert("Credit Card Number does not correspond to a VISA card");
		return(false);
	}

	if ((cctype == "MC") && (!((ccnum.substring(0,1) == "5") || (ccnum.substring(0,1) == "6") || (ccnum.substring(0,1) == "3")))) {
		alert("Credit Card Number does not correspond to a MasterCard card");
		return(false);
	}

	if ((cctype == "AMEX") && (ccnum.substring(0,1) != "3")) {
		alert("Credit Card Number does not correspond to a AMEX card");
		return(false);
	}

	if ((cctype == "LASER") && (!((ccnum.substring(0,1) == "6") || (ccnum.substring(0,1) == "5")))) {
		alert("Credit Card Number does not correspond to a Laser card");
		return(false);
	}

	if ((cctype == "SWITCH") && (!((ccnum.substring(0,1) == "6") || (ccnum.substring(0,1) == "5") || (ccnum.substring(0,1) == "3") || (ccnum.substring(0,2) == "49")))) {
		alert("Credit Card Number does not correspond to a Switch card");
		return(false);
	}

	return(true);
}


function checkCardName() {
	var ccname = document.ccform.pas_ccname.value;

	if (ccname == "") {
		alert("Please enter cardholder's name");
		return(false);
	}

	return(true);
}


function checkCVV() {
	// careful.  sometimes these input fields aren't presented to the user, so they're
	// not always going to exist or be populated.  -andrewh 13/7/06

	// are we checking for CVN?
	var cccvcind = "0";
	if (eval(document.ccform.pas_cccvcind)) {
		cccvcind = document.ccform.pas_cccvcind.value;
	}

	if (cccvcind == "0") {
		// we're not checking. return success.
		return(true);
	} else if (cccvcind != "1") {
		// we received a weird value. someone's playing silly buggers.
		alert("Invalid CVV indicator set. Please only submit transaction requests through official web forms.");
		return(false);
	}

	// ok, we're checking.
	var cvnNum;
	if (eval(document.ccform.pas_cccvc)) {
		cvnNum = document.ccform.pas_cccvc.value;
	} else {
		cvnNum = "";
	}

	// check that it contains only digits
	for (i=0; i < cvnNum.length; i++) {
		var c = cvnNum.charAt(i);
		if(numbers.indexOf(c) == -1) {
			alert("Please enter only digits for the Security Code\n(No spaces, dashes or letters)");
			return(false);
		}
	}

	var cctype = document.ccform.pas_cctype[document.ccform.pas_cctype.selectedIndex].value;
	if (cctype == "LASER") {
		// LASER cards don't have CVV
		cvnRequiredLength = 0;
	} else if (cctype == "AMEX") {
		// CVN should be 4 digits long
		cvnRequiredLength = 4;
	} else if (cctype == "SWITCH") {
		// CVN should be 3 digits long if present; 0 if not
		// not sure if all SWITCH cards have CVN details. All Maestro cards do, but not sure about older cards.
		if (cvnNum.length == 0) {
			cvnRequiredLength = 0;
			//No CVN entered,
			//CVN is not enforced for Switch
			//Set the cvn indicator to 0
			document.ccform.pas_cccvcind.value = 0;
		} else {
			cvnRequiredLength = 3;
		}
	} else {
		// CVN should be 3 digits long
		cvnRequiredLength = 3;
	}

	// correct length?
	if ( (cvnNum.length != cvnRequiredLength) && (cctype != "LASER") ) {
		alert("Security Code must be " + cvnRequiredLength + " digits long for this card type.");
		return(false);
	}

	// passed all checks. well done, lad.
	return(true);
}


function checkLUHN() {
	var ccnum = document.ccform.pas_ccnum.value;
	ccnum = "" + ccnum;
	
	var i, sum, weight;
	sum=0;
	for (i = 0; i < ccnum.length - 1; i++) {
		weight = ccnum.substr(ccnum.length - (i + 2), 1) * (2 - (i % 2));
		sum += ((weight < 10) ? weight : (weight - 9));
	}

	if (parseInt(ccnum.substr(ccnum.length-1)) == ((10 - sum % 10) % 10)) {
		return(true);
	} else {
		alert("Card Number Fails Luhn Test\n(You did not enter a valid card number. Please check that you have entered it correctly)");
		return(false);
	}

	return(true);
}

function checkLUN() {
    return checkLUHN();
}

function checkDate() {
	var ccmonth = parseFloat(document.ccform.pas_ccmonth[document.ccform.pas_ccmonth.selectedIndex].value);
	var ccyear = parseFloat(document.ccform.pas_ccyear[document.ccform.pas_ccyear.selectedIndex].value);

	if ((ccmonth == "13") || (ccyear == "1")){
		alert("Please enter a valid expiry date for you credit card!");
		return(false);
	
	} else {

		var presentDate = new Date();
		var year = presentDate.getYear();
		if (year < 1900) year += 1900;
		var month = presentDate.getMonth() + 1;

		today = (year * 100) + month;
		ccexp = ((2000 + ccyear) * 100) + ccmonth;

		if (ccexp >= today) {
			return(true);
		} else {
			alert("Credit Card has Expired");
			return(false);
		}
	}

	return(true);
}

function opentrans(i, j) {
			obj = eval("document.all."+i+".style");
			obj2 = eval("document.all."+j+".style");
			if (obj.display == 'none') {
				obj.display = '';
			} else {
				obj.display = 'none';
			}
			if (obj2.display == 'none') {
				obj2.display = '';
			} else {
				obj2.display = 'none';
			}
}


function autoYear() {
  	var time = new Date();
  	var year = time.getFullYear();

  	var future = year + 12; 
	objAccountDD = document.getElementById('pas_ccyear'); 
  	numItems = objAccountDD.length;

   	for(i=0; i<numItems; i++) {
   	    objAccountDD.remove(0);
   	}
   	i=0;
 	
	objAccountDD[i++] = new Option("", "1");
  	do {  //ya ya i know, this will be a year 2100 bug!! 
		objAccountDD[i++] = new Option(year, year.toString().substring(2,4));
		year++;
  	}
  	while (year < future)
}


function checkLaserCard() {
	var span_id = "optional_cvn";
	var cctype = document.ccform.pas_cctype[document.ccform.pas_cctype.selectedIndex].value;

	if (cctype == "LASER") {
		// Show the label
		document.getElementById(span_id).style.display = 'block';	
	}
	else {
		// Hide the label
		document.getElementById(span_id).style.display = 'none';
	}
}

 function showCVN() {
    pageurl = base_url + "payment/cvn";
    window.open(pageurl, "CVN", "resizable=1,menubar=no,location=no,directories=no,scrollbars=no,status=no,height=500,width=400");
}

function checkTerms() {
	if (document.ccform.terms.checked == false) {
		alert("You must tick 'I agree to the Terms & Conditions' to continue");
		return(false);
	}

	return(true);
}