(function ($, undefined) {

	/**
	*  conditionManager
	*
	*  Field group condition rules functionality 
	*
	*  @date	15/12/17
	*  @since	5.7.0
	*
	*  @param	void
	*  @return	void
	*/

	var conditionManager = new acf.Model({

		id: 'conditionManager',
		wait: 'ready',

		events: {
			'click .add-condition-rule': 'onClickAddRule',
			'click .add-condition-group': 'onClickAddGroup',
			'click .remove-condition-rule': 'onClickRemoveRule',
			'change .refresh-condition-rule': 'onChangeRemoveRule',
			'change #acf_field_group-acfcle_conditional_logic_ext_enabled': 'onChangeShowHide',
		},

		initialize: function () {
			this.$el = $('#acf-field-group-conditional-logic');
			this.onChangeShowHide();
		},

		onClickAddRule: function (e, $el) {
			this.addRule($el.closest('tr'));
		},

		onClickRemoveRule: function (e, $el) {
			this.removeRule($el.closest('tr'));
		},

		onChangeRemoveRule: function (e, $el) {
			this.changeRule($el.closest('tr'));
		},

		onClickAddGroup: function (e, $el) {
			this.addGroup();
		},

		addRule: function ($tr) {
			acf.duplicate($tr);
		},

		removeRule: function ($tr) {
			if ($tr.siblings('tr').length == 0) {
				$tr.closest('.rule-group').remove();
			} else {
				$tr.remove();
			}
		},

		changeRule: function ($rule) {

			// vars
			var $group = $rule.closest('.rule-group');
			var prefix = $rule.find('td.param select').attr('name').replace('[param]', '');

			// ajaxdata
			var ajaxdata = {};
			ajaxdata.action = 'acf/field_group/render_condition_rule';
			ajaxdata.rule = acf.serialize($rule, prefix);
			ajaxdata.rule.id = $rule.data('id');
			ajaxdata.rule.group = $group.data('id');

			// temp disable
			acf.disable($rule.find('td.value'));

			// ajax
			$.ajax({
				url: acf.get('ajaxurl'),
				data: acf.prepareForAjax(ajaxdata),
				type: 'post',
				dataType: 'html',
				success: function (html) {
					if (!html) return;
					$rule.replaceWith(html);
				}
			});
		},

		addGroup: function () {

			// vars
			var $group = this.$('.rule-group:last');

			// duplicate
			$group2 = acf.duplicate($group);

			// update h4
			$group2.find('h4').text(acf.__('or'));

			// remove all tr's except the first one
			$group2.find('tr').not(':first').remove();
		},

		onChangeShowHide: function() {
			var value = this.$el.find('#acf_field_group-acfcle_conditional_logic_ext_enabled')
			var $group =  this.$el.find('.rule-groups');

			if(value.prop('checked')) {
				$group.show();
			} else {
				$group.hide();
			}
		}
	});

})(jQuery);