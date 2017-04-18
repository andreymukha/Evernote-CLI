<?php
namespace AndreyMukha\EvernoteCLI;

/**
 * Base class for run Evernote command
 *
 * Class EvernoteCLI
 * 
 * @author Andrey Mukha <andryfly7@gmail.com>
 */
class EvernoteCLI extends EvernoteCLIAbstract{

	/**
	 * @param string $password
	 * @param bool|string $username
	 * @param bool|string $filename
	 * @param string $path_to_evernote
	 * @return ENScript
	 */
	public function ENScript($password, $username = false, $filename = false, $path_to_evernote = 'c:/Program Files (x86)/Evernote/Evernote/') {
		return new ENScript($password, $username, $filename, $path_to_evernote);
	}

	/**
	 * @param string $path_to_evernote
	 * @return Evernote
	 */
	public function Evernote(){
		return new Evernote();
	}
}