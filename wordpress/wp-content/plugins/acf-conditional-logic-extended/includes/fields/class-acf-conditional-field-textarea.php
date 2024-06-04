<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!class_exists('ACF_Conitional_Field_Textarea')) :

	class ACF_Conitional_Field_Textarea extends ACF_Conitional_Field_Text
	{
		/**
		 * Initializes props.
		 *
		 * @param   void
		 * @return  void
		 */
		public function initialize()
		{
			$this->name        = 'textarea';
			$this->label       = __('textarea', 'acfcle');
			$this->category    = 'basic';
			$this->object_type = 'field';
		}
	}

endif; // class_exists check
