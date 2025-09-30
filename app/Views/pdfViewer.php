<?php
	$path = '';
	$type = '';
	$file_source = '';
	$licenseKey = return_value_in_options('pdf_viewer_license')['pdfjse'];
	switch ($file) {
		case 'skp':
		    if(!empty($data_file))
		    {
		    	if(file_exists($data_file->file)){
		        	$path = $data_file->file;
		    	}
		    	if(!array_keys([0,''],$path))
		    	{
			        $type = pathinfo($path, PATHINFO_EXTENSION);
			        $data = file_get_contents($path);
			        $file_source = /*'data:application/'. $type .';base64,'.*/base64_encode($data);
			        // print_r(bsre_file_sign_verify($path, 'test.pdf'));
			        ?>
                    <div id='viewerPDFJSE' style='height:690px; margin: 0 auto; border:1px solid grey;'></div>
					<?php
				}else{
		        	echo '<h4>File tidak ditemukan..</h4>';
				}
		    }else{
		        echo '<h4>File tidak ditemukan...</h4>';
		    } 
			break;
		default:
		    if(file_exists($data_file->path))
		    {
		        $path = $data_file->path;
		        $type = pathinfo($path, PATHINFO_EXTENSION);
		        $data = file_get_contents($path);
		        $file_source = /*'data:application/'. $type .';base64,'.*/base64_encode($data);
		        ?>
                <div id='viewerPDFJSE' style='height:690px; margin: 0 auto; border:1px solid grey;'></div>
				<?php
		    }else{
		        echo '<h4>File tidak ditemukan...</h4>';
		    } 
			break;
	}
?>
<script src="<?=base_url('assets/js/jquery-3.7.1.min.js')?>"></script>
<script src='<?=base_url('assets/vendors/pdfjse/lib/webviewer.min.js')?>'></script>
<script>
    $(function(){
        pushPdfToViewer('<?=$file_source?>', '<?=$licenseKey?>', '<?=$file.''.date('YmdHis').'.'.$type?>')
    })

	function pushPdfToViewer(pdf, license, pdf_source='fileView<?=date('YmdHis')?>.pdf', elementID='viewerPDFJSE')
	{
	  WebViewer({
	      path: '<?=base_url()?>assets/vendors/pdfjse/lib',
	      licenseKey: license,
	  },document.getElementById(elementID))
	  .then(instance => {
	      // instance.UI.loadDocument(pdf, { filename: pdf_source_path });
	      instance.UI.loadDocument(base64ToBlob(pdf), { filename: pdf_source });
	      const { documentViewer } = instance.Core;
	      var FitMode = instance.UI.FitMode;
	      documentViewer.addEventListener('documentLoaded', () => {
	        instance.UI.setFitMode(FitMode.FitWidth);
	      });
	  });
	}

	function base64ToBlob(base64) {
	    const binaryString = window.atob(base64);
	    const len = binaryString.length;
	    const bytes = new Uint8Array(len);
	    for (let i = 0; i < len; ++i) {
	        bytes[i] = binaryString.charCodeAt(i);
	    }
	    return new Blob([bytes], { type: 'application/pdf' });
	}
</script>