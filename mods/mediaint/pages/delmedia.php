<?php
/*
@package: Zipp
@version: 0.1 <2019-06-25>
*/

namespace MediaInt\Pages;

use Ajax\Request as AjaxRequest;
use Admin\{Page, DataRequest};
use \Error;

class DelMedia extends Page {

	protected $nonceKey = 'delmedia';

	public function onData( DataRequest $req ) {

		$mediaId = (int) ( $req->parts[2] ?? 0 );

		if ( $mediaId <= 0 )
			return 'Id is incorrect';

		return [
			'nonce' => $this->nonce(),
			'mediaId' => $mediaId
		];

		/*$sInt = $this->mods->SiteInt;
		$lang = $sInt->getCookieLang();

		$itms = [];

		foreach ( $this->mods->Media->getAll( $lang ) as $itm )
			$itms[] = $itm->exportShort();

		return [
			'title' => $l->mediaTitle,
			'items' => $itms,
			'editUrl' => $this->admin->bUrl( 'media/edit' ),
			'langsSelect' => $sInt->langs,
			'baselang' => $lang,
			'multilingual' => $sInt->multilingual
		];*/

	}

	public function onAjax( AjaxRequest $req ) {

		if ( !$this->checkNonce( $req ) )
			return;

		$l = $this->lang;
		$d = $req->data;

		$mediaId = (int) ( $d->id ?? 0 );

		if ( $mediaId <= 0 )
			return $req->error( $l->delError );

		$media = $this->mods->Media;

		$item = $media->getById( $mediaId );

		if ( !$item )
			return $req->error( $l->delError );

		$path = $media->getPath(). $item->getFilename();

		@unlink( $path );
		$media->delete( $mediaId );

		$req->ok( $this->admin->bUrl( 'media/' ) );

		/*try {



			$media->delete( $mediaId );
			$req->ok( $this->admin->bUrl( 'media/' ) );

		} catch ( Error $e ) {

			$req->error( $l->delError );

		}*/

	}

}