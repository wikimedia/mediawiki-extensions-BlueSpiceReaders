<?php

namespace BlueSpice\Readers\ConfigDefinition;

use BlueSpice\ConfigDefinition\IntSetting;

class ReadersNumOfReaders extends IntSetting {
	public function getLabelMessageKey() {
		return 'bs-readers-pref-numofreaders';
	}
}