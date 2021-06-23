<?php
/*
@package: Zipp
@version: 0.2 <2019-07-08>
*/

namespace Erupt;

use DateTime;
use Themes\Theme;
use Fields\Validator;

class Erupt extends Theme {

	protected $dependencies = [ 'fields', 'mediaint', 'pagesint' ];

	public function onInit() {

		$this->loadConfig();

	}

    function build_sorter($key, $flip) {
        return function ($inA, $inB) use ($key, $flip) {

            if ($inA->$key instanceof DateTime) {
                $outA = $inA->$key->format("U");
            } else if ($inA == '') {
                $outA = -1;
            } else if (is_string($inA->$key)) {
                $outA = $inA->$key;
            } else if (!is_string($inA->$key) && is_string(strval($inA->$key))) {
                $outA = strval($inA->$key);
            } else {
                $outA = 0;
            }
            if ($inB->$key instanceof DateTime) {
                $outB = $inB->$key->format("U");
            } else if ($inB == '') {
                $outB = -1;
            } else if (is_string($inB->$key)) {
                $outB = $inB->$key;
            } else if (!is_string($inB->$key) && is_string(strval($inB->$key))) {
                $outB = strval($inB->$key);
            } else {
                $outB = 0;
            }

            $result = strnatcmp($outA, $outB);
            $result = $flip ? ($result * -1) : $result;
            return $result;
        };
    }

    public function ctnSort(array &$pages, string $order, string $field) {
        if (!is_array($pages)) $pages = array($pages);
        if ($order == 'random') {
            shuffle($pages);
        } else if ($order == 'ascending') {
            usort($pages, $this->build_sorter($field, true));
        } else if ($order == null || $order == 'descending') {
            usort($pages, $this->build_sorter($field, false));
        }
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
		$str .= '<b>'. e( $ctn->lastname ). ': </b>' . e( $form->lastname ). '<br>';
		$str .= '<b>'. e( $ctn->firstname ). ': </b>' . e( $form->firstname ). '<br>';
		$str .= '<b>'. e( $ctn->email ). ': </b>'. e( $form->email ). '<br><br>';
		$str .= '<br><b>'. e( $ctn->message ). '</b><br>';
		$str .= str_replace( "\n", '<br>', e( $form->message ) );

		mail( $ctn->mailTo, 'Kontaktformular', $str, $header );

		return $form;
	}

}