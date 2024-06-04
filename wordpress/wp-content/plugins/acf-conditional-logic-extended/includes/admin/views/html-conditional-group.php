<div class="rule-group" data-id="<?php echo esc_attr($group_id); ?>">

	<h4><?php echo ( $group_id == 'group_0' ) ? __( 'Show this field group if', 'acfcle' ) : __( 'or', 'acfcle' ); ?></h4>
	
	<table class="acf-table -clear">
		<tbody>
			<?php

			foreach ( $group as $i => $rule ) :

				// validate rule
				$rule = acfcle_validate_condition_rule( $rule );

				// append id and group
				$rule['id']    = "rule_{$i}";
				$rule['group'] = $group_id;

				// view
				acfcle_get_view(
					'html-conditional-rule',
					array(
						'rule' => $rule,
					)
				);

			 endforeach;
			?>
		</tbody>
	</table>
	
</div>
