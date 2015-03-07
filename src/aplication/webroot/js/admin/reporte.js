// JavaScript Document Develoweb


 $(document).ready( function()
	{
	$("#chkall").click(function()				
	{
		var checked_status = this.checked;
		//$("input[@name='itemSelect[]']:checked").each(function() {selectedItems.push($(this).val());});

		$("input[@name='chklst[]']").each(function()
		{
			this.checked = checked_status;
		});
	});	
	
});
