<?php

// global
global $field_group;

?>
<div class="acf-field">
	<div class="acf-label">
		<label><?php _e('Rules', 'acfcle'); ?></label>
		<p class="description"><?php _e('Create a set of rules to determine which edit screens will use these advanced custom fields', 'acfcle'); ?></p>
	</div>
	<div class="acf-input">
		<?php
		acf_render_field_wrap(
			array(
				'label'        => __('Use Conditional Logic?', 'acfcle'),
				'instructions' => '',
				'type'         => 'true_false',
				'name'         => 'acfcle_conditional_logic_ext_enabled',
				'prefix'       => 'acf_field_group',
				'value'        => $field_group['acfcle_conditional_logic_ext_enabled'] ?? '',
				'ui'           => 1,
				'ui_on_text'    => __('Enable', 'acfcle'),
				'ui_off_text'    => __('Disable', 'acfcle')
			)
		);
		?>
		<div class="rule-groups">

			<?php
			foreach ($field_group['acfcle_conditional_logic_ext'] as $i => $group) :

				// bail ealry if no group
				if (empty($group)) {
					return;
				}

				// view
				acfcle_get_view(
					'html-conditional-group',
					array(
						'group'    => $group,
						'group_id' => "group_{$i}",
					)
				);

			endforeach;
			?>

			<h4><?php _e('or', 'acfcle'); ?></h4>

			<a href="#" class="button add-condition-group"><?php _e('Add rule group', 'acfcle'); ?></a>

		</div>
	</div>
</div>
<script type="text/javascript">
	if (typeof acf !== 'undefined') {

		acf.newPostbox({
			'id': 'acf-field-group-conditional-logic',
			'label': 'left'
		});

	}
</script>