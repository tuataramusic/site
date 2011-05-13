/* Author: 

*/

function selectEmule(obj, filed) {
	var htmlStr = $(".layout #"+filed+" .active").html();
	var htmlStr1 = $(".layout #"+filed+" .active").html(obj.innerHTML);
	document.getElementById(filed).value = obj.innerHTML;
	obj.innerHTML = htmlStr;
}


function hiddenBlock(obj_cont) {
	var collection = obj_cont.getElementsByTagName('em');
	if (collection[0].style.visibility == 'visible') {
		collection[0].style.visibility = 'hidden';
	} else {
		collection[0].style.visibility = 'visible';
	}
}



















