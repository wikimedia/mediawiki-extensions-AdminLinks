<?php
/**
 * The 'tree' that holds all the sections, rows, and links for the AdminLinks
 * page
 */
class ALTree {
	/** @var ALSection[] */
	public array $sections;

	public function __construct() {
		$this->sections = [];
	}

	public function getSection( string $section_header ): ?ALSection {
		foreach ( $this->sections as $cur_section ) {
			if ( $cur_section->header === $section_header ) {
				return $cur_section;
			}
		}
		return null;
	}

	public function addSection( ALSection $section, ?string $next_section_header = null ): void {
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

	public function toString(): string {
		$text = "";
		foreach ( $this->sections as $section ) {
			$text .= $section->toString();
		}
		return $text;
	}
}
