    {* $Id$ *}
    {if $this->user->getPermission('user.board.canViewUsersOnlineBox')}
        <div class="border" id="box{$boxID}">
            <div class="containerHead">
                {if !USERSONLINEBOX_BOXOPENED}
                    <div class="containerIcon">
                        <a href="javascript: void(0)" onclick="openList('usersOnlineBox', true)"><img src="{@RELATIVE_WCF_DIR}icon/minusS.png" id="usersOnlineBoxImage" alt="" /></a>
                    </div>
                {/if}
                <div class="containerContent"><a href="index.php?page=UsersOnline{@SID_ARG_2ND}">{lang}wbb.portal.box.usersOnlineBox.title{/lang}</a><a name="usersOnlineBoxTgt"></a>{if USERSONLINEBOX_SHOWNUMOFUSERNEXTTITLE} {lang}wbb.portal.box.usersOnlineBox.title.numOfUser{/lang}{/if}</div>
            </div>
            <div class="container-1" id="usersOnlineBox">
                <div class="containerContent" style="padding-right:5px; padding-bottom:5px;">
                    {if $users|count > 0}
                        <div class="smallFont">
                            {if USERSONLINEBOX_SHOWLEGEND && $usersOnlineMarkings|count > 0}
                                <div style="float:right;">{lang}wcf.usersOnline.marking.legend{/lang} {implode from=$usersOnlineMarkings item=usersOnlineMarking}{@$usersOnlineMarking}{/implode}</div>
                            {/if}
                            {if !USERSONLINEBOX_SHOWNUMOFUSERNEXTTITLE}{lang}wcf.usersOnline.members{/lang}{/if}
                        </div>
                    {/if}

                    {if USERSONLINEBOX_MAXHEIGHT > 0}<div style="width:100%; max-height:{USERSONLINEBOX_MAXHEIGHT}px; overflow-y:auto; overflow-x:hidden;">{/if}
                    {cycle values='container-1,container-2' print=false advance=false}

                    <!-- USERs ************************* -->
                    {if $users|count > 0}
                        {cycle reset=true advance=false print=false}
                        <table class="tableList border">
                            <thead>
                                <tr class="tableHead">
                                    <th class="columnUsername{if $sortField == 'username'} active{/if}">
                                        <div><a href="index.php?page=Portal&amp;sortField=username&amp;sortOrder={if $sortField == 'username' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;detailedSpiderList={@$detailedSpiderList}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}#usersOnlineBoxTgt">
                                            {lang}wcf.usersOnline.username{/lang}
                                            {if $sortField == 'username'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                                        </a></div>
                                    </th>
                                    {if $canViewIpAddress}
                                        <th class="columnIP{if $sortField == 'ipAddress'} active{/if}">
                                            <div><a href="index.php?page=Portal&amp;sortField=ipAddress&amp;sortOrder={if $sortField == 'ipAddress' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;detailedSpiderList={@$detailedSpiderList}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}#usersOnlineBoxTgt">
                                                {lang}wcf.usersOnline.ipAddress{/lang}
                                                {if $sortField == 'ipAddress'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                                            </a></div>
                                        </th>
                                        <th class="columnUserAgent{if $sortField == 'userAgent'} active{/if}">
                                            <div><a href="index.php?page=Portal&amp;sortField=userAgent&amp;sortOrder={if $sortField == 'userAgent' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;detailedSpiderList={@$detailedSpiderList}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}#usersOnlineBoxTgt">
                                                {lang}wcf.usersOnline.userAgent{/lang}
                                                {if $sortField == 'userAgent'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                                            </a></div>
                                        </th>
                                    {/if}
                                    <th class="columnLastActivity{if $sortField == 'lastActivityTime'} active{/if}">
                                        <div><a href="index.php?page=Portal&amp;sortField=lastActivityTime&amp;sortOrder={if $sortField == 'lastActivityTime' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;detailedSpiderList={@$detailedSpiderList}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}#usersOnlineBoxTgt">
                                            {lang}wcf.usersOnline.lastActivity{/lang}
                                            {if $sortField == 'lastActivityTime'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                                        </a></div>
                                    </th>
                                    <th class="columnLocation{if $sortField == 'requestURI'} active{/if}">
                                        <div><a href="index.php?page=Portal&amp;sortField=requestURI&amp;sortOrder={if $sortField == 'requestURI' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;detailedSpiderList={@$detailedSpiderList}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}#usersOnlineBoxTgt">
                                            {lang}wcf.usersOnline.location{/lang}
                                            {if $sortField == 'requestURI'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                                        </a></div>
                                    </th>

                                    {if $additionalColumns|isset}{@$additionalColumns}{/if}
                                </tr>
                            </thead>
                            <tbody>
                                {foreach from=$users item=user}
                                    <tr class="{cycle}">
                                        <td class="columnUsername" style="width: 20%"><p><a href="index.php?page=User&amp;userID={@$user.userID}{@SID_ARG_2ND}">{@$user.username}</a></p></td>
                                        {if $canViewIpAddress}
                                            <td class="columnIP"><p>{$user.ipAddress}</p></td>
                                            <td class="columnUserAgent" style="width: 30%"><p>{$user.userAgent}</p></td>
                                        {/if}
                                        <td class="columnLastActivity"><p>{@$user.lastActivityTime|shorttime}</p></td>
                                        <td class="columnLocation" style="width: {if $canViewIpAddress}50{else}80{/if}%;"><p>{@$user.location}</p></td>

                                        {if $user.additionalColumns|isset}{@$user.additionalColumns}{/if}
                                    </tr>
                                {/foreach}
                            </tbody>
                        </table>

                    {/if}

                    <!-- GUESTs ************************ -->
                    {if $guests|count > 0}
                        {cycle reset=true advance=false print=false}
                        <h3>{lang}wcf.usersOnline.guests{/lang}</h3>
                        <table class="tableList border">
                            <thead>
                                <tr class="tableHead">
                                    <th class="columnUsername{if $sortField == 'username'} active{/if}">
                                        <div><a href="index.php?page=Portal&amp;sortField=username&amp;sortOrder={if $sortField == 'username' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;detailedSpiderList={@$detailedSpiderList}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}#usersOnlineBoxTgt">
                                            {lang}wcf.usersOnline.guestname{/lang}
                                            {if $sortField == 'username'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                                        </a></div>
                                    </th>
                                    {if $canViewIpAddress}
                                        <th class="columnIP{if $sortField == 'ipAddress'} active{/if}">
                                            <div><a href="index.php?page=Portal&amp;sortField=ipAddress&amp;sortOrder={if $sortField == 'ipAddress' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;detailedSpiderList={@$detailedSpiderList}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}#usersOnlineBoxTgt">
                                                {lang}wcf.usersOnline.ipAddress{/lang}
                                                {if $sortField == 'ipAddress'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                                            </a></div>
                                        </th>
                                        <th class="columnUserAgent{if $sortField == 'userAgent'} active{/if}">
                                            <div><a href="index.php?page=Portal&amp;sortField=userAgent&amp;sortOrder={if $sortField == 'userAgent' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;detailedSpiderList={@$detailedSpiderList}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}#usersOnlineBoxTgt">
                                                {lang}wcf.usersOnline.userAgent{/lang}
                                                {if $sortField == 'userAgent'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                                            </a></div>
                                        </th>
                                    {/if}
                                    <th class="columnLastActivity{if $sortField == 'lastActivityTime'} active{/if}">
                                        <div><a href="index.php?page=Portal&amp;sortField=lastActivityTime&amp;sortOrder={if $sortField == 'lastActivityTime' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;detailedSpiderList={@$detailedSpiderList}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}#usersOnlineBoxTgt">
                                            {lang}wcf.usersOnline.lastActivity{/lang}
                                            {if $sortField == 'lastActivityTime'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                                        </a></div>
                                    </th>
                                    <th class="columnLocation{if $sortField == 'requestURI'} active{/if}">
                                        <div><a href="index.php?page=Portal&amp;sortField=requestURI&amp;sortOrder={if $sortField == 'requestURI' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;detailedSpiderList={@$detailedSpiderList}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}#usersOnlineBoxTgt">
                                            {lang}wcf.usersOnline.location{/lang}
                                            {if $sortField == 'requestURI'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                                        </a></div>
                                    </th>

                                    {if $additionalColumns|isset}{@$additionalColumns}{/if}
                                </tr>
                            </thead>
                            <tbody>
                                {foreach from=$guests item=guest}
                                    <tr class="{cycle}">
                                        <td class="columnUsername" style="width: 20%"><p>{$guest.guestname}</p></td>
                                        {if $canViewIpAddress}
                                            <td class="columnIP"><p>{$guest.ipAddress}</p></td>
                                            <td class="columnUserAgent" style="width: 30%"><p>{$guest.userAgent}</p></td>
                                        {/if}
                                        <td class="columnLastActivity"><p>{@$guest.lastActivityTime|shorttime}</p></td>
                                        <td class="columnLocation" style="width: {if $canViewIpAddress}50{else}80{/if}%"><p>{@$guest.location}</p></td>

                                        {if $guest.additionalColumns|isset}{@$guest.additionalColumns}{/if}
                                    </tr>
                                {/foreach}
                            </tbody>
                        </table>
                    {/if}

                    <!-- SPIDERs *********************** -->
                    {if $spiders|count > 0}
                        {cycle reset=true advance=false print=false}
                        <h3>{lang}wcf.usersOnline.spiders{/lang}</h3>
                        <table class="tableList border">
                            <thead>
                                <tr class="tableHead">
                                    <th class="columnUsername{if $sortField == 'username'} active{/if}">
                                        <div><a href="index.php?page=Portal&amp;sortField=username&amp;sortOrder={if $sortField == 'username' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;detailedSpiderList={@$detailedSpiderList}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}#usersOnlineBoxTgt">
                                            {lang}wcf.usersOnline.spiderName{/lang}
                                            {if $sortField == 'username'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                                        </a></div>
                                    </th>
                                    {if $canViewIpAddress}
                                        <th class="columnLastActivity{if $sortField == 'ipAddress'} active{/if}">
                                            <div><a href="index.php?page=Portal&amp;sortField=ipAddress&amp;sortOrder={if $sortField == 'ipAddress' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;detailedSpiderList={@$detailedSpiderList}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}#usersOnlineBoxTgt">
                                                {lang}wcf.usersOnline.ipAddress{/lang}
                                                {if $sortField == 'ipAddress'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                                            </a></div>
                                        </th>
                                        <th class="columnUserAgent{if $sortField == 'userAgent'} active{/if}">
                                            <div><a href="index.php?page=Portal&amp;sortField=userAgent&amp;sortOrder={if $sortField == 'userAgent' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;detailedSpiderList={@$detailedSpiderList}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}#usersOnlineBoxTgt">
                                                {lang}wcf.usersOnline.userAgent{/lang}
                                                {if $sortField == 'userAgent'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                                            </a></div>
                                        </th>
                                    {/if}
                                    <th class="columnLastActivity{if $sortField == 'lastActivityTime'} active{/if}">
                                        <div><a href="index.php?page=Portal&amp;sortField=lastActivityTime&amp;sortOrder={if $sortField == 'lastActivityTime' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;detailedSpiderList={@$detailedSpiderList}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}#usersOnlineBoxTgt">
                                            {lang}wcf.usersOnline.lastActivity{/lang}
                                            {if $sortField == 'lastActivityTime'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                                        </a></div>
                                    </th>
                                    <th class="columnLocation{if $sortField == 'requestURI'} active{/if}">
                                        <div><a href="index.php?page=Portal&amp;sortField=requestURI&amp;sortOrder={if $sortField == 'requestURI' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;detailedSpiderList={@$detailedSpiderList}{if $additionalParameters|isset}{@$additionalParameters}{/if}{@SID_ARG_2ND}#usersOnlineBoxTgt">
                                            {lang}wcf.usersOnline.location{/lang}
                                            {if $sortField == 'requestURI'}<img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}
                                        </a></div>
                                    </th>

                                    {if $additionalColumns|isset}{@$additionalColumns}{/if}
                                </tr>
                            </thead>
                            <tbody>
                                {foreach from=$spiders item=spider}
                                    <tr class="{cycle}">
                                        <td class="columnUsername" style="width: 20%"><p>{if $spider.spiderURL}<a href="{$spider.spiderURL}">{$spider.spiderName}</a>{else}{$spider.spiderName}{/if}{if $spider.count > 1} ({#$spider.count}x){/if}</p></td>
                                        {if $canViewIpAddress}
                                            <td class="columnIP"><p>{$spider.ipAddress}</p></td>
                                            <td class="columnUserAgent" style="width: 30%"><p>{$spider.userAgent}</p></td>
                                        {/if}
                                        <td class="columnLastActivity"><p>{@$spider.lastActivityTime|shorttime}</p></td>
                                        <td class="columnLocation" style="width: {if $canViewIpAddress}50{else}80{/if}%"><p>{@$spider.location}</p></td>

                                        {if $spider.additionalColumns|isset}{@$spider.additionalColumns}{/if}
                                    </tr>
                                {/foreach}
                            </tbody>
                        </table>
                    {/if}

                    {if USERSONLINEBOX_MAXHEIGHT > 0}</div>{/if}
                    {if USERSONLINEBOX_SHOWLEGENDBOTTOM && $usersOnlineMarkings|count > 0}
                        <div class="smallFont">
                            {lang}wcf.usersOnline.marking.legend{/lang} {implode from=$usersOnlineMarkings item=usersOnlineMarking}{@$usersOnlineMarking}{/implode}
                        </div>
                    {/if}
                </div>
            </div>
        </div>
        <script type="text/javascript">
        //<![CDATA[
        if('{@$item.Status}' != '') initList('usersOnlineBox', {@$item.Status});
        //]]>
        </script>
    {/if}
