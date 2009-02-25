{include file="documentHeader"}
{* $Id$ *}
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
{assign var='thisPage' value='UserWantedPosterListMembers'}
{include file='header' sandbox=false}

<div id="main">
	
	<ul class="breadCrumbs">
		<li><a href="index.php?page=Index{@SID_ARG_2ND}"><img src="icon/indexS.png" alt="" /> <span>{PAGE_TITLE}</span></a> &raquo;</li>
	</ul>
	
	<div class="mainHeadline">
		<a href="index.php?page=UserWantedPosterListMembers{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/userWantedPosterListL.png" alt="" /></a>
		<div class="headlineContainer">
			<h2>{lang}wcf.user.wantedPoster.list.title{/lang}</h2>
			<p>{lang}wcf.user.wantedPoster.list.listHeader{/lang}</p>
		</div>
	</div>
	
	{if $userMessages|isset}{@$userMessages}{/if}

	<div class="tabMenu">
		<ul>
		    {if $this->user->getPermission('user.membersList.canView')}
    			<li><a href="index.php?page=MembersList{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/membersM.png" alt="" /> {lang}wcf.user.membersList.allMembers{/lang}</a></li>
    			<li><a href="index.php?form=MembersSearch{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/searchM.png" alt="" /> {lang}wcf.user.membersList.membersSearch{/lang}</a></li>
    			<li><a href="index.php?page=Team{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/teamM.png" alt="" /> {lang}wcf.user.team.title{/lang}</a></li>
    		{/if}
			{if $additionalTabs|isset}{@$additionalTabs}{/if}
		</ul>
	</div>
	<div class="subTabMenu">
		<div class="containerHead"><div> </div></div>
	</div>

    {include file='userWantedPosterListData' sandbox=false}

</div>
{include file='footer' sandbox=false}
</body>
</html>
