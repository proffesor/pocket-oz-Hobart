<?php
function ValidateEmail($email)
{
   $pattern = '/^([0-9a-z]([-.\w]*[0-9a-z])*@(([0-9a-z])+([-\w]*[0-9a-z])*\.)+[a-z]{2,6})$/i';
   return preg_match($pattern, $email);
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['formid'] == 'form1')
{
   $mailto = 'yourname@mail.com';
   $mailfrom = isset($_POST['email']) ? $_POST['email'] : $mailto;
   $subject = 'Subject Goes Here';
   $message = 'Values submitted from web site form:';
   $success_url = './thanks.html';
   $error_url = '';
   $error = '';
   $eol = "\n";
   $max_filesize = isset($_POST['filesize']) ? $_POST['filesize'] * 1024 : 1024000;
   $boundary = md5(uniqid(time()));

   $header  = 'From: '.$mailfrom.$eol;
   $header .= 'Reply-To: '.$mailfrom.$eol;
   $header .= 'MIME-Version: 1.0'.$eol;
   $header .= 'Content-Type: multipart/mixed; boundary="'.$boundary.'"'.$eol;
   $header .= 'X-Mailer: PHP v'.phpversion().$eol;
   if (!ValidateEmail($mailfrom))
   {
      $error .= "The specified email address is invalid!\n<br>";
   }

   if (!empty($error))
   {
      $errorcode = file_get_contents($error_url);
      $replace = "##error##";
      $errorcode = str_replace($replace, $error, $errorcode);
      echo $errorcode;
      exit;
   }

   $internalfields = array ("submit", "reset", "send", "filesize", "formid", "captcha_code", "recaptcha_challenge_field", "recaptcha_response_field", "g-recaptcha-response");
   $message .= $eol;
   $message .= "IP Address : ";
   $message .= $_SERVER['REMOTE_ADDR'];
   $message .= $eol;
   foreach ($_POST as $key => $value)
   {
      if (!in_array(strtolower($key), $internalfields))
      {
         if (!is_array($value))
         {
            $message .= ucwords(str_replace("_", " ", $key)) . " : " . $value . $eol;
         }
         else
         {
            $message .= ucwords(str_replace("_", " ", $key)) . " : " . implode(",", $value) . $eol;
         }
      }
   }
   $body  = 'This is a multi-part message in MIME format.'.$eol.$eol;
   $body .= '--'.$boundary.$eol;
   $body .= 'Content-Type: text/plain; charset=ISO-8859-1'.$eol;
   $body .= 'Content-Transfer-Encoding: 8bit'.$eol;
   $body .= $eol.stripslashes($message).$eol;
   if (!empty($_FILES))
   {
       foreach ($_FILES as $key => $value)
       {
          if ($_FILES[$key]['error'] == 0 && $_FILES[$key]['size'] <= $max_filesize)
          {
             $body .= '--'.$boundary.$eol;
             $body .= 'Content-Type: '.$_FILES[$key]['type'].'; name='.$_FILES[$key]['name'].$eol;
             $body .= 'Content-Transfer-Encoding: base64'.$eol;
             $body .= 'Content-Disposition: attachment; filename='.$_FILES[$key]['name'].$eol;
             $body .= $eol.chunk_split(base64_encode(file_get_contents($_FILES[$key]['tmp_name']))).$eol;
          }
      }
   }
   $body .= '--'.$boundary.'--'.$eol;
   if ($mailto != '')
   {
      mail($mailto, $subject, $body, $header);
   }
   header('Location: '.$success_url);
   exit;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8">
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Untitled Page</title>
<meta name="generator" content="90 Second Website Builder - http://www.90secondwebsitebuilder.com">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<link href="hobart.css" rel="stylesheet" type="text/css">
<link href="contact.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
function ValidatemyForm(theForm)
{
   var regexp;
   regexp = /^([0-9a-z]([-.\w]*[0-9a-z])*@(([0-9a-z])+([-\w]*[0-9a-z])*\.)+[a-z]{2,6})$/i;
   if (theForm.TextArea2.value.length != 0 && !regexp.test(theForm.TextArea2.value))
   {
      alert("Please enter a valid email address.");
      theForm.TextArea2.focus();
      return false;
   }
   return true;
}
</script>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no;"/>
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
</head>
<body>
<div id="wb_Form1" style="position:absolute;left:14px;top:84px;width:282px;height:376px;z-index:9;">
<form name="myForm" method="post" action="<?php echo basename(__FILE__); ?>" enctype="multipart/form-data" id="Form1" onsubmit="return ValidatemyForm(this)">
<input type="hidden" name="formid" value="form1">
<input type="hidden" name="hidden1" value="this is hidden content">
<div id="wb_Text4" style="position:absolute;left:12px;top:40px;width:42px;height:16px;z-index:0;text-align:left;">
<span style="color:#2F4F4F;font-family:Arial;font-size:13px;">Name</span></div>
<textarea name="TextArea1" id="TextArea1" style="position:absolute;left:59px;top:40px;width:198px;height:20px;z-index:1;" rows="0" cols="30"></textarea>
<div id="wb_Text5" style="position:absolute;left:11px;top:83px;width:42px;height:16px;z-index:2;text-align:left;">
<span style="color:#2F4F4F;font-family:Arial;font-size:13px;">E-Mail</span></div>
<textarea name="TextArea2" id="TextArea2" style="position:absolute;left:60px;top:80px;width:198px;height:20px;z-index:3;" rows="0" cols="30"></textarea>
<input type="submit" id="Button1" name="" value="Submit" style="position:absolute;left:169px;top:333px;width:96px;height:25px;z-index:4;">
<textarea name="phone" id="TextArea3" style="position:absolute;left:60px;top:119px;width:198px;height:20px;z-index:5;" rows="0" cols="30"></textarea>
<div id="wb_Text6" style="position:absolute;left:11px;top:121px;width:42px;height:16px;z-index:6;text-align:left;">
<span style="color:#2F4F4F;font-family:Arial;font-size:13px;">Phone</span></div>
<textarea name="Comment" id="TextArea4" style="position:absolute;left:25px;top:167px;width:232px;height:138px;z-index:7;" rows="7" cols="35"></textarea>
<div id="wb_Text1" style="position:absolute;left:25px;top:151px;width:66px;height:16px;z-index:8;text-align:left;">
<span style="color:#2F4F4F;font-family:Arial;font-size:13px;">Comment</span></div>
</form>
</div>
<div id="wb_Image2" style="position:absolute;left:0px;top:0px;width:319px;height:62px;z-index:10;">
<img src="images/pocket-strip-330.jpg" id="Image2" alt=""></div>
<div id="wb_Shape2" style="position:absolute;left:0px;top:484px;width:319px;height:41px;z-index:11;">
<img src="images/img0017.png" id="Shape2" alt="" style="width:319px;height:41px;"></div>
<div id="wb_Image11" style="position:absolute;left:244px;top:484px;width:50px;height:41px;z-index:12;">
<a href="./About_Us.html"><img src="images/Info_LightBlue.png" id="Image11" alt=""></a></div>
<div id="wb_Image12" style="position:absolute;left:17px;top:484px;width:50px;height:41px;z-index:13;">
<a href="./index.html"><img src="images/House_LightBlue.png" id="Image12" alt=""></a></div>
<div id="wb_Image14" style="position:absolute;left:162px;top:484px;width:50px;height:41px;z-index:14;">
<a href="./contact.php"><img src="images/Envelope_LightBlue.png" id="Image14" alt=""></a></div>
<div id="wb_Image15" style="position:absolute;left:86px;top:484px;width:50px;height:41px;z-index:15;">
<a href="tel:1300753517"><img src="images/Phone_LightBlue.png" id="Image15" alt=""></a></div>
</body>
</html>