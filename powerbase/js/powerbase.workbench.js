(function() {
	var Workbench = function() {
		this.body = $('body');
		this.users = new Users();
		this.cookieExpireDays = 30;
		this.changed = [];
	};

	Workbench.prototype = {
		paneling : function(id) {
			this.destroyLayout('#center-container');
			var panel = '#' + id.replace('menu-', 'panel-');
			$('#center-container').html($(panel).html());
			this.createLayout('#center-container');
			
			if ($(panel).hasClass('v-split-pane')) {
				if ($(panel).hasClass('upper-list')) {
					switch(panel) {
						case '#panel-users':
							this.users.getList();
							this.users.get();
							break;
						default:
							break;
					}
				}
			}
		},

		layout : function() {
			this.body.layout({
				//applyDemoStyles: true,
				//	reference only - these options are NOT required because 'true' is the default
				closable:						true	// pane can open & close
				,	resizable:					true	// when open, pane can be resized
				,	slidable:					true	// when closed, pane can 'slide' open over other panes - closes on mouse-out
				,	livePaneResizing:			true

				,	spacing_open:    			3
				,   spacing_closed: 			3

				,	north__spacing_open:   		0

				//	some resizing/toggling settings
				,	north__closable:			false	// pane can open & close
				,	north__resizable:			false	// when open, pane can be resized
				,	north__slidable:			false	// OVERRIDE the pane-default of 'slidable=true'
				,	north__togglerLength_closed:'100%'	// toggle-button is full-width of resizer-bar
				,	north__spacing_closed:		20		// big resizer-bar when open (zero height)

				//	some pane-size settings
				,	west__minSize:				100
				,	center__minWidth:			100

				//	some pane animation settings
				,	west__animatePaneSizing:	false
				,	west__fxSpeed_size:			"fast"	// 'fast' animation when resizing west-pane
				,	west__fxSpeed_open:			100		// 1-second animation when opening west-pane
				//,	west__fxSettings_open:		{ easing: "easeOutBounce" } // 'bounce' effect when opening
				,	west__fxName_close:			"none"	// NO animation when closing west-pane

				//	enable showOverflow on west-pane so CSS popups will overlap north pane
				,	west__showOverflowOnHover:	true
			});
		},

		createLayout : function(containerSelector) {
			var name = (containerSelector === 'body' ? 'outerLayout' : 'innerLayout');
			var $C = $(containerSelector);
			if (!$C.data("layoutContainer")) {
				window[name] = $C.layout({
					name:							name
					,	initChildren:				false
					,	destroyChildren:			false
					,	stateManagement__enabled:	true // maintain state between destroy/create
					,	includeChildren:			false

					,	spacing_open:    			3
					,   spacing_closed: 			3

					,	 north__spacing_open:   	3
					,   north__spacing_closed: 		3
					,	closable:					false	// pane can open & close
					,	slidable:					false	// OVERRIDE the pane-default of 'slidable=true'
					,	north__size:				"40%"
					,	north__closable:			false	// pane can open & close
					,	north__resizable:			false	// when open, pane can be resized
					,	north__slidable:			false	// OVERRIDE the pane-default of 'slidable=true'
					,	north__togglerLength_closed:'100%'	// toggle-button is full-width of resizer-bar
					,	south__size:				"60%"
				//	,	north__spacing_closed:		20		// big resizer-bar when open (zero height)
				});
			}
		},
		
		destroyLayout : function(containerSelector) {
			var name = (containerSelector==='body' ? 'outerLayout' : 'innerLayout');
			var $C = $(containerSelector);
			if ($C.data("layoutContainer")) $C.layout().destroy();				
			window[name] = null;
		},
		
		setChanged : function(form, value) {
			this.changed[form] = value;
		},

		resetChanged : function() {
			this.changed = [];
		},

		isChanged : function(form) {
			if (form) {
				if (!this.changed[form]) return false;
				return this.changed[form];
			}
			for(var f in this.changed) {
				if (this.changed[f]) return true;
			}
			return false;
		},
		
		confirmDiscardChanged : function(func) {
			$.confirm({
				icon: 'glyphicon glyphicon-exclamation-sign',
				title: 'Data has been changed.',
				content: 'Discard the changes?',
				closeIcon: true,
				escapeKey: 'No',
				buttons: {
					Yes: {
						btnClass: 'btn-red',
						action: function () {
							func();
						}
					},
					No: {
						btnClass: 'btn-default',
						keys: ['enter', 'n'],
						action: function () {}
					}
				}
			});
		},

		listenUpdate : function(form) {
			var self = this;
			var listener = function(obj) {
				obj.change(function(){
					obj.parent().parent().addClass("has-warning");
					self.setChanged(form, true);
				});
				obj.keypress(function(e){
					if (e.keyCode === 13 || e.keyCode === 9) return; 
					if (obj.is(':disabled') || obj.attr("readonly") === "readonly") return; 
					obj.parent().parent().addClass("has-warning");
					self.setChanged(form, true);
				});
			};
			$(form + ' input')   .each(function(){ listener($(this)); });
			$(form + ' select')  .each(function(){ listener($(this)); });
			$(form + ' textarea').each(function(){ listener($(this)); });
		},
		
		indicateError : function(form, error) {
			var source = JSON.parse(error);
			for(var table in source) {
				for(var item in source[table]) {
					var selector = form + ' [name="data[' + table + '][' + item + ']"]';
					var message = source[table][item].join(", ");
					$.smkAddError(selector, message);
				}
			}
		}
	};

	var Users = function() {
		this.userDataForm = "#userDataForm";
		
		this.getList = function() {
			var self = this;
			var userList = "#userList";
			var colWidth = 0;
			var width = $.cookie("userList.colWidth");
			if (width) colWidth = width.split(',');
			request({
				url: endPoint + 'users/get_users_as_html/',
				type: 'GET',
				dataType : 'html',
				success: function(html) {
					$('#outer-user-list').html(html);
					$(userList).DataTable({
						paging: false,
						info: false,
						order: [],
						colReorder: true,
						dom: 'Zlfrtip',
						"colResize": {
							"resizeCallback": function() {
								var width = [];
								$(userList + ' th').each(function(idx , elm) {
									width.push($(elm).width());
								});
								$.cookie("userList.colWidth", width, { expires: workbench.cookieExpireDays });
							}
						}
					});
					$('.pb-user-row').on('click', function(){
						var id = $(this).attr('id').match(/\d/g).join('');
						if (workbench.isChanged(self.userDataForm)) {
							workbench.confirmDiscardChanged(function(){
								self.get(id);
							});
						} else {
							self.get(id);
						}
					});
					if (colWidth) {
						$(userList + ' th').each(function(idx , elm) {
							$(elm).width(colWidth[idx]);
						});
					}
				}
			});
		};

		this.get = function(id) {
			var self = this;
			if (!id) id = "";
			request({
				url: endPoint + 'users/get_user/?id=' + id,
				type: 'GET',
				dataType : 'html',
				success: function(html) {
					$('#outer-user-panel').html(html);
					workbench.listenUpdate(self.userDataForm);
					workbench.setChanged(self.userDataForm, false);
					$('#user-panel-new').on('click', function(){
						self.get();
					});
					$('#user-panel-save').on('click', function(){
						self.save();
					});
				}
			});
		};

		this.save = function() {
			if ($(this.userDataForm).smkValidate()) {
				var self = this;
				var data = $(this.userDataForm).serialize();
				request({
					url: endPoint + 'users/save/',
					type: 'POST',
					data: data,
					success: function(error) {
						if (error) {
							workbench.indicateError(self.userDataForm, error);
						} else {
							self.getList();
							self.get();
							flashMessage("success", "Saved");
						}
					}
				});
			}
		};

		this.delete = function(id) {
			var self = this;
			$.confirm({
				icon: 'glyphicon glyphicon-exclamation-sign',
				title: 'The user will be deleted.',
				content: 'Are you sure?',
				closeIcon: true,
				escapeKey: 'No',
				buttons: {
					Yes: {
						btnClass: 'btn-red',
						action: function () {
							var data = $(self.userDataForm).serialize();
							request({
								url: endPoint + 'users/delete/?id=' + id,
								type: 'POST',
								data: data,
								success: function() {
									self.getList();
									self.get();
									flashMessage("success", "Deleted");
								}
							});
						}
					},
					No: {
						btnClass: 'btn-default',
						keys: ['enter', 'n'],
						action: function () {}
					}
				}
			});
		};
	};

	window.Workbench = Workbench;
})();

