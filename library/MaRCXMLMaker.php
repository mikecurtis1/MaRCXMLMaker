<?php 

class MaRCXMLMaker 
{
	private $recs;
	private $rec;
	
	public function __construct()
	{
		$this->recs = array();
	}
	
	public function buildLeader($type='book')
	{
		//NOTE: http://www.loc.gov/marc/bibliographic/bdleader.html, 
        	//NOTE: http://www.loc.gov/marc/ldr06guide.html, 
		if ( $type === 'book' ) {
			$this->rec .= "\t\t<leader>^^^^^^am^^^^^^^^^^^^^^^^</leader>\n";
		} elseif ( $type === 'dvd' ) {
			$this->rec .= "\t\t<leader>^^^^^^gm^^^^^^^^^^^^^^^^</leader>\n";
		} elseif ( $type === 'website' ) {
			$this->rec .= "\t\t<leader>^^^^^^mi^^^^^^^^^^^^^^^^</leader>\n";
		}  else {
			$this->rec .= "\t\t<leader>^^^^^^^^^^^^^^^^^^^^^^^^</leader>\n";
		}
		
		return;
	}
	
	public function buildControlfield($tag='a', $str='')
	{
		$this->rec .= "\t\t" . '<controlfield tag="' . $this->_html($tag) . '">' 
        	. $this->_html($str) . '</controlfield>' . "\n";
		
        return;
	}
	
	public function buildSubfield($code='a', $str='')
	{
		$subfield = "\t\t\t" . '<subfield code="' . $this->_html($code) . '">' 
        	. $this->_html($str) . '</subfield>' . "\n";
		
        return $subfield;
	}
	
	public function buildDatafield($tag='500', $ind1='', $ind2='', $subfields='')
	{
		if ( $subfields !== '' ) {
			$this->rec .= "\t\t" . '<datafield tag="' . $this->_html($tag) 
        		. '" ind1="' . $this->_html($ind1) 
        		. '" ind2="' . $this->_html($ind2) . '">' . "\n"
			. $subfields . "\t\t" . '</datafield>' . "\n";
		} else {
			$this->rec .= '';
		}
		
		return;
	}
	
	public function addRec($rec='')
	{
		$this->recs[] = $this->getRec();
		$this->rec = '';
		
		return;
	}
	
	public function getRec()
	{
		return "\t<record>\n" . $this->rec . "\t</record>\n";
	}
	
	public function getCollection()
	{
		return "<collection>\n" . implode('', $this->recs) . "</collection>";
	}
	
	private function _html($str='')
	{
		return htmlspecialchars($str, ENT_COMPAT, 'ISO-8859-1', false);
	}
	
	public function makeFile($filename, $content, $mode='w')
	{
		if(!is_file($filename)){
			//NOTE: mode a = append, w = write
        		$ourFileHandle = fopen($filename, $mode);
			fwrite($ourFileHandle, $content);
			fclose($ourFileHandle);
		}
	}
}
