{if $usersWasOnline|isset}
		<div class="border" id="box{$boxID}">
		    <div class="containerHead">
		    	<div class="containerIcon">
		    		<a href="javascript: void(0)" onclick="openList('todayonlinebox', true)">
                	<img src="{@RELATIVE_WCF_DIR}icon/minusS.png" id="todayonlineboxImage" alt="" /></a>
                </div>
                <div class="containerContent">{lang}wbb.portal.box.todayonlinebox.tplTitle{/lang}</div>
            </div>
            <div class="container-1" id="todayonlinebox">
                {cycle values='container-1,container-2' print=false advance=false}
                {if TODAYONLINEBOX_SHOWONLINEUSER_ACP && $usersOnlineTotal|isset && $usersOnline|count}
                    <div class="{cycle}">
                        <div class="containerIcon"><img src="{@RELATIVE_WCF_DIR}icon/membersM.png" alt="" /></div>
                        <div class="containerContent" style="margin-left:32px;">
                            <h3><a href="index.php?page=UsersOnline{@SID_ARG_2ND}" title="{lang}wcf.usersOnline.title{/lang}">{lang}wbb.index.usersOnline{/lang}</a></h3> 
                            <p class="smallFont">{lang}wbb.index.usersOnline.detail{/lang} {lang}wbb.portal.box.todayonlinebox.usersOnline.record{/lang}</p>
                            <p class="smallFont">
                                {implode from=$usersOnline item=userOnline}<a href="index.php?page=User&amp;userID={@$userOnline.userID}{@SID_ARG_2ND}">{@$userOnline.username}</a>{/implode}
                            </p>
                        	{if TODAYONLINEBOX_SHOWLEGEND_ACP == 'wio' && $usersOnlineMarkings|count}
                        		<p class="smallFont">
                        		    {lang}wcf.usersOnline.marking.legend{/lang} {implode from=$usersOnlineMarkings item=usersOnlineMarking}{@$usersOnlineMarking}{/implode}
                        		</p>
                        	{/if}
                        </div>
                    </div>
                {/if}

                <div class="{cycle}">
                    <div class="containerIcon"><a href="javascript:wwolist_box_togglesort();"><img src="{@RELATIVE_WBB_DIR}icon/wasOnlineSortM.png" alt="" id="wwoIconBox" /></a></div>
                    <div class="containerContent" style="margin-left:32px;">
                        <h3><a href="javascript:wwolist_box_togglesort();">{lang}wbb.index.usersWasOnline{/lang}</a></h3>
                        <p class="smallFont">{lang}wbb.index.usersWasOnline.detail{/lang} {lang}wbb.index.usersWasOnline.record{/lang}</p>
                        {if $usersWasOnline|count}
                            {if INDEX_LIMIT_WASONLINE_LIST && INDEX_LIMIT_WASONLINE_LIST_AMOUNT > 0}<p class="smallFont">{lang}wbb.index.usersWasOnline.limitedlist{/lang}</p>{/if}
                            <p class="smallFont" id="wwoNamesBox">{implode from=$usersWasOnline item=userWasOnline}<a href="index.php?page=User&amp;userID={@$userWasOnline.userID}{@SID_ARG_2ND}">{@$userWasOnline.username}</a>{if TODAYONLINEBOX_SHOWTIME_ACP}&nbsp;({@$userWasOnline.lastActivityTime|time:"%H:%M"}){/if}{/implode}</p>
                        {/if}
                        {if TODAYONLINEBOX_SHOWLEGEND_ACP == 'wwo' && $usersOnlineMarkings|count}
                        	<p class="smallFont">
                        	    {lang}wcf.usersOnline.marking.legend{/lang} {implode from=$usersOnlineMarkings item=usersOnlineMarking}{@$usersOnlineMarking}{/implode}
                        	</p>
                        {/if}
                    </div>
                </div>

                {if TODAYONLINEBOX_SHOWSTATS_ACP && $stats|count}
                	<div class="{cycle}">
                		<div class="containerIcon"><img src="{@RELATIVE_WBB_DIR}icon/statisticsM.png" alt="" /></div>
                		<div class="containerContent" style="margin-left:32px;">
                			<h3>{if $this->user->getPermission('user.board.canViewStats')}<a href="index.php?page=Stats{@SID_ARG_2ND}">{lang}wbb.index.stats{/lang}</a>{else}{lang}wbb.index.stats{/lang}{/if}</h3> 
                			<p class="smallFont">{lang}wbb.index.stats.detail{/lang}</p>
                            {if TODAYONLINEBOX_SHOWLEGEND_ACP == 'stat' && $usersOnlineMarkings|count}
                            	<p class="smallFont">
                            	    {lang}wcf.usersOnline.marking.legend{/lang} {implode from=$usersOnlineMarkings item=usersOnlineMarking}{@$usersOnlineMarking}{/implode}
                            	</p>
                            {/if}
                		</div>
                	</div>
                {/if}
            </div>
        </div>

        <script type="text/javascript">
		//<![CDATA[
		initList('todayonlinebox', {@$item.Status});
        document.getElementById("wwoIconBox").title = "{lang}wbb.index.usersWasOnline.sortByName{/lang}";
        var wwolist_box_sortbyname = false;
        var wwolist_box_byname = '{implode from=$usersWasOnlineByName item=userWasOnline}<a href="index.php?page=User&amp;userID={@$userWasOnline.userID}{@SID_ARG_2ND}">{@$userWasOnline.username|addcslashes:"'\\"}</a>{if TODAYONLINEBOX_SHOWTIME_ACP}&nbsp;({@$userWasOnline.lastActivityTime|time:"%H:%M"}){/if}{/implode}';
        var wwolist_box_bytime = '{implode from=$usersWasOnline item=userWasOnline}<a href="index.php?page=User&amp;userID={@$userWasOnline.userID}{@SID_ARG_2ND}">{@$userWasOnline.username|addcslashes:"'\\"}</a>{if TODAYONLINEBOX_SHOWTIME_ACP}&nbsp;({@$userWasOnline.lastActivityTime|time:"%H:%M"}){/if}{/implode}';
        function wwolist_box_togglesort () {
        	if(wwolist_box_sortbyname == true) {
        		document.getElementById("wwoNamesBox").innerHTML = wwolist_box_bytime;
        		document.getElementById("wwoIconBox").title = "{lang}wbb.index.usersWasOnline.sortByName{/lang}";
        		wwolist_box_sortbyname = false;
        	}
        	else {
        		document.getElementById("wwoNamesBox").innerHTML = wwolist_box_byname;
        		document.getElementById("wwoIconBox").title = "{lang}wbb.index.usersWasOnline.sortByTime{/lang}";
			    wwolist_box_sortbyname = true;
		    }
        }
		//]]>
		</script>
{/if}
