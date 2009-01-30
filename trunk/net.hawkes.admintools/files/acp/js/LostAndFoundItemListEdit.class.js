
function LostAndFoundListEdit(data, count, page, mode) {
	this.data = data;
	this.count = count;
	this.page = page;	
	this.mode = mode;
	
	/**
	 * Saves the marked status.
	 */
	this.saveMarkedStatus = function(data) {
		var ajaxRequest = new AjaxRequest();		
		ajaxRequest.openPost('index.php?page=AdminToolsLostAndFoundAction&type='+this.mode+'&packageID='+PACKAGE_ID+SID_ARG_2ND, data);
	}
	
	/**
	 * Returns a list of the edit options for the edit menu.
	 */
	this.getEditOptions = function(id) {
		var options = new Array();
		var i = 0;
					
		
			// delete
			//if (permissions['canDeleteItem']) {
				options[i] = new Object();
				options[i]['function'] = 'itemListEdit.remove('+id+');';
				options[i]['text'] = language['wcf.global.button.delete'];
				i++;
		//	}
					
			// marked status
		//	if (permissions['canMarkItem']) {
				var markedStatus = this.data[id] ? this.data[id]['isMarked'] : false;
				options[i] = new Object();
				options[i]['function'] = 'itemListEdit.parentObject.markItem(' + (markedStatus ? 'false' : 'true') + ', '+id+');';
				options[i]['text'] = markedStatus ? language['wcf.global.button.unmark'] : language['wcf.global.button.mark'];
				i++;
		//	}
		
				
		return options;
	}
	
	/**
	 * Returns a list of the edit options for the edit marked menu.
	 */
	this.getEditMarkedOptions = function() {
		var options = new Array();
		var i = 0;
		
		// delete
		//if (permissions['canDeleteItem']) {
			options[i] = new Object();
			options[i]['function'] = 'itemListEdit.removeAll();';
			options[i]['text'] = language['wcf.global.button.delete'];
			i++;
		//}
		
		// unmark all
		options[i] = new Object();
		options[i]['function'] = 'itemListEdit.unmarkAll();';
		options[i]['text'] = language['wcf.global.button.unmark'];
		i++;
		
		
		return options;
	}
	
	/**
	 * Returns the title of the edit marked menu.
	 */
	this.getMarkedTitle = function() {
		return eval(language['wcf.acp.admintools.lostandfound.markedItems']);
	}
	
	
	
	/**
	 * Deletes an item.
	 */
	this.remove = function(id) {
		if (confirm(language['wcf.acp.admintools.delete.sure'])) {
				document.location.href = fixURL('index.php?page=AdminToolsLostAndFoundAction&action=delete&itemID='+id+'&url='+encodeURIComponent(url)+SID_ARG_2ND);
		}
	}
	
	
	/**
	 * Deletes the marked items.
	 */
	this.removeAll = function() {
		if (confirm(language['wcf.acp.admintools.delete.sure'])) {
			document.location.href = fixURL('index.php?page=AdminToolsLostAndFoundAction&&action=deleteAll&url='+encodeURIComponent(url)+SID_ARG_2ND);
		}
	}	
	
	/**
	 * Ummarked all marked threads.
	 */
	this.unmarkAll = function() {
		var ajaxRequest = new AjaxRequest();
		ajaxRequest.openGet('index.php?page=AdminToolsLostAndFoundAction&type='+this.mode+'&action=unmarkAll'+SID_ARG_2ND);
		
		// checkboxes
		this.count = 0;
		for (var id in this.data) {
			this.data[id]['isMarked'] = 0;
			var checkbox = document.getElementById(this.type + 'Mark' + id);
			if (checkbox) {
				checkbox.checked = false;
			}
			
			this.showStatus(id);
		}
		
		// mark all checkbox
		this.parentObject.checkMarkAll(false);
		
		// edit marked menu
		this.parentObject.showMarked();
	}
	
	
	/**
	 * Show the status of a item.
	 */
	this.showStatus = function(id) {
		// get row
		var row = document.getElementById(this.type + 'Row'+id);
		
		// update css class
		if (row) {
			// get class
			var className = row.className;

			// remove all classes except first one
			//className = className.replace(/ .*/, '');
			
			// original className
			if (this.data[id]['class'] != className) {
				className = this.data[id]['class'];
			}
								
			
			// marked
			if (this.data[id]['isMarked']) {
				className += ' marked';
			}
			
			row.className = className;
		}
		
	}
	

	this.parentObject = new InlineListEdit(this.page, this);
}