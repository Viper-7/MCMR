<?php

Director::addRules(100, array('pack/$ID/$Action//$OtherID' => 'Packs_Controller'));
Director::addRules(100, array('mod/$ID/$Action' => 'Mods_Controller'));
