{include file='header'}
{include file='Wysiwyg'}
{* $Id$ *}
<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/pmNewL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.pmToUgrps.newMessage{/lang}</h2>
	</div>
</div>
<div class="contentHeader">
	<div class="largeButtons">
		<ul><li><a href="index.php?page=GroupList&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/groupM.png" alt="" title="{lang}wcf.acp.menu.link.group.view{/lang}" /> <span>{lang}wcf.acp.menu.link.group.view{/lang}</span></a></li></ul>
	</div>
</div>

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

{if $preview|isset}
	<div class="border">
		<div class="containerHead">
			<h3>{lang}wcf.message.preview{/lang}</h3>
		</div>
		
		<div class="message content">
			<div class="messageInner container-1">
				{if $subject}
					<h4>{$subject}</h4>
				{/if}
				<div class="messageBody">
					{@$preview}
				</div>
			</div>
		</div>
	</div>
	<br />
{/if}

<form method="post" action="index.php?form=PMToUserGroups">
	<div class="border content">
		<div class="container-1">
			<fieldset>
				<legend>{lang}wcf.pmToUgrps.sendTo.legend{/lang}</legend>
				<div class="formGroup{if $errorField == 'groupIDs'} formError{/if}">
					<div class="formGroupLabel">
						<label>{lang}wcf.pmToUgrps.sendTo.groups{/lang}</label>
					</div>
					<div class="formGroupField">
						<fieldset>
							<legend>{lang}wcf.pmToUgrps.sendTo.groups{/lang}</legend>

							<div class="formOptions">
                        	    {htmlCheckboxes options=$groups name=groupIDs selected=$groupIDs}
							</div>
						</fieldset>
						{if $errorField == 'groupIDs'}
							<p class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							</p>
						{/if}
					</div>
				</div>

            	<div class="formElement{if $errorField == 'maxLifeTime'} formError{/if}">
            		<div class="formFieldLabel">
            			<label for="maxLifeTime">{lang}wcf.pmToUgrps.maxLifeTime{/lang}</label>
            		</div>
            		<div class="formField">
            			<input type="text" class="inputText" name="maxLifeTime" id="maxLifeTime" value="{$maxLifeTime}" />
            			{if $errorField == 'maxLifeTime'}
            				<p class="innerError">
            					{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
            					{if $errorType == 'isNoInteger'}{lang}wcf.pmToUgrps.error.isNoInteger{/lang}{/if}
            				</p>
            			{/if}
            		</div>
					<div class="formFieldDesc hidden" id="maxLifeTimeHelpMessage">
						{lang}wcf.pmToUgrps.maxLifeTime.description{/lang}
					</div>
            	</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('maxLifeTime');
				//]]></script>

            	<div class="formElement{if $errorField == 'subject'} formError{/if}">
            		<div class="formFieldLabel">
            			<label for="subject">{lang}wcf.pmToUgrps.pm.subject{/lang}</label>
            		</div>
            		<div class="formField">
            			<input type="text" class="inputText" name="subject" id="subject" value="{$subject}" />
            			{if $errorField == 'subject'}
            				<p class="innerError">
            					{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
            				</p>
            			{/if}
            		</div>
            	</div>
			</fieldset>

            <fieldset>
            	<legend>{lang}wcf.pmToUgrps.pm.legend{/lang}</legend>
            	<div class="formElement{if $errorField == 'text'} formError{/if}" id="editor">
            		<div class="formFieldLabel">
            			<label for="text">{lang}wcf.pmToUgrps.pm.text{/lang}</label>
            		</div>
            		<div class="formField">
            			<textarea name="text" id="text" rows="20" cols="40">{if $text|isset}{$text}{/if}</textarea>
            			{if $errorField == 'text'}
            				<p class="innerError">
            					{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
            					{if $errorType == 'tooLong'}{lang}wcf.message.error.tooLong{/lang}{/if}
            					{if $errorType == 'censoredWordsFound'}{lang}wcf.message.error.censoredWordsFound{/lang}{/if}
            				</p>
            			{/if}
            		</div>
            	</div>
                {include file='messageFormTabs'}
            </fieldset>
    	</div>
    </div>
    
    <div class="formSubmit">
        {@SID_INPUT_TAG}
        <input type="hidden" name="packageID" value="{@PACKAGE_ID}" />
    	<input type="submit" name="send" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
        <input type="submit" name="preview" accesskey="p" value="{lang}wcf.global.button.preview{/lang}" />
    	<input type="reset" name="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
    </div>
</form>
{include file='footer'}
