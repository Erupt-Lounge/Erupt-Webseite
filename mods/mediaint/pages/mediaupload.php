<?php
/*
@package: Zipp
@version: 0.1 <2019-06-24>
*/

namespace MediaInt\Pages;

use Ajax\Request as AjaxRequest;
use Admin\Page;
use Admin\DataRequest;
use Media\Media;

class MediaUpload extends Page {

	protected $nonceKey = 'mediaupload';

	public function onData( DataRequest $req ) {

		// max upload
		// max file
		// allowed Filetypes

		return [
			'title' => $this->lang->uploadTitle,
			'mainLang' => '',
			'nonce' => $this->nonce(),
			'uploadInfo' => $this->mod->uploadInfoText(),
			'notAllowed' => $this->lang->notAllowed,
			'text' => sprintf( $this->lang->uploadText, $this->mod->maxFileUploads, $this->mod->maxFileSize, $this->mod->maxPostSize ),
			'allowed' => Media::getAllowedExtensions()
		];

	}

	/*public function onAjax( AjaxRequest $req ) {

		// need to prevent Directory traversal
		// check mimetype and extension

		// how do we change nonce here

		var_dump( $req );

		return $req->ok( 'hey' );

		die;

		$f = $req->files->file ?? null;

		if ( !is_array( $f ) || !has( $f['name'] ) || !cLen( $f['name'][0] ) )
			return $req->formError( 'no file uploaded' );

		$fs = [];

		foreach ( $f['size'] as $k => $s ) {

			if ( $s <= 0 && $f['error'][$k] > 0 )
				continue;

			$ext = $this->parseForExt( $f['name'][$k] );

			if ( !Media::checkExt( $ext, mime_content_type( $f['tmp_name'][$k] ) ) )
				return $req->formError( sprintf( 'extension %s not allowed', $ext ) );

			$fs[] = (object) [
				'name' => $this->sanitizeName( $f['name'][$k] ),
				'type' => $ext,
				'tmp' => $f['tmp_name'][$k],
				'size' => $s
			];

		}

		if ( !has( $fs ) )
			return $req->formError( 'there was an error, maybe you uploaded an empty file' );

		foreach ( $fs as $file )
			$this->addFile( $file );

		$req->formOk( count( $fs ), $this->newNonce() );

	}

	protected function sanitizeName( string $name ) {
		$n = lower( pathinfo( $name, PATHINFO_FILENAME ) );
		$n = preg_replace( '/[;?:@=&"\'<>#%{}|\\^~\/\[\]`\s]/', '-', $n );
		return substr( $n, 0, 26 ); // 26 because (30 max) - 4 > (_100 from random)
	}

	protected function parseForExt( string $name ) {
		// type is not used at the moment, is it safe to use cross plattform??
		return lower( pathinfo( $name, PATHINFO_EXTENSION ) );
	}

	protected function addFile( object $file ) {

		// the file is valid to upload and where now able to move it and to the right folder
		$path = $this->mods->Media->getPath();

		if ( !is_dir( $path ) )
			mkdir( $path );

		$rnd = '';
		$c = 0;
		$nP = sprintf( '%s%s.%s', $path, $file->name, $file->type );

		// i dont like this parts :/

		while( is_file( $nP ) ) {

			$c++;
			if ( $c > 10 )
				throw new Error( 'could not find a file match please upload a file with another name' );

			$rnd = randomToken( 3 );
			$nP = sprintf( '%s%s_%s.%s', $path, $file->name, $rnd, $file->type );

		}

		if ( cLen( $rnd ) )
			$file->name .= '_'. $rnd;

		move_uploaded_file( $file->tmp, $nP );

		$this->mods->Media->new( $file->name, $file->type, $file->size );

	}*/

}