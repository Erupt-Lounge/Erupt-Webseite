<?php
/*
@package: Zipp
@version: 0.1 <2019-05-28>
*/

namespace PagesInt\Pages;

use Ajax\Request as AjaxRequest;
use Admin\{Page, DataRequest};
use Fields\Fields\DropDown;
use \Error;

class NewPageLang extends Page {

	protected $nonceKey = 'newpagelang';

	public function onData( DataRequest $req ) {

		$id = (int) ( $req->parts[2] ?? -1 );

		if ( $id <= 0 )
			return 'Id is incorrect';

		$p = $this->mods->Pages->getById( $id );
		if ( !$p )
			return 'Id is incorrect';

		$d = (object) [];

		$fields = [];
		foreach ( $this->fields( array_keys( $p->langs ) ) as $f )
			$fields[] = $f->export( $d );

		return [
			'fields' => $fields,
			'nonce' => $this->nonce(),
			'pageId' => $p->id
		];

	}

	protected function fields( array $langs ) {

		$niceLangs = $this->mods->SiteInt->niceLangs;
		$l = $this->lang;

		$nLangs = [];
		$cLangs = [
			'nulll' => $l->dontCopy
		];

		foreach ( $niceLangs as $k => $n ) {
			if ( in_array( $k, $langs ) )
				$cLangs[$k] = $n;
			else
				$nLangs[$k] = $n;
		}

		return [
			new DropDown( 'newLang', [
				'name' => $l->langField,
				'options' => $nLangs
			] ),
			new DropDown( 'copyFrom', [
				'name' => $l->copyFromField,
				'options' => $cLangs
			] )
		];

	}

	public function onAjax( AjaxRequest $req ) {

		if ( !$this->checkNonce( $req ) )
			return;

		$data = $req->data;

		$id = (int) ( $data->pageId ?? -1 );

		if ( $id <= 0 )
			throw new Error( 'Id is incorrect' );

		$nLang = $data->copyFrom ?? null;
		if ( !is_string( $nLang ) || $nLang === 'nulll' )
			$nLang = null;

		$p = $this->mods->Pages->getById( $id, $nLang );
		if ( !$p )
			throw new Error( 'Id is incorrect' );

		$d = [];
		foreach ( $this->fields( array_keys( $p->langs ) ) as $f ) {

			if ( !$f->validate( $data ) )
				$req->formError( $l->fieldError, $f->name );

			$d[$f->slug] = $f->out( $data );

		}
		$d = (object) $d;

		// those two values are validated
		$newLang = $d->newLang;
		$copyFrom = $d->copyFrom;

		// var_dump( $newLang );


		/*$page = [
			'title' => '',
			'url' => null,
			'ctn' => '',
			'state' => 1,
			'publish' => now(),
			'keywords' => ''
		];*/

		// maybe should add this as events
		$this->mods->Themes->contentChanged( 'pagenewlang', $d );

		if ( $copyFrom === 'nulll' )
			$this->mods->Pages->newPageLang( $id, $newLang );
		else
			$this->mods->Pages->newPageLangCopy( $p, $newLang );

		$req->ok( $newLang );

		/*$lang = (string) ( $d->newpagelang ?? '' );

		$l = $this->lang;

		if ( !cLen( $lang, 2, 5 ) || $id <= 0 )
			return $req->formError( $l->newPageLangInputError, $this->newNonce() );

		$p = $this->mods->Pages;

		if ( !$p->idExists( $id ) || $p->langExists( $id, $lang ) )
			return $req->formError( $l->newPageLangExists, $this->newNonce() );

		$p->newPageLang( $id, $lang );*/




		// $req->formError( $l->errorNewPage, $this->newNonce() );

		/*$fields = $this->fields();
		$l = $this->lang;

		$d = [];

		foreach ( $fields as $f ) {

			if ( !$f->validate( $req->data ) )
				return $req->formError( sprintf( $l->newPageError, $f->name ), $this->newNonce() );

			$d[$f->slug] = $f->out( $req->data );

		}

		$d = (object) $d;

		$uId = $this->mods->Users->id;
		$p = $this->mods->Pages->newPage( $d->layout, $uId, $d->lang );

		if ( $p )
			return $req->ok( $this->admin->bUrl( sprintf( 'pages/edit/%d/', $p ) ) );

		$req->formError( $l->errorNewPage, $this->newNonce() );*/

	}

}