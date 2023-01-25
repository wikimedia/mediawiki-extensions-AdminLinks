<?php

use MediaWiki\MediaWikiServices;

/**
 * A single row of the AdminLinks page, with a name (not displayed, used only
 * for organizing the rows), and a set of "items" (links)
 */
class ALRow {
	public $name;
	public $items;

	function __construct( $name ) {
		$this->name = $name;
		$this->items = [];
	}

	function addItem( $item, $next_item_label = null ) {
		if ( $next_item_label == null ) {
			$this->items[] = $item;
			return;
		}
		foreach ( $this->items as $i => $cur_item ) {
			if ( $cur_item->label === $next_item_label ) {
				array_splice( $this->items, $i, 0, [ $item ] );
				return;
			}
		}
		$this->items[] = $item;
	}

	function toString() {
		$config = MediaWikiServices::getInstance()->getConfigFactory()->makeConfig( 'AdminLinks' );

		$text = "	<p>\n";
		foreach ( $this->items as $i => $item ) {
			if ( $i > 0 ) {
				$text .= ' ' . htmlspecialchars( $config->get( 'AdminLinksDelimiter' ) ) . "\n";
			}
			$text .= '		' . $item->text;
		}
		return $text . "\n	</p>\n";
	}
}
