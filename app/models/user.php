<?php

class User extends BaseModel {
	public $id, $name, $hash, $email, $admin;

	public function __construct($attributes){
		parent::__construct($attributes);
	}

	public function save() {
		$q = DB::connection()->prepare(
			'INSERT INTO forum_user (name, hash, email, accepted, admin) VALUES (:name, :hash, :email, :accepted, :admin) RETURNING id'
		);

		$q->execute(array(
			'name' => $this->name,
			'hash' => $this->hash,
			'email' => $this->email,
			'accepted' => $this->accepted,
			'admin' => $this->admin
		));

		$row = $q->fetch();

		$this->id = $row['id'];
	}

	public static function all() {
		$q = DB::connection()->prepare('SELECT * FROM forum_user');
		$q->execute();
		
		$rows = $q->fetchAll();
		$users = array();

		foreach($rows as $row){
			$users[] = new User(array(
				'id' => $row['id'],
				'name' => $row['name'],
				'hash' => $row['hash'],
				'email' => $row['email'],
				'admin' => $row['admin']
			));
		}

		return $users;
	}

	public static function find($id) {
		$q = DB::connection()->prepare('SELECT * FROM forum_user WHERE id = :id LIMIT 1');
		$q->execute(array('id' => $id));

		$row = $q->fetch();

		if($row) {
			return new User(array(
				'id' => $row['id'],
				'name' => $row['name'],
				'hash' => $row['hash'],
				'email' => $row['email'],
				'accepted' => $row['accepted'],
				'admin' => $row['admin']
			));
		} else {
			return null;
		}
	}

	public static function findBy($key, $val) {
		$q = DB::connection()->prepare('SELECT * FROM forum_user WHERE ' . $key . ' = :val LIMIT 1');
		$q->execute(array('val' => $val));

		$row = $q->fetch();

		if($row) {
			return new User(array(
				'id' => $row['id'],
				'name' => $row['name'],
				'hash' => $row['hash'],
				'email' => $row['email'],
				'accepted' => $row['accepted'],
				'admin' => $row['admin']
			));
		} else {
			return null;
		}
	}
}

?>