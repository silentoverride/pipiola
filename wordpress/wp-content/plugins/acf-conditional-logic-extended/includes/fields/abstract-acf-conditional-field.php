<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!class_exists('ACF_Conitional_Field')) :

	abstract class ACF_Conitional_Field
	{

		/**
		 * The condition rule name.
		 *
		 * @since 5.9.0
		 * @var string
		 */
		public $name = '';

		/**
		 * The condition rule label.
		 *
		 * @since 5.9.0
		 * @var string
		 */
		public $label = '';

		/**
		 * The condition rule category.
		 *
		 * Accepts "post", "page", "user", "forms" or a custom label.
		 *
		 * @since 5.9.0
		 * @var string
		 */
		public $category = 'post';

		/**
		 * Whether or not the condition rule is publicly accessible.
		 *
		 * @since 5.0.0
		 * @var bool
		 */
		public $public = true;

		/**
		 * The object type related to this condition rule.
		 *
		 * Accepts an object type discoverable by `acf_get_object_type()`.
		 *
		 * @since 5.9.0
		 * @var string
		 */
		public $object_type = '';

		/**
		 * The object subtype related to this condition rule.
		 *
		 * Accepts a custom post type or custom taxonomy.
		 *
		 * @since 5.9.0
		 * @var string
		 */
		public $object_subtype = '';

		/**
		 * Constructor.
		 *
		 * @param   void
		 * @return  void
		 */
		public function __construct() {
			$this->initialize();
		}

		/**
		 * Initializes props.
		 *
		 * @param   void
		 * @return  void
		 */
		public function initialize()
		{
			// Set props here.
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
				'==' => __('is equal to', 'acfcle'),
				'!=' => __('is not equal to', 'acfcle'),
			);
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

		/**
		 * Returns the object_type connected to this condition.
		 *
		 * @param   array $rule A condition rule.
		 * @return  string
		 */
		public function get_object_type($rule)
		{
			return $this->object_type;
		}

		/**
		 * Returns the object_subtype connected to this condition.
		 *
		 * @param   array $rule A condition rule.
		 * @return  string|array
		 */
		public function get_object_subtype($rule)
		{
			return $this->object_subtype;
		}

		/**
		 * Matches the provided rule against the screen args returning a bool result.
		 *
		 * @param   array $rule The condition rule.
		 * @param   array $screen The screen args.
		 * @param   array $field_group The field group settings.
		 * @return  bool
		 */
		public function match($rule, $screen, $field_group)
		{
			return false;
		}

		/**
		 * Compares the given value and rule params returning true when they match.
		 *
		 * @param   array $rule The condition rule data.
		 * @param   mixed $value The value to compare against.
		 * @return  bool
		 */
		public function compare_to_rule($value, $rule)
		{
			switch ($rule['operator']) {
				case '==':
					$result = ($value == $rule['value']);
					break;
				
				case '!=':
					$result = !($value == $rule['value']);
					break;
				
				case '>':
					$result = ($value > $rule['value']);
					break;
				
				case '<':
					$result = ($value < $rule['value']);
					break;
				
				case 'hasValue':
					$result = ($value != '');
					break;
				
				case 'hasNoValue':
					$result = ($value == '');
					break;
				
				default:
					$result = false;
					break;
			}

			return $result;
		}
	}

endif; // class_exists check
