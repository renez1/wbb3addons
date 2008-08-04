{include file="documentHeader"}
<head>
	<title>{lang}wcf.user.guestbook.title{/lang} - {lang}wcf.user.profile.members{/lang} - {PAGE_TITLE}</title>
	{include file='headInclude' sandbox=false}
    <script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/MultiPagesLinks.class.js"></script>
    <script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/ImageResizer.class.js"></script>
	<script language="javascript">
        function confirmDel(url) {
			if(confirm('{lang}wcf.user.guestbook.confirmDelete{/lang}')) window.location=url;
		}
        function confirmDelComment(url) {
			if(confirm('{lang}wcf.user.guestbook.confirmDeleteComment{/lang}')) window.location=url;
		}
	</script>
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
    		<h2>{lang}wcf.user.guestbook.title{/lang}</h2>
    		{if $user->getOldUsername()}
    			<p>{lang}wcf.user.profile.oldUsername{/lang}</p>
    		{/if}
    	</div>
    </div>

    {if $userMessages|isset}{@$userMessages}{/if}
    {if !$locked.locked|empty}<p class="error">{lang}wcf.user.guestbook.lockMsg{/lang}</p>{/if}

    {if $this|method_exists:'getUserProfileMenu'}
    	{if $this->getUserProfileMenu()->getMenuItems('')|count > 1}
    		<div class="tabMenu">
    			<ul>
    				{foreach from=$this->getUserProfileMenu()->getMenuItems('') item=item}
    					<li{if $item.menuItem|in_array:$this->getUserProfileMenu()->getActiveMenuItems()} class="activeTabMenu"{/if}><a href="{$item.menuItemLink}">{if $item.menuItemIcon}<img src="{$item.menuItemIcon}" alt="" /> {/if}<span>{lang}{@$item.menuItem}{/lang}</span></a></li>
    				{/foreach}
    			</ul>
    		</div>

    		<div class="subTabMenu">
    			<div class="containerHead">
    				{assign var=activeMenuItem value=$this->getUserProfileMenu()->getActiveMenuItem()}
    				{if $activeMenuItem && $this->getUserProfileMenu()->getMenuItems($activeMenuItem)|count}
    					<ul>
    						{foreach from=$this->getUserProfileMenu()->getMenuItems($activeMenuItem) item=item}
    							<li{if $item.menuItem|in_array:$this->getUserProfileMenu()->getActiveMenuItems()} class="activeSubTabMenu"{/if}><a href="{$item.menuItemLink}">{if $item.menuItemIcon}<img src="{$item.menuItemIcon}" alt="" /> {/if}<span>{lang}{@$item.menuItem}{/lang}</span></a></li>
    						{/foreach}
    					</ul>
    				{else}
    					<div> </div>
    				{/if}
    			</div>
    		</div>
    	{/if}
    {/if}

    {if $locked.locked|empty || $this->user->getPermission('mod.guestbook.canLock') || $this->user->userID == $user->userID}
        <div class="border {if $this|method_exists:'getUserProfileMenu' && $this->getUserProfileMenu()->getMenuItems('')|count > 1}tabMenuContent{else}content{/if}">
            <div class="contentHeader">
                <table style="width:100%;">
                    <tr>
                        <td class="light smallFont">{lang}wcf.user.guestbook.header{/lang}</td>
                        {if $user->userGuestbook_enable_posting && $this->user->getPermission('user.guestbook.canWrite')}
                            <td style="text-align:right; padding-right:52px;">
                                <div class="largeButtons">
                                    <ul>
                                        <li><a href="index.php?form=UserGuestbookNew&userID={$user->userID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/guestbookNewM.png" alt="" /><span>{lang}wcf.user.guestbook.newEntry{/lang}</span></a></li>
                                    </ul>
                                </div>
                            </td>
                        {/if}
                    </tr>
                </table>
            </div>

            {if !$cntEntries|empty}
                {cycle values='container-1,container-2' print=false advance=false}
                {foreach from=$gbData item=entry}
                    <div class="message content">
                        <div class="messageInner content">
                            <fieldset class="{cycle}" style="margin-top:10px;">
                                <legend>
                                    {if USERGUESTBOOK_SHOWENTRYIMAGES}<img src="{@RELATIVE_WCF_DIR}icon/guestbookEntryS.png" alt="" /> {/if}
                                    {if USERGUESTBOOK_SHOWREPLYLINK && $this->user->getPermission('user.guestbook.canWrite')}
                                        {if $entry.username && $entry.fuIsEnabled && !$entry.fuIsLocked && $entry.fuCanUseOwn}
                                            <a href="index.php?form=UserGuestbookNew&userID={$entry.fromUserID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/guestbookReplyS.png" alt="" title="{lang}wcf.user.guestbook.ttReply{/lang}" /></a>
                                        {else}
                                            <img src="{@RELATIVE_WCF_DIR}icon/guestbookReplyDisabledS.png" alt="" title="{lang}wcf.user.guestbook.ttNoReply{/lang}" />
                                        {/if}
                                    {/if}
                                    {if USERGUESTBOOK_SHOWGUESTBOOKLINK && $this->user->getPermission('user.guestbook.canRead')}
                                        {if $entry.username && $entry.fuIsEnabled && !$entry.fuIsLocked}
                                            <a href="index.php?page=UserGuestbook&userID={$entry.fromUserID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/guestbookLinkS.png" alt="" title="{lang}wcf.user.guestbook.ttGuestbook{/lang}" /></a>
                                        {else}
                                            <img src="{@RELATIVE_WCF_DIR}icon/guestbookLinkDisabledS.png" alt="" title="{lang}wcf.user.guestbook.ttNoReply{/lang}" />
                                        {/if}
                                    {/if}
                                    {@$entry.entryTime|time} {lang}wcf.user.guestbook.fromUser{/lang}
                                </legend>
                                {if $entry.updateUser}
                                    <div class="light smallFont">{lang}wcf.user.guestbook.lastEdit{/lang}</div>
                                {/if}

                                <div class="messageBody">
                                	{@$entry.text}<br>
                                	{if $entry.comment}
                                	    <fieldset>
                                	        <legend><img src="{@RELATIVE_WCF_DIR}icon/guestbookQuoteS.png" alt="" /> {lang}wcf.user.guestbook.commentTitle{/lang}</legend>
                                	        <div>{@$entry.comment}</div>
                                	    </fieldset>
                                	{/if}
                                </div>
                                {if $entry.permDelete || $entry.permEdit || $entry.permComment}
                                    <div class="messageFooter">
                                        <div class="smallButtons">
                                        	<ul>
                                        	    {if $entry.permDelete}
                                                	<li><a href="javascript:void(0);" onClick="confirmDel('index.php?page=UserGuestbook&action=delete&userID={$user->userID}&id={@$entry.id}');"><img src="{@RELATIVE_WCF_DIR}icon/deleteS.png" alt="" /> <span>{lang}wcf.user.guestbook.deleteEntry{/lang}</span></a></li>
                                                {/if}
                                                {if $entry.permEdit}
                                                	<li><a href="index.php?form=UserGuestbookNew&action=edit&userID={$user->userID}&id={@$entry.id}"><img src="{@RELATIVE_WCF_DIR}icon/editS.png" alt="" /> <span>{lang}wcf.user.guestbook.editEntry{/lang}</span></a></li>
                                                {/if}
                                                {if $entry.permComment}
                                                    {if $entry.comment}
                                                    	<li><a href="javascript:void(0);" onClick="confirmDelComment('index.php?page=UserGuestbook&action=deleteComment&userID={$user->userID}&id={@$entry.id}');"><img src="{@RELATIVE_WCF_DIR}icon/guestbookQuoteDeleteS.png" alt="" /> <span>{lang}wcf.user.guestbook.deleteComment{/lang}</span></a></li>
                                                    	<li><a href="index.php?form=UserGuestbookNew&action=comment&userID={$user->userID}&id={@$entry.id}"><img src="{@RELATIVE_WCF_DIR}icon/guestbookQuoteEditS.png" alt="" /> <span>{lang}wcf.user.guestbook.editComment{/lang}</span></a></li>
                                                    {else}
                                                    	<li><a href="index.php?form=UserGuestbookNew&action=comment&userID={$user->userID}&id={@$entry.id}"><img src="{@RELATIVE_WCF_DIR}icon/guestbookQuoteS.png" alt="" /> <span>{lang}wcf.user.guestbook.addComment{/lang}</span></a></li>
                                                    {/if}
                                                {/if}
                                        	</ul>
                                        </div>
                                    </div>
                                {/if}
                            </fieldset>
                        </div>
                    </div>
                {/foreach}
            {else}
                <div class="contentHeader">
                    <p>{lang}wcf.user.guestbook.noEntry{/lang}</p>
                </div>
            {/if}
            {if !$cntEntries|empty && $user->userGuestbook_enable_posting && $this->user->getPermission('user.guestbook.canWrite')}
                <div class="contentFooter">
                    {pages print=true assign=pagesLinks link="index.php?page=UserGuestbook&pageNo=%d&userID=$userID"}
                </div>
            {/if}
        </div>
    {/if}
    <div class="smallButtons">
        <ul>
            {if $this->user->getPermission('user.guestbook.canViewList')}
                {if $this->user->getPermission('user.guestbook.canViewMembersListTab')}
                    <li><a href="index.php?page=UserGuestbookListMembers{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/guestbookListS.png" alt="" /> <span>{lang}wcf.user.guestbook.list.title{/lang}</span></a></li>
                {else}
                    <li><a href="index.php?page=UserGuestbookList{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/guestbookListS.png" alt="" /> <span>{lang}wcf.user.guestbook.list.title{/lang}</span></a></li>
                {/if}
            {/if}
            {if $this->user->getPermission('user.guestbook.canUseOwn') && $this->user->userID != $user->userID}
                <li><a href="index.php?page=UserGuestbook&userID={$this->user->userID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/guestbookS.png" alt="" /> <span>{lang}wcf.user.guestbook.own{/lang}</span></a></li>
            {/if}
            {if $this->user->getPermission('mod.guestbook.canLock')}
                {if $locked.locked|empty}
                    <li><a href="index.php?page=UserGuestbook&userID={$user->userID}&action=lock{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/enabledS.png" alt="" /> <span>{lang}wcf.user.guestbook.lock{/lang}</span></a></li>
                {else}
                    <li><a href="index.php?page=UserGuestbook&userID={$user->userID}&action=unlock{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/disabledS.png" alt="" /> <span>{lang}wcf.user.guestbook.unlock{/lang}</span></a></li>
                {/if}
            {/if}
        </ul>
    </div>
</div>
{include file='footer' sandbox=false}
</body>
</html>
