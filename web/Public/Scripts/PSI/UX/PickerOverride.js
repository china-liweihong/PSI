Ext.define("PSI.UX.PickerOverride", {
			override : "Ext.form.field.Picker",

			initEvents : function() {
				var me = this;
				me.callParent();

				// Add handlers for keys to expand/collapse the picker
				me.keyNav = new Ext.util.KeyNav(me.inputEl, {
							down : me.onDownArrow,
							esc : {
								handler : me.onEsc,
								scope : me,
								defaultEventAction : false
							},
							scope : me,
							forceKeyDown : true
						});

				// Non-editable allows opening the picker by clicking the field
				if (!me.editable) {
					me.mon(me.inputEl, 'click', me.onTriggerClick, me);
				}

				// Disable native browser autocomplete
				if (Ext.isGecko) {
					me.inputEl.dom.setAttribute('autocomplete', 'off');
				}

				// 上面的代码都是原来ExtJS的代码
				// 增加了下面的功能：双击鼠标弹出日期选择框
				me.mon(me.inputEl, 'dblclick', me.onTriggerClick, me);
			}
		});