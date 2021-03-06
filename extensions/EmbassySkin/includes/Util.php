<?php


namespace EmbassySkin;

use OutputPage, Skin;

class Util {

	public static function loadStylesheets(){
		global $egChameleonExternalStyleVariables, $egChameleonExternalStyleModules, $IP;

		$egChameleonExternalStyleVariables = [

			// Add default Bootstrap vars
			// This is nesseccary otherwise we will run into compiling issues
			'gray-600' => '#6c757d',
			'cyan' => '#17a2b8',

			// The Embassy color set
			'te-white' => '#FFFFFF',
			'te-black' => '#000000',
			'te-homepage-background' => '#EDF6F8',
			'te-verylightblue' => '#DFF0F3',
			'te-lightblue' => '#BCD5DA',
			'te-glacier' => '#78B7C2',
			'te-blue' => '#567C83',
			'te-sunfloweryellow' => '#F6D30B',
			'te-sunfloweryellow-dark' => '#D2B409',
			'te-error' =>'#EC3232',
			'te-border-color' => '#C1C1C1',


			 'theme-colors' => '(
					"primary": $primary,
					"secondary": $secondary,
					"success": $success,
					"info": $info,
					"warning": $warning,
					"danger": $danger,
					"light": $light,
					"dark": $dark
			)',

			// Color system
			'primary' => '$te-sunfloweryellow',
			'secondary' => '$te-blue',
			'success' => '$te-glacier',
			'info' => '$cyan', // needs to be defined here
			'warning' => '$te-sunfloweryellow-dark',
			'danger' => '$te-error',
			'light' => '$te-homepage-background',
			'dark' => '$te-black',

			// Body
			//
			// Settings for the `<body>` element.
			'body-bg' => '$te-white',
			'body-color' => '$te-black',

			// Links
			//
			// Style anchor elements.

			'link-color' => '$te-blue',
			'link-hover-color' => '$te-sunfloweryellow-dark',

			// Grid breakpoints
			//
			// Define the minimum dimensions at which your layout will change,
			// adapting to different screen sizes, for use in media queries.

			'grid-breakpoints' => '(
					xs: 0,
					sm: 320px,
					md: 640px,
					lg: 1024px,
					cmln: 1024px, // added for chameleon SMW skin
					xl: 1200px
			)',

			// Grid containers
			//
			// Define the maximum width of `.container` for different screen sizes.

			'container-max-widths' => '(
					sm: 100%,
					md: 100%,
					lg: 960px,
					xl: 1180px
			)',


			// Grid columns
			//
			// Set the number of columns and specify the width of the gutters.

			'grid-gutter-width' => '60px',

			// Spacing
			//
			// Control the default styling of most Bootstrap elements by modifying these
			// variables. Mostly focused on spacing.
			// You can add more entries to the $spacers map, should you need more variation.

			'spacer' => '60px',
			//'spacers' => '()',
			// stylelint-disable-next-line scss/dollar-variable-default
			'spacers' => 'map-merge(
						(
									0: 0,
									1: ($spacer * .25),
									2: ($spacer * .5),
									3: $spacer,
									4: ($spacer * 1.5),
									5: ($spacer * 3)
						),
						()
			);',

			// Typography
			//
			
			//'font-family-base' => '$te-font-regular, $font-family-sans-serif',  // Dublicate
			//'headings-font-family' => '$te-font-extrabold, $font-family-sans-serif', /Dublicate
			//'font-family-base' => '$font-family-base', // Dublicate

			'font-family-base' => '$te-font-regular, $font-family-sans-serif',

			//'font-size-base' => '18',       ///// CAUSES Compiling issues
			'font-size-lg' => '1rem',
			'font-size-sm' => '1rem',
			
			'font-weight-lighter' => 'lighter',
			'font-weight-light' => '300',
			'font-weight-normal' => '600',
			'font-weight-bold' => '600',
			'font-weight-bolder' => 'bolder',
			
			'font-weight-base' => '$font-weight-normal',
			'line-height-base' => '1.5',
			
			'h1-font-size' => '64',
			'h2-font-size' => '28',
			'h3-font-size' => '26',
			'h4-font-size' => '20',
			'h5-font-size' => '19',
			'h6-font-size' => '$font-size-base',
			
			'headings-margin-bottom' => '$spacer / 2',
			'headings-font-family' => '$te-font-extrabold, $font-family-sans-serif',
			'headings-font-weight' => '600',
			'headings-line-height' => '1',
			'headings-color' => 'null',
			
			'display1-size' => '6rem',
			'display2-size' => '5.5rem',
			'display3-size' => '4.5rem',
			'display4-size' => '3.5rem',
			
			'display1-weight' => '300',
			'display2-weight' => '300',
			'display3-weight' => '300',
			'display4-weight' => '300',
			'display-line-height' => '$headings-line-height',
			
			'lead-font-size' => '$font-size-base * 1.25',
			'lead-font-weight' => '300',
			
			'small-font-size' => '80%',
			
			'text-muted' => '$gray-600',
			
			'blockquote-small-color' => '$gray-600',
			'blockquote-small-font-size' => '$small-font-size',
			'blockquote-font-size' => '$font-size-base * 1.25',
			
			'hr-border-color' => 'rgba($black, .1)',
			'hr-border-width' => '$border-width',
			
			'mark-padding' => '.2em',
			
			'dt-font-weight' => '$font-weight-bold',
			
			'kbd-box-shadow' => 'inset 0 -.1rem 0 rgba($black, .25)',
			'nested-kbd-font-weight' => '$font-weight-bold',
			
			'list-inline-padding' => '.5rem',
			
			'mark-bg' => '#fcf8e3',
			
			'hr-margin-y' => '$spacer',

			// Paragraphs
			//
			// Style p element.

			'paragraph-margin-bottom' => 'rem-calc(60)',

			// NOT FROM BOOTSTRAP
			// Custom paragraph bottom margins
			'paragraph-bottom-margins' => '(
					sm: $paragraph-margin-bottom/2,
					md: $paragraph-margin-bottom/2,
					lg: $paragraph-margin-bottom,
					xl: $paragraph-margin-bottom
			)',

			// Z-index master list
			//
			// Warning: Avoid customizing these values. They're used for a bird's eye view
			// of components dependent on the z-axis and are designed to all work together.

			'zindex-dropdown' => '1000',
			'zindex-sticky' => '1020',
			'zindex-fixed' => '1030',
			'zindex-navigation' => '1035',
			'zindex-modal-backdrop' => '1040',
			'zindex-modal' => '1050',
			'zindex-popover' => '1060',
			'zindex-tooltip' => '1070',

		];

		// Add Stylesheets
		$egChameleonExternalStyleModules = [
			$IP . '/extensions/EmbassySkin/resources/scss/fonts.scss' => 'beforeVariables',
			//$IP . '/extensions/EmbassySkin/resources/momkai/scss/bootstrap-custom.scss' => 'afterVariables',
			$IP . '/extensions/EmbassySkin/resources/scss/main.scss' => 'afterMain',
		];

		// Add custom stylesheet to overrule existing styles
		if ( file_exists( $IP . '/_custom/custom.scss' ) ) {
			$egChameleonExternalStyleModules = [ $IP . '/_custom/custom.scss' => 'afterMain' ];
		}
	}

	public static function getLayout( OutputPage $out, Skin $skin ){
		global $IP, $wgEmbassySkinUseSidebarLayout;

		$layout = $IP . '/extensions/EmbassySkin/resources/layout/main.xml';

		if(!empty($wgEmbassySkinUseSidebarLayout)){
			if((array_key_exists('action', $_GET) && $_GET['action'] === 'formedit') || in_array($out->getTitle()->getNamespace(), $wgEmbassySkinUseSidebarLayout, true)
				|| in_array($out->getTitle()->getFullText(), $wgEmbassySkinUseSidebarLayout, true )
				|| self::isArrayKeyPartOfTitle($wgEmbassySkinUseSidebarLayout, $out->getTitle()->getFullText())){

				$layout = $IP . '/extensions/EmbassySkin/resources/layout/sidebar.xml';
			}
		};

		return $layout;
	}

	private static function isArrayKeyPartOfTitle($arr, $string) {
		foreach ($arr as $item) {
			if(gettype($item) === "string" && strpos($string, $item) !== false){
				return true;
			}
		}
		return false;
	}
}