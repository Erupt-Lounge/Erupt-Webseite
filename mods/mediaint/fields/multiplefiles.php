<?php
/*
@package: Zipp
@version: 0.1 <2019-06-12>
*/

namespace MediaInt\Fields;

use \Error;
use Media\Media;

class MultipleFiles extends SingleFile {

	public $type = 'multiplefiles';

	// maybe should add a max?
	/*protected function parseCfg( object $cfg ) {
		$this->select = $cfg->selectText ?? 'lang.select';
		$this->notAllowed = $cfg->notAllowed ?? 'lang.notAllowed';
		$this->allowed = $cfg->allowed ?? Media::getAllowedExtensions();
	}*/

	public function exportValue( object $data ) {

		$nData = $this->getValue( $data, [] );
		if ( !is_array( $nData ) )
			$nData = [];

		$nData = array_unique( $nData );
		$media = Media::getInstance();

		$ids = [];
		foreach ( $nData as $d ) {

			if ( is_string( $d ) && cLen( $d, 7 ) ) {

				$ids[] = (int) substr( $d, 7 );

				// $itm = $media->getById( $id );

				// if ( $itm )
					// $viewData[] = $itm->ExportShort();

			}

		}

		if ( !has( $ids ) )
			return [];

		$itms = $media->getByIds( $ids );
		$viewData = [];
		foreach ( $itms as $itm )
			$viewData[] = $itm->exportShort();

		return $viewData;

	}

	/*protected function exportData( object $data ) {
		return [ $this->select, $this->notAllowed, $this->allowed ];
	}*/

	// we need a required setting
	public function validate( object $in ) {

		// maybe should validate if the image is valid
		// the type doenst get validated here, thats not really a security issue

		$d = $this->getValue( $in );
		if ( is_string( $d ) )
			$d = json_decode( $d );

		if ( !is_array( $d ) )
			return false;

		foreach ( $d as $m ) {

			$media = substr( $m, 0, 7 );
			$id = (int) substr( $m, 7 );

			if ( $media !== 'mediaId' || $id <= 0 )
				return false;

		}

		return true;

	}

	public function out( object $data ) {
		$d = $this->getValue( $data );
		if ( is_string( $d ) )
			return json_decode( $d );
		return $d;
	}

	public function view( object $data ) {

		// if received data is lang add lang to getById
		$lang = $data->lang ?? null;

		$d = $this->getValue( $data, [] );

		if ( !is_array( $d ) || !has( $d ) )
			return [];

		$media = Media::getInstance();

		$ids = [];
		foreach ( $d as $id )
			$ids[] = (int) substr( $id, 7 );
		
		return $media->getByIds( $ids, $lang );

	}

}