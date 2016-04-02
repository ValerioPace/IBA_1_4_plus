<?php

class MailController extends BaseController {

	public function sendMail($view, $toEmail, $toName, $subject, $mailViewData){

		Mail::send($view, $mailViewData, function($message) use ($toEmail, $toName, $subject) {
		
		    $message->to($toEmail, $toName)->subject($subject);
		});

	}

}
