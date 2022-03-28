<?php 
	function debug_to_console($data) {
	    $output = $data;
	    if (is_array($output))
	        $output = implode(',', $output);
	    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
	}
?>
<?php
use PHPMailer\PHPMailer\PHPMailer;
debug_to_console("Starting");
if(isset($_POST['name']) && isset($_POST['email'])) {
    debug_to_console("I'm IN !!!");
    require 'PHPMailer/PHPMailer.php';
    require 'PHPMailer/SMTP.php';
    require 'PHPMailer/Exception.php';
    
    $mail = new PHPMailer(true);
    // SMTP Settings
    $mail->isSMTP();
    $mail->CharSet = "UTF-8";
    $mail->SMTPAuth = true;
	$mail->SMTPSecure = 'tls';
	$mail->SMTPAutoTLS = false;
    $mail->SMTPDebug = 4;
    // $mail->Debugoutput = function ($str, $level) {
    //     $GLOBALS['status'][] = $str;
    // };

    // smtp settings
	//echo !extension_loaded('openssl')?"Not Available":"Available";
	$mail->SMTPKeepAlive = true;
	$mail->Mailer = “smtp”; // don't change the quotes!!!!
	$mail->Host = "ssl://smtp.gmail.com"; // SMTP server
    $mail->Username = 'shopifyandorossmtp@gmail.com'; // email login
    $mail->Password = '3p7Gkwh#hD'; // email pass
    $mail->SMTPSecure = 'ssl';
    // $mail->Port = 587;
    $mail->Port = 465;
/*     print_r($mail); */
    $mail->setFrom('shopifyandorossmtp@gmail.com', 'LawFirm - Andoros');
    
    // attachment files
    // if (!empty($file['name'][0])) {
    //     for ($ct = 0; $ct < count($file['tmp_name']); $ct++) {
    //         $uploadfile = tempnam(sys_get_temp_dir(), sha1($file['name'][$ct]));
    //         $filename = $file['name'][$ct];
    //         if (move_uploaded_file($file['tmp_name'][$ct], $uploadfile)) {
    //             $mail->addAttachment($uploadfile, $filename);
    //             $rfile[] = "File $filename attachmented";
    //         } else {
    //             $rfile[] = "Error attachment $filename";
    //         }
    //     }
    // }

    // send message
    $messageus = file_get_contents("receive_email.html");
    $messageuser = file_get_contents("email.html");
    $messageus = str_replace(['{{companyname}}', '{{name}}', '{{furikana}}', '{{email}}', '{{phone}}', '{{message}}'], [$_POST['companyname'], $_POST['name'], $_POST['furikana'], $_POST['email'], $_POST['phone'], $_POST['message']], $messageus);
    $messageuser = str_replace(['{{companyname}}', '{{name}}', '{{furikana}}', '{{email}}', '{{phone}}', '{{message}}'], [$_POST['companyname'], $_POST['name'], $_POST['furikana'], $_POST['email'], $_POST['phone'], $_POST['message']], $messageuser);
    // print_r($messageuser);
    $mail->addAddress('create2@h-info.net'); // email receiver
    //$mail->addAddress('shapoval@a2design.biz'); // email receiver
    //$mail->addAddress('luparevich@a2design.biz'); // email receiver
    // $name = $_POST['name'];
    $subject = "【司法書士佐藤淑江事務所】 様からのお問い合わせ";
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $messageus;
    
    // check sending
    if($mail->send()) {
        $status = 'success';
        $response = 'Email is sent to admin';
    } else {
        $status = 'failed';
        $response = 'Something is wrong' . $mail->ErrorInfo;
    }
    
    $mail->clearAllRecipients();
	$mail->ClearAddresses();
    /* print_r($mail); */
    
    $user_email = $_POST['email'];
    /* $user_email = 'create2@h-info.net'; */
    $user_subject = '【司法書士佐藤淑江事務所】- お問い合わせ頂きましてありがとうございました';
    $mail->addAddress($user_email); // email receiver 2
    $mail->isHTML(true);
    $mail->Subject = $user_subject;
    $mail->Body = $messageuser;
    
    // check sending
    if($mail->send()) {
        $status = 'success';
        $response = 'Email is sent to sender';
    } else {
        $status = 'failed';
        $response = 'Something is wrong' . $mail->ErrorInfo;
    }

    exit(json_encode(array("status" => $status, "response" => $response)));
}
?>