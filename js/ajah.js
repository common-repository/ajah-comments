var ajahCommentsStart, ajahCommentsCommentsPerPage, ajahCommentsBaseUrl, ajahCommentsTarget, ajahCommentsTotal;

function ajahCommentsInit(start, perPage, baseUrl, target, total) {
	ajahCommentsStart = start;
	ajahCommentsCommentsPerPage = perPage;
	ajahCommentsBaseUrl = baseUrl;
	ajahCommentsTarget = target;
	ajahCommentsTotal = total;
	document.getElementById('ajah-comments-next').style.visibility = 'hidden';
}

function ajahGetCommentsUrl(start, length) {
	return ajahCommentsBaseUrl + "&ajah_start=" + ajahCommentsStart + "&ajah_length=" + ajahCommentsCommentsPerPage;
}

function ajahPreviousComments() {
	//alert("ajahPreviousComments " + ajahCommentsStart);
	ajahCommentsStart = ajahCommentsStart - ajahCommentsCommentsPerPage;
	if (ajahCommentsStart <= 0) {
		ajahCommentsStart = 0;
		document.getElementById('ajah-comments-previous').style.display = 'none';
	}
	ajahReplace(ajahGetCommentsUrl(ajahCommentsStart, ajahCommentsCommentsPerPage), ajahCommentsTarget);
	document.getElementById('ajah-comments-next').style.visibility = 'visible';
}

function ajahNextComments() {
	//alert("ajahNextComments");
	ajahCommentsStart = ajahCommentsStart + ajahCommentsCommentsPerPage;
	ajahReplace(ajahGetCommentsUrl(ajahCommentsStart, ajahCommentsCommentsPerPage), ajahCommentsTarget);
	//alert(ajahCommentsStart + " from " + ajahCommentsTotal);
	if (ajahCommentsStart + ajahCommentsCommentsPerPage >= ajahCommentsTotal) {
		document.getElementById('ajah-comments-next').style.visibility = 'hidden';
	}
	document.getElementById('ajah-comments-previous').style.display = '';
}

function ajahReplace(url, target) {
  //document.getElementById(target).innerHTML = '<h1>Fetching comments ...</h1>';
  if (window.XMLHttpRequest) {
    req = new XMLHttpRequest();
  } else if (window.ActiveXObject) {
    req = new ActiveXObject("Microsoft.XMLHTTP");
  }
  if (req != undefined) {
    req.onreadystatechange = function() {ajahDone(url, target);};
    req.open("GET", url, true);
    req.send("");
  }
}  

function ajahDone(url, target) {
  if (req.readyState == 4) { // only if req is "loaded"
    if (req.status == 200) { // only if "OK"
      document.getElementById(target).innerHTML = req.responseText;
    } else {
      document.getElementById(target).innerHTML=" AJAH Error:\n"+ req.status + "\n" +req.statusText;
    }
  }
}