<?php
/*
@package: Zipp
@version: 1.0 <2019-05-28>
*/

namespace Admin\Pages;

use Admin\{Page, DataRequest};
use Router\Interactor;
use Router\Request;
use Ajax\Request as AjaxRequest;

class Home extends Page {

	protected $section = 'home';

	protected $slug = 'home';

	protected $template = 'home';

	/*public function onRequest( Request $req ) {

		$this->loadHeader();


		$this->loadTempl( 'home' );
		// echo 'welcome to the home screen :)'.EOL;

		$this->loadFooter();

	}*/

	public function onData( DataRequest $req ) {

		if ( $req->uri !== '/' && $req->uri !== 'home/' )
			return $this->lang->pageNotFound;
		// error 404

		return [
			'title' => $this->lang->homeTitle
		];

	}

	/*public function onAjax( AjaxRequest $req ) {

		$this->nonceKey = 'login';

		if ( !$this->checkNonce( $req ) )
			return;

		$d = $req->data;

		$username = $d['username'] ?? '';
		$password = $d['password'] ?? '';

		$res = $this->mods->Users->login( $username, $password );

		if ( $res )
			$req->ok( true );
		else
			$req->formError( $this->lang->credsError, $this->newNonce() );

	}*/

	// onCLI

}