<?php
/*
@package: Zipp
@version: 0.1 <2019-06-11>
*/

namespace MediaInt;

use Admin\Interactor;

class AdminInteractor extends Interactor {

	protected function onInit() {

		$l = $this->lang;

		$this->addScripts( ['mediaupload', 'popups', 'singlefile', 'multiplefiles', 'pages', 'main'] );
		$this->addStyle( 'style', 'mgcss' );

		$this->addSection( 'media', $l->mediaSection, -30 );

		/*$this->addPage( 'PagesInt\EditPage' );
		$this->addPageUrl( 'pages/edit/', 'editpage' );
		$this->addPage( 'PagesInt\NewPage' );
		$this->addPageUrl( 'pages/new/', 'newpage' );

		$this->addPage( 'PagesInt\NewPageLang' );
		$this->addPage( 'PagesInt\DelPage' );*/

		$this->addPage( 'Pages\MediaUpload' );
		$this->addPageUrl( 'media/upload/', 'mediaupload' );

		$this->addPage( 'Pages\MediaEdit' );
		$this->addPageUrl( 'media/edit/', 'mediaedit' );

		$this->addPage( 'Pages\MediaSelect' );
		$this->addPageUrl( 'media/select/', 'mediaselect' );

		$this->addPage( 'Pages\DelMedia' );
		$this->addPageUrl( 'media/delmedia/', 'delmedia' );

		$this->addMultiple([
			[ 'Pages\Media', 'media', $l->mediaTitle ]
		]);

		// do the dynamic stuff

		/*$cats = $this->mods->Themes->getPageCats();

		foreach ( $cats as $k => $cat ) {
			$this->addPage( $k, 'PagesInt\PagesDyn' );
			$this->addPageToSection( 'pages', $k, $cat->name );
		}*/

	}

}