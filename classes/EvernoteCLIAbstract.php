<?php
namespace AndreyMukha\EvernoteCLI;

use \Exception;
/**
 * Class EvernoteCLIAbstract
 * 
 * @author Andrey Mukha <andryfly7@gmail.com>
 */
abstract class EvernoteCLIAbstract {
	/**
	 * @var string
	 * 	Path to Evernote folder, where placed executable files
	 */
	protected $path_to_evernote;

	/**
	 * @var string
	 * 	Password for evernote account
	 */
	protected $password;

	/**
	 * @var string
	 * 	Username for evernote account
	 */
	protected $username;

	/**
	 * @var string
	 * 	If username and database file are different, you must specify path to .exb file
	 */
	protected $filename;

	/**
	 * EvernoteCLIAbstract constructor.
	 * @param string $path_to_evernote
	 */
	function __construct($path_to_evernote = 'c:/Program Files (x86)/Evernote/Evernote/') {
		$last_char = strlen($path_to_evernote)-1;

		if($path_to_evernote[$last_char] != '/'){
			if($path_to_evernote[$last_char] != '\\'){
				$path_to_evernote .= '/';
			}
		}

		if(!file_exists($path_to_evernote) or (!file_exists($path_to_evernote.'Evernote.exe') and !file_exists($path_to_evernote.'ENScript.exe'))){
			throw new Exception('Evernote not found in: '.$path_to_evernote);
		}

		$this->path_to_evernote = $path_to_evernote;
	}

	/**
	 * Insert authorize data in query
	 * 
	 * @param string $query
	 * @return string $query
	 */
	protected function insertConnection($query){
		$query .= " /p $this->password ";
		if($this->username !== false){
			$query .= " /u \"$this->username\" ";
		}
		if($this->filename !== false){
			$query .= " /d \"$this->filename\" ";
		}
		return $query;
	}

	/**
	 * Execute Evernote command
	 * 
	 * @param string $query
	 * @return string|array|null
	 */
	private function execute($query){
		$query .= ' 2>&1';
		$result = exec($query, $out, $return_var);
		if(count($out) <= 1){
			return $result;
		}else{
			return $out;
		}
	}


	/**
	 * You can invoke Evernote.exe with the commands described below. Note that individual commands cannot be combined.
	 * If Evernote is already running, your command will be passed to the existing instance.
	 *
	 * @param string $query
	 * @return string|array|null
	 * @throws Exception
	 */
	protected function EvernoteEXE($query){
		$path = '"'.$this->path_to_evernote.'Evernote.exe" ';
		return $this->execute($path.$query);
	}

	/**
	 *  You can invoke ENScript.exe with the commands described below.
	 *  Note that individual commands cannot be combined. Some of the scriptable functionality in ENScript.exe overlaps with that in Evernote.exe.
	 * 	ENScript.exe is available in version 3.0 and later of Evernote for Windows.
	 *
	 * @param string $query
	 * @return string|array|null
	 * @throws Exception
	 */
	protected function ENScriptEXE($query){
		$path = '"'.$this->path_to_evernote.'ENScript.exe" ';
		return $this->execute($path.$query);
	}
}