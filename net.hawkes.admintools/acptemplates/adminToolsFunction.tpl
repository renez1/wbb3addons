{include file='header'}
<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/TabMenu.class.js"></script>
<script type="text/javascript">
	//<![CDATA[
	var tabMenu = new TabMenu();
	onloadEvents.push(function() { tabMenu.showSubTabMenu("{$activeTabMenuItem}"); });
	function callFunction(functionID, functionName) {
		if(confirm('Möchten sie wirklich die Funktion' + functionName + 'ausführen?')) {
        document.forms['functionForm'].elements['functionID'].value = functionID;
        document.forms['functionForm'].submit();
  		} else {
        	return false;
    	}
    }			
	//]]>
</script>

<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/adminToolsFunctionL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.admintools.functions{/lang}</h2>
	</div>
</div>

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

{if $success|isset}
	<p class="success">{lang}wcf.acp.admintools.functions.success{/lang}</p>	
{/if}


<div class="contentHeader">
	<div class="largeButtons">
		<ul><li><a href="index.php?page=AdminTools&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/adminToolsM.png" alt="" title="{lang}wcf.acp.menu.link.admintools.index{/lang}" /> <span>{lang}wcf.acp.menu.link.admintools.index{/lang}</span></a></li></ul>
		<ul><li><a href="index.php?form=AdminToolsExport&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/adminToolsExportM.png" alt="" title="{lang}wcf.acp.menu.link.admintools.export{/lang}" /> <span>{lang}wcf.acp.menu.link.admintools.export{/lang}</span></a></li></ul>
	</div>
</div>
<form enctype="multipart/form-data" method="post" name="functionForm" action="index.php?form=AdminToolsFunction">		
			
			<div class="tabMenu">
				<ul>
					{foreach from=$options item=categoryLevel1}
						<li id="{@$categoryLevel1.categoryName}"><a onclick="tabMenu.showSubTabMenu('{@$categoryLevel1.categoryName}');"><span>{lang}wcf.acp.admintools.option.category.{@$categoryLevel1.categoryName}{/lang}</span></a></li>
					{/foreach}
				</ul>
			</div>
			<div class="subTabMenu">
				<div class="containerHead">					
				</div>
			</div>
			
			{foreach from=$options item=categoryLevel1}				
					<div class="border tabMenuContent hidden" id="{@$categoryLevel1.categoryName}-content">
						<div class="container-1">
							<h3 class="subHeadline">{lang}wcf.acp.admintools.option.category.{@$categoryLevel1.categoryName}{/lang}</h3>
							<p class="description">{lang}wcf.acp.admintools.option.category.{@$categoryLevel1.categoryName}.description{/lang}</p>							
							{if $categoryLevel1.options|isset && $categoryLevel1.options|count}
								{include file='optionFieldList' options=$categoryLevel1.options langPrefix='wcf.acp.admintools.option.'}
							{/if}
							
							{if $categoryLevel1.categories|isset}
								{foreach from=$categoryLevel1.categories item=categoryLevel2}
									<fieldset>
										<legend>{lang}wcf.acp.admintools.option.category.{@$categoryLevel2.categoryName}{/lang}</legend>
										<p class="description">{lang}wcf.acp.admintools.option.category.{@$categoryLevel2.categoryName}.description{/lang}</p>
									
										<div>
											{include file='optionFieldList' options=$categoryLevel2.options langPrefix='wcf.acp.admintools.option.'}
										</div>
									</fieldset>
								{/foreach}
							{/if}
							<div class="smallButtons">
								{capture assign=functionName}
								{lang}wcf.acp.admintools.option.category.{@$categoryLevel1.categoryName}{/lang}
								{/capture}
								{if $additionalFunctionButtons|isset && $additionalFunctionButtons.functionID|isset}{@$additionalFunctionButtons.functionID}{/if}
								<ul><li><a href="javascript: callFunction({$categoryLevel1.functionID}, '{@$functionName|encodeJS}');"><img src="{@RELATIVE_WCF_DIR}icon/adminToolsRunM.png" alt="" title="{lang}wcf.acp.admintools.function.run{/lang}" /> <span>{lang}wcf.acp.admintools.function.run{/lang}</span></a></li></ul>								
							</div>
						</div>						
					</div>				
			{/foreach}
	
	<div class="formSubmit">
		<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
		<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
		<input type="hidden" name="packageID" value="{@PACKAGE_ID}" />
		<input type="hidden" name="functionID" value="0" />
 		{@SID_INPUT_TAG}
 		<input type="hidden" name="action" value="{@$action}" />
 		{if $groupID|isset}<input type="hidden" name="groupID" value="{@$groupID}" />{/if}
 		
 		<input type="hidden" id="activeTabMenuItem" name="activeTabMenuItem" value="{$activeTabMenuItem}" /> 		
 	</div>
</form>

{include file='footer'}