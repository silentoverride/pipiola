<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!class_exists('ACF_Conitional_Field_Url')) :

	class ACF_Conitional_Field_Url extends ACF_Conitional_Field_Text
	{
		/**
		 * Initializes props.
		 *
		 * @param   void
		 * @return  void
		 */
		public function initialize()
		{
			$this->name        = 'url';
			$this->label       = __('url', 'acfcle');
			$this->category    = 'basic';
			$this->object_type = 'field';
		}
	}

endif; // class_exists check
