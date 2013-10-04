<?php

Director::addRules(100, array('vote/pack/$ID/$Action' => 'Packs_Controller'));
Director::addRules(100, array('downloads/pack/$ID/$Action' => 'Packs_Controller'));
Director::addRules(100, array('favourites/pack/$ID/$Action' => 'Packs_Controller'));
