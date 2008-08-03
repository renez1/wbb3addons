    {if $this->user->getPermission('user.board.canViewWbb3addonsBox05')}
		<div class="border" id="box{$boxID}">
		    <div class="containerHead">
                {if !$wbb3addonsBox05Opened}
    		    	<div class="containerIcon">
    		    		<a href="javascript: void(0)" onclick="openList('wbb3addonsBox05', true)"><img src="{@RELATIVE_WCF_DIR}icon/minusS.png" id="wbb3addonsBox05Image" alt="" /></a>
                    </div>
                {/if}
                <div class="containerContent">{lang}{@$wbb3addonsBox05Title}{/lang}</div>
            </div>
            <div id="wbb3addonsBox05">{@$wbb3addonsBox05Value}</div>
        </div>
        <script type="text/javascript">
		//<![CDATA[
		if('{@$item.Status}' != '') initList('wbb3addonsBox05', {@$item.Status});
		//]]>
		</script>
    {/if}
