<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Title\Title;

/**
 * A single 'item' in the AdminLinks page, most likely representing a link
 * but also conceivably containing other text; also contains a label, which
 * is not displayed and is only used for organizational purposes.
 */
class ALItem {
	/** @var string */
	public $text;
	/** @var string|null */
	public $label;

	/**
	 * @param string|Title $page_name_or_title
	 * @param string|null $desc
	 * @param array $query
	 * @return self|null
	 */
	public static function newFromPage( $page_name_or_title, $desc = null, $query = [] ) {
		$item = new ALItem();
		$item->label = $desc;
		if ( $page_name_or_title instanceof Title ) {
			$title = $page_name_or_title;
		} else {
			$title = Title::newFromText( $page_name_or_title );
			if ( !$title ) {
				return null;
			}
		}
		$item->text = MediaWikiServices::getInstance()->getLinkRenderer()
			->makeKnownLink( $title, $desc, [], $query );
		return $item;
	}

	/**
	 * @param string $page_name
	 * @return self
	 */
	public static function newFromSpecialPage( $page_name ) {
		$item = new ALItem();
		$item->label = $page_name;

		$page = MediaWikiServices::getInstance()
			->getSpecialPageFactory()
			->getPage( $page_name );

		if ( $page ) {
			$item->text = MediaWikiServices::getInstance()->getLinkRenderer()
				->makeKnownLink( $page->getPageTitle(), $page->getDescription() );
		}
		return $item;
	}

	/**
	 * @param string $page_name
	 * @param string $desc
	 * @return self
	 */
	public static function newFromEditLink( $page_name, $desc ) {
		$item = new ALItem();
		$item->label = $page_name;
		$title = Title::makeTitleSafe( NS_MEDIAWIKI, $page_name );
		$edit_link = $title->getFullURL( 'action=edit' );
		$item->text = "<a href=\"$edit_link\">" . htmlspecialchars( $desc ) . "</a>";
		return $item;
	}

	/**
	 * @param string $url
	 * @param string $label
	 * @return self
	 */
	public static function newFromExternalLink( $url, $label ) {
		$item = new ALItem();
		$item->label = $label;
		$item->text = "<a class=\"external text\" rel=\"nofollow\" href=\"" .
			Sanitizer::encodeAttribute( $url ) . "\">" . htmlspecialchars( $label ) . "</a>";
		return $item;
	}
}
