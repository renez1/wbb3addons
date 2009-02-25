{if $this->user->getPermission('user.board.canSeeThanksgivingBox')}
    {if TOPTHANKSGIVING_SBCOLOR_ACP}
        {assign var='ttSecondBoxColor' value=TOPTHANKSGIVING_SBCOLOR_ACP}
    {else}
        {assign var='ttSecondBoxColor' value=2}
    {/if}
    {assign var='ttFirstBoxColor' value=1}
    {cycle values="$ttFirstBoxColor,$ttSecondBoxColor" print=false advance=false reset=true}
		<div class="border" id="box{$boxID}">
		    <div class="containerHead">
		    	<div class="containerIcon">
		    		<a href="javascript: void(0)" onclick="openList('topthanksgivingposts', true)">
                	<img src="{@RELATIVE_WCF_DIR}icon/minusS.png" id="topthanksgivingpostsImage" alt="" /></a>
                </div>
                <div class="containerContent">{lang}wbb.portal.box.topthanksgivingposts.title{/lang}</div>
            </div>
            <div class="container-1" id="topthanksgivingposts">
            	<div class="containerContent">
                    {if $item.thanksgiving|isset}
                        {foreach from=$item.thanksgiving item=$post}
                            <div class="container-{cycle values="$ttFirstBoxColor,$ttSecondBoxColor"} smallFont" style="float:none;">
                                <div style="float:right;">&nbsp;{if TOPTHANKSGIVING_HITS_ACP}{@$post.thanks}{/if}</div>
                                <div><a href="index.php?page=Thread&amp;postID={$post.postID}#post{$post.postID}" title="{@$post.title}">{@$post.subject}</a></div>
                            </div>
                        {/foreach}
                    {else}
                        <p class="smallFont">{lang}wbb.portal.box.topthanksgivingposts.nothreads{/lang}</p>
                    {/if}
                </div>
            </div>
        </div>
        <script type="text/javascript">
		//<![CDATA[
		initList('topthanksgivingposts', {@$item.Status});
		//]]>
		</script>
{/if}
