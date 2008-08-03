{include file="documentHeader"}
<head>
	<title>{lang}wcf.user.guestbook.title{/lang} - {lang}wcf.user.profile.members{/lang} - {PAGE_TITLE}</title>
	{include file='headInclude' sandbox=false}
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/TabbedPane.class.js"></script>
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/ImageResizer.class.js"></script>
</head>
<body>
{* --- quick search controls --- *}
{assign var='searchFieldTitle' value='{lang}wcf.user.profile.search.query{/lang}'}
{capture assign=searchHiddenFields}
	<input type="hidden" name="userID" value="{@$user->userID}" />
{/capture}
{* --- end --- *}
{include file='header' sandbox=false}

<div id="main">
	<ul class="breadCrumbs">
		<li><a href="index.php?page=Index{@SID_ARG_2ND}"><img src="icon/indexS.png" alt="" /> <span>{PAGE_TITLE}</span></a> &raquo;</li>
		<li><img src="{@RELATIVE_WCF_DIR}icon/membersS.png" alt="" /> <span>{lang}wcf.user.profile.members{/lang}</span> &raquo;</li>
	</ul>

	<div class="mainHeadline">
		<img src="{@RELATIVE_WCF_DIR}icon/guestbookL.png" alt="" />
		<div class="headlineContainer">
			<h2> {lang}wcf.user.guestbook.title{/lang}</h2>
		</div>
	</div>

	{if $userMessages|isset}{@$userMessages}{/if}

	{if $errorField}
		<p class="error">{lang}wcf.global.form.error{/lang}</p>
	{/if}

	<form enctype="multipart/form-data" method="post" action="index.php?form=UserGuestbookNew{@SID_ARG_2ND}&amp;userID={$user->userID}">
		<div class="border content">
			<div class="container-1">
        		{if $action == 'comment'}
        			<fieldset>
        				<legend>{lang}wcf.user.guestbook.entry{/lang}</legend>
        				<div>
        					{@$entryTxt}
        				</div>
        			</fieldset>
                {/if}
        		{if !$guestbookPreview|empty}
        			<fieldset>
        				<legend>{lang}wcf.user.guestbook.preview{/lang}</legend>
        				<div>
        					{@$guestbookPreview}
        				</div>
        			</fieldset>
        		{/if}
				<fieldset>
					<legend>
					    {if $action == 'comment'}
					        {lang}wcf.user.guestbook.addComment{/lang}
					    {else if $action == 'edit'}
					        {lang}wcf.user.guestbook.editEntry{/lang}
					    {else}
					        {lang}wcf.user.guestbook.newEntry{/lang}
					    {/if}
					</legend>
                    <div class="formElement" id="editor">
                    	<div class="formField">
                    		{include file=wysiwyg}
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

				<div class="formSubmit">
					{@SID_INPUT_TAG}
					<input type="submit" name="send" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
					<input type="submit" name="preview" accesskey="p" value="{lang}wcf.global.button.preview{/lang}" />
					<input type="reset" name="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
					<input type="hidden" name="userID" value="{$user->userID}" />
					<input type="hidden" name="action" value="{$action}" />
					<input type="hidden" name="id" value="{$id}" />
				</div>
			</div>
		</div>
	</form>

    <div class="smallButtons">
        <ul>
            {if $this->user->getPermission('user.guestbook.canViewList')}
                {if $this->user->getPermission('user.guestbook.canViewMembersListTab')}
                    <li><a href="index.php?page=UserGuestbookListMembers{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/guestbookListS.png" alt="" /> <span>{lang}wcf.user.guestbook.list.title{/lang}</span></a></li>
                {else}
                    <li><a href="index.php?page=UserGuestbookList{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/guestbookListS.png" alt="" /> <span>{lang}wcf.user.guestbook.list.title{/lang}</span></a></li>
                {/if}
            {/if}
            {if $this->user->getPermission('user.guestbook.canUseOwn') && $this->user->userID}
                <li><a href="index.php?page=UserGuestbook&userID={$this->user->userID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/guestbookS.png" alt="" /> <span>{lang}wcf.user.guestbook.own{/lang}</span></a></li>
            {/if}
            {if $this->user->getPermission('user.guestbook.canRead') && $this->user->userID != $user->userID}
                <li><a href="index.php?page=UserGuestbook&userID={$user->userID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/guestbookS.png" alt="" /> <span>{lang}wcf.user.guestbook.title{/lang}</span></a></li>
            {/if}
        </ul>
    </div>

</div>


{include file='footer' sandbox=false}
</body>
</html>
