{include file='header'}
{include file='Wysiwyg'}
{* $Id$ *}
	<script language="javascript">
    //<![CDATA[
        function confirmTplDel(fName) {
            if(document.forms[fName].elements['deleteTemplate'] && document.forms[fName].elements['deleteTemplate'].checked) {
    			if(confirm('{lang}wcf.acp.wantedPoster.confirmDelete{/lang}')) return true;
    			else return false;
    	    } else {
    	        return true;
    	    }
		}
    //]]>
	</script>

<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/userWantedPosterL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.wantedPoster.title{/lang}</h2>
	</div>
</div>

{if !$errorField|empty}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{else if !$error|empty}
	<p class="error">{@$error}</p>
{else if !$success|empty}
	<p class="success">{@$success}</p>
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

<div class="border content">
    <div class="container-1">
        <form method="post" action="index.php?form=UserWantedPosterAcp" name="selectTemplate">
            <fieldset>
            	<legend>{lang}wcf.acp.wantedPoster.tplSelection{/lang}</legend>
            	<div class="formElement" id="tplSelDiv">
                    <div class="formFieldLabel">
                    	<label for="tplID">{lang}wcf.acp.wantedPoster.tplSelTitle{/lang}</label>
                    </div>
                    <div class="formField">
                        <select name="tplID" id="tplID" style="vertical-align:middle;" onChange="this.form.submit()">
                            <option value="0"> {lang}wcf.acp.wantedPoster.tplCreate{/lang}</option>
                            {foreach from=$tplList item=tpl}
                                <option value="{$tpl.templateID}"{if $tpl.templateID == $tplID} selected="selected"{/if}> {@$tpl.templateName}</option>
                            {/foreach}
                        </select>
                        <input class="inputImage" type="image" src="{@RELATIVE_WCF_DIR}icon/submitS.png" align="absmiddle" alt="" style="width:16px; height:16px;" />
                        <span class="smallFont" style="vertical-align:middle;">({#$tplCount})</span>
                    </div>
                </div>
            </fieldset>
            {@SID_INPUT_TAG}
        </form>

        <form method="post" action="index.php?form=UserWantedPosterAcp" name="uwpTemplate" onSubmit="return confirmTplDel(this.name)">
            <fieldset>
            	<legend>{lang}wcf.acp.wantedPoster.tplOptions{/lang}</legend>
                {if !$tplID|empty}
                    <div class="formElement" id="tplCreatedDiv">
                        <div class="formFieldLabel"><label for="tplCreated">{lang}wcf.acp.wantedPoster.tplCreated{/lang}</label></div>
                        <div class="formField" id="tplCreated">{if $tplData.insertDate|isset}{@$tplData.insertDate|time} &bull; {@$tplData.IUser}{/if}</div>
                    </div>
                    <div class="formElement" id="tplModifiedDiv">
                        <div class="formFieldLabel"><label for="tplModified">{lang}wcf.acp.wantedPoster.tplModified{/lang}</label></div>
                        <div class="formField" id="tplModified">{if $tplData.updateDate|isset}{@$tplData.updateDate|time} &bull; {@$tplData.UUser}{/if}</div>
                    </div>
                {/if}

                <div class="formElement" id="enableTplDiv">
                    <div class="formFieldLabel">
                    	<label for="enabled">&nbsp;</label>
                    </div>
                    <div class="formField{if $enabled|empty} warning{else} success{/if}">
                    	<input type="checkbox" name="enabled" id="enabled" value="1"{if !$enabled|empty} checked="checked"{/if}> {lang}wcf.acp.wantedPoster.tplEnable{/lang}
                    </div>
                </div>

                {if !$tplData.templateID|empty}
                    <div class="formElement" id="delTplDiv">
                        <div class="formFieldLabel">
                        	<label for="deleteTemplate">&nbsp;</label>
                        </div>
                        <div class="formField error">
                        	<input type="checkbox" name="deleteTemplate" id="deleteTemplate" value="1"> {lang}wcf.acp.wantedPoster.tplDelete{/lang}
                        </div>
                    </div>
                {/if}
                <div class="formElement" id="subjectDiv">
                    <div class="formFieldLabel">
                    	<label for="subject">{lang}wcf.acp.wantedPoster.tplName{/lang}</label>
                    </div>
                    <div class="formField">
                    	<input type="text" class="inputText" name="subject" id="subject" value="{if !$subject|empty}{@$subject}{/if}" />
                    	{if $errorField == 'subject'}
                    		<p class="innerError">
                    			{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
                    			{if $errorType == 'exists'}{lang}wcf.acp.wantedPoster.errTplNameExists{/lang}{/if}
                    		</p>
                    	{/if}
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend>{lang}wcf.acp.wantedPoster.template{/lang}</legend>
            	<div class="formElement{if $errorField == 'text'} formError{/if}" id="editor">
                    <div class="formFieldLabel">
                    	<label for="text">{lang}wcf.acp.wantedPoster.tplContent{/lang}</label>
                    </div>
                    <div class="formField">
                    	<textarea name="text" id="text" rows="15" cols="40">{if !$text|empty}{$text}{/if}</textarea>
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
            <div class="formSubmit">
                {@SID_INPUT_TAG}
                <input type="hidden" name="packageID" value="{@PACKAGE_ID}" />
                <input type="submit" name="send" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
                <input type="submit" name="preview" accesskey="p" value="{lang}wcf.global.button.preview{/lang}" />
                <input type="reset" name="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
            	<input type="hidden" name="tplID" value="{$tplID}" />
            	<input type="hidden" name="fDo" value="mod" />
            </div>
        </form>
    </div>
</div>
{include file='footer'}
