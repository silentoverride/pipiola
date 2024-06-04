<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!class_exists('ACF_Conitional_Field_Select')) :

	class ACF_Conitional_Field_Select extends ACF_Conitional_Field
	{
		/**
		 * Initializes props.
		 *
		 * @param   void
		 * @return  void
		 */
		public function initialize()
		{
			$this->name        = 'select';
			$this->label       = __('select', 'acfcle');
			$this->category    = 'choice';
			$this->object_type = 'field';
		}

		/**
		 * Matches the provided rule against the screen args returning a bool result.
		 *
		 * @param   array $rule The location rule.
		 * @param   array $screen The screen args.
		 * @param   array $field_group The field group settings.
		 * @return  bool
		 */
		public function match($rule, $screen, $field_group)
		{
			$value = get_field($rule['param']);

			if (!$value) return false;

			// Compare rule against post_id.
			return $this->compare_to_rule($value, $rule);
		}
		
		/**
		 * Returns field type for this condition.
		 *
		 * @param   array $rule A condition rule.
		 * @return  mixed
		 */
		public function get_field_type($rule)
		{
			return 'select';
		}

		/**
		 * Returns an array of possible values for this rule type.
		 *
		 * @param   array $rule A location rule.
		 * @return  array
		 */
		public function get_values($rule)
		{
			$field = get_field_object($rule['param']);

			// Append to choices.
			if (!$field) return array();

			return $field['choices'];
		}
	}

endif; // class_exists check
