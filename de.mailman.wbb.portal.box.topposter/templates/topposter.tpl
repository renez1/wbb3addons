    {if TOPPOSTER_SBCOLOR_ACP}
        {assign var='secondBox' value=TOPPOSTER_SBCOLOR_ACP}
    {else}
        {assign var='secondBox' value=2}
    {/if}
    {assign var='firstBox' value=1}
    {cycle values="$firstBox,$secondBox" print=false advance=false reset=true}
		<div class="border" id="box{$boxID}">
		    <div class="containerHead">
		    	<div class="containerIcon">
		    		<a href="javascript: void(0)" onclick="openList('topposter', true)">
                	<img src="{@RELATIVE_WCF_DIR}icon/minusS.png" id="topposterImage" alt="" /></a>
                </div>
                <div class="containerContent">
                    {if $this->user->userID}
                        <a href="index.php?page=MembersList&sortField=posts&sortOrder=DESC{@SID_ARG_2ND}">{lang}wbb.portal.box.topposter.title{/lang}</a>
                    {else}
                        {lang}wbb.portal.box.topposter.title{/lang}
                    {/if}
                </div>
            </div>
            <div class="container-1" id="topposter">
            	<div class="containerContent">
                    {if $item.users|isset}
                        {foreach from=$item.users item=$user}
                            <div class="container-{cycle values="$firstBox,$secondBox"} smallFont" style="float:none;">
                                <div style="float:right;">{@$user.posts}</div>
                                <div><a href="index.php?page=User&amp;userID={@$user.userid}{@SID_ARG_2ND}">{$user.username}</a></div>
                            </div>
                        {/foreach}
                    {else}
                        	<p class="smallFont">{lang}wbb.portal.box.topposter.nouser{/lang}</p>
                    {/if}
                </div>
            </div>
        </div>
        <script type="text/javascript">
		//<![CDATA[
		initList('topposter', {@$item.Status});
		//]]>
		</script>
