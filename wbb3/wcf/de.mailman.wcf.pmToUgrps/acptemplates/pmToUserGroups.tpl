{include file='header'}
{include file='wysiwyg'}
<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/pmNewL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.pmToUgrps.newMessage{/lang}</h2>
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

                <div class="formElement">
                    <div class="formFieldLabel">
                        <label for="limit">{lang}wcf.pmToUgrps.limit{/lang}</label>
                    </div>
                    <div class="formField">
                        <select name="limit" id="limit">
                            <option value="5"{if $limit == 5} selected="selected"{/if}> 5</option>
                            <option value="10"{if $limit == 10} selected="selected"{/if}> 10</option>
                            <option value="15"{if $limit == 15} selected="selected"{/if}> 15</option>
                            <option value="20"{if $limit == 20} selected="selected"{/if}> 20</option>
                            <option value="25"{if $limit == 25} selected="selected"{/if}> 25</option>
                            <option value="50"{if $limit == 50} selected="selected"{/if}> 50</option>
                            <option value="75"{if $limit == 75} selected="selected"{/if}> 75</option>
                            <option value="100"{if $limit == 100} selected="selected"{/if}> 100</option>
                            <option value="150"{if $limit == 150} selected="selected"{/if}> 150</option>
                            <option value="200"{if $limit == 200} selected="selected"{/if}> 200</option>
                            <option value="300"{if $limit == 300} selected="selected"{/if}> 300</option>
                            <option value="400"{if $limit == 400} selected="selected"{/if}> 400</option>
                            <option value="500"{if $limit == 500} selected="selected"{/if}> 500</option>
                            <option value="1000"{if $limit == 1000} selected="selected"{/if}> 1000</option>
                        </select>
                    </div>
                    <div class="formFieldDesc hidden" id="limitHelpMessage">
                    	<p>{lang}wcf.pmToUgrps.limit.description{/lang}</p>
                    </div>
                </div>
	            <script type="text/javascript">
                    //<![CDATA[
                    inlineHelp.register('limit');
                    //]]>
                </script>

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
