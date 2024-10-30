<?php

	class HelloHereFunctions {
		private $_table_name;
		private $_wpdb;

		public function __construct($table_name) {
			global $wpdb; // this is how you get access to the database
			$this->_wpdb = $wpdb;
			$this->_table_name = $wpdb->prefix . $table_name;
		}

		private function _getGoMeets(){
			$_meets = $this->_wpdb->get_results("SELECT * FROM $this->_table_name ORDER BY scheduled_date DESC, created_at DESC", ARRAY_A);

			$meets = array();
			foreach ( $_meets as $meet ) {
				if($meet['is_custom_domain'] === '0'){
					$meet['domain'] = 'Public server';
				}
				$meets[] = $meet;
			}

			return $meets;
		}

		public function getGoMeets(){
			$meets = $this->_getGoMeets();

			return $meets;
		}

		private function _getGoMeet($code){
			$meet = $this->_wpdb->get_results("SELECT * FROM $this->_table_name WHERE code = '".$code."'", ARRAY_A);

			return $meet;
		}
		public function getGoMeet($code){
			return $this->_getGoMeet($code);
		}

		private function _createGoMeet($data){
			try {
				$title         = $data[ 'title' ];
				$meeting_room  = $data[ 'meeting_room' ];
				$code          = $data[ 'code' ];
				$is_scheduled  = $data[ 'is_scheduled' ];
				$domain        = $data[ 'domain' ];
				$custom_domain = $data[ 'custom_domain' ];

				$is_scheduled = ( $is_scheduled === "true" ? 1 : 0 );
				if ( $is_scheduled ) {
					$scheduled_date = $data[ 'scheduled_date' ];
				} else {
					$scheduled_date = NULL;
				}

				$custom_domain = ( $custom_domain === "true" ? 1 : 0 );

				$inserted = $this->_wpdb->insert(
					$this->_table_name,
					array(
						'title'            => $title,
						'meeting_room'     => $meeting_room,
						'code'             => $code,
						'is_scheduled'     => $is_scheduled,
						'scheduled_date'   => $scheduled_date,
						'created_at'       => date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) ),
						'domain'           => $domain,
						'is_custom_domain' => $custom_domain
					)
				);

				return $inserted;
			}catch(Exception $ex){
				return $ex->getMessage();
			}
		}

		public function createGoMeet($data){
			return $this->_createGoMeet($data);
		}

		private function _deleteGoMeet($id){
			$sql = "DELETE FROM $this->_table_name WHERE id = $id";
			$this->_wpdb->query($sql);

			return 'ok';
		}

		public function deleteGoMeet($id){
			return $this->_deleteGoMeet($id);
		}
	}
