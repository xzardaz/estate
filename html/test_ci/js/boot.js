function loadScript(url, callback)
{
    // Adding the script tag to the head as suggested before
    //var head = document.getElementsByTagName('head')[0];
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = url;

    // Then bind the event to the callback function.
    // There are several events for cross browser compatibility.
    //script.onreadystatechange = callback;
	if(typeof callback == "function") script.onload = callback;
	//script.onload=function()
	//{
	//	console.timeStamp("loaded script");
	//};

    // Fire the loading
    document.getElementsByTagName('head')[0].appendChild(script);
};

window.onload=function(){
	loadScript("/test_ci/js/all.php");
	//loadScript("http://code.jquery.com/jquery-1.11.1.js");
	//loadScript("/test_ci/js/all.js");
};

/*
function promisesLoaded()
{
	alert("a");
};
*/

function promiseScript(url)
{

}
