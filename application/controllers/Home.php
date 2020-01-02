<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		$this->load->model('kamus_model', 'kamus');
		$this->load->library('docxToTextConversion');
		$this->load->library('pdf2Text');
		$this->load->helper('stemming_nazief');
	}

	public function index()
	{
		$data = array(
			'title' => 'Information Retrieval',
			'success' 	=> $this->session->flashdata('success'),
		);
		$this->load->view('home', $data);
	}

	function upload()
	{
		$this->load->library('upload');

		$config['upload_path'] = FCPATH . 'assets/';
        $config['allowed_types'] = 'doc|docx|pdf';
        $config['max_size'] = 2048;
        $config['overwrite'] = TRUE;
        $config['file_ext_tolower'] = TRUE;
		
		$this->upload->initialize($config);
		
		if ($this->upload->do_upload('dokumen')){
			$data = array('upload_data' => $this->upload->data());
			$upload_data = $this->upload->data();
			$file_name = $upload_data['file_name'];
			$file_ext = $upload_data['file_ext'];

			if($file_ext === '.doc'){
				$fileHandle = fopen(FCPATH . 'assets/'.$file_name, "r");
		        $line = @fread($fileHandle, filesize(FCPATH . 'assets/'.$file_name));   
		        $lines = explode(chr(0x0D),$line);
		        $outtext = "";
		        foreach($lines as $thisline)
          		{
            		$pos = strpos($thisline, chr(0x00));
            		if (($pos !== FALSE)||(strlen($thisline)==0)){}
            		else {
                		$outtext .= $thisline." ";
              		}
          		}
         		fclose($fileHandle);
         		$outtext = preg_replace("/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/","",$outtext);
         		$token = $this->retrieval($outtext);
			} elseif($file_ext === '.docx'){
				$converter = new DocxToTextConversion(FCPATH . 'assets/'.$file_name);
				$token = $this->retrieval($converter->convertToText());
			} elseif($file_ext === '.pdf'){
				$a = new PDF2Text();
				$a->setFilename(FCPATH . 'assets/'.$file_name); 
				$a->decodePDF();
				$token = $this->retrieval($a->output());
			}
			unlink(FCPATH . 'assets/'.$file_name);
			echo @$token;
			$this->output->enable_profiler(FALSE);
		} else {
			$error = array('error' => $this->upload->display_errors());
			echo $error['error'];
		}
	}

	function retrieval($text_convert=0)
	{
		$text_no_simbol_number = preg_replace(array('/[^\da-z ]/i','/\d+/u'), '', $text_convert);
		$text_lower = strtolower($text_no_simbol_number);
		$text_array = preg_split("/[\s,.:]+/", $text_lower);

		// STOPWORD REMOVE		
		$data_stem = array();
		foreach($text_array as $item)
		{
		    $stopword = $this->kamus->get_where('dictionary', array('word' => $item, 'stopword' => 'Ya'));
			if($stopword->num_rows() > 0){
				echo false;
			} else {
				// STEMMING
				$item_stem = stemming($item);
				if($item_stem !== NULL){
			    	array_push($data_stem, $item_stem);
			    }
			}
		}
		// TOKENISASI
		$result_stem = array_count_values($data_stem);
		array_multisort($result_stem, SORT_NUMERIC, SORT_DESC);
		return json_encode(array($result_stem));
	}
}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */