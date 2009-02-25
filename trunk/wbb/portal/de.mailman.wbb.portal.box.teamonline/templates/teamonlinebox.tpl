{if $this->user->getPermission('user.board.canSeeTeamOnlineBox')}
    {if TEAMONLINEBOX_SBCOLOR_ACP}
        {assign var='secondBox' value=TEAMONLINEBOX_SBCOLOR_ACP}
    {else}
        {assign var='secondBox' value=2}
    {/if}
    {assign var='firstBox' value=1}
    {cycle values="$firstBox,$secondBox" print=false advance=false reset=true}
		<div class="border" id="box{$boxID}">
		    <div class="containerHead">
		    	<div class="containerIcon">
		    		<a href="javascript: void(0)" onclick="openList('teamonlinebox', true)">
                	<img src="{@RELATIVE_WCF_DIR}icon/minusS.png" id="teamonlineboxImage" alt="" /></a>
                </div>
                <div class="containerContent"><a href="index.php?page=TeamOnline{@SID_ARG_2ND}" title="{lang}wbb.portal.box.teamonlinebox.pageTitle{/lang}">{lang}wbb.portal.box.teamonlinebox.title{/lang}</a>{if $teamOnline|count && TEAMONLINEBOX_SHOWCOUNT_ACP} <span class="smallFont">({#$teamOnline|count})</span>{/if}</div>
            </div>
            <div class="container-1" id="teamonlinebox">
            	<div class="containerContent">
                    {if $teamOnline|isset && $teamOnline|count}
                        {if TEAMONLINEBOX_SHOWBYLINE_ACP}
                            {foreach from=$teamOnline item=teamUserOnline}
                                <div class="container-{cycle values="$firstBox,$secondBox"} smallFont" style="float:none;">
                                    {if TEAMONLINEBOX_SHOWTIME_ACP}<div style="float:right;">{@$teamUserOnline.lastActivityTime|time:"%H:%M"}</div>{/if}
                                    <div><a href="index.php?page=User&amp;userID={@$teamUserOnline.userID}{@SID_ARG_2ND}">{@$teamUserOnline.username}</a></div>
                                </div>
                            {/foreach}
                        {else}
                            <p class="smallFont">
                                {implode from=$teamOnline item=teamUserOnline}<a href="index.php?page=User&amp;userID={@$teamUserOnline.userID}{@SID_ARG_2ND}">{@$teamUserOnline.username}</a>{if TEAMONLINEBOX_SHOWTIME_ACP} ({@$teamUserOnline.lastActivityTime|time:"%H:%M"}){/if}{/implode}
                            </p>
                        {/if}
                    {else}
                        <p class="smallFont">
                            {lang}wbb.portal.box.teamonlinebox.nouser{/lang}
                        </p>
                    {/if}
                </div>
            </div>
        </div>
        <script type="text/javascript">
		//<![CDATA[
		initList('teamonlinebox', {@$item.Status});
		//]]>
		</script>
{/if}
