<% include SideBar %>
<div class="content-container unit size3of4 lastUnit">
	<article>
		<h1>$Title</h1>
		<div class="content">
			<% control Mods %>
				<div class="mod">
					<div class="mod_image">$PackImage.PaddedImage(96,96)</div>
					<div class="mod_title">$Title</div>
					<div class="mod_mcversion"><label>MC Version</label>$MCVersion.Title</div>
					<div class="mod_author"><label>Author:</label>$Author.Name</div>
					<div class="mod_packcount"><label>Packs:</label>$UpVoteCount</div>
					<div class="mod_upvotes"><label>Up Votes:</label>$UpVoteCount</div>
					<div class="mod_downvotes"><label>Down Votes:</label>$DownVoteCount</div>
					<div style="float: clear; clear: both"></div>
				</div>
			<% end_control %>
		</div>
	</article>
		$Form
		$PageComments
</div>