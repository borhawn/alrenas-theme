<?php
/**
 * Groups a product's WooCommerce attributes into the labeled accordion
 * sections used on the single-product template (Dimensions & capacity,
 * Power & connectivity, etc.) -- purely by matching keywords in each
 * attribute's name, so admins manage all of this from Product > Attributes
 * with nothing extra to configure.
 *
 * @package Alrenas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Ordered group definitions: label => keywords matched against the
 * attribute name (case-insensitive, matched anywhere in the string).
 * The last group ("Other") is the catch-all for anything unmatched.
 *
 * @return array<string,string[]>
 */
function alrenas_product_attribute_groups() {
	return array(
		'general'    => array(
			'label'    => esc_html__( 'General information', 'alrenas' ),
			'keywords' => array( 'general', 'model', 'material', 'colour', 'color', 'sku', 'category', 'type' ),
		),
		'dimensions' => array(
			'label'    => esc_html__( 'Dimensions & capacity', 'alrenas' ),
			'keywords' => array( 'dimension', 'size', 'weight', 'capacity', 'height', 'width', 'depth', 'length', 'load' ),
		),
		'technical'  => array(
			'label'    => esc_html__( 'Technical specifications', 'alrenas' ),
			'keywords' => array( 'technical', 'sensor', 'resolution', 'accuracy', 'frequency', 'processor', 'screen', 'display', 'software', 'platform', 'level', 'calibration' ),
		),
		'power'      => array(
			'label'    => esc_html__( 'Power, languages & units', 'alrenas' ),
			'keywords' => array( 'power', 'voltage', 'wattage', 'supply', 'connectivity', 'wireless', 'bluetooth', 'usb', 'language', 'unit' ),
		),
		'warranty'   => array(
			'label'    => esc_html__( 'Standards & warranty', 'alrenas' ),
			'keywords' => array( 'warranty', 'certificat', 'standard', 'compliance', 'iso', 'ce ', 'ukca' ),
		),
	);
}

/**
 * Build the grouped accordion sections for a product's attributes.
 *
 * @param WC_Product $product Product.
 * @return array<int,array{label:string,specs:array<int,array{label:string,value:string}>}> Non-empty groups only, in group-definition order, "Other" last.
 */
function alrenas_get_grouped_product_attributes( $product ) {
	$definitions = alrenas_product_attribute_groups();
	$buckets     = array_fill_keys( array_keys( $definitions ), array() );
	$other       = array();

	foreach ( $product->get_attributes() as $attribute ) {
		if ( ! $attribute->get_visible() ) {
			continue;
		}

		$name = wc_attribute_label( $attribute->get_name(), $product );

		if ( $attribute->is_taxonomy() ) {
			$terms = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'names' ) );
			$value = implode( ', ', $terms );
		} else {
			$value = implode( ', ', $attribute->get_options() );
		}

		if ( '' === trim( $value ) ) {
			continue;
		}

		$spec        = array( 'label' => $name, 'value' => $value );
		$name_lower  = strtolower( $name );
		$placed      = false;

		foreach ( $definitions as $key => $definition ) {
			foreach ( $definition['keywords'] as $keyword ) {
				if ( false !== strpos( $name_lower, $keyword ) ) {
					$buckets[ $key ][] = $spec;
					$placed             = true;
					break 2;
				}
			}
		}

		if ( ! $placed ) {
			$other[] = $spec;
		}
	}

	$groups = array();

	foreach ( $definitions as $key => $definition ) {
		if ( $buckets[ $key ] ) {
			$groups[] = array( 'label' => $definition['label'], 'specs' => $buckets[ $key ] );
		}
	}

	if ( $other ) {
		$groups[] = array( 'label' => esc_html__( 'Other', 'alrenas' ), 'specs' => $other );
	}

	return $groups;
}
