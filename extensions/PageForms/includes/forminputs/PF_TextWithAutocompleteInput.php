<?php
/**
 * @file
 * @ingroup PF
 */

/**
 * @ingroup PFFormInput
 */
class PFTextWithAutocompleteInput extends PFTextInput {
	public static function getName() {
		return 'text with autocomplete';
	}

	public static function getDefaultPropTypes() {
		return array(
			'_wpg' => array()
		);
	}

	public static function getOtherPropTypesHandled() {
		if ( defined( 'SMWDataItem::TYPE_STRING' ) ) {
			// SMW < 1.9
			return array( '_str' );
		} else {
			return array( '_txt' );
		}
	}

	public static function getDefaultPropTypeLists() {
		return array();
	}

	public static function getOtherPropTypeListsHandled() {
		if ( defined( 'SMWDataItem::TYPE_STRING' ) ) {
			// SMW < 1.9
			return array( '_str' );
		} else {
			return array( '_txt' );
		}
	}

	public static function getDefaultCargoTypes() {
		return array();
	}

	public static function getOtherCargoTypesHandled() {
		return array( 'Page', 'String' );
	}

	public static function getDefaultCargoTypeLists() {
		return array();
	}

	public static function getOtherCargoTypeListsHandled() {
		return array( 'String' );
	}

	public static function getAutocompletionTypeAndSource( &$field_args ) {
		global $wgCapitalLinks;

		if ( array_key_exists( 'values from property', $field_args ) ) {
			$autocompletionSource = $field_args['values from property'];
			$autocompleteFieldType = 'property';
		} elseif ( array_key_exists( 'values from category', $field_args ) ) {
			$autocompleteFieldType = 'category';
			$autocompletionSource = $field_args['values from category'];
		} elseif ( array_key_exists( 'values from concept', $field_args ) ) {
			$autocompleteFieldType = 'concept';
			$autocompletionSource = $field_args['values from concept'];
		} elseif ( array_key_exists( 'values from namespace', $field_args ) ) {
			$autocompleteFieldType = 'namespace';
			$autocompletionSource = $field_args['values from namespace'];
		} elseif ( array_key_exists( 'values from url', $field_args ) ) {
			$autocompleteFieldType = 'external_url';
			$autocompletionSource = $field_args['values from url'];
		} elseif ( array_key_exists( 'values', $field_args ) ) {
			global $wgPageFormsFieldNum;
			$autocompleteFieldType = 'values';
			$autocompletionSource = "values-$wgPageFormsFieldNum";
		} elseif ( array_key_exists( 'autocomplete field type', $field_args ) ) {
			$autocompleteFieldType = $field_args['autocomplete field type'];
			$autocompletionSource = $field_args['autocompletion source'];
		} elseif ( array_key_exists( 'full_cargo_field', $field_args ) ) {
			$autocompletionSource = $field_args['full_cargo_field'];
			$autocompleteFieldType = 'cargo field';
		} elseif ( array_key_exists( 'cargo field', $field_args ) ) {
			$fieldName = $field_args['cargo field'];
			$tableName = $field_args['cargo table'];
			$autocompletionSource = "$tableName|$fieldName";
			$autocompleteFieldType = 'cargo field';
		} elseif ( array_key_exists( 'semantic_property', $field_args ) ) {
			$autocompletionSource = $field_args['semantic_property'];
			$autocompleteFieldType = 'property';
		} else {
			$autocompleteFieldType = null;
			$autocompletionSource = null;
		}

		if ( $wgCapitalLinks && $autocompleteFieldType != 'external_url' ) {
			global $wgContLang;
			$autocompletionSource = $wgContLang->ucfirst( $autocompletionSource );
		}

		return array( $autocompleteFieldType, $autocompletionSource );
	}

	public static function setAutocompleteValues( $field_args ) {
		global $wgPageFormsAutocompleteValues, $wgPageFormsMaxLocalAutocompleteValues;

		// Get all autocomplete-related values, plus delimiter value
		// (it's needed also for the 'uploadable' link, if there is one).
		list( $autocompleteFieldType, $autocompletionSource ) =
			self::getAutocompletionTypeAndSource( $field_args );
		$autocompleteSettings = $autocompletionSource;
		$is_list = ( array_key_exists( 'is_list', $field_args ) && $field_args['is_list'] == true );
		if ( $is_list ) {
			$autocompleteSettings .= ',list';
			if ( array_key_exists( 'delimiter', $field_args ) ) {
				$delimiter = $field_args['delimiter'];
				$autocompleteSettings .= ',' . $delimiter;
			} else {
				$delimiter = ',';
			}
		} else {
			$delimiter = null;
		}

		$remoteDataType = null;
		if ( $autocompleteFieldType == 'external_url' ) {
			// Autocompletion from URL is always done remotely.
			$remoteDataType = $autocompleteFieldType;
		} elseif ( $autocompletionSource !== '' ) {
			// @TODO - that count() check shouldn't be necessary
			if ( array_key_exists( 'possible_values', $field_args ) &&
				count( $field_args['possible_values'] ) > 0
			) {
				$autocompleteValues = $field_args['possible_values'];
			} elseif ( $autocompleteFieldType == 'values' ) {
				$autocompleteValues = explode( ',', $field_args['values'] );
			} else {
				$autocompleteValues = PFValuesUtils::getAutocompleteValues( $autocompletionSource, $autocompleteFieldType );
			}
			if ( count( $autocompleteValues ) > $wgPageFormsMaxLocalAutocompleteValues &&
				$autocompleteFieldType != 'values' && !array_key_exists( 'values dependent on', $field_args ) && !array_key_exists( 'mapping template', $field_args )
			) {
				$remoteDataType = $autocompleteFieldType;
			} else {
				$wgPageFormsAutocompleteValues[$autocompleteSettings] = $autocompleteValues;
			}
		}
		return array( $autocompleteSettings, $remoteDataType, $delimiter );
	}

	public static function getHTML( $cur_value, $input_name, $is_mandatory, $is_disabled, $other_args ) {
		global $wgPageFormsTabIndex, $wgPageFormsFieldNum;

		list( $autocompleteSettings, $remoteDataType, $delimiter ) = self::setAutocompleteValues( $other_args );

		$className = ( $is_mandatory ) ? 'autocompleteInput mandatoryField' : 'autocompleteInput createboxInput';
		if ( array_key_exists( 'unique', $other_args ) ) {
			$className .= ' uniqueField';
		}
		if ( array_key_exists( 'class', $other_args ) ) {
			$className .= ' ' . $other_args['class'];
		}
		$input_id = 'input_' . $wgPageFormsFieldNum;

		if ( array_key_exists( 'size', $other_args ) ) {
			$size = $other_args['size'];
		} elseif ( array_key_exists( 'is_list', $other_args ) && $other_args['is_list'] ) {
			$size = '100';
		} else {
			$size = '35';
		}

		$inputAttrs = array(
			'id' => $input_id,
			'size' => $size,
			'class' => $className,
			'tabindex' => $wgPageFormsTabIndex,
			'autocompletesettings' => $autocompleteSettings,
		);
		if ( array_key_exists( 'origName', $other_args ) ) {
			$inputAttrs['origName'] = $other_args['origName'];
		}
		if ( !is_null( $remoteDataType ) ) {
			$inputAttrs['autocompletedatatype'] = $remoteDataType;
		}
		if ( $is_disabled ) {
			$inputAttrs['disabled'] = true;
		}
		if ( array_key_exists( 'maxlength', $other_args ) ) {
			$inputAttrs['maxlength'] = $other_args['maxlength'];
		}
		if ( array_key_exists( 'placeholder', $other_args ) ) {
			$inputAttrs['placeholder'] = $other_args['placeholder'];
		}

		// The input value passed in to Html::input() cannot be an array.
		if ( is_array( $cur_value ) ) {
			$curValueStr = implode( $delimiter . ' ', $cur_value );
		} else {
			$curValueStr = $cur_value;
		}
		$text = "\n\t" . Html::input( $input_name, $curValueStr, 'text', $inputAttrs ) . "\n";

		if ( array_key_exists( 'uploadable', $other_args ) && $other_args['uploadable'] == true ) {
			if ( array_key_exists( 'default filename', $other_args ) ) {
				$default_filename = $other_args['default filename'];
			} else {
				$default_filename = '';
			}
			$text .= self::uploadableHTML( $input_id, $delimiter, $default_filename, $cur_value, $other_args );
		}

		$spanClass = 'inputSpan';
		if ( $is_mandatory ) {
			$spanClass .= ' mandatoryFieldSpan';
		}
		if ( array_key_exists( 'unique', $other_args ) ) {
			$spanClass .= ' uniqueFieldSpan';
		}
		$text = "\n" . Html::rawElement( 'span', array( 'class' => $spanClass ), $text );

		return $text;
	}

	public static function getAutocompletionParameters() {
		$params = PFEnumInput::getValuesParameters();
		$params[] = array(
			'name' => 'values from url',
			'type' => 'string',
			'description' => wfMessage( 'pf_forminputs_valuesfromurl' )->text()
		);
		$params[] = array(
			'name' => 'list',
			'type' => 'boolean',
			'description' => wfMessage( 'pf_forminputs_list' )->text()
		);
		$params[] = array(
			'name' => 'delimiter',
			'type' => 'string',
			'description' => wfMessage( 'pf_forminputs_delimiter' )->text()
		);
		return $params;
	}

	public static function getParameters() {
		$params = parent::getParameters();
		$params = array_merge( $params, self::getAutocompletionParameters() );
		return $params;
	}

	/**
	 * Returns the HTML code to be included in the output page for this input.
	 * @return string
	 */
	public function getHtmlText() {
		return self::getHTML(
			$this->mCurrentValue,
			$this->mInputName,
			$this->mIsMandatory,
			$this->mIsDisabled,
			$this->mOtherArgs
		);
	}
}
