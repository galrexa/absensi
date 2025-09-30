<script type="text/javascript">
	// $(function(){
		// $('[data-bs-toggle="tooltip"]').tooltip()
		// const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
		// const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
	// })
	$(function(){
		$("#flashdata_message").fadeTo(5000, 500).slideUp(500, function(){
			$("#flashdata_message").slideUp(500);
		});
	})

	<?php #if(return_access_link(['kepegawaian/download/foto'])){?>
		function download_foto(status, page='')
		{
			window.open('<?=site_url('kepegawaian/download/foto')?>?status='+status+'&page='+page+'&status_pns='+$('#input_status_pns').val()+'&unit_kerja='+$("#input_unit_kerja").val()+'&jabatan='+$('#input_jabatan').val()+'&kelamin='+$('#input_kelamin').val(), '_blank')
		}
	<?php #}?>
	<?php #if(return_access_link(['kepegawaian/download/data'])){?>
		function download_data(status, page='')
		{
			window.open('<?=site_url('kepegawaian/download/data')?>?status='+status+'&page='+page+'&status_pns='+$('#input_status_pns').val()+'&unit_kerja='+$("#input_unit_kerja").val()+'&jabatan='+$('#input_jabatan').val()+'&kelamin='+$('#input_kelamin').val(), '_blank')
		}
	<?php #}?>



    function select2_referensi(element, ref)
    {
        $(element).select2({
            placeholder: 'Pilih Data',
            delay: 90,
            ajax: {
                url: '<?=site_url('api/referensi')?>',
                dataType: 'json',
                data: function (params) {
                    var query = {
                        id: ref,
                        search: params.term,
                    }
                    return query;
                },
                processResults: function (data) {
                    var dataSet = [];
                    $.each(data.data, function(i, item){
                        var tmp = {id:item.ref_code, text: item.ref_name+''}
                    	if(ref=='pegawai_golongan')
                    	{
                        	tmp = {id:item.ref_name, text: item.ref_name+''}
                    	}
                    	if(ref=='cuti')
                    	{
                        	tmp = {id:item.ref_code, text: item.ref_name+' - '+item.ref_description}
                    	}
                        dataSet.push(tmp)
                    })
                    return {
                        results: dataSet
                    };
                }
            }
        });
    }


    function select2_wilayah(element, ref='')
    {
        $(element).select2({
            placeholder: 'Pilih Wilayah',
            delay: 90,
            ajax: {
                url: '<?=site_url('api/wilayah')?>',
                dataType: 'json',
                data: function (params) {
                    var query = {
                        id: ref,
                        search: params.term,
                    }
                    return query;
                },
                processResults: function (data) {
                    var dataSet = [];
                    $.each(data.data, function(i, item){
                        var tmp = {id:item.id, text: item.name+''}
                        dataSet.push(tmp)
                    })
                    return {
                        results: dataSet
                    };
                }
            }
        });
    }


    function select2_unit_kerja(element)
    {
	    $(element).select2({
	        placeholder: 'Pilih Unit Kerja',
	        delay: 90,
	        ajax: {
	            url: '<?=site_url('api/unit_kerja')?>',
	            dataType: 'json',
	            data: function (params) {
	                var query = {
	                    search: params.term
	                }
	                return query;
	            },
	            processResults: function (data) {
	                var dataSet = [];
	                $.each(data.data, function(i, item){
	                	var txtAlt = ""
	                	if(item.unit_kerja_id != "1" || item.unit_kerja_id != "5"){
	                		txtAlt = item.unit_kerja_name_alt+' - '
	                	}
	                    var tmp = {id:item.unit_kerja_id, text: txtAlt+item.unit_kerja_name+''}
	                    dataSet.push(tmp)
	                })
	                return {
	                    results: dataSet
	                };
	            }
	        }
	    });
	}


	function select2_jabatan(element, lock='')
	{
	    $(element).select2({
	        placeholder: 'Pilih Jabatan',
	        delay: 90,
	        ajax: {
	            url: '<?=site_url('api/jabatan')?>',
	            dataType: 'json',
	            data: function (params) {
	                var query = {
	                    lock: lock,
	                    search: params.term,
	                }
	                return query;
	            },
	            processResults: function (data) {
	                var dataSet = [];
	                $.each(data.data, function(i, item){
	                    var tmp = {id:item.jabatan_id, text: item.jabatan_name+''}
	                    dataSet.push(tmp)
	                })
	                return {
	                    results: dataSet
	                };
	            }
	        }
	    });
	}


    function select2_perguruan_tinggi(element)
    {
	    $(element).select2({
	        placeholder: 'Pilih Perguruan Tinggi atau Universitas',
	        delay: 90,
	        ajax: {
	            url: '<?=site_url('api/perguruan_tinggi')?>',
	            dataType: 'json',
	            data: function (params) {
	                var query = {
	                    search: params.term
	                }
	                return query;
	            },
	            processResults: function (data) {
	                var dataSet = [];
	                $.each(data.data, function(i, item){
	                    var tmp = {id:item.id_pt, text: item.nama_pt+''}
	                    dataSet.push(tmp)
	                })
	                return {
	                    results: dataSet
	                };
	            }
	        }
	    });
	}


    function select2_gugus_tugas(element)
    {
	    $(element).select2({
	        placeholder: 'Pilih Gugus Tugas',
	        delay: 90,
	        ajax: {
	            url: '<?=site_url('api/gugus_tugas')?>',
	            dataType: 'json',
	            data: function (params) {
	                var query = {
	                    search: params.term
	                }
	                return query;
	            },
	            processResults: function (data) {
	                var dataSet = [];
	                $.each(data.data, function(i, item){
	                    var tmp = {id:item.id, text: item.gugustugas+''}
	                    dataSet.push(tmp)
	                })
	                return {
	                    results: dataSet
	                };
	            }
	        }
	    });
	}


    function select2_pegawai(element)
    {
	    $(element).select2({
	        placeholder: 'Pilih Pejabat/Pegawai Internal',
	        delay: 90,
	        ajax: {
	            url: '<?=site_url('api/pegawai')?>',
	            dataType: 'json',
	            data: function (params) {
	                var query = {
	                    search: params.term
	                }
	                return query;
	            },
	            processResults: function (data) {
	                var dataSet = [];
	                $.each(data.data, function(i, item){
	                    var tmp = {id:item.id, text: item.name}
	                    dataSet.push(tmp)
	                })
	                return {
	                    results: dataSet
	                };
	            }
	        }
	    });
	}


    function select2_member(element)
    {
	    $(element).select2({
	        placeholder: 'Pilih Pejabat/Pegawai Eksternal',
	        delay: 90,
	        ajax: {
	            url: '<?=site_url('api/member')?>',
	            dataType: 'json',
	            data: function (params) {
	                var query = {
	                    search: params.term
	                }
	                return query;
	            },
	            processResults: function (data) {
	                var dataSet = [];
	                $.each(data.data, function(i, item){
	                    var tmp = {id:item.id, text: item.name+' ('+item.jabatan+', '+item.instansi+')'}
	                    dataSet.push(tmp)
	                })
	                return {
	                    results: dataSet
	                };
	            }
	        }
	    });
	}


	function thumbnail_default_pegawai(id)
	{
		var rs = 'peg_male.png'
		if(id=='2'){
			rs = 'peg_female.png'
		}
		return rs
	}

	
	function log_out()
	{
		var cf = confirm('Keluar aplikasi?')
		if(cf==true)
		{
			$.get('<?=site_url('auth/logout')?>', function(rs){
				if(rs.status==true)
				{
					window.location.assign('<?=base_url()?>')
				}else{
					alert(rs.message)
				}
			})
		}
	}


	// function file_upload_form_capture_whit_query(fieldSelect, firstName, urlQuery='')
	// {
	// 	var formData = new FormData();
	// 	formData.append('first', firstName);
	// 	formData.append('output', 'json');
	// 	formData.append('userfile', fieldSelect);
	// 	formData.append('<?=csrf_token()?>', '<?=csrf_hash()?>');
	// 	// $(fieldSelect).html('<i class="fas fa-spinner fa-spin me-2"></i>Memproses...')
	// 	$.ajax({
	// 		crossDomain: true,
	//         crossOrigin: true,
	// 		type:'POST',
	// 		data: formData,
	// 		cache:false,
	// 	    processData: false,
	// 	    contentType: false,
	// 		url: '<?=site_url('api/file/upload')?>'+urlQuery,
	// 		success:function(data){;
	// 			var rt = data
	// 			$('input[name=<?=csrf_token()?>]').val(rt.csrf);
	// 			if(rt.status){
	// 				$(fieldSelect).val('')
	// 			}else{
	// 				alert(rt.message);
	// 			}
	// 			return rt
	// 		}
	// 	});
	// }

	function file_upload_form_whit_query(fieldSelect, firstName, urlQuery='')
	{
		var formData = new FormData();
		formData.append('first', firstName);
		formData.append('output', 'json');
		formData.append('userfile', $(fieldSelect)[0].files[0]);
		formData.append('<?=csrf_token()?>', '<?=csrf_hash()?>');
		// $(fieldSelect).html('<i class="fas fa-spinner fa-spin me-2"></i>Memproses...')
		$.ajax({
			crossDomain: true,
	        crossOrigin: true,
			type:'POST',
			data: formData,
			cache:false,
		    processData: false,
		    contentType: false,
			url: '<?=site_url('api/file/upload')?>'+urlQuery,
			success:function(data){;
				var rt = data
				$('input[name=<?=csrf_token()?>]').val(rt.csrf);
				if(rt.status){
					$(fieldSelect).val('')
				}else{
					alert(rt.message);
				}
				return rt
			}
		});
	}

	function file_upload_form(fieldSelect, firstName, fieldTarget, fileList='', jml=1)
	{
		var formData = new FormData();
		formData.append('first', firstName);
		formData.append('output', 'json');
		formData.append('userfile', $(fieldSelect)[0].files[0]);
		formData.append('<?=csrf_token()?>', '<?=csrf_hash()?>');
		// if(jml=='1')
		// {
		// 	formData.append('allowed_type', 'docx|pdf|DOCX|PDF')
		// }
		$.ajax({
			crossDomain: true,
	        crossOrigin: true,
			type:'POST',
			data: formData,
			cache:false,
		    processData: false,
		    contentType: false,
			url: '<?=site_url('api/file/upload')?>',
			success:function(data){;
				var rt = data
				// console.log('HASILNYA::', rt)
				$('input[name=<?=csrf_token()?>]').val(rt.csrf);
				if(rt.status){
					console.log('Berhasil upload file: ',rt.data['client_name'], 'Component:',fieldSelect);
					var txtViewListFile = $(fileList).html();
					if(jml=='1'){
						var filenya = $(fieldTarget).val()
						console.log(filenya)
						if(filenya!==''){
							crypto_test(filenya, 'encode', function(result){
								file_deleted(result, fieldTarget, jml, 1)
							})
						}
						$(fieldTarget).val(rt.data.id)
					}else{
						var filenya = $(fieldTarget).val().split(',');
						filenya.push(rt.data.id)
						$(fieldTarget).val(filenya.join(','));
					}
					txtViewListFile += '<li id="li-file-'+rt.data.id_hash+'"><a href="<?=site_url('file/download?id=')?>'+rt.data.id_hash+'" target="_blank" title="Unduh File" class="me-1">'+rt.data.client_name+'</a> <a href="#" title="Hapus File" onclick="file_deleted(\''+rt.data.id_hash+'\', \''+fieldTarget+'\', \''+jml+'\')"><i class="fa fa-trash"></i></a></li> '
					$(fileList).html(txtViewListFile);
					// $('#progress-bar_').prop('style', 'width:100%').html('100%').prop('class', 'progress-bar bg-success')
					$(fieldSelect).val('')
				}else{
					// $('#text_message').html(rt.error)
					// $('#progress-bar_').prop('style', 'width:50%').html('50%').prop('class', 'progress-bar bg-danger')
					alert(rt.message);
				}
				// $('#modal_for_message_result').modal('hide')
				return rt
			},
			// error: function(data){
			// 	// $('#text_message').html(data)
			// 	// $('#progress-bar_').prop('style', 'width:50%').html('50%').prop('class', 'progress-bar bg-danger')
			// 	// $('#modal_for_message_result').modal('hide')
			// 	alert(data);
			// }
		});
	}

	function file_deleted(id, fieldTarget, jml='1', silent=0){
		var cf = true
		if (silent==0){
			confirm('Hapus File ini?...')
		}
		if(cf===true)
		{
			$.get('<?=site_url('file/deleted')?>', {id:id}, function(data){
				if(data.status){
					$('#li-file-'+id).remove()
					var iddx = crypto_test(id, 'decode', function(result){
						var idd = result
						if(jml=='1'){
							var isinya = $(fieldTarget).val()
							if(isinya==idd){
								$(fieldTarget).val('')
							}
						}else{
							var y = $(fieldTarget).val().split(',')
							y = y.filter(e => e !== idd);
							$(fieldTarget).val(y.join(','));
						}
					})
				}else{
					alert(data.message)
				}
			})
		}
	}

	function crypto_test(id, opt='encode', callback)
	{
		$.get('<?=site_url('crypto')?>', {id:id, act:opt}, function(rs){
			callback(rs)
		})
	}

	function get_foto(id, opt=1, callback)
	{
		$.get('<?=site_url('foto')?>', {id:id, thumbnail:opt}, function(rs){
			callback(rs.data)
		})
	}
</script>
<script src='<?=base_url('assets/vendors/pdfjse/lib/webviewer.min.js')?>'></script>
<script>
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