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

    public function CreatePackForm() {
        $fields = new FieldList(
            new TextField('Name'),
			new OptionsetField('MCVersion', 'Minecraft Version', DataObject::get('MCVersion')->Map()),
			$f = new UploadField('PackImage', 'Pack Icon')
        );
        
		$f->getValidator()->setAllowedExtensions(array('jpg', 'gif', 'png'));
		
        $actions = new FieldList(
            new FormAction('doCreatePack', 'Choose Mods')
        );
     
        return new Form($this, 'CreatePackForm', $fields, $actions);
    }

	public function ChooseModsForm() {
        $fields = new FieldList(
            new CheckboxSetField('Mods', 'Mods included in this pack', DataObject::get('MCMod')->Map())
        );
         
        $actions = new FieldList(
            new FormAction('doChooseMods', 'Build Pack')
        );
     
        return new Form($this, 'ChooseModsForm', $fields, $actions);
	}
	
	public function BuildPackForm() {
        $fields = new FieldList(
            new Object()
        );
         
        $actions = new FieldList(
            new FormAction('doBuildPack', 'Save Pack')
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
	
	public function doCreatePack($request) {
		$_SESSION['pack_state'] = 1;
		
	}
	
	public function doChooseMods($request) {
		$_SESSION['pack_state'] = 2;
	
	}
	
	public function doBuildPack($request) {
		$_SESSION['pack_state'] = 3;
	
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
		return Director::redirect('home/packs');
	}
	
	public function DownVote($request) {
		$pack = DataObject::get_by_id('MCPack', $request->param('ID'));
		$pack->DownVote();
		return Director::redirect('home/packs');
	}
}