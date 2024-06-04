<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!class_exists('ACF_Conitional_Field_Text')) :

	class ACF_Conitional_Field_Text extends ACF_Conitional_Field
	{
		/**
		 * Initializes props.
		 *
		 * @param   void
		 * @return  void
		 */
		public function initialize()
		{
			$this->name        = 'text';
			$this->label       = __('text', 'acfcle');
			$this->category    = 'basic';
			$this->object_type = 'field';
		}

		/**
		 * Returns an array of operators for this condition.
		 *
		 * @param   array $rule A condition rule.
		 * @return  array
		 */
		public static function get_operators($rule)
		{
			return array(
				'hasValue' => __('has any value', 'acfcle'),
				'hasNoValue' => __('has no value', 'acfcle'),
			);
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
			return 'disabled';
		}
	}

endif; // class_exists check
