	{capture append=userMessages}
		{if $errorField}
			<p class="error">{lang}wcf.global.form.error{/lang}</p>
		{/if}
	{/capture}

	{include file="userCPHeader"}

    <form enctype="multipart/form-data" method="post" action="index.php?form=UserWantedPosterEdit{@SID_ARG_2ND}&amp;userID={$user->userID}">
        <div class="border tabMenuContent">
        	<div class="container-1">
                <h3 class="subHeadline">
                    <img src="{@RELATIVE_WCF_DIR}icon/userWantedPosterM.png" alt="" /> {lang}wcf.user.usercp.menu.link.profile.wantedPoster.edit{/lang}
                </h3>

        		{if $wantedPosterPreview}
        			<fieldset>
        				<legend>{lang}wcf.user.wantedPoster.preview{/lang}</legend>
        				<div>
        					{@$wantedPosterPreview}
        				</div>
        			</fieldset>
        		{/if}

                {if $tplList|isset && $tplList|count > 0}
                    <fieldset>
                    	<legend>{lang}wcf.user.wantedPoster.tplTitle{/lang}</legend>
                    	<div class="formFieldDesc">
                    		<p>{lang}wcf.user.wantedPoster.tplTitle.description{/lang}</p>
                    	</div>
                    	<div class="formElement" id="tplSelDiv">
                            <div class="formFieldLabel">
                            	<label for="tplID">{lang}wcf.user.wantedPoster.tplSelect{/lang}</label>
                            </div>
                            <div class="formField">
                                <select name="tplID" id="tplID" style="vertical-align:middle;">
                                    {foreach from=$tplList item=tpl}
                                        <option value="{$tpl.templateID}"> {@$tpl.templateName}</option>
                                    {/foreach}
                                </select>
                                <input type="submit" name="tplSelect" value="&nbsp;&raquo;&nbsp;" style="vertical-align:middle;" />
                                <span class="smallFont" style="vertical-align:middle;">({#$tplList|count})</span>
                            </div>
                        </div>
                    </fieldset>
                {/if}

                <fieldset>
                	<legend>{lang}wcf.user.wantedPoster.title{/lang}</legend>
                    {if $uwpData.lockDate|isset && $uwpData.locked == 1}
                        <p class="error">{lang}wcf.user.wantedPoster.lockedMessage{/lang}</p>
                    {/if}
                    <div class="formElement" id="editor">
                    	<div class="formField">
                    		<textarea name="text" id="text" rows="10" cols="40">{$text}</textarea>
                    		{if $errorField == 'text'}
                    			<p class="innerError">
                    				{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
                    				{if $errorType == 'tooLong'}{lang}wcf.message.error.tooLong{/lang}{/if}
                    				{if $errorType == 'censoredWordsFound'}{lang}wcf.message.error.censoredWordsFound{/lang}{/if}
                    			</p>
                    		{/if}
                    	</div>
                    </div>
                	{include file="messageFormTabs"}
                </fieldset>
                {if $additionalFields|isset}{@$additionalFields}{/if}
                <div class="formSubmit">
                	{@SID_INPUT_TAG}
                	<input type="submit" name="send" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
                	<input type="submit" name="preview" accesskey="p" value="{lang}wcf.global.button.preview{/lang}" />
                	<input type="reset" name="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
                	<input type="hidden" name="userID" value="{$user->userID}" />
                </div>
        	</div>
        </div>
    </form>
    <div class="smallButtons">
        <ul>
            {if $user->userID == $this->getUser()->userID || $this->user->getPermission('mod.wantedPoster.canLockEntries') || $this->user->getPermission('mod.wantedPoster.canDeleteEntries')}
                {if !$uwpData.userID|empty}
                    {if $this->user->getPermission('mod.wantedPoster.canDeleteEntries') || $user->userID == $this->getUser()->userID}<li><a href="javascript:void(0);" onclick="confirmDel('index.php?page=UserWantedPoster&action=delete&userID={$user->userID}{@SID_ARG_2ND}');"><img src="{@RELATIVE_WCF_DIR}icon/deleteS.png" alt="" /> <span>{lang}wcf.user.wantedPoster.delete{/lang}</span></a></li>{/if}
                    {if $this->user->getPermission('mod.wantedPoster.canLockEntries')}
                        {if $uwpData.locked|isset && $uwpData.locked == 1}
                            <li><a href="index.php?page=UserWantedPoster&action=unlock&userID={$user->userID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/disabledS.png" alt="" /> <span>{lang}wcf.user.wantedPoster.unlock{/lang}</span></a></li>
                        {else}
                            <li><a href="index.php?page=UserWantedPoster&action=lock&userID={$user->userID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/enabledS.png" alt="" /> <span>{lang}wcf.user.wantedPoster.lock{/lang}</span></a></li>
                        {/if}
                    {/if}
                {/if}
            {/if}
            {if $this->user->getPermission('user.wantedPoster.canViewMembersListTab')}
                <li><a href="index.php?page=UserWantedPosterListMembers{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/userWantedPosterListS.png" alt="" /> <span>{lang}wcf.user.wantedPoster.list.title{/lang}</span></a></li>
            {else}
                <li><a href="index.php?page=UserWantedPosterList{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/userWantedPosterListS.png" alt="" /> <span>{lang}wcf.user.wantedPoster.list.title{/lang}</span></a></li>
            {/if}
            <li><a href="index.php?page=UserWantedPoster&userID={$user->userID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/userWantedPosterS.png" alt="" /> <span>{lang}wcf.user.wantedPoster.title{/lang}</span></a></li>
        </ul>
    </div>
