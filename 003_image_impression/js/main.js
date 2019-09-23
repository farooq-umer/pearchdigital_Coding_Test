
ajaxCall = (url, data, successCallBack) => {
	
    $.ajax({type:"POST", url: url, data, success: successCallBack, error: failCallBack});
}

successCallBack = (response, status) => {

    console.log("ajax response "+status, response);
    //console.dir("ajax response", response);
}

failCallBack = (response, status) => {

    console.dir("ajax response "+status, response);
}

jQueryCheck = () => {
    window.onload = function() {
        if (window.jQuery) {  
            console.log("Yeah! jQuery is loaded");
        } else {
            console.log("jQuery is NOT loaded");
        }
    }
}
