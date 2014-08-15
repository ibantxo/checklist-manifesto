$(document).ready(function(){
	/* The following code is executed once the DOM is loaded */


	
	$(".companyList").sortable({
		axis		: 'y',				// Only vertical movements allowed
		containment	: 'window',			// Constrained by the window
		update		: function(){		// The function is called after the companys are rearranged
		
			// The toArray method returns an array with the ids of the companys
			var arr = $(".companyList").sortable('toArray');
			
			
			// Striping the company- prefix of the ids:
			
			arr = $.map(arr,function(val,key){
				return val.replace('company-','');
			});
			
			// Saving with AJAX
			$.get('ajax.php',{action:'rearrange company',positions:arr});
		},
		
		/* Opera fix: */
		
		stop: function(e,ui) {
			ui.item.css({'top':'0','left':'0'});
		}
	});

	// A global variable, holding a jQuery object 
	// containing the current company item:
	var currentCOMPANY;	


	$(".companyTaskList").sortable({
		axis		: 'y',				// Only vertical movements allowed
		containment	: 'window',			// Constrained by the window
		update		: function(){		// The function is called after the companys are rearranged
		
			// The toArray method returns an array with the ids of the companys
			var arr = $(".companyTaskList").sortable('toArray');
			
			
			// Striping the company- prefix of the ids:
			
			arr = $.map(arr,function(val,key){
				return val.replace('companytask-','');
			});
			
			// Saving with AJAX
			$.get('ajax.php',{action:'rearrange company task',positions:arr});
		},
		
		/* Opera fix: */
		
		stop: function(e,ui) {
			ui.item.css({'top':'0','left':'0'});
		}
	});


	
	// A global variable, holding a jQuery object 
	// containing the current company item:
	var currentCOMPANYTask;	
	var currentCOMPANYTaskSelected;		
	

	$(".companySubTaskList").sortable({
		axis		: 'y',				// Only vertical movements allowed
		containment	: 'window',			// Constrained by the window
		update		: function(){		// The function is called after the companys are rearranged
		
			// The toArray method returns an array with the ids of the companys
			var arr = $(".companySubTaskList").sortable('toArray');
			
			
			// Striping the company- prefix of the ids:
			
			arr = $.map(arr,function(val,key){
				return val.replace('companysubtask-','');
			});
			
			// Saving with AJAX
			$.get('ajax.php',{action:'rearrange company task subtask',positions:arr});
		},
		
		/* Opera fix: */
		
		stop: function(e,ui) {
			ui.item.css({'top':'0','left':'0'});
		}
	});	

	var currentCOMPANYSubTask;	
	var currentCOMPANYSubTaskSelected;		
	
	

	

	
	

	


	
	// Configuring the delete confirmation dialog
	$("#dialog-confirm").dialog({
		resizable: false,
		height:130,
		modal: true,
		autoOpen:false,
		buttons: {
			'Delete item': function() {
				$.get("ajax.php",{"action":"delete","id":currentTODO.data('id')},function(msg){
					currentTODO.fadeOut('fast');
				})
				
				$(this).dialog('close');
			},
			Cancel: function() {
				$(this).dialog('close');
			}
		}
	});




	// Configuring the delete confirmation dialog COMPANY TASK
	$("#dialog-confirm-company-task").dialog({
		resizable: false,
		height:130,
		modal: true,
		autoOpen:false,
		buttons: {
			'Delete item': function() {
				$.get("ajax.php",{"action":"delete company task","id":currentCOMPANYTask.data('id')},function(msg){
					currentCOMPANYTask.fadeOut('fast');
				})
				
				$(this).dialog('close');
			},
			Cancel: function() {
				$(this).dialog('close');
			}
		}
	});	

	// Configuring the delete confirmation dialog COMPANY TASK SUBTASK
	$("#dialog-confirm-company-task-subtask").dialog({
		resizable: false,
		height:130,
		modal: true,
		autoOpen:false,
		buttons: {
			'Delete item': function() {
				$.get("ajax.php",{"action":"delete company task subtask","id":currentCOMPANYSubTask.data('id')},function(msg){
					currentCOMPANYSubTask.fadeOut('fast');
				})
				
				$(this).dialog('close');
			},
			Cancel: function() {
				$(this).dialog('close');
			}
		}
	});		
	
	// Configuring the delete confirmation dialog COMPANY
	$("#dialog-confirm-company").dialog({
		resizable: false,
		height:130,
		modal: true,
		autoOpen:false,
		buttons: {
			'Delete item': function() {
				$.ajax({
				  url: "ajax.php",
				  data: {"action":"delete company","id":currentCOMPANY.data('id')},
				  dataType: "json",
				  timeout: 15000,    
				  success: function(response){
					switch(response.status){
					  case 'error':
						alert(response.message);
						break;
					  case 'done':
						currentCOMPANY.fadeOut('fast');
						break;
					  default:
						alert("unknown response");
					}  
				   },
				   error:function(){
					alert("failure");
				   }
				})	
				
				$(this).dialog('close');
			},
			Cancel: function() {
				$(this).dialog('close');
			}
		}
	});		
	

	
	
	
	
	
	
	
	
	
	
	
	


		
	// When a double click occurs, just simulate a click on the edit button:
	$('.company').live('dblclick',function(){
		$(this).find('a.edit').click();
	});

	
	// When a double click occurs, just simulate a click on the edit button:
	$('.companytask').live('dblclick',function(){
		$(this).find('a.edit').click();
	});	

	// When a double click occurs, just simulate a click on the edit button:
	$('.companysubtask').live('dblclick',function(){
		$(this).find('a.edit').click();
	});		

	$('.companyTaskList a.target').live('click',function(e){
		currentCOMPANYTask = $(this).closest('.companytask');
		var existia_seleccion_previamente=0;
		if (typeof currentCOMPANYTaskSelected != 'undefined'){
			existia_seleccion_previamente=1;
		}
		if (existia_seleccion_previamente==0){
			currentCOMPANYTaskSelected = currentCOMPANYTask;
		}
		
		if (currentCOMPANYTask.attr('id')==currentCOMPANYTaskSelected.attr('id')){
			if (existia_seleccion_previamente==0){
				//UNSELECT
				$('.companytask').addClass('unselected');
				//SELECT
				currentCOMPANYTask.removeClass('unselected');
		
			} else {
				$('.companytask').removeClass('unselected');
				currentCOMPANYTaskSelected=undefined;
			}
		} else {
			//UNSELECT
			$('.companytask').addClass('unselected');
			//SELECT
			currentCOMPANYTask.removeClass('unselected');

			currentCOMPANYTaskSelected = $(this).closest('.companytask');
			currentCOMPANYTaskSelected.data('id',currentCOMPANYTask.attr('id'));		
		} 


		//REPOBLAR COLUMNA "SUBTASKS"
		var currentCOMPANYTaskSelected_id;
		if (typeof currentCOMPANYTaskSelected == 'undefined'){
			currentCOMPANYTaskSelected_id=-1;
		} else {
			currentCOMPANYTaskSelected_id=currentCOMPANYTaskSelected.attr('id').replace('companytask-','');
		}
		
		$.ajax({
		  url: "companysubtask.php",
		  data: {
			'action':'refresh Company Task Subtask list',
			'companytask_id':currentCOMPANYTaskSelected_id,
			'rand':Math.random()			  
			},
		  dataType: "json",
		  timeout: 15000,    
		  success: function(response){
			switch(response.status){
			  case 'error':
				alert(response.message);
				break;
			  case 'done':
				//var x=document.getElementById('main_table').rows;
				//var y=x[0].cells;
				//y[1].innerHTML=response.data;					

				$("#companySubTaskList").empty();	
				$("#companySubTaskList").append(response.data);					
				
			
 				
				
			
				break;
			  default:
				alert("unknown response");		
			}  
		   },
		   error:function(){
			alert("failure");
		   }
		});		
		
		e.preventDefault();		
	});

	$('.companySubTaskList a.target').live('click',function(e){
		currentCOMPANYSubTask = $(this).closest('.companysubtask');
		var existia_seleccion_previamente=0;
		if (typeof currentCOMPANYSubTaskSelected != 'undefined'){
			existia_seleccion_previamente=1;
		}
		if (existia_seleccion_previamente==0){
			currentCOMPANYSubTaskSelected = currentCOMPANYSubTask;
		}
		
		if (currentCOMPANYSubTask.attr('id')==currentCOMPANYSubTaskSelected.attr('id')){
			if (existia_seleccion_previamente==0){
				//UNSELECT
				$('.companysubtask').addClass('unselected');
				//SELECT
				currentCOMPANYSubTask.removeClass('unselected');
		
			} else {
				$('.companysubtask').removeClass('unselected');
				currentCOMPANYSubTaskSelected=undefined;
			}
		} else {
			//UNSELECT
			$('.companysubtask').addClass('unselected');
			//SELECT
			currentCOMPANYSubTask.removeClass('unselected');

			currentCOMPANYSubTaskSelected = $(this).closest('.companysubtask');
			currentCOMPANYSubTaskSelected.data('id',currentCOMPANYSubTask.attr('id'));		
		} 
			
		e.preventDefault();		
	});	
	
	


	// If any link in the company is clicked, assign
	// the company item to the currentCOMPANY variable for later use.
	$('.company a').live('click',function(e){
		currentCOMPANY = $(this).closest('.company');
		currentCOMPANY.data('id',currentCOMPANY.attr('id').replace('company-',''));
		
		e.preventDefault();
	});	
	
	// If any link in the company task is clicked, assign
	// the company item to the currentCOMPANYTask variable for later use.
	$('.companytask a').live('click',function(e){
		currentCOMPANYTask = $(this).closest('.companytask');
		currentCOMPANYTask.data('id',currentCOMPANYTask.attr('id').replace('companytask-',''));
		
		e.preventDefault();
	});	

	// If any link in the company task is clicked, assign
	// the company item to the currentCOMPANYTask variable for later use.
	$('.companysubtask a').live('click',function(e){
		currentCOMPANYSubTask = $(this).closest('.companysubtask');
		currentCOMPANYSubTask.data('id',currentCOMPANYSubTask.attr('id').replace('companysubtask-',''));
		
		e.preventDefault();
	});		
	
	
	
	



	// Listening for a click on a delete button:
	$('.company a.delete').live('click',function(){
		$("#dialog-confirm-company").dialog('open');
	});

	// Listening for a click on a delete button:
	$('.companytask a.delete').live('click',function(){
		$("#dialog-confirm-company-task").dialog('open');
	});	
	
	// Listening for a click on a delete button:
	$('.companysubtask a.delete').live('click',function(){
		alert("dialog-confirm-company-task-subtask");
		$("#dialog-confirm-company-task-subtask").dialog('open');
	});		
	
	
	
	


	
	
	
	

	// Listening for a click on a edit button
	$('.company a.edit').live('click',function(){

		var container = currentCOMPANY.find('.text');
		
		if(!currentCOMPANY.data('origText'))
		{
			// Saving the current value of the Company so we can
			// restore it later if the user discards the changes:
			
			currentCOMPANY.data('origText',container.text());
		}
		else
		{
			// This will block the edit button if the edit box is already open:
			return false;
		}
		
		$('<input type="text">').val(container.text()).appendTo(container.empty());
		
		// Appending the save and cancel links:
		container.append(
			'<div class="editCompany">'+
				'<a class="saveChanges" href="#">Save</a> or <a class="discardChanges" href="#">Cancel</a>'+
			'</div>'
		);
		
	});


	// Listening for a click on a edit button
	$('.companytask a.edit').live('click',function(){

		var container = currentCOMPANYTask.find('.text');
		
		if(!currentCOMPANYTask.data('origText'))
		{
			// Saving the current value of the Company Task so we can
			// restore it later if the user discards the changes:
			
			currentCOMPANYTask.data('origText',container.text());
		}
		else
		{
			// This will block the edit button if the edit box is already open:
			return false;
		}
		
		$('<input type="text">').val(container.text()).appendTo(container.empty());
		
		// Appending the save and cancel links:
		container.append(
			'<div class="editCompanyTask">'+
				'<a class="saveChanges" href="#">Save</a> or <a class="discardChanges" href="#">Cancel</a>'+
			'</div>'
		);
		
	});	

	// Listening for a click on a edit button
	$('.companysubtask a.edit').live('click',function(){

		var container = currentCOMPANYSubTask.find('.text');
		
		if(!currentCOMPANYSubTask.data('origText'))
		{
			// Saving the current value of the Company Task so we can
			// restore it later if the user discards the changes:
			
			currentCOMPANYSubTask.data('origText',container.text());
		}
		else
		{
			// This will block the edit button if the edit box is already open:
			return false;
		}
		
		$('<input type="text">').val(container.text()).appendTo(container.empty());
		
		// Appending the save and cancel links:
		container.append(
			'<div class="editCompanySubTask">'+
				'<a class="saveChanges" href="#">Save</a> or <a class="discardChanges" href="#">Cancel</a>'+
			'</div>'
		);
		
	});		

	
	// Listening for a click on a edit button
	$('.company a.target_new_window').live('click',function(){
		window.open('?company_id='+currentCOMPANY.attr('id').replace('company-',''), '_blank'); 
	});	

	// Listening for a click on a edit button
	$('.company a.target_same_window').live('click',function(){
		location.href = ('?company_id='+currentCOMPANY.attr('id').replace('company-','')); 
	});		
	
	// Listening for a click on a edit button
	$('.companytask a.target').live('click',function(){
		//window.open('?id='+currentCOMPANY.attr('id').replace('company-',''), '_blank'); 
		//location.href = ('?company_id='+currentCOMPANY_id+"&companytask_id="+currentCOMPANYTask.attr('id').replace('companytask-',''));
        //remove `selected` class from all COMPANYTasks
  
	});	

	// Listening for a click on a edit button
	$('.companysubtask a.target').live('click',function(){
		//window.open('?id='+currentCOMPANY.attr('id').replace('company-',''), '_blank'); 
		//location.href = ('?company_id='+currentCOMPANY_id+"&companysubtask_id="+currentCOMPANYSubTask.attr('id').replace('companysubtask-',''));
        //remove `selected` class from all COMPANYSubTasks
  
	});		
	
	// Listening for a click on a edit button
	$('.company a.back_to_companylist').live('click',function(){
		location.href = ('companies.php');
	});	
	
	
	
	
	
	/////////////////////////////////////////////////////////////////
	//DISCARD CHANGES
	/////////////////////////////////////////////////////////////////	
	
	


	// The cancel edit link:
	$('.company a.discardChanges').live('click',function(){
		currentCOMPANY.find('.text')
					.text(currentCOMPANY.data('origText'))
					.end()
					.removeData('origText');
	});
	
	// The cancel edit link:
	$('.companytask a.discardChanges').live('click',function(){
		currentCOMPANYTask.find('.text')
					.text(currentCOMPANYTask.data('origText'))
					.end()
					.removeData('origText');
	});	

	// The cancel edit link:
	$('.companysubtask a.discardChanges').live('click',function(){
		currentCOMPANYSubTask.find('.text')
					.text(currentCOMPANYSubTask.data('origText'))
					.end()
					.removeData('origText');
	});		
	
	/////////////////////////////////////////////////////////////////
	//SAVE CHANGES
	/////////////////////////////////////////////////////////////////
		


	// The save changes link:
	$('.company a.saveChanges').live('click',function(){
		var text = currentCOMPANY.find("input[type=text]").val();
		
		$.get(
			"ajax.php",
			{
				'action':'edit company',
				'id':currentCOMPANY.data('id'),
				'text':text
			}
		);
		
		currentCOMPANY.removeData('origText').find(".text").text(text);
					
	});	
	
	// The save changes link:
	$('.companytask a.saveChanges').live('click',function(){
		var text = currentCOMPANYTask.find("input[type=text]").val();
		
		$.get(
			"ajax.php",
			{
				'action':'edit company task',
				'id':currentCOMPANYTask.data('id'),
				'text':text
			}
		);
		
		currentCOMPANYTask.removeData('origText').find(".text").text(text);
					
	});
	
	// The save changes link:
	$('.companysubtask a.saveChanges').live('click',function(){
		var text = currentCOMPANYSubTask.find("input[type=text]").val();
		
		$.get(
			"ajax.php",
			{
				'action':'edit company task subtask',
				'id':currentCOMPANYSubTask.data('id'),
				'text':text
			}
		);
		
		currentCOMPANYSubTask.removeData('origText').find(".text").text(text);
					
	});	
	
	
	
	
	/////////////////////////////////////////////////////////////////
	//ADD BUTTON
	/////////////////////////////////////////////////////////////////
	// The Add New ToDo button:
	var timestamp=0;
	
	// The Add New COMPANY button:
	$('#addButton_Company').click(function(e){
			// Only one company per 5 seconds is allowed:
			if((new Date()).getTime() - timestamp<5000) return false;

			$.get(
				"ajax.php",
				{
					'action':'new company',
					'text':'New Company Item. Doubleclick to Edit.',
					'rand':Math.random()
				},
				function(msg){
					// Appending the new company and fading it into view:
					$(msg).hide().appendTo('.companyList').fadeIn();
				}
			);

			// Updating the timestamp:
			timestamp = (new Date()).getTime();
		
			e.preventDefault();
		}
	);	

	// The Add New COMPANY Task button:
	$('#addButton_Task').click(function(e){
			// Only one company per 5 seconds is allowed:
			if((new Date()).getTime() - timestamp<5000) return false;
			$.get(
				"ajax.php",
				{
					'action':'new task for company',
					'text':'New Task Item for company ' + currentCOMPANY_id + '. Doubleclick to Edit.',
					'company_id':currentCOMPANY_id,
					'rand':Math.random()
				},
				function(msg){
					// Appending the new company and fading it into view:
					$(msg).hide().appendTo('.companyTaskList').fadeIn();
				}
			);

			// Updating the timestamp:
			timestamp = (new Date()).getTime();
		
			e.preventDefault();
		}
	);	

	// The Add New COMPANY Task button:
	$('#addButton_SubTask').click(function(e){
			var my_companytask_id;
			
			if (typeof currentCOMPANYTaskSelected == 'undefined'){
				my_companytask_id=0;
				//no habría que generar una subtask porque no quedaría ligada con una task
			} else {
				my_companytask_id=currentCOMPANYTask.attr('id');
				my_companytask_id=my_companytask_id.replace('companytask-','');
			}			
			
			// Only one company per 5 seconds is allowed:
			if((new Date()).getTime() - timestamp<5000) return false;
			$.ajax({
			  url: "ajax.php",
			  data: {
				'action':'new subtask for task of company',
				'text':'New SubTask Item. Company: ' + currentCOMPANY_id + '. Task: ' + my_companytask_id + ' Doubleclick to Edit.',
				'company_id':currentCOMPANY_id,
				'companytask_id':my_companytask_id,
				'rand':Math.random()			  
				},
			  dataType: "json",
			  timeout: 15000,    
			  success: function(response){
				switch(response.status){
				  case 'error':
					alert(response.message);
					break;
				  case 'done':
					$(response.data).hide().appendTo('.companySubTaskList').fadeIn();
					break;
				  default:
					alert("unknown response");
				}  
			   },
			   error:function(){
				alert("failure");
			   }
			});				
			
			// Updating the timestamp:
			timestamp = (new Date()).getTime();
		
			e.preventDefault();
		}
	);	
	
}); // Closing $(document).ready()