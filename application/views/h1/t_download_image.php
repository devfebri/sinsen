<?php
		ob_start();
		$bin = base64_decode($b64, true);

		# Perform a basic validation to make sure that the result is a valid PDF file
		# Be aware! The magic number (file signature) is not 100% reliable solution to validate PDF files
		# Moreover, if you get Base64 from an untrusted source, you must sanitize the PDF contents
		if (strpos($bin, '%PDF') !== 0) {
			// throw new Exception('Missing the PDF file signature');
		}

		# Write the PDF contents to a local file
		//file_put_contents('file.pdf', $bin);

		# Base64 to pdf (create file pdf from database, no file in server)
		/**/

		ob_clean(); // jika mau file download aktifkan line ini, jika utk preview dinonaktifkan
		// content pdf yang rumit (fpdf) tidak bisa generate file pdf dengan sempurna / corrupt

		header('Content-Description: File Transfer');
		
		if($ext == 'xls' || $ext =='xlsx'){
			header("Content-type: application/vnd-ms-excel");
			header('Content-disposition: attachment; filename='.$filename); // komen line ini utk bisa preview pdf atau aktifkan line ini utk bisa download file pdf
			header('Content-Length: '.strlen($bin));
			header("Cache-Control: max-age=0");
		}else{
			header('Content-Type: application/'.$ext);
			header('Content-disposition: attachment; filename='.$filename); // komen line ini utk bisa preview pdf atau aktifkan line ini utk bisa download file pdf
			header('Content-Length: '.strlen($bin));
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		}
		
		header('Expires: 0');
		header('Pragma: public');
		echo $bin;
		exit;

		# atau bisa dengan akses link from path file (perlu save file di server). 
		# eg: http://localhost/base64/aplikasi.pdf
		ob_end_flush();
?>
