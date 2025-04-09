<?php
/**
 * The 'tree' that holds all the sections, rows, and links for the AdminLinks
 * page
 */
class ALTree {
	/** @var ALSection[] */
	public $sections;

	public function __construct() {
		$this->sections = [];
	}

	/**
	 * @param string $section_header
	 * @return ALSection|null
	 */
	public function getSection( $section_header ) {
		foreach ( $this->sections as $cur_section ) {
			if ( $cur_section->header === $section_header ) {
				return $cur_section;
			}
		}
		return null;
	}

	/**
	 * @param ALSection $section
	 * @param string|null $next_section_header
	 */
	public function addSection( $section, $next_section_header = null ) {
		if ( $next_section_header == null ) {
			$this->sections[] = $section;
			return;
		}
		foreach ( $this->sections as $i => $cur_section ) {
			if ( $cur_section->header === $next_section_header ) {
				array_splice( $this->sections, $i, 0, [ $section ] );
				return;
			}
		}
		$this->sections[] = $section;
	}

	/**
	 * @return string
	 */
	public function toString() {
		$text = "";
		foreach ( $this->sections as $section ) {
			$text .= $section->toString();
		}
		return $text;
	}
}
