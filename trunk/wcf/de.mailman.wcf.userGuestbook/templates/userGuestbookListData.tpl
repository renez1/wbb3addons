{* $Id$ *}
    {if $this->user->getPermission('user.guestbook.canViewList') && $ugbData|isset && !$cntEntries|empty}
        <div class="border">
        	{cycle values='container-1,container-2' print=false advance=false}
        	{if $ugbData|count > 0}

                <table class="tableList membersList">
                	<thead>
                		<tr class="tableHead">
                			<th class="columnIcon{if $sortField == 'userID'} active{/if}">
                				<div><a href="index.php?page={$thisPage}&amp;sortField=userID&amp;sortOrder={if $sortField == 'userID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}">
                					<img src="{@RELATIVE_WCF_DIR}icon/guestbookS.png" alt="" />
                					{if $sortField == 'userID'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                				</a></div>
                			</th>
                			<th class="columnUsername{if $sortField == 'username'} active{/if}">
                				<div><a href="index.php?page={$thisPage}&amp;sortField=username&amp;sortOrder={if $sortField == 'username' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}">
                					{lang}wcf.user.guestbook.list.username{/lang}
                					{if $sortField == 'username'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                				</a></div>
                			</th>
                			{if $this->user->getPermission('user.guestbook.listView.showAvatar')}
                				<th class="columnAvatar{if $sortField == 'avatarID'} active{/if}">
                					<div><a href="index.php?page={$thisPage}&amp;sortField=avatarID&amp;sortOrder={if $sortField == 'avatarID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}">
                						{lang}wcf.user.guestbook.list.avatar{/lang}
                						{if $sortField == 'avatarID'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                					</a></div>
                				</th>
                		    {/if}
                			{if $this->user->getPermission('user.guestbook.listView.showUserLastVisit')}
                				<th class="columnLastActivity{if $sortField == 'userLastVisit'} active{/if}">
                					<div><a href="index.php?page={$thisPage}&amp;sortField=userLastVisit&amp;sortOrder={if $sortField == 'userLastVisit' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}">
                						{lang}wcf.user.guestbook.list.userLastVisit{/lang}
                						{if $sortField == 'userLastVisit'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                					</a></div>
                				</th>
                		    {/if}
                			{if $this->user->getPermission('user.guestbook.listView.showUserLastComment')}
                				<th class="columnLastActivity{if $sortField == 'userLastCommentTime'} active{/if}">
                					<div><a href="index.php?page={$thisPage}&amp;sortField=userLastCommentTime&amp;sortOrder={if $sortField == 'userLastCommentTime' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}">
                						{lang}wcf.user.guestbook.list.userLastComment{/lang}
                						{if $sortField == 'userLastCommentTime'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                					</a></div>
                				</th>
                		    {/if}
                		    {if $this->user->getPermission('user.guestbook.listView.showLastEntryUser')}
                			    <th class="columnUsername{if $sortField == 'lastEntryUser'} active{/if}">
                					<div><a href="index.php?page={$thisPage}&amp;sortField=lastEntryUser&amp;sortOrder={if $sortField == 'lastEntryUser' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}">
                						{lang}wcf.user.guestbook.list.lastEntryUser{/lang}
                						{if $sortField == 'lastEntryUser'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                					</a></div>
                				</th>
                		    {/if}
                		    {if $this->user->getPermission('user.guestbook.listView.showLastEntryDate')}
                				<th class="columnLastActivity{if $sortField == 'lastEntry'} active{/if}" style="width:160px;">
                					<div><a href="index.php?page={$thisPage}&amp;sortField=lastEntry&amp;sortOrder={if $sortField == 'lastEntry' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}">
                						{lang}wcf.user.guestbook.list.lastEntry{/lang}
                						{if $sortField == 'lastEntry'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                					</a></div>
                				</th>
                			{/if}
                			{if $this->user->getPermission('user.guestbook.listView.showNumOfEntries')}
                				<th class="columnNumbers{if $sortField == 'entries'} active{/if}">
                					<div><a href="index.php?page={$thisPage}&amp;sortField=entries&amp;sortOrder={if $sortField == 'entries' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}">
                						{lang}wcf.user.guestbook.list.entries{/lang}
                						{if $sortField == 'entries'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                					</a></div>
                				</th>
                			{/if}
                			{if $this->user->getPermission('user.guestbook.listView.showNumOfNewEntries')}
                				<th class="columnNumbers{if $sortField == 'newEntries'} active{/if}">
                					<div><a href="index.php?page={$thisPage}&amp;sortField=newEntries&amp;sortOrder={if $sortField == 'newEntries' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}">
                						{lang}wcf.user.guestbook.list.newEntries{/lang}
                						{if $sortField == 'newEntries'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                					</a></div>
                				</th>
                			{/if}
                			{if $this->user->getPermission('user.guestbook.listView.showNumOfViews')}
                				<th class="columnNumbers{if $sortField == 'views'} active{/if}">
                					<div><a href="index.php?page={$thisPage}&amp;sortField=views&amp;sortOrder={if $sortField == 'views' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}">
                						{lang}wcf.user.guestbook.list.views{/lang}
                						{if $sortField == 'views'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                					</a></div>
                				</th>
                			{/if}
                		    {if $this->user->getPermission('user.guestbook.listView.showLastVisitor')}
                			    <th class="columnUsername{if $sortField == 'lastVisitor'} active{/if}">
                					<div><a href="index.php?page={$thisPage}&amp;sortField=lastVisitor&amp;sortOrder={if $sortField == 'lastVisitor' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}">
                						{lang}wcf.user.guestbook.list.lastVisitor{/lang}
                						{if $sortField == 'lastVisitor'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                					</a></div>
                				</th>
                		    {/if}
                		    {if $this->user->getPermission('user.guestbook.listView.showLastVisitDate')}
                				<th class="columnLastActivity{if $sortField == 'visitorLastVisit'} active{/if}">
                					<div><a href="index.php?page={$thisPage}&amp;sortField=visitorLastVisit&amp;sortOrder={if $sortField == 'visitorLastVisit' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}">
                						{lang}wcf.user.guestbook.list.visitorLastVisit{/lang}
                						{if $sortField == 'visitorLastVisit'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                					</a></div>
                				</th>
                			{/if}
                		    {if $this->user->getPermission('mod.guestbook.canLock')}
                				<th class="columnIcon{if $sortField == 'locked'} active{/if}">
                					<div><a href="index.php?page={$thisPage}&amp;sortField=locked&amp;sortOrder={if $sortField == 'locked' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}">
                						<img src="{@RELATIVE_WCF_DIR}icon/enabledS.png" alt="" />
                						{if $sortField == 'locked'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                					</a></div>
                				</th>
                			{/if}

                			{if $additionalColumns|isset}{@$additionalColumns}{/if}
                		</tr>
                	</thead>
                	<tbody>
                		{foreach from=$ugbData item=user}
                			<tr class="{cycle}">
                				<td class="columnIcon">
                				    {if $this->user->getPermission('user.guestbook.canRead')}
                				        <a href="index.php?page=UserGuestbook&userID={$user.userID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/guestbookLinkM.png" alt="" /></a>
                				    {else}
                					    <img src="{@RELATIVE_WCF_DIR}icon/guestbookM.png" alt="" />
                				    {/if}
                				</td>
                				<td class="columnUsername">{if $user.curUserEntry}<div class="smallPages"><img src="{@RELATIVE_WCF_DIR}icon/userS.png" title="{lang}wcf.user.guestbook.list.ownEntries{/lang}" alt="" /></div>{/if}{@$user.username}</td>
                				{if $this->user->getPermission('user.guestbook.listView.showAvatar')}
                				    <td class="columnAvatar">{@$user.avatar}</td>
                				{/if}
                				{if $this->user->getPermission('user.guestbook.listView.showUserLastVisit')}
                				    <td class="columnLastActivity">{if $user.userLastVisit}{@$user.userLastVisit|shorttime}{else}-{/if}</td>
                				{/if}
                				{if $this->user->getPermission('user.guestbook.listView.showUserLastComment')}
                				    <td class="columnLastActivity">{if $user.userLastCommentTime}{@$user.userLastCommentTime|shorttime}{else}-{/if}</td>
                				{/if}
                				{if $this->user->getPermission('user.guestbook.listView.showLastEntryUser')}
                					<td class="columnUsername">{if $user.lastEntryUserID}<a href="index.php?page=User&userID={$user.lastEntryUserID}{@SID_ARG_2ND}">{@$user.lastEntryUser}</a>{else}{@$user.lastEntryUser}{/if}</td>
                				{/if}
                				{if $this->user->getPermission('user.guestbook.listView.showLastEntryDate')}
                					<td class="columnLastActivity">{if $user.lastEntry}{@$user.lastEntry|shorttime}{else}-{/if}</td>
                				{/if}
                				{if $this->user->getPermission('user.guestbook.listView.showNumOfEntries')}
                					<td class="columnNumbers">{@$user.entries}</td>
                				{/if}
                				{if $this->user->getPermission('user.guestbook.listView.showNumOfNewEntries')}
                					<td class="columnNumbers">{@$user.newEntries}</td>
                				{/if}
                				{if $this->user->getPermission('user.guestbook.listView.showNumOfViews')}
                					<td class="columnNumbers">{@$user.views}</td>
                				{/if}
                				{if $this->user->getPermission('user.guestbook.listView.showLastVisitor')}
                					<td class="columnUsername">{if $user.lastVisitorID}<a href="index.php?page=User&userID={$user.lastVisitorID}{@SID_ARG_2ND}">{@$user.lastVisitor}</a>{else}-{/if}</td>
                				{/if}
                				{if $this->user->getPermission('user.guestbook.listView.showLastVisitDate')}
                					<td class="columnLastActivity">{if $user.visitorLastVisit}{@$user.visitorLastVisit|shorttime}{else}-{/if}</td>
                				{/if}
                				{if $this->user->getPermission('mod.guestbook.canLock')}
                					<td class="columnIcon">
                					    {if $user.locked}
                					        <a href="index.php?page=UserGuestbook&userID={$user.userID}&action=unlock{@SID_ARG_2ND}" title="{lang}wcf.user.guestbook.unlock{/lang}"><img src="{@RELATIVE_WCF_DIR}icon/disabledS.png" alt="" /></a>
                					    {else}
                					        <a href="index.php?page=UserGuestbook&userID={$user.userID}&action=lock{@SID_ARG_2ND}" title="{lang}wcf.user.guestbook.lock{/lang}"><img src="{@RELATIVE_WCF_DIR}icon/enabledS.png" alt="" /></a>
                					    {/if}
                					</td>
                				{/if}

                			    {if $user.additionalColumns|isset}{@$user.additionalColumns}{/if}
                			</tr>
                		{/foreach}
                	</tbody>
                </table>
        	{/if}
        </div>
    {else if $this->user->getPermission('user.guestbook.canViewList') && $cntEntries|empty}
		<div class="border tabMenuContent">
			<div class="container-1">
				{lang}wcf.user.guestbook.list.empty{/lang}
			</div>
		</div>
    {/if}

    <div class="contentFooter">
        {if !$pagesLinks|isset}
            {pages print=true assign=pagesLinks link="index.php?page=$thisPage&pageNo=%d&sortField=$sortField&sortOrder=$sortOrder"}
        {else}
            {@$pagesLinks}
        {/if}
    	{if $this->user->getPermission('user.guestbook.canUseOwn')}
            <div class="largeButtons">
                <ul>
    			    <li><a href="index.php?page=UserGuestbook&userID={$this->user->userID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/guestbookM.png" alt="" /> <span>{lang}wcf.user.guestbook.own{/lang}</span></a></li>
    			</ul>
    	    </div>
    	{/if}
    </div>
