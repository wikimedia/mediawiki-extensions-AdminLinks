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

	/**
	 * Adds a section to the tree. If a section with the same header already
	 * exists, merges the rows from the new section into the existing one,
	 * avoiding duplicate items based on their labels.
	 *
	 * @param ALSection $section The section to add
	 * @param string|null $next_section_header The header of the section before which to insert the new section
	 */
	public function addSection( ALSection $section, ?string $next_section_header = null ): void {
		foreach ( $this->sections as $existingSection ) {
			if ( trim( strtolower( $existingSection->header ) ) === trim( strtolower( $section->header ) ) ) {

				foreach ( $section->rows as $newRow ) {
					$existingRow = null;
					foreach ( $existingSection->rows as $row ) {
						if ( $row->name === $newRow->name ) {
							$existingRow = $row;
							break;
						}
					}

					if ( $existingRow ) {
						$new_row = new ALRow( $newRow->name );
						foreach ( $newRow->items as $newItem ) {
							if ( !$this->itemExistsByLabel( $existingSection, $newItem->label ) ) {
								$new_row->addItem( $newItem );
							}
						}
						$existingSection->addRow( $new_row );
					} else {
						$filteredItems = [];
						foreach ( $newRow->items as $newItem ) {
							if ( !$this->itemExistsByLabel( $existingSection, $newItem->label ) ) {
								$filteredItems[] = $newItem;
							}
						}
						if ( count( $filteredItems ) > 0 ) {
							$newRow->items = $filteredItems;
							$existingSection->addRow( $newRow );
						}
					}
				}

				return;
			}
		}

		if ( $next_section_header === null ) {
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
	 * Method to check if an item with the given label exists in the section
	 * @param ALSection $section
	 * @param string $label
	 * @return bool
	 */
	private function itemExistsByLabel( ALSection $section, string $label ): bool {
		foreach ( $section->rows as $row ) {
			foreach ( $row->items as $existingItem ) {
				if ( isset( $existingItem->label ) && $existingItem->label === $label ) {
					return true;
				}
			}
		}
		return false;
	}

	public function toString(): string {
		$text = '';
		foreach ( $this->sections as $section ) {
			$text .= $section->toString();
		}
		return $text;
	}
}
