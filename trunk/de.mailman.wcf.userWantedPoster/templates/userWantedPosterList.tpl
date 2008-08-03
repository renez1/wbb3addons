{include file="documentHeader"}
<head>
	<title>{lang}wcf.user.wantedPoster.list.title{/lang} - {PAGE_TITLE}</title>
	{include file='headInclude' sandbox=false}
    <script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/MultiPagesLinks.class.js"></script>
	<script language="javascript">
        function confirmDel(url) {
			if(confirm('{lang}wcf.user.wantedPoster.confirmDelete{/lang}')) window.location=url;
		}
	</script>
</head>
<body>
{assign var='thisPage' value='UserWantedPosterList'}
{include file='header' sandbox=false}

<div id="main">
	
	<ul class="breadCrumbs">
		<li><a href="index.php?page=Index{@SID_ARG_2ND}"><img src="icon/indexS.png" alt="" /> <span>{PAGE_TITLE}</span></a> &raquo;</li>
	</ul>
	
	<div class="mainHeadline">
		<a href="index.php?page=UserWantedPosterList{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/userWantedPosterListL.png" alt="" /></a>
		<div class="headlineContainer">
			<h2>{lang}wcf.user.wantedPoster.list.title{/lang}</h2>
		</div>
	</div>
	
    <div class="contentHeader">
        {pages print=true assign=pagesLinks link="index.php?page=UserWantedPosterList&pageNo=%d&sortField=$sortField&sortOrder=$sortOrder"}
    	{if $this->user->getPermission('user.wantedPoster.canUseWantedPoster')}
            <div class="largeButtons">
                <ul>
    			    <li><a href="index.php?form=UserWantedPosterEdit&userID={$this->user->userID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/editM.png" alt="" /> <span>{lang}wcf.user.wantedPoster.list.entry{/lang}</span></a></li>
    			</ul>
    	    </div>
    	{/if}
    </div>

	{if $userMessages|isset}{@$userMessages}{/if}

	<div class="subTabMenu">
		<div class="containerHead">{lang}wcf.user.wantedPoster.list.listHeader{/lang}</div>
	</div>

    {include file='userWantedPosterListData' sandbox=false}

</div>
{include file='footer' sandbox=false}
</body>
</html>
