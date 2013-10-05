<?php
class Mods extends Page {

	public static $db = array(
	);

	public static $has_one = array(
	);

	public function getMods() {
		return DataObject::get('MCMod');
	}
}
class Mods_Controller extends Page_Controller {

	/**
	 * An array of actions that can be accessed via a request. Each array element should be an action name, and the
	 * permissions or conditions required to allow the user to access it.
	 *
	 * <code>
	 * array (
	 *     'action', // anyone can access this action
	 *     'action' => true, // same as above
	 *     'action' => 'ADMIN', // you must have ADMIN permissions to access this action
	 *     'action' => '->checkAction' // you can only access this action if $this->checkAction() returns true
	 * );
	 * </code>
	 *
	 * @var array
	 */
	public static $allowed_actions = array (
		'UpVote',
		'DownVote',
		'CreateMod',
		'UploadFiles',
		'ResolveIDs',
		'PublishMod',
		'CreateModForm',
		'UploadFilesForm',
		'ResolveIDsForm',
		'PublishModForm',
	);

	public function init() {
		parent::init();

		// Note: you should use SS template require tags inside your templates 
		// instead of putting Requirements calls here.  However these are 
		// included so that our older themes still work
		Requirements::themedCSS('reset');
		Requirements::themedCSS('layout'); 
		Requirements::themedCSS('typography'); 
		Requirements::themedCSS('form'); 
	}
	
	public function index($request) {
		$this->ModID = $request->param('ID');
		
		return $this;
	}
	
    public function CreateModForm() {
        $fields = new FieldList(
            new TextField('Name'),
			new CheckboxSetField('MCVersion', 'Minecraft Versions', DataObject::get('MCVersion')->Map()),
			$f = new UploadField('ModIcon', 'Mod Icon (96x96)')
        );
        
		$f->getValidator()->setAllowedExtensions(array('jpg', 'gif', 'png'));
		
        $actions = new FieldList( new FormAction('doCreateMod', 'Next Step') );
     
        return new Form($this, 'CreateModForm', $fields, $actions);
    }
	
	public function UploadFilesForm() {
		if(empty($_SESSION['mod_state']) || $_SESSION['mod_state'] < 1)
			return Director::redirect('home/mods');

		$field = new FieldList();
		$actions = new FieldList( new FormAction('doUploadFiles', 'Next Step') );
		return new Form($this, 'UploadFilesForm', $field, $actions);
	}
	
	public function ResolveIDsForm() {
		if(empty($_SESSION['mod_state']) || $_SESSION['mod_state'] < 2)
			return Director::redirect('home/mods');

		$field = new FieldList();
		$actions = new FieldList( new FormAction('doResolveIDs', 'Next Step') );
		return new Form($this, 'ResolveIDsForm', $field, $actions);
	}
	
	public function PublishModForm() {
		if(empty($_SESSION['mod_state']) || $_SESSION['mod_state'] < 3)
			return Director::redirect('home/mods');

		$field = new FieldList();
		$actions = new FieldList( new FormAction('doPublishMod', 'Finish') );
		return new Form($this, 'PublishModForm', $field, $actions);
	}
	
	public function doCreateMod($request) {
		$_SESSION['mod_state'] = 1;
		$_SESSION['mod_data'][0] = $request->postVars();
		
		Director::redirect('home/mods/UploadFiles');
	}

	public function doUploadFiles($request) {
		$_SESSION['mod_state'] = 2;
		$_SESSION['mod_data'][1] = $request->postVars();
		Director::redirect('home/mods/ResolveIDs');
	}
	
	public function doResolveIDs($request) {
		$_SESSION['mod_state'] = 3;
		$_SESSION['mod_data'][2] = $request->postVars();
		Director::redirect('home/mods/PublishMod');
	}

	public function doPublishMod($request) {
		$_SESSION['mod_state'] = 0;
		$_SESSION['mod_data'][3] = $request->postVars();

		$data = $_SESSION['mod_data'];
		
		$mod = new MCMod();
		$mod->AuthorID = Member::currentUserID();
		$mod->Title = $data[0]['Title'];
		$mod->Description = $data[0]['Description'];
		// Image
		$mod->write();

		$version = new MCModVersion();
		$version->ModID = $mod->ID;
		$version->MajorVersion = 1;
		$version->MinorVersion = 0;
		$version->PatchVersion = 0;
		$version->write();
		
		Director::redirect('mod/' . $mod->ID . '/version/' . $version->ID);
	}

	public function Mod() {
		return DataObject::get_by_id('MCMod', $this->ModID);
	}

	public function UpVote($request) {
		$mod = DataObject::get_by_id('MCMod', $request->param('ID'));
		$mod->UpVote();
		return Director::redirect('home/mods');
	}
	
	public function DownVote($request) {
		$mod = DataObject::get_by_id('MCMod', $request->param('ID'));
		$mod->DownVote();
		return Director::redirect('home/mods');
	}
}