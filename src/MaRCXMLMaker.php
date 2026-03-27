<?php 

class MaRCXMLMaker 
{
	private $recs;
	private $rec;
	private $leader;
	private $subfields;
	
	public function __construct()
	{
		$this->recs = array();
		$this->rec = '';
		$this->leader = '^^^^^^^^^^^^^^^^^^^^4500';
		$this->subfields = '';
	}
	
	public function buildLeader($pos=null, $str='')
	{
		if ( is_null($pos)) {
			$this->leader = substr(strval($str), 0, 24);
		} elseif (is_int($pos)) {
			$this->leader = substr_replace($this->leader, strval($str), $pos, strlen($str));
		}

		$this->leader = str_pad(substr($this->leader, 0, 24), 24, '^', STR_PAD_RIGHT);

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
		
		$this->subfields .= $subfield;

        return;
	}
	
	public function buildDatafield($tag='500', $ind1='', $ind2='')
	{
		if ( $this->subfields !== '' ) {
			$this->rec .= "\t\t" . '<datafield tag="' . $this->_html($tag) 
        		. '" ind1="' . $this->_html($ind1) 
        		. '" ind2="' . $this->_html($ind2) . '">' . "\n"
			. $this->subfields . "\t\t" . '</datafield>' . "\n";
		} else {
			$this->rec .= '';
		}

		$this->subfields = '';
		
		return;
	}
	
	public function addRec($rec='')
	{
		$this->rec = "\t\t<leader>" . $this->leader . "</leader>\n" . $this->rec;
		$this->recs[] = $this->getRec();
		$this->rec = '';
		$this->leader = '^^^^^^^^^^^^^^^^^^^^4500';
		$this->subfields = '';
		
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
}
