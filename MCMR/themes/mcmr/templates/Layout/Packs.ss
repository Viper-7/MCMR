<% include SideBar %>
<div class="content-container unit size3of4 lastUnit">
	<article>
		<div class="create_pack">
			<a href="home/packs/createpack">Create a New Pack</a>
		</div>
		<h1>$Title</h1>
		<div class="sort_controls">
			<span class="label">Sort By:</span>
			<span class="sort_option"><a href="#">Newest First</a></span>
			<span class="sort_option"><a href="#">Oldest First</a></span>
			<span class="sort_option"><a href="#">Most Downloads</a></span>
			<span class="sort_option"><a href="#">Most Votes</a></span>
			<span class="sort_option"><a href="#">Most Mods</a></span>
		</div>
		<div class="content">
			<% control Packs %>
				<div class="pack">
					<div class="pack_image">$PackImage.PaddedImage(96,96)</div>
					<div class="arrow"><img src="themes/mcmr/images/right_arrow.png"></div>
					<div class="pack_title">$Title</div>
					
					<div class="pack_mcversion"><span class="label">MC Version:</span>$MCVersion.Title</div>
					<div class="pack_mods"><span class="label">Mods:</span>$Mods.Count</div>
					<div class="pack_author"><span class="label">Author:</span>$Author.Name</div>
					
					<div class="pack_downloads"><span class="label">Downloads:</span>$DownloadCount</div>
					<div class="pack_upvotes"><span class="label">Up Votes:</span>$UpVoteCount <a href="vote/pack/$ID/UpVote">/\\</a></div>
					<div class="pack_downvotes"><span class="label">Down Votes:</span>$DownVoteCount <a href="vote/pack/$ID/DownVote">\\/</a></div>
					
					<div class="pack_download"><a href="downloads/pack/$ID/Client">Download Client</a></div>
					<div class="pack_download"><a href="downloads/pack/$ID/Server">Download Server</a></div>
					<div class="pack_download"><a href="favourites/pack/$ID/AddFavourite">Add to Favourites</a></div>
					
					<div style="float: clear; clear: both"></div>
				</div>
			<% end_control %>
		</div>
	</article>
		$Form
		$PageComments
</div>