<?php

use Premmerce\Wishlist\Frontend\WishlistFunctions;

if ( ! function_exists( 'premmerce_wishlist' ) ) {

    function premmerce_wishlist() {

        return WishlistFunctions::getInstance();

    }

}

add_action( 'wp_ajax_send_email', 'callback_send_email' );
add_action( 'wp_ajax_nopriv_send_email', 'callback_send_email' );

function callback_send_email() {

    // project board slug
    $project_board = 'project-board';
    $website_url = get_site_url();
    $parseLink = $website_url.'/'.$project_board.'/?key=';

    $name = $_REQUEST['name'];
	$email = $_REQUEST['email'];
    $company_name = $_REQUEST['company_name'];
    $phone = $_REQUEST['phone'];
    $message = $_REQUEST['message'];
    $pricing = $_REQUEST['pricing'];
    $project_board_urls = $_REQUEST['project_board_urls'];
    $project_board_names = $_REQUEST['project_board_names'];
      
    $s = explode(",", $project_board_names);
    $v = explode(",", $project_board_urls);
	
	$email_arr = explode(",", $email);
	
    $current_user = wp_get_current_user();
    $fname = $current_user->user_firstname ? $current_user->user_firstname : "someone";
    $lname = $current_user->user_lastname ? $current_user->user_lastname : "";

    for ($x = 0; $x < count($s); $x++) {
       $w .= '<a href="'.$parseLink.$v[$x].'&pricing='.$pricing.'&code=letmein">'.$s[$x].'</a><br />';
    }

    $subject = "Design Ideas For Your Project";
	$email_body = ucfirst($fname)." ".$lname." shared multiple design boards with product ideas for your upcoming project.<br>".
      "Click <a href='".$parseLink.$project_board_urls."&show=all&pricing=".$pricing."&code=letmein'>here</a> to review these ideas and request physical samples.<br><br>".
      "To see an individual design boards, click one of the links below.<br>".
      "$w<br><br>".
      "<b>Note:</b> $message".
      "<br><br>".
      "---".
      "<br><b>$name</b><br>".
      "$company_name<br>".
      "$phone";
        
      $headers = "Content-type:text/html;charset=UTF-8";
      foreach($email_arr as $single_email) {
          $mail = wp_mail($single_email, $subject, $email_body, $headers);
      if($mail) {
          echo "Email Sent Successfully";
        }
    }
}