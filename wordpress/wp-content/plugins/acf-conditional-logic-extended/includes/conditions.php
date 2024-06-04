<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

// Register store.
acf_register_store('condition-types');

function acfcle_get_field_type($rule)
{
	$field = get_field_object($rule['param']);

	return $field['type'] ?? null;
}

/**
 * Registers a condition type.
 *
 * @param   string $class_name The condition class name.
 * @return  (ACF_Location|false)
 */
function acfcle_register_condition_type($class_name)
{
	$store = acf_get_store('condition-types');

	// Check class exists.
	if (!class_exists($class_name)) {
		$message = sprintf(__('Class "%s" does not exist.', 'acfcle'), $class_name);
		_doing_it_wrong(__FUNCTION__, $message, '5.9.0');
		return false;
	}

	// Create instance.
	$condition_type = new $class_name();
	$name          = $condition_type->name;

	// Check condition type is unique.
	if ($store->has($name)) {
		$message = sprintf(__('Condition type "%s" is already registered.', 'acfcle'), $name);
		_doing_it_wrong(__FUNCTION__, $message, '5.9.0');
		return false;
	}

	// Add to store.
	$store->set($name, $condition_type);

	/**
	 * Fires after a condition type is registered.
	 *
	 * @param   string $name The condition type name.
	 * @param   ACF_Location $condition_type The condition type instance.
	 */
	do_action('acf/registered_condition_type', $name, $condition_type);

	// Return condition type instance.
	return $condition_type;
}

/**
 * Returns an array of all registered condition types.
 *
 * @param   void
 * @return  array
 */
function acfcle_get_condition_types()
{
	return acf_get_raw_field_groups();
}

/**
 * Returns a condition type for the given name.
 *
 * @param   string $parent The field group or field settings. Also accepts the field group ID or key.
 * @return  (ACF_Location|null)
 */
function acfcle_get_condition_type($type)
{
	return acf_get_store('condition-types')->get($type);
}

/**
 * Returns a grouped array of all condition rule types.
 *
 * @param   void
 * @return  array
 */
function acfcle_get_condition_rule_types()
{
	$types = array();

	// Loop over all condition types and append to $type.
	$condition_types = acfcle_get_condition_types();
	foreach ($condition_types as $condition_type) {

		// Ignore if not public.
		if (!$condition_type['active']) {
			continue;
		}

		// Find category label from category name.
		$category = $condition_type['title'];

		$fields = acf_get_fields($condition_type['ID']);

		foreach ($fields as $field) {
			if (!isset($field['type'])) continue;

			if (acfcle_get_condition_type($field['type']))
				$types[$category][$field['key']] = $field['label'];
		}
		// Append
	}

	/**
	 * Filters the condition rule types.
	 *
	 * @param   array $types The condition rule types.
	 */
	return apply_filters('acf/condition/rule_types', $types);
}

/**
 * Returns a validated condition rule with all props.
 *
 * @param   array $rule The condition rule.
 * @return  array
 */
function acfcle_validate_condition_rule($rule = array())
{
	// Apply defaults.
	$rule = wp_parse_args(
		$rule,
		array(
			'id'       => '',
			'group'    => '',
			'param'    => '',
			'operator' => '==',
			'value'    => '',
		)
	);

	/**
	 * Filters the condition rule to ensure is valid.
	 *
	 * @param   array $rule The condition rule.
	 */
	$rule = apply_filters("acf/condition/validate_rule/type={$rule['param']}", $rule);
	$rule = apply_filters('acf/condition/validate_rule', $rule);
	return $rule;
}

/**
 * Returns an array of operators for a given rule.
 *
 * @param   array $rule The condition rule.
 * @return  array
 */
function acfcle_get_condition_rule_operators($rule)
{
	$operators = ACF_Location::get_operators($rule);

	// Get operators from condition type since 5.9.
	$condition_type = acfcle_get_condition_type(acfcle_get_field_type($rule));
	if ($condition_type) {
		$operators = $condition_type->get_operators($rule);
	}

	/**
	 * Filters the condition rule operators.
	 *
	 * @param   array $types The condition rule operators.
	 */
	$operators = apply_filters("acf/condition/rule_operators/type={$rule['param']}", $operators, $rule);
	$operators = apply_filters("acf/condition/rule_operators/{$rule['param']}", $operators, $rule);
	$operators = apply_filters('acf/condition/rule_operators', $operators, $rule);
	return $operators;
}

/**
 * Returns an array of values for a given rule.
 *
 * @param   array $rule The condition rule.
 * @return  array
 */
function acfcle_get_condition_rule_values($rule)
{
	$values = array();

	// Get values from condition type since 5.9.
	$condition_type = acfcle_get_condition_type(acfcle_get_field_type($rule));
	if ($condition_type) {
		$values = $condition_type->get_values($rule);
	}

	/**
	 * Filters the condition rule values.
	 *
	 * @param   array $types The condition rule values.
	 */
	$values = apply_filters("acf/condition/rule_values/type={$rule['param']}", $values, $rule);
	$values = apply_filters("acf/condition/rule_values/{$rule['param']}", $values, $rule);
	$values = apply_filters('acf/condition/rule_values', $values, $rule);
	return $values;
}

/**
 * Returns an array of values for a given rule.
 *
 * @param   array $rule The condition rule.
 * @return  array
 */
function acfcle_get_condition_rule_values_field($rule)
{
	$field = '';

	// Get values from condition type since 5.9.
	$condition_type = acfcle_get_condition_type(acfcle_get_field_type($rule));
	if ($condition_type) {
		$field = $condition_type->get_field_type($rule);
	}

	/**
	 * Filters the condition rule values.
	 *
	 * @param   array $types The condition rule values.
	 */
	$field = apply_filters("acf/condition/rule_values_field/type={$rule['param']}", $field, $rule);
	$field = apply_filters("acf/condition/rule_values_field/{$rule['param']}", $field, $rule);
	$field = apply_filters('acf/condition/rule_values_field', $field, $rule);
	return $field;
}

/**
 * Returns true if the provided rule matches the screen args.
 *
 * @param   array $rule The condition rule.
 * @param   array $screen The screen args.
 * @param   array $field The field group array.
 * @return  bool
 */
function acfcle_match_condition_rule($rule, $screen, $field_group)
{
	$result = false;

	// Get result from condition type since 5.9.
	$condition_type = acfcle_get_condition_type(acfcle_get_field_type($rule));
	if ($condition_type) {
		$result = $condition_type->match($rule, $screen, $field_group);
	}

	/**
	 * Filters the result.
	 *
	 * @param   bool $result The match result.
	 * @param   array $rule The condition rule.
	 * @param   array $screen The screen args.
	 * @param   array $field_group The field group array.
	 */
	$result = apply_filters("acf/condition/match_rule/type={$rule['param']}", $result, $rule, $screen, $field_group);
	$result = apply_filters('acf/condition/match_rule', $result, $rule, $screen, $field_group);
	$result = apply_filters("acf/condition/rule_match/{$rule['param']}", $result, $rule, $screen, $field_group);
	$result = apply_filters('acf/condition/rule_match', $result, $rule, $screen, $field_group);
	return $result;
}

/**
 * Alias of acf_register_condition_type().
 *
 * @param   string $class_name The condition class name.
 * @return  (ACF_Location|false)
 */
function acfcle_register_condition_rule($class_name)
{
	return acfcle_register_condition_type($class_name);
}

/**
 * Alias of acf_get_condition_type().
 *
 * @param   string $class_name The condition class name.
 * @return  (ACF_Location|false)
 */
function acfcle_get_condition_rule($name)
{
	return acfcle_get_condition_type($name);
}

/**
 * Alias of acf_validate_condition_rule().
 *
 * @param   array $rule The condition rule.
 * @return  array
 */
function acfcle_get_valid_condition_rule($rule)
{
	return acfcle_validate_condition_rule($rule);
}

// important for group keys
function acfcle_group_before_save($field_group)
{
	// Clean up condition keys.
	if (isset($field_group['acfcle_conditional_logic_ext'])) {

		// Remove empty values and convert to associated array.
		$field_group['acfcle_conditional_logic_ext'] = array_filter($field_group['acfcle_conditional_logic_ext']);
		$field_group['acfcle_conditional_logic_ext'] = array_values($field_group['acfcle_conditional_logic_ext']);
		$field_group['acfcle_conditional_logic_ext'] = array_map('array_filter', $field_group['acfcle_conditional_logic_ext']);
		$field_group['acfcle_conditional_logic_ext'] = array_map('array_values', $field_group['acfcle_conditional_logic_ext']);
	}

	return $field_group;
}
add_filter('acf/validate_field_group', 'acfcle_group_before_save', 99);

function acfcle_filter_groups($field_groups)
{
	global $typenow;
	
	$excluded_pages = [
		'acf-field-group'
	];
	$excluded_pages = apply_filters('acfcle/filter_excluded_pages', $excluded_pages);

	if (wp_doing_ajax() || is_null($typenow) || in_array($typenow, $excluded_pages)) return $field_groups;
	
	$valid = [];

	// Get the current screen.
	$screen = acf_get_location_screen();

	foreach ($field_groups as $group) {
		if (isset($group['acfcle_conditional_logic_ext_enabled']) && $group['acfcle_conditional_logic_ext_enabled'] == true && isset($group['acfcle_conditional_logic_ext'])) {
			$logic = $group['acfcle_conditional_logic_ext'];

			// ignore group if no rules.
			if (empty($logic)) {
				continue;
			}

			$match_group_map = [];

			foreach ($logic as $rule_key => $rules) {
				$match_group = true;

				if (count($rules) == 1) {
					// OR logic

					$match = [];
					foreach ($rules as $rule) {
						if (acfcle_match_condition_rule($rule, $screen, $group)) {
							$match[$rule_key] = 1;
						}
					}

					if (empty($match))
						$match_group = false;
				} else {
					// AND logic

					$match = [];
					// Loop over rules and determine if all rules match.
					foreach ($rules as $rule) {
						if (!acfcle_match_condition_rule($rule, $screen, $group)) {
							$match_group = false;
							break;
						}
					}
				}

				if ($match_group)
					$match_group_map[$rule_key] = 1;
			}

			// If we have matches, show the field group.
			if (!empty($match_group_map)) {
				array_push($valid, $group);
			}
		} else {
			array_push($valid, $group);
		}
	}

	return $valid;
}
add_filter('acf/load_field_groups', 'acfcle_filter_groups', 99);
