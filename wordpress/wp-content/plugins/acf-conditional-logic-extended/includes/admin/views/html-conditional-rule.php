<?php

// vars
$prefix = 'acf_field_group[acfcle_conditional_logic_ext][' . $rule['group'] . '][' . $rule['id'] . ']';

?>
<tr data-id="<?php echo esc_attr($rule['id']); ?>">
	<td class="param">
		<?php

		// vars
		$choices = acfcle_get_condition_rule_types();

		// array
		if (is_array($choices)) {

			acf_render_field(array(
				'type'		=> 'select',
				'name'		=> 'param',
				'prefix'	=> $prefix,
				'value'		=> $rule['param'],
				'choices'	=> $choices,
				'class'		=> 'refresh-condition-rule',
				'allow_null' => true
			));
		}

		?>
	</td>
	<td class="operator">
		<?php

		// vars
		$choices = acfcle_get_condition_rule_operators($rule);

		// array
		if (is_array($choices)) {

			acf_render_field(array(
				'type'		=> 'select',
				'name'		=> 'operator',
				'prefix'	=> $prefix,
				'value'		=> $rule['operator'],
				'class'		=> 'refresh-condition-rule',
				'choices'	=> $choices
			));

			// custom	
		}

		?>
	</td>
	<td class="value">
		<?php

		// vars
		$field_type = acfcle_get_condition_rule_values_field($rule);

		switch ($field_type) {
			case 'text':
				acf_render_field(array(
					'type'		=> 'text',
					'name'		=> 'value',
					'prefix'	=> $prefix,
					'value'		=> $rule['value'],
				));
				break;
			case 'number':
				acf_render_field(array(
					'type'		=> 'number',
					'name'		=> 'value',
					'prefix'	=> $prefix,
					'value'		=> $rule['value'],
				));
				break;
			case 'select':
				$choices = acfcle_get_condition_rule_values($rule);

				acf_render_field(array(
					'type'		=> 'select',
					'name'		=> 'value',
					'prefix'	=> $prefix,
					'value'		=> $rule['value'],
					'choices'	=> $choices
				));
				break;

			default:
				acf_render_field(array(
					'type'		=> 'text',
					'name'		=> 'value',
					'prefix'	=> $prefix,
					'value'		=> '',
					'disabled'	=> 'disabled'
				));
				break;
		}

		?>
	</td>
	<td class="add">
		<a href="#" class="button add-condition-rule"><?php _e("and", 'acfcle'); ?></a>
	</td>
	<td class="remove">
		<a href="#" class="acf-icon -minus remove-condition-rule"></a>
	</td>
</tr>