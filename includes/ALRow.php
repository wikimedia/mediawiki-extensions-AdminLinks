<?php

use MediaWiki\Html\Html;
use MediaWiki\MediaWikiServices;

/**
 * A single row of the AdminLinks page, with a name (not displayed, used only
 * for organizing the rows), and a set of "items" (links)
 */
class ALRow {
	public string $name;
	/** @var ALItem[] */
	public array $items;

	public function __construct( string $name ) {
		$this->name = $name;
		$this->items = [];
	}

	public function addItem( ALItem $item, ?string $next_item_label = null ): void {
		if ( $next_item_label === null ) {
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

	public function toString(): string {
		$config = MediaWikiServices::getInstance()->getConfigFactory()->makeConfig( 'AdminLinks' );

		$content = '';
		foreach ( $this->items as $i => $item ) {
			if ( $item->text === '' ) {
				continue;
			}
			if ( $i > 0 ) {
				$content .= ' ' . htmlspecialchars( $config->get( 'AdminLinksDelimiter' ) ) . "\n";
			}
			$content .= '		' . $item->text;
		}
		$text = Html::rawElement( 'p', [ 'data-row-name' => $this->name ], $content . "\n" );
		return $text . "\n";
	}

}
