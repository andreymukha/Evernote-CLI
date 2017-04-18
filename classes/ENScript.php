<?php
namespace AndreyMukha\EvernoteCLI;

use \Exception;
/**
 * You can invoke ENScript.exe with the commands described below.
 * Note that individual commands cannot be combined.
 * Some of the scriptable functionality in ENScript.exe overlaps with that in Evernote.exe.
 * ENScript.exe is available in version 3.0 and later of Evernote for Windows.
 * 
 * @author Andrey Mukha <andryfly7@gmail.com>
 *
 * Class ENScript
 */
class ENScript extends EvernoteCLIAbstract{

	/**
	 * ENScript constructor.
	 * 
	 * @param string $password
	 * @param string $username
	 * @param string $filename
	 * @param string $path_to_evernote
	 */
	function __construct($password, $username, $filename, $path_to_evernote) {
		parent::__construct($path_to_evernote);
		$this->password = $password;
		$this->username = $username;
		$this->filename = $filename;
	}


	/**
	 * Create a new note with the following options
	 *
	 * @param string $filename
	 * 	Specifies a file containing the note's plain text contents. If omitted, the note contents are read from standard input.
	 *
	 * @param bool|string $title
	 *	Specifies the title of the note.
	 *
	 * @param bool|string $notebook
	 * 	Specifies the name of the notebook to create the note in. If the notebook does not exist, it is created.
	 *
	 * @param bool|string $tag
	 * 	Specifies the name of a tag to apply to the note. If the tag does not exist, it is created. You can repeat this flag to add multiple tags.
	 *
	 * @param bool|string|array $filename2
	 * 	Specifies a file to attach to the note. You can repeat this flag to attach multiple files.
	 *
	 * @param bool|string $date
	 *	Specifies the note creation date and time using the format "YYYY/MM/DD hh:mm:ss". If omitted, the current time is used.
	 *
	 * @return array|null|string
	 * @throws Exception
	 */
	public function createNote($filename, $title = false, $notebook = false, $tag = false, $filename2 = false, $date = false){
		$query = 'createNote ';

		$file = realpath($filename);
		if(!$file and !file_exists($filename)){
			throw new Exception('File not fount: '.$filename);
		}
		$query .= "/s $file ";

		if($title !== false){
			$query .= "/i \"$title\" ";
		}

		if($notebook !== false){
			$query .= "/n \"$notebook\" ";
		}

		if($tag !== false){
			$query .= "/t \"$tag\" ";
		}

		if($filename2 !== false){
			if(is_array($filename2)){
				foreach ($filename2 as $file){
					$file2 = realpath($file);
					if(!$file and !file_exists($file)){
						throw new Exception('File not fount: '.$file);
					}
					$query .= "/a $file2 ";
				}
			}else{
				$file2 = realpath($filename2);
				if(!$file2 and !file_exists($filename2)){
					throw new Exception('File not fount: '.$filename2);
				}
				$query .= "/a $file2 ";
			}
		}

		if($date !== false){
			if(preg_match('!^[0-9]{4}\/(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1]) [0-2][0-9]\:[0-6][0-9]\:[0-6][0-9]$!', $date)){
				$query .= "/c \"$date\" ";
			}else{
				throw new Exception('Incorrect date: '.$date);
			}
		}

		$query = $this->insertConnection($query);

		return $this->ENScriptEXE($query);
	}

	/**
	 * Import one or more notes from an Evernote export file (ENEX):
	 *
	 * @param $filename
	 * 	Specifies a file containing an ENEX to import, or a URL from which an ENEX can be retrieved.
	 * 	If omitted, the ENEX is read from standard input.
	 *
	 * @param bool|string $notebook
	 * 	Specifies the name of the notebook to create the note(s) in. If the notebook does not exist, it is created.
	 *
	 * @return array|null|string
	 */
	public function importNotes($filename, $notebook = false){
		$query = 'importNotes ';

		$query .= "/s $filename ";

		if($notebook !== false){
			$query .= "/n \"$notebook\" ";
		}

		$query = $this->insertConnection($query);
		return $this->ENScriptEXE($query);
	}

	/**
	 * Set the current note list view to the results of a query:
	 *
	 * @param string $query
	 * 	Specifies the query to run. The query string is formatted according to the search grammar.
	 * 	To show all notes, use "/q any:"
	 *
	 * @return array|null|string
	 */
	public function showNotes($query = 'any:'){
		$query = $this->insertConnection($query);
		return $this->ENScriptEXE("showNotes /q $query");
	}

	/**
	 * Print a set of notes:
	 *
	 * @param string $query
	 * 	Specifies the query that selects the notes to be printed.
	 * 	The query string is formatted according to the search grammar.
	 * 	To show all notes, use "/q any:"
	 *
	 * @return array|null|string
	 */
	public function printNotes($query = 'any:'){
		$query = $this->insertConnection($query);
		return $this->ENScriptEXE("printNotes /q $query");
	}

	/**
	 * Export the set of notes to an Evernote export file (ENEX)
	 *
	 * @param string $query
	 * 	Specifies the query that selects the notes to be exported.
	 * 	The query string is formatted according to the search grammar. To show all notes, use "/q any:"
	 *
	 * @param bool|string $filename
	 * 	Specifies the name of the file to export the notes to. If omitted, the ENEX is written to standard output.
	 *
	 * @return array|null|string
	 */
	public function exportNotes($query = 'any:', $filename = false){
		if($query !== false){
			$query .= " /f $filename";
		}
		$query = $this->insertConnection($query);
		return $this->ENScriptEXE("exportNotes /q $query");
	}

	/**
	 * Create a new notebook
	 *
	 * @param string $notebook
	 * 	Specifies the name of the notebook. If omitted, the name is read from standard input
	 *
	 * @param bool|string $place
	 * 	Specifies whether the new notebook is a local or synchronized notebook. If omitted, a synchronized notebook is created.
	 *
	 * @return array|null|string
	 */
	public function createNotebook($notebook, $place = false){
		$query = '';
		
		$query .= " /n $notebook";

		if($place !== false){
			$query .= " /t $place";
		}
		$query = $this->insertConnection($query);
		return $this->ENScriptEXE("createNotebook $query");
	}

	/**
	 * Lists existing notebooks
	 *
	 * @param bool $place
	 * 	Specifies whether local or synchronized notebooks are listed. If omitted, all notebooks are listed.
	 *
	 * @return array|null|string
	 */
	public function listNotebooks($place = false){
		$query = '';
		if($place !== false){
			$query .= " /t $place";
		}
		$query = $this->insertConnection($query);
		return $this->ENScriptEXE("listNotebooks $query");
	}

	/**
	 * Causes Evernote for Windows to synchronize with the Evernote service
	 *
	 * @param bool|string $filename
	 * 	Specifies the name of a file to write log messages to. If omitted, log messages are written to standard output.
	 * 
	 * @return array|null|string
	 */
	public function syncDatabase($filename = false){
		$query = "";
		if($filename !== false){
			$query .= " /l $filename";
		}
		$query = $this->insertConnection($query);
		return $this->ENScriptEXE("syncDatabase $query");
	}
}