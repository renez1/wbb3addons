{if $canSeeLastOnlineBox|isset && $canSeeLastOnlineBox > 0}
    {if LASTONLINEBOX_SBCOLOR_ACP}
        {assign var='secondBox' value=LASTONLINEBOX_SBCOLOR_ACP}
    {else}
        {assign var='secondBox' value=2}
    {/if}
    {assign var='firstBox' value=1}
    {cycle values="$firstBox,$secondBox" print=false advance=false reset=true}
    <a name="louBox"></a>
    <div class="border" id="box{$boxID}">
        <div class="containerHead">
        	<div class="containerIcon">
        		<a href="javascript: void(0)" onclick="openList('lastonlinebox', true)">
            	<img src="{@RELATIVE_WCF_DIR}icon/minusS.png" id="lastonlineboxImage" alt="" /></a>
            </div>
            <div class="containerContent">
                {if $this->user->userID}
                    <a href="index.php?page=MembersList&sortField=lastActivity&sortOrder=DESC{@SID_ARG_2ND}">{lang}wbb.portal.box.lastonlinebox.title{/lang}</a>
                {else}
                    {lang}wbb.portal.box.lastonlinebox.title{/lang}
                {/if}
            </div>
        </div>
        <div class="container-1" id="lastonlinebox">
        	<div class="containerContent"{if $showAllOU || LASTONLINEBOX_NUMOFUSER_ACP == 0} style="max-height:{LASTONLINEBOX_MAXHEIGHT_ACP}px; overflow:auto;"{/if}>
                {if $lastOnline|isset && $lastOnline|count}
                    <div class="container-{cycle values="$firstBox,$secondBox"} smallFont" style="float:none;">
                        <div style="float:right;">
                            {if !$showAllOU && LASTONLINEBOX_NUMOFUSER_ACP > 0}
                                <a href="index.php?page={$curPage}&amp;showAllOU=1{@SID_ARG_2ND}#louBox" title="{lang}wbb.portal.box.lastonlinebox.onlineinfotitle{/lang}">{lang}wbb.portal.box.lastonlinebox.onlineinfo{/lang}</a>
                            {else}
                                {lang}wbb.portal.box.lastonlinebox.onlineinfo{/lang}
                            {/if}
                        </div>
                        {if $showAllOU || LASTONLINEBOX_NUMOFUSER_ACP == 0}
                            <a href="javascript:louList_togglesort();"><img src="{@RELATIVE_WBB_DIR}icon/wasOnlineSortM.png" alt="" title="{lang}wbb.portal.box.lastonlinebox.sortinfo{/lang}" /></a>
                        {else}
                            &nbsp;
                        {/if}
                    </div>
                    <div class="smallFont" id="lastOnlineUser">
                        {foreach from=$lastOnline item=lastOnlineUser}
                            <div class="container-{cycle values="$firstBox,$secondBox"}" style="float:none;">
                                {if LASTONLINEBOX_SHOWTIME_ACP}<div style="float:right;">{@$lastOnlineUser.lastActivityTime|time:"%H:%M"}</div>{/if}
                                <div><a href="index.php?page=User&amp;userID={@$lastOnlineUser.userID}{@SID_ARG_2ND}">{@$lastOnlineUser.username}</a></div>
                            </div>
                        {/foreach}
                    </div>
                {else}
                    <p class="smallFont">{lang}wbb.portal.box.lastonlinebox.nouser{/lang}</p>
                {/if}
            </div>
        </div>
    </div>
    <script type="text/javascript">
    //<![CDATA[
        initList('lastonlinebox', {@$item.Status});
        var lou_sortbyname = false;
        var louList_byname = '{foreach from=$lastOnlineByName item=lastOnlineUser}<div class="container-{cycle values="$firstBox,$secondBox"}" style="float:none;">{if LASTONLINEBOX_SHOWTIME_ACP}<div style="float:right;">{@$lastOnlineUser.lastActivityTime|time:"%H:%M"}</div>{/if}<div><a href="index.php?page=User&amp;userID={@$lastOnlineUser.userID}{@SID_ARG_2ND}">{@$lastOnlineUser.username}</a></div></div>{/foreach}';
        var louList_bytime = '{foreach from=$lastOnline item=lastOnlineUser}<div class="container-{cycle values="$firstBox,$secondBox"}" style="float:none;">{if LASTONLINEBOX_SHOWTIME_ACP}<div style="float:right;">{@$lastOnlineUser.lastActivityTime|time:"%H:%M"}</div>{/if}<div><a href="index.php?page=User&amp;userID={@$lastOnlineUser.userID}{@SID_ARG_2ND}">{@$lastOnlineUser.username}</a></div></div>{/foreach}';
        function louList_togglesort() {
            if(lou_sortbyname) {
        	    document.getElementById("lastOnlineUser").innerHTML = louList_bytime;
        		lou_sortbyname = false;
        	}
        	else {
            	document.getElementById("lastOnlineUser").innerHTML = louList_byname;
        		lou_sortbyname = true;
        	}
        }
    //]]>
    </script>
{/if}
