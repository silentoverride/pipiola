<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!class_exists('ACF_Conitional_Field_Number')) :

	class ACF_Conitional_Field_Number extends ACF_Conitional_Field
	{
		/**
		 * Initializes props.
		 *
		 * @param   void
		 * @return  void
		 */
		public function initialize()
		{
			$this->name        = 'number';
			$this->label       = __('number', 'acfcle');
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
				'==' => __('is equal to', 'acfcle'),
				'!=' => __('is not equal to', 'acfcle'),
				'>' => __('is greather than', 'acfcle'),
				'<' => __('is less than', 'acfcle'),
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
			$condition = 'number';

			if ($rule['operator'] === 'hasValue' || $rule['operator'] === 'hasNoValue') {
				return 'disabled';
			}

			return $condition;
		}

		/**
		 * Returns an array of possible values for this condition.
		 *
		 * @param   array $rule A condition rule.
		 * @return  array
		 */
		public function get_values($rule)
		{
			return array();
		}
	}

endif; // class_exists check
