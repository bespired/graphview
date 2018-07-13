<?php

return [

	'nodes' => [
		[
			'suid' => 'Nv',
			'name' => 'Visitors',
			'type' => 'multiple-public',
			'draw' => [
				'top' => 80, 'left' => 400,
			],
			'props' => [
				'strings' => [
					'fingerprint' => [],
					'ip' => [],
					'agent' => [],
				],
			],
		], [
			'suid' => 'Np',
			'name' => 'Persons',
			'type' => 'multiple-public',
			'draw' => [
				'top' => 80 + 160, 'left' => 400,
			],
			'props' => [
				'keys' => [
					'email' => [],
				],
				'strings' => [
					'birthday' => [],
					'contact_status' => [],
					'facebook' => [],
					'firstname' => [],
					'gender' => [],
					'infix' => [],
					'instagram' => [],
					'job_title' => [],
					'language' => [],
					'lastname' => [],
					'salutation' => [],
					'skype' => [],
					'twitter' => [],
				],
			],
		], [
			'suid' => 'Nc',
			'name' => 'Companies',
			'type' => 'multiple-public',
			'draw' => [
				'top' => 80 + 160 + 160, 'left' => 400,
			],
			'props' => [
				'keys' => [
					'name' => [],
				],
				'strings' => [
					'chamberofcommerce_code' => [],
					'employee_count' => [],
					'legal_form' => [],
					'sap_code' => [],
					'turnover_annual' => [],
					'turnover_currency' => [],
					'vat_code' => [],
					'website' => [],
					'workarea' => [],
				],
			],
		], [
			'suid' => 'Nt',
			'name' => 'Tags',
			'type' => 'single-public',
			'draw' => [
				'top' => 120, 'left' => 140,
			],
			'props' => [
				'keys' => [
					'name' => [],
				],
			],
		], [
			'suid' => 'Nq',
			'name' => 'Qualifiers',
			'type' => 'single-public',
			'draw' => [
				'top' => 120 + 160, 'left' => 140,
			],
			'props' => [
				'keys' => [
					'name' => [],
				],
			],
		], [
			'suid' => 'Na',
			'name' => 'Addresses',
			'type' => 'multiple-public',
			'draw' => [
				'top' => 120 + 160, 'left' => 640,
			],
			'props' => [
				'keys' => [
					'telephone_land' => [],
					'telephone_mobile' => [],
				],
				'strings' => [
					'address' => [],
					'address_additional' => [],
					'city' => [],
					'country' => [],
					'district' => [],
					'long_lat' => [],
					'state' => [],
					'telephone_fax' => [],
					'zipcode' => [],
				],
			],
		], [
			'suid' => 'Nn',
			'name' => 'Notes',
			'type' => 'multiple-private',
			'draw' => [
				'top' => 120 + 160 + 160, 'left' => 140,
			],
			'props' => [
				// 'keys' => [
				// 	'tag' => [],
				// ],
				'strings' => [
					'note' => [],
				],
			],
		],
	],
	'edges' => [
		[
			'suid' => 'E1',
			'name' => 'company',
			'type' => 'has-tag',
			'direction' => 'out',
			'startpoint' => 'Np',
			'endpoint' => 'Nc',

		], [
			'suid' => 'E2',
			'name' => 'has address',
			'type' => 'has-one',
			'direction' => 'out',
			'startpoint' => 'Np',
			'endpoint' => 'Na',

		], [
			'suid' => 'E3',
			'name' => 'has address',
			'type' => 'has-one',
			'direction' => 'out',
			'startpoint' => 'Nc',
			'endpoint' => 'Na',

		], [
			'suid' => 'E4',
			'name' => 'has score',
			'type' => 'has-many',
			'direction' => 'out',
			'startpoint' => 'Nv',
			'endpoint' => 'Nq',
		], [
			'suid' => 'E5',
			'name' => 'has score',
			'type' => 'has-many',
			'direction' => 'out',
			'startpoint' => 'Np',
			'endpoint' => 'Nq',
		], [
			'suid' => 'E6',
			'name' => 'is person',
			'type' => 'has-one',
			'direction' => 'out',
			'startpoint' => 'Nv',
			'endpoint' => 'Np',
		], [
			'suid' => 'E7',
			'name' => 'has tag',
			'type' => 'has-one',
			'direction' => 'out',
			'startpoint' => 'Np',
			'endpoint' => 'Nt',
		], [
			'suid' => 'E8',
			'name' => 'has notes',
			'type' => 'has-many',
			'direction' => 'out',
			'startpoint' => 'Np',
			'endpoint' => 'Nn',
		],
	],

];
