<% include SideBar %>
<div class="content-container unit size3of4 lastUnit">
	<article>
		<% if PackID %>
			<% control Pack %>
				Title: $Title<br/>
				Posted On: $Created<br/>
				Author: $Author.Surname<br/>
				Image: $PackIcon.PaddedImage(96,96)<br/>
				MCVersion: $MCVersion.Title<br/>
				CurrentVersion: $CurrentVersion.Version<br/>
				Mods: <div class="mods">
					<ul>
						<% control Mods %>
							<li><a href="mod/$ID">$Title</a> ($CurrentVersion.Version)</li>
						<% end_control %>
					</ul>
				</div>
				Versions: <div class="versions">
					<% control Versions %>
						<h2>$Version</h2>
						<div class="mods">
							<% control Mods %>
								<span class="title">$ModVersion.Mod.Title</span>
								<span class="version">($ModVersion.Version)</span>
								<span class="author"> by $ModVersion.Mod.Author.Surname</span>
								(<% if ModVersion.IsCurrent %>
									<span class="current">Current</span>
								<% else %>
									<span class="out_of_date">Out of Date</span>
								<% end_if %>)
								
							<% end_control %>
						</div>
					<% end_control %>
				</div>
			<% end_control %>
		<% else %>
			<div class="create_pack">
				<a href="home/packs/CreatePack">Create a New Pack</a>
			</div>
			<h1>$Title</h1>
			<div class="sort_controls">
				<span class="label">Sort By:</span>
				<span class="sort_option"><a href="#">Newest Release</a></span>
				<span class="sort_option"><a href="#">Newest Update</a></span>
				<span class="sort_option"><a href="#">Most Downloads</a></span>
				<span class="sort_option"><a href="#">Most Votes</a></span>
				<span class="sort_option"><a href="#">Most Mods</a></span>
			</div>
			<div class="content">
				<% control Packs %>
					<div class="pack">
						<div class="pack_image">$PackImage.PaddedImage(96,96)</div>
						<div class="arrow"><a href="pack/$ID"><img src="themes/mcmr/images/right_arrow.png"></a></div>
						<div class="pack_title">$Title</div>
						
						<div class="pack_item author"><span class="label">Author:</span>$Author.Surname</div>
						<div class="pack_item mcversion"><span class="label">MC Version:</span>$MCVersion.Title</div>
						<div class="pack_item version"><span class="label">Pack Version:</span>$CurrentVersion.Version</div>
						
						<div class="pack_item upvotes"><span class="label">Votes:</span>$UpVoteCount <a href="pack/$ID/UpVote">/\\</a> &nbsp; &nbsp; $DownVoteCount <a href="pack/$ID/DownVote">\\/</a></div>
						<div class="pack_item downloads"><span class="label">Downloads:</span>$DownloadCount</div>
						<div class="pack_item update"><span class="label">Last Updated:</span>$LastEdited.Ago</div>
						
						<div class="pack_item download"><a href="pack/$ID/Client">Download Client</a></div>
						<div class="pack_item download"><a href="pack/$ID/Server">Download Server</a></div>
						<div class="pack_item download"><a href="pack/$ID/AddFavourite">Add to Favourites</a></div>
						
						<div style="float: clear; clear: both"></div>
					</div>
				<% end_control %>
			</div>
		<% end_if %>
	</article>
		$Form
		$PageComments
</div>