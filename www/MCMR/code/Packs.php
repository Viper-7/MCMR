<?php
class Packs extends Page {

	public static $db = array(
	);

	public static $has_one = array(
	);
	
	public function getPacks() {
		return DataObject::get('MCPack');
	}
	
}

class Packs_Controller extends Page_Controller {

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
		'CreatePack',
		'ChooseMods',
		'BuildPack',
		'CreatePackForm',
		'ChooseModsForm',
		'BuildPackForm',
		'UpVote',
		'DownVote',
		'Client',
		'Server',
		'AddFavourite',
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
		$this->PackID = $request->param('ID');
		
		return $this;
	}
	
	public function Pack() {
		return DataObject::get_by_id('MCPack', $this->PackID);
	}

    public function CreatePackForm() {
		
		if(!Member::currentUserID())
			return Director::redirect('Security/Login?BackURL=' . $this->Link() . 'CreatePack');
		
        $fields = new FieldList(
            new TextField('Title'),
			new OptionsetField('MCVersion', 'Minecraft Version', DataObject::get('MCVersion')->Map()),
			$f = new UploadField('PackIcon', 'Pack Icon (96x96)')
        );
		
		$f->getValidator()->setAllowedExtensions(array('jpg', 'gif', 'png'));
		
        $actions = new FieldList(
            new FormAction('doCreatePack', 'Choose Mods')
        );
     
        return new Form($this, 'CreatePackForm', $fields, $actions);
    }

	public function ChooseModsForm() {
		if(empty($_SESSION['pack_state']) || $_SESSION['pack_state'] < 1)
			return Director::redirect('home/packs');
		
		$mcversion = DataObject::get_by_id('MCVersion', $_SESSION['pack_data'][0]['MCVersion']);
		
        $fields = new FieldList(
            new CheckboxSetField('Mods', 'Mods included in this pack', $mcversion->Mods()->Map())
        );
         
        $actions = new FieldList(
            new FormAction('doChooseMods', 'Select Versions')
        );
     
        return new Form($this, 'ChooseModsForm', $fields, $actions);
	}
	
	public function BuildPackForm() {
		if(empty($_SESSION['pack_state']) || $_SESSION['pack_state'] < 2)
			return Director::redirect('home/packs');
		
        $fields = new FieldList();
		
		$mods = $_SESSION['pack_data'][1]['Mods'];
		foreach($mods as $mod_id) {
			$mod = DataObject::get_by_id('MCMod', $mod_id);
			$fields->push(new DropdownField('ModVersion[' . $mod_id . ']', $mod->Title, $mod->Versions()->Map('ID', 'VersionString')));
        }
		
        $actions = new FieldList(
            new FormAction('doBuildPack', 'Build Pack')
        );
     
        return new Form($this, 'BuildPackForm', $fields, $actions);
	}
	
	public function getPackState() {
		if(isset($_SESSION['pack_state'])) {
			return $_SESSION['pack_state'];
		} else {
			return 0;
		}
	}
	
	public function doCreatePack($data) {
		$_SESSION['pack_state'] = 1;
		$_SESSION['pack_data'] = array($data);
		$this->redirect('home/packs/ChooseMods');
	}
	
	public function doChooseMods($data) {
		$_SESSION['pack_state'] = 2;
		$_SESSION['pack_data'][1] = $data;

		$this->redirect('home/packs/BuildPack');
	}
	
	public function doBuildPack($data) {
		$_SESSION['pack_state'] = 0;
		$_SESSION['pack_data'][2] = $data;

		$data = $_SESSION['pack_data'];
		
		$pack = new MCPack();
		$pack->Title = $data[0]['Title'];
		$pack->MCVersionID = $data[0]['MCVersion'];
		$pack->AuthorID = Member::currentUserID();
		// Image
		$pack->write();
		
		$mods = array();
		foreach($data[1]['Mods'] as $mod_id) {
			$mod = DataObject::get_by_id('MCMod', $mod_id);
			$pack->Mods()->add($mod);
			$mods[$mod_id] = $mod_id;
		}
		
		$version = new MCPackVersion();
		$version->PackID = $pack->ID;
		$version->write();
		
		foreach($data[2]['ModVersion'] as $mod_id => $mod_version_id) {
			unset($mods[$mod_id]);
			$packmod = new MCPackMod();
			$packmod->PackVersionID = $version->ID;
			$packmod->ModVersionID = $mod_version_id;
			$packmod->write();
			$packmod->buildConfig();
		}

		$this->redirect('pack/' . $pack->ID . '/version/' . $version->ID);
	}
	
	public function Client($request) {
		$pack = DataObject::get_by_id('MCPack', $request->param('ID'));
		$pack->trackDownload();
	}
	
	public function Server($request) {
		$pack = DataObject::get_by_id('MCPack', $request->param('ID'));
		$pack->trackDownload();
	}
	
	public function AddFavourite($request) {
		$pack = DataObject::get_by_id('MCPack', $request->param('ID'));
		
	}

	public function UpVote($request) {
		$pack = DataObject::get_by_id('MCPack', $request->param('ID'));
		$pack->UpVote();
		$this->redirect('home/packs');
	}
	
	public function DownVote($request) {
		$pack = DataObject::get_by_id('MCPack', $request->param('ID'));
		$pack->DownVote();
		$this->redirect('home/packs');
	}
}