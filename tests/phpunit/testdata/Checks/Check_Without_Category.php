<?php

namespace WordPress\Plugin_Check\Test_Data;

use WordPress\Plugin_Check\Checker\Check_Result;
use WordPress\Plugin_Check\Checker\Static_Check;
use WordPress\Plugin_Check\Traits\Stable_Check;

class Check_Without_Category implements Static_Check {

	use Stable_Check;

	public function run( Check_Result $check_result ) {
		return;
	}

	public function get_categories() {
		return array();
	}

	public function get_description(): string {
		return '';
	}

	public function get_documentation_url(): string {
		return '';
	}
}
