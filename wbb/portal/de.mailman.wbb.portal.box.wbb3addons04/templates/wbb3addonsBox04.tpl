    {if $this->user->getPermission('user.board.canViewWbb3addonsBox04')}
		<div class="border" id="box{$boxID}">
		    <div class="containerHead">
                {if !$wbb3addonsBox04Opened}
    		    	<div class="containerIcon">
    		    		<a href="javascript: void(0)" onclick="openList('wbb3addonsBox04', true)"><img src="{@RELATIVE_WCF_DIR}icon/minusS.png" id="wbb3addonsBox04Image" alt="" /></a>
                    </div>
                {/if}
                <div class="containerContent">{lang}{@$wbb3addonsBox04Title}{/lang}</div>
            </div>
            <div id="wbb3addonsBox04">{@$wbb3addonsBox04Value}</div>
        </div>
        <script type="text/javascript">
		//<![CDATA[
		if('{@$item.Status}' != '') initList('wbb3addonsBox04', {@$item.Status});
		//]]>
		</script>
    {/if}
