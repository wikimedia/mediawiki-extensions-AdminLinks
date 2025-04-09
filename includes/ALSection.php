<?php
/**
 * A single section of the Admin Links 'tree', composed of a header and rows
 */
class ALSection {
	/** @var string */
	public $header;
	/** @var ALRow[] */
	public $rows;

	/**
	 * @param string $header
	 */
	public function __construct( $header ) {
		$this->header = $header;
		$this->rows = [];
	}

	/**
	 * @param string $row_name
	 * @return ALRow|null
	 */
	public function getRow( $row_name ) {
		foreach ( $this->rows as $cur_row ) {
			if ( $cur_row->name === $row_name ) {
				return $cur_row;
			}
		}
		return null;
	}

	/**
	 * @param ALRow $row
	 * @param string|null $next_row_name
	 */
	public function addRow( $row, $next_row_name = null ) {
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

	/**
	 * @return string
	 */
	public function toString() {
		$text = '	<h2 class="mw-specialpagesgroup">' .
			htmlspecialchars( $this->header ) . "</h2>\n";
		foreach ( $this->rows as $row ) {
			$text .= $row->toString();
		}
		return $text;
	}
}
