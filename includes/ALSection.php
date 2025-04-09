<?php
/**
 * A single section of the Admin Links 'tree', composed of a header and rows
 */
class ALSection {
	public string $header;
	/** @var ALRow[] */
	public array $rows;

	public function __construct( string $header ) {
		$this->header = $header;
		$this->rows = [];
	}

	public function getRow( string $row_name ): ?ALRow {
		foreach ( $this->rows as $cur_row ) {
			if ( $cur_row->name === $row_name ) {
				return $cur_row;
			}
		}
		return null;
	}

	public function addRow( ALRow $row, ?string $next_row_name = null ): void {
		if ( $next_row_name == null ) {
			$this->rows[] = $row;
			return;
		}
		foreach ( $this->rows as $i => $cur_row ) {
			if ( $cur_row->name === $next_row_name ) {
				array_splice( $this->rows, $i, 0, [ $row ] );
				return;
			}
		}
		$this->rows[] = $row;
	}

	public function toString(): string {
		$text = '	<h2 class="mw-specialpagesgroup">' .
			htmlspecialchars( $this->header ) . "</h2>\n";
		foreach ( $this->rows as $row ) {
			$text .= $row->toString();
		}
		return $text;
	}
}
