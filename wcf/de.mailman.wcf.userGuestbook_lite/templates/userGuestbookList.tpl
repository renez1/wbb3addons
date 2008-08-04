{include file="documentHeader"}
<head>
	<title>{lang}wcf.user.guestbook.list.title{/lang} - {PAGE_TITLE}</title>
	{include file='headInclude' sandbox=false}
    <script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/MultiPagesLinks.class.js"></script>
</head>
<body>
{assign var='thisPage' value='UserGuestbookList'}
{include file='header' sandbox=false}

<div id="main">
	
	<ul class="breadCrumbs">
		<li><a href="index.php?page=Index{@SID_ARG_2ND}"><img src="icon/indexS.png" alt="" /> <span>{PAGE_TITLE}</span></a> &raquo;</li>
	</ul>
	
	<div class="mainHeadline">
		<a href="index.php?page=UserGuestbookList{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/guestbookListL.png" alt="" /></a>
		<div class="headlineContainer">
			<h2>{lang}wcf.user.guestbook.list.title{/lang}</h2>
		</div>
	</div>
	
    <div class="contentHeader">
        {pages print=true assign=pagesLinks link="index.php?page=UserGuestbookList&pageNo=%d&sortField=$sortField&sortOrder=$sortOrder"}
    	{if $this->user->getPermission('user.guestbook.canUseOwn')}
            <div class="largeButtons">
                <ul>
    			    <li><a href="index.php?page=UserGuestbook&userID={$this->user->userID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/guestbookM.png" alt="" /> <span>{lang}wcf.user.guestbook.own{/lang}</span></a></li>
    			</ul>
    	    </div>
    	{/if}
    </div>

	{if $userMessages|isset}{@$userMessages}{/if}
	<div class="subTabMenu">
		<div class="containerHead">{lang}wcf.user.guestbook.list.listHeader{/lang}</div>
	</div>

    {include file='userGuestbookListData' sandbox=false}

</div>
{include file='footer' sandbox=false}
</body>
</html>
