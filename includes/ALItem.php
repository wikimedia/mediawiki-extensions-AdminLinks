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
	public string $text;
	public ?string $label;

	/**
	 * @param string|Title $page_name_or_title
	 * @param string|null $desc
	 * @param array $query
	 * @return self|null
	 */
	public static function newFromPage( $page_name_or_title, ?string $desc = null, array $query = [] ): ?self {
		$item = new self();
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

	public static function newFromSpecialPage( string $page_name ): self {
		$item = new self();
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

	public static function newFromEditLink( string $page_name, string $desc ): self {
		$item = new self();
		$item->label = $page_name;
		$title = Title::makeTitleSafe( NS_MEDIAWIKI, $page_name );
		$edit_link = $title->getFullURL( 'action=edit' );
		$item->text = "<a href=\"$edit_link\">" . htmlspecialchars( $desc ) . '</a>';
		return $item;
	}

	public static function newFromExternalLink( string $url, string $label ): self {
		$item = new self();
		$item->label = $label;
		$item->text = '<a class="external text" rel="nofollow" href="' .
			Sanitizer::encodeAttribute( $url ) . '">' . htmlspecialchars( $label ) . '</a>';
		return $item;
	}
}
