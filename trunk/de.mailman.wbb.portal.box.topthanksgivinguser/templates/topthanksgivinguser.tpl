{if $this->user->getPermission('user.board.canSeeThanksgivingUserBox')}
    {if TOPTHANKSGIVINGUSER_SBCOLOR_ACP}
        {assign var='ttSecondBoxColor' value=TOPTHANKSGIVINGUSER_SBCOLOR_ACP}
    {else}
        {assign var='ttSecondBoxColor' value=2}
    {/if}
    {assign var='ttFirstBoxColor' value=1}
    {cycle values="$ttFirstBoxColor,$ttSecondBoxColor" print=false advance=false reset=true}
		<div class="border" id="box{$boxID}">
		    <div class="containerHead">
		    	<div class="containerIcon">
		    		<a href="javascript: void(0)" onclick="openList('topthanksgivinguser', true)">
                	<img src="{@RELATIVE_WCF_DIR}icon/minusS.png" id="topthanksgivinguserImage" alt="" /></a>
                </div>
                <div class="containerContent">{lang}wbb.portal.box.topthanksgivinguser.title{/lang}</div>
            </div>
            <div class="container-1" id="topthanksgivinguser">
            	<div class="containerContent">
                    {if $item.thanksgivinguser|isset}
                        {foreach from=$item.thanksgivinguser item=$user}
                            <div class="container-{cycle values="$ttFirstBoxColor,$ttSecondBoxColor"} smallFont" style="float:none;">
                                <div style="float:right;">&nbsp;{if TOPTHANKSGIVINGUSER_HITS_ACP}{@$user.thanks_got}{/if}</div>
                                <div><a href="index.php?page=User&amp;userID={$user.userID}">{$user.username}</a></div>
                            </div>
                        {/foreach}
                    {else}
                        <p class="smallFont">{lang}wbb.portal.box.topthanksgivinguser.nouser{/lang}</p>
                    {/if}
                </div>
            </div>
        </div>
        <script type="text/javascript">
		//<![CDATA[
		initList('topthanksgivinguser', {@$item.Status});
		//]]>
		</script>
{/if}
