<% include SideBar %>
<div class="content-container unit size3of4 lastUnit">
	<article>
		<% if ModID %>
			<% control Mod %>
				Title: $Title<br/>
				Image: $ModIcon.PaddedImage(96,96)<br/>
				Description: $Description<br/>
				Created On: $Created<br/>
				Last Updated: $LatestVersion.Created<br/>
				Author: $Author.Surname<br/>
				Up Votes: $UpVoteCount<br/>
				Down Votes: $DownVoteCount<br/>
				MC Versions: <ul><% control MCVersions %><li>$Title</li><% end_control %></ul>
				Versions: <div class="versions">
					<% control Versions %>
						$Version
					<% end_control %>
				</div>
				Screenshots: <div class="screenshots">
					<% control Screenshots %>
						<a href="$Me.Link">$Me.PaddedImage(128,128)</a>
					<% end_control %>
				</div>
				
			<% end_control %>
		<% else %>
			<div class="create_mod">
				<a href="home/mods/CreateMod">Add a Mod</a>
			</div>
			<h1>$Title</h1>
			<div class="content">
				<% control Mods %>
					<div class="mod">
						<div class="mod_image">$ModImage.PaddedImage(96,96)</div>
						<div class="arrow"><a href="mod/$ID"><img src="themes/mcmr/images/right_arrow.png"></a></div>
						<div class="mod_title">$Title</div>
						
						<div class="mod_item mcversion"><span class="label">MC Version<% if MCMultiVersion %>s<% end_if %>:</span>$MCVersion</div>
						<div class="mod_item author"><span class="label">Author:</span>$Author.Surname</div>
						<div class="mod_item packcount"><span class="label">Packs:</span>$PackCount</div>
						
						<div class="mod_item upvotes"><span class="label">Up Votes:</span>$UpVoteCount</div>
						<div class="mod_item downvotes"><span class="label">Down Votes:</span>$DownVoteCount</div>
						<div style="float: clear; clear: both"></div>
					</div>
				<% end_control %>
			</div>
		<% end_if %>
	</article>
		$Form
		$PageComments
</div>