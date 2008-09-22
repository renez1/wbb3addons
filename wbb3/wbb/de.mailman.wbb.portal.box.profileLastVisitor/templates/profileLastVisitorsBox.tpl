{if SHOW_LASTVISITOR == 1 && $this->user->userID && SHOW_LASTVISITOR_AMOUNT}
{* $Id$ *}
    <div class="border" id="box{$boxID}">
        <div class="containerHead">
        	<div class="containerIcon">
        		<a href="javascript: void(0)" onclick="openList('profileLastVisitorsBox', true)">
            	<img src="{@RELATIVE_WCF_DIR}icon/minusS.png" id="profileLastVisitorsBoxImage" alt="" /></a>
            </div>
            <div class="containerContent">
                <a href="index.php?page=User&amp;userID={$this->user->userID}">{lang}wbb.portal.box.profileLastVisitors.title{/lang}</a>
            </div>
        </div>
        <div class="container-1" id="profileLastVisitorsBox">
        	<div class="containerContent smallFont">
        	        {if $visitors|count}
						{foreach from=$visitors item=visitor}
                	    	<div style="float:right;">{@$visitor.time|time:"%d.%m.%y, %H:%M"}</div>
            	        	<div><a href="index.php?page=User&amp;userID={$visitor.userID}">{@$visitor.username}</a></div>
						{/foreach}
					{else}
                    	{lang}wbb.portal.box.profileLastVisitors.noVisitors{/lang}
					{/if}
            </div>
        </div>
    </div>
    <script type="text/javascript">
    //<![CDATA[
    if('{@$item.Status}' != '') initList('profileLastVisitorsBox', {@$item.Status});
    //]]>
    </script>
{/if}
