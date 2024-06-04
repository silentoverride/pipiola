<?php
/*
Plugin Name: 		ACF Conditional Logic Extended
Description: 		Display Field Group based on a field value from another group
Version: 			1.2
Requires at least: 	5.0
Requires PHP: 		7.2
Author: 			Ivan Kancijan
Text Domain: 		acfcle
Domain Path: 		/lang
License: 			GPLv2 or later
*/

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

define('ACFCLE_PATH', plugin_dir_path(__FILE__));
define('ACFCLE_URL', esc_url(plugin_dir_url(__FILE__), 'https', 'constant'));

include_once 'helpers.php';

/**
 * Register scripts
 */
function acfcle_scripts()
{
	$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
	$version = get_plugin_data(__FILE__)['Version'];
	wp_enqueue_script('acfcle-field-group-conditions', acfcle_get_url('assets/src/js/acf-conditionals' . $suffix . '.js'), array('acf-input'), $version);
}
add_action('acf/field_group/admin_enqueue_scripts', 'acfcle_scripts');

/**
 * Register condition types
 */
function acfcle_acf_init()
{
	include_once acfcle_get_path('/includes/fields/abstract-acf-conditional-field.php');
	include_once acfcle_get_path('/includes/fields/class-acf-conditional-field-text.php');
	include_once acfcle_get_path('/includes/fields/class-acf-conditional-field-textarea.php');
	include_once acfcle_get_path('/includes/fields/class-acf-conditional-field-range.php');
	include_once acfcle_get_path('/includes/fields/class-acf-conditional-field-url.php');
	include_once acfcle_get_path('/includes/fields/class-acf-conditional-field-select.php');
	include_once acfcle_get_path('/includes/fields/class-acf-conditional-field-post_object.php');
	include_once acfcle_get_path('/includes/fields/class-acf-conditional-field-number.php');

	include_once acfcle_get_path('/includes/conditions.php');

	acfcle_register_condition_type('ACF_Conitional_Field_Text');
	acfcle_register_condition_type('ACF_Conitional_Field_Textarea');
	acfcle_register_condition_type('ACF_Conitional_Field_Range');
	acfcle_register_condition_type('ACF_Conitional_Field_Url');
	acfcle_register_condition_type('ACF_Conitional_Field_Select');
	acfcle_register_condition_type('ACF_Conitional_Field_Post_Object');
	acfcle_register_condition_type('ACF_Conitional_Field_Number');
}
add_action('acf/init', 'acfcle_acf_init');

/**
 * Register Group Conditional Logic Metabox
 */
function admin_head()
{
	add_meta_box('acf-field-group-conditional-logic', __('Group Conditional Logic', 'acfcle'), 'acfcle_conditional_logic_ext', 'acf-field-group', 'normal');
}
add_action('acf/field_group/admin_head', 'admin_head');

/**
 * Metabox view
 */
function acfcle_conditional_logic_ext()
{
	// global
	global $field_group;

	// UI needs at lease 1 condition rule
	if (empty($field_group['acfcle_conditional_logic_ext'])) {
		$field_group['acfcle_conditional_logic_ext'] = array(
			// group 0
			array(
				// rule 0
				array(),
			),
		);
	}

	// view
	acfcle_get_view('field-group-conditional-logic');
}

/**
 * Ajax render condition by rule
 */
function acfcle_ajax_render_condition_rule()
{
	// validate
	if (!acf_verify_ajax()) die();

	// validate rule
	$rule = acfcle_validate_condition_rule($_POST['rule']);

	// view
	acfcle_get_view('html-conditional-rule', array(
		'rule' => $rule
	));

	// die
	die();
}
add_action('wp_ajax_acf/field_group/render_condition_rule', 'acfcle_ajax_render_condition_rule');
