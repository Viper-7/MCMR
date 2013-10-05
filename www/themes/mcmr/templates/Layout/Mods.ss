<% include SideBar %>
<div class="content-container unit size3of4 lastUnit">
	<article>
		<% if ModID %>
			<% loop Mod %>
				Title: $Title<br/>
				Image: $ModIcon.PaddedImage(96,96)<br/>
				Description: $Description<br/>
				Created On: $Created<br/>
				Last Updated: $LatestVersion.Created<br/>
				Author: $Author.Surname<br/>
				Up Votes: $UpVoteCount<br/>
				Down Votes: $DownVoteCount<br/>
				MC Versions: <ul><% loop MCVersions %><li>$Title</li><% end_loop %></ul>
				Versions: <div class="versions">
					<% loop Versions %>
						$Version
					<% end_loop %>
				</div>
				Screenshots: <div class="screenshots">
					<% loop Screenshots %>
						<a href="$Me.Link">$Me.PaddedImage(128,128)</a>
					<% end_loop %>
				</div>
				
			<% end_loop %>
		<% else %>
			<div class="create_mod">
				<a href="home/mods/CreateMod">Add a Mod</a>
			</div>
			<h1>$Title</h1>
			<div class="content">
				<% loop Mods %>
					<div class="mod">
						<div class="mod_image">$ModImage.PaddedImage(96,96)</div>
						<div class="arrow"><a href="mod/$ID"><img src="themes/mcmr/images/right_arrow.png"></a></div>
						<div class="mod_title">$Title</div>
						
						<div class="mod_item mcversion"><span class="label">MC Version<% if MCMultiVersion %>s<% end_if %>:</span>$MCVersion</div>
						<div class="mod_item author"><span class="label">Author:</span>$Author.Surname</div>
						<div class="mod_item packcount"><span class="label">Packs:</span>$PackCount</div>
						
						<div class="mod_item upvotes"><span class="label">Votes:</span>$UpVoteCount <a href="mod/$ID/UpVote">/\\</a> &nbsp; &nbsp; $DownVoteCount <a href="mod/$ID/DownVote">\\/</a></div>
						<div style="float: clear; clear: both"></div>
					</div>
				<% end_loop %>
			</div>
		<% end_if %>
	</article>
		$Form
		$PageComments
</div>