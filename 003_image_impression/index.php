<!DOCTYPE html>
<html>
	<head>
        <link rel="stylesheet" href="css/styles.css" />
		<title>PearchDigital</title>
	</head>
	<body>
		<div class="gallery">
			<h2>Image Gallery</h2>
			<p>Please open <code><b><u>Console</u></b></code> to see the AjaxCall responses.</p>
			<img class="img-size" src="images/img-1.jpg" id="im-img-1" onclick="detectImg(['trackEvent:onclick_Image_Impression', 'imageId:im_img_1', 'client:Google', 'adName:Google_Cloud_Storage'])">
			<img class="img-size" src="images/img-2.jpg" id="im-img-2" onclick="detectImg(['trackEvent:onclick_Image_Impression', 'imageId:im_img_2', 'client:Amazone', 'adName:Amazone_Web_Services'])">
			<img class="img-size" src="images/img-3.jpg" id="im-img-3" onclick="detectImg(['trackEvent:onclick_Image_Impression', 'imageId:im_img_3', 'client:Facebook', 'adName:Facebook_Ad'])">
			<img class="img-size" src="images/img-4.jpg" id="im-img-4" onclick="detectImg(['trackEvent:onclick_Image_Impression', 'imageId:im_img_4', 'client:MicroSoft', 'adName:Windows_11_Ad'])">
			<img class="img-size" src="images/img-5.jpg" id="im-img-5" onclick="detectImg(['trackEvent:onclick_Image_Impression', 'imageId:im_img_5', 'client:Apple', 'adName:Apple_OS_13'])">
			<img class="img-size" src="images/img-6.jpg" id="im-img-6" onclick="detectImg(['trackEvent:onclick_Image_Impression', 'imageId:im_img_6', 'client:Android', 'adName:Peanut_Butter_Oreos'])">
		</div>

        <script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
        <script type="text/javascript" src="js/main.js"></script>
	</body>
</html>

<script>
jQueryCheck();

const url = 'http://localhost/PearchDigital/003_image_impression/api/logging_page.php';

detectImg = (imgData) => {

	const today = new Date();
	const date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
	const time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
	const dateTime = date+' '+time;
	imgData.push(dateTime);
	
	//console.log(imgData);
	const img_impression = JSON.stringify(imgData);
	
	//console.log({img_impression});
	ajaxCall(url, {img_impression}, successCallBack );
};

</script>