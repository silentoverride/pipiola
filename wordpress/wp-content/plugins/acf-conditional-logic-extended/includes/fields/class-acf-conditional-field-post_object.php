<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!class_exists('ACF_Conitional_Field_Post_Object')) :

	class ACF_Conitional_Field_Post_Object extends ACF_Conitional_Field
	{
		/**
		 * Initializes props.
		 *
		 * @param   void
		 * @return  void
		 */
		public function initialize()
		{
			$this->name        = 'post_object';
			$this->label       = __('post object', 'acfcle');
			$this->category    = 'relational';
			$this->object_type = 'post';
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

			if (is_object($value))
				$value = $value->ID;

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
			$choices = array();
			$field = get_field_object($rule['param']);

			// Get post types.
			if (!empty($field['post_type'])) {
				$post_types = $field['post_type'];
			} else {

				$post_types = acf_get_post_types(
					array(
						'show_ui' => 1,
						'exclude' => array('attachment'),
					)
				);
			}

			// Get grouped posts.
			$groups = acf_get_grouped_posts(
				array(
					'post_type' => $post_types,
				)
			);

			// Append to choices.
			if ($groups) {
				foreach ($groups as $label => $posts) {
					$choices[$label] = array();
					foreach ($posts as $post) {
						$choices[$label][$post->ID] = acf_get_post_title($post);
					}
				}
			}
			return $choices;
		}
	}

endif; // class_exists check
