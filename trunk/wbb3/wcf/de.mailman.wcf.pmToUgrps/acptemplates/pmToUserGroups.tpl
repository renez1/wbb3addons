{include file='header'}
<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/pmNewL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.pmToUgrps.newMessage{/lang}</h2>
	</div>
</div>

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
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
			</fieldset>

            <fieldset>
            	<legend>{lang}wcf.pmToUgrps.pm.legend{/lang}</legend>
            
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
                        {if $extWysiwyg}
                            <div class="smallButtons">
                                <ul>
                                    <li><a href="{@RELATIVE_WBB_DIR}index.php?form=ExternalWysiwygEditor&amp;permissionType=message{@SID_ARG_2ND}" target="_blank"><img src="{@RELATIVE_WBB_DIR}icon/externalWysiwygEditorS.png" alt="" /> <span>{lang}wbb.externalWysiwygEditor.title{/lang}</span></a></li>
                                </ul>
                            </div>
                        {/if}
            		</div>
            	</div>
            </fieldset>

            <fieldset>
            	<legend>{lang}wcf.pmToUgrps.settings.legend{/lang}</legend>
                {if $canUseSmilies}
                    <div class="formCheckBox formElement" id="enableSmiliesDiv">
                        <div class="formField">
                        	<label for="enableSmilies">
                            	<input type="checkbox" name="enableSmilies" id="enableSmilies" value="1"{if !$enableSmilies|empty} checked="checked"{/if}>
                                {lang}wcf.message.settings.enableSmilies{/lang}
                            </label>
                        </div>
                    </div>
                {/if}
                {if $canUseHtml}
                    <div class="formCheckBox formElement" id="enableHtmlDiv">
                        <div class="formField">
                        	<label for="enableHtml">
                            	<input type="checkbox" name="enableHtml" id="enableHtml" value="1"{if !$enableHtml|empty} checked="checked"{/if}>
                                {lang}wcf.message.settings.enableHtml{/lang}
                            </label>
                        </div>
                    </div>
                {/if}
                {if $canUseBBCodes}
                    <div class="formCheckBox formElement" id="enableBBCodesDiv">
                        <div class="formField">
                        	<label for="enableBBCodes">
                            	<input type="checkbox" name="enableBBCodes" id="enableBBCodes" value="1"{if !$enableBBCodes|empty} checked="checked"{/if}>
                                {lang}wcf.message.settings.enableBBCodes{/lang}
                            </label>
                        </div>
                    </div>
                {/if}
                <div class="formCheckBox formElement" id="showSignatureDiv">
                    <div class="formField">
                    	<label for="showSignature">
                        	<input type="checkbox" name="showSignature" id="showSignature" value="1"{if !$showSignature|empty} checked="checked"{/if}>
                            {lang}wcf.message.settings.showSignature{/lang}
                        </label>
                    </div>
                </div>

                <div class="formElement">
                    <div class="formFieldLabel">
                        <label for="limit">{lang}wcf.pmToUgrps.limit{/lang}</label>
                    </div>
                    <div class="formField">
                        <select name="limit" id="limit">
                            <option value="5"> 5</option>
                            <option value="10"> 10</option>
                            <option value="15"> 15</option>
                            <option value="20"> 20</option>
                            <option value="25" selected="selected"> 25</option>
                            <option value="50"> 50</option>
                            <option value="75"> 75</option>
                            <option value="100"> 100</option>
                            <option value="150"> 150</option>
                            <option value="200"> 200</option>
                            <option value="300"> 300</option>
                            <option value="400"> 400</option>
                            <option value="500"> 500</option>
                            <option value="1000"> 1000</option>
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

            </fieldset>
    	</div>
    </div>
    
    <div class="formSubmit">
        {@SID_INPUT_TAG}
        <input type="hidden" name="packageID" value="{@PACKAGE_ID}" />
    	<input type="submit" name="send" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
    	<input type="reset" name="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
    </div>
</form>
{include file='footer'}