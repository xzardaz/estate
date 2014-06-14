function LoadPopup(url) {
 if (window.XMLHttpRequest) {
	try {
		req= new XMLHttpRequest();
	}
	catch (e) {
		req = false;
	}
 }
 else if(window.ActiveXObject) {
	 try {
		req = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch(e)
		{
			try
			{
				req = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e)
			{
				req = false;
			}
		}
 }
 if (req) {
 req.onreadystatechange=processReqChange;
 req.open("GET", url, true);
 req.send(null);
 }

}



function processReqChange()
{	
	var htm;
	if (req.readyState == 4)
	{	
		if (req.status == 200)
		{	
			htm = req.responseText;
			return overlib(htm,FULLHTML,CENTER,ABOVE);
		}
	}
}