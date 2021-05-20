<?php
/*
@package: Zipp
@version: 0.2 <2019-07-08>
*/

namespace Erupt;

use Themes\Theme;
 use Fields\Validator;

class Erupt extends Theme {

	protected $dependencies = [ 'fields', 'mediaint', 'pagesint' ];

	public function onInit() {

		$this->loadConfig();

	}

	public function handleContact() {

		$post = $this->activeRequest->post;

		$form = [];
		$fields = [
			'firstname', 'lastname', 'email', 'message', 'g-recaptcha-response'
		];

		$success = true;

		foreach ( $fields as $f ) {
			$form[$f] = (string) ( $post->$f ?? '' );

			if ( !Validator::str( $form[$f], true ) )
				$success = false;
		}

		$form = (object) $form;

		if ( !Validator::email( $form->email, true ) )
			$success = false;

		// recaptcha
		$recaptchaSk = $this->activeData->ctn->recaptchaSecretKey;
		$res = file_get_contents(sprintf(
			'https://www.google.com/recaptcha/api/siteverify?secret=%s&response=%s',
			urlencode($recaptchaSk),
			urlencode($form->{'g-recaptcha-response'})
		));
		$resJson = json_decode($res, true);
		if (!( $resJson && $resJson['success'] ?? false ))
			$success = false;

		// finish form
		$form->success = $success;
		$form->submitted = isset($post->submit);

		if ( !$success )
			return $form;

		// send mail
		$ctn = $this->activeData->ctn;

		$host = $this->activeRequest->host;

		$header  = "MIME-Version: 1.0\r\n";
		$header .= "Content-type: text/html; charset=utf-8\r\n";
		$header .= sprintf( "From: mailer@%s\r\n", $host );
		$header .= sprintf( "Reply-To: %s\r\n", $form->email );

		//$str = $ctn->confMessage;
		$str = '<b>Kontaktformular</b><br><br>';
		$str .= mailField( $ctn->lastname, $form->lastname );
		$str .= mailField( $ctn->firstname, $form->firstname );
		$str .= mailField( $ctn->email, $form->email );
		$str .= '<br><b>'. e( $ctn->message ). '</b><br>';
		$str .= str_replace( "\n", '<br>', e( $form->message ) );

		mail( $ctn->mailTo, 'Kontaktformular', $str, $header );

		return $form;
	}

}