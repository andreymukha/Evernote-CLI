<?php
/**
 * You can invoke Evernote.exe with the commands described below. Note that individual commands cannot be combined.
 * If Evernote is already running, your command will be passed to the existing instance.
 *
 * Class Evernote
 *
 * @author Andrey Mukha <andryfly7@gmail.com>
 */
final class Evernote extends EvernoteCLIAbstract{

	/**
	 * If you pass the full path to a file, a new note is created.
	 * If you pass a .txt, .htm or .html file, the file contents are used as the new note's content.
	 * If you pass a .enex file, the Evernote note(s) that it contains are imported.
	 * If you pass a .url file, the hyperlink is used as the new note's content.
	 * If you pass any other file type, it is attached to the new note.
	 *
	 * @param $filepath
	 * @return array|string|null
	 * @throws Exception
	 */
	public function filename($filepath){
		$file = realpath($filepath);
		if(!$file and !file_exists($filepath)){
			throw new Exception('File not fount: '.$filepath);
		}
		return $this->EvernoteEXE($file);
	}

	/**
	 * Opens a new window with a new, empty note.
	 * Equivalent to right-clicking on the Evernote icon in the taskbar and choosing "New note".
	 * This command was added in Evernote for Windows version 3.5.
	 *
	 * @return array|null|string
	 */
	public function NewNote(){
		return $this->EvernoteEXE('/NewNote');
	}

	/**
	 * Opens a new window with a new, empty ink note. This command was added in Evernote for Windows version 3.5.
	 *
	 * @return array|null|string
	 */
	public function NewInkNote(){
		return $this->EvernoteEXE('/NewInkNote');
	}

	/**
	 * Opens a new window that allows the user to capture a new web cam note.
	 * This command was added in Evernote for Windows version 3.5.
	 *
	 * @return array|null|string
	 */
	public function NewWebCamNote(){
		return $this->EvernoteEXE('/NewWebCamNote');
	}

	/**
	 * Invokes Evernote's screenshot clipper, which allows the user to take a screenshot of the desired portion of their screen and save it in a new note.
	 * Equivalent to right-clicking on the Evernote icon in the taskbar and choosing "Clip screenshot".
	 * This command was added in Evernote for Windows version 4.0.
	 *
	 * @return array|null|string
	 */
	public function TaskClipScreen(){
		return $this->EvernoteEXE('/Task:ClipScreen');
	}

	/**
	 * Creates a new note containing the contents of the clipboard.
	 * Equivalent to right-clicking on the Evernote icon in the taskbar and choosing "Paste clipboard".
	 * If the clipboard contains text or HTML, or a .txt, .htm or .html file, it is used as the new note's content.
	 * If the clipboard contains a .enex file, the Evernote note(s) that it contains are imported.
	 * If the clipboard contains a .url file, the hyperlink is used as the new note's content.
	 * If the clipboard contains any other type of file, it is attached to the new note.
	 * This command was added in Evernote for Windows version 4.0.
	 *
	 * @return array|null|string
	 */
	public function TaskPasteClipboard(){
		return $this->EvernoteEXE('/Task:PasteClipboard');
	}

	/**
	 * Causes Evernote for Windows to synchronize with the Evernote service.
	 * Equivalent to right-clicking on the Evernote icon in the taskbar and choosing "Sync".
	 * This command was added in Evernote for Windows version 4.0.
	 * 
	 * @return array|null|string
	 */
	public function TaskSyncDatabase(){
		return $this->EvernoteEXE('/Task:SyncDatabase');
	}
}