{if $this->user->getPermission('user.board.canViewThreadLastPostsBox')}
    {if THREADLASTPOSTSBOX_SBCOLOR}
        {assign var='tlpSecondBoxColor' value=THREADLASTPOSTSBOX_SBCOLOR}
    {else}
        {assign var='tlpSecondBoxColor' value=2}
    {/if}
    {assign var='tlpFirstBoxColor' value=1}
    {cycle values="$tlpFirstBoxColor,$tlpSecondBoxColor" print=false advance=false reset=true}
		<div class="border" id="box{$boxID}">
		    <div class="containerHead">
		    	<div class="containerIcon">
		    		<a href="javascript: void(0)" onclick="openList('threadlastpostsbox', true)">
                	<img src="{@RELATIVE_WCF_DIR}icon/minusS.png" id="threadlastpostsboxImage" alt="" /></a>
                </div>
                <div class="containerContent">{lang}wbb.portal.box.threadlastpostsbox.title{/lang}</div>
            </div>
            <div class="container-1" id="threadlastpostsbox">
            	<div class="containerContent">
                    {if $item.box|isset && $threadLastPostBoxCnt > 0}
                        {foreach from=$item.box item=$post}
                            <div class="container-{cycle values="$tlpFirstBoxColor,$tlpSecondBoxColor"} smallFont" style="float:none;">
                                <div><a href="index.php?page=Thread&amp;postID={$post.postID}#post{$post.postID}" title="{$post.time|time}">{@$post.title}</a></div>
                            </div>
                        {/foreach}
                    {else}
                        <p class="smallFont">{lang}wbb.portal.box.threadlastpostsbox.noposts{/lang}</p>
                    {/if}
                </div>
            </div>
        </div>
        <script type="text/javascript">
		//<![CDATA[
		initList('threadlastpostsbox', {@$item.Status});
		//]]>
		</script>
{/if}
