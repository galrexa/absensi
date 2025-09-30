<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=(isset($title))?$title:'Presensi'?></title>
    <link href="<?=base_url('assets/css/bootstrap.min.css')?>" rel="stylesheet">
    <link href="<?=base_url('assets/vendors/fontawesome/css/all.min.css')?>" rel="stylesheet">
    <link href="<?=base_url('assets/css/main.css')?>" rel="stylesheet">
    <script src="<?=base_url('assets/js/jquery-3.7.1.min.js')?>"></script>
    <script src="<?=base_url('assets/js/bootstrap.bundle.min.js')?>"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers:{
                "Key": "<?=session()->get('key')?>",
                "User": '<?=session()->get('id')?>',
                "Token": '<?=session()->get('token')?>',
            }
        });
    </script>
    <style>

        .map-container {
            position: relative;
            height: calc(100vh - 270px);
            min-height: 500px;
        }
        #map {
            width: 100%;
            height: 100%;
            border: none;
        }
        #div_map {
            width: 100%;
            height: 100%;
            border: none;
        }

        .bottom-panel {
            position: fixed;
            bottom: 70px;
            left: 0;
            right: 0;
            background: white;
            border-radius: 20px 20px 0 0;
            box-shadow: 0 -5px 20px rgba(0,0,0,0.1);
            z-index: 1000;
            transition: transform 0.3s ease;
        }
        .bottom-panel.collapsed {
            transform: translateY(calc(100% - 60px));
        }

        .bottom-handle {
            width: 40px;
            height: 4px;
            background: #ddd;
            border-radius: 2px;
            margin: 12px auto 16px;
            cursor: pointer;
        }

        .info-input {
            padding: 0 20px 20px;
        }

        .info-text {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            /*padding: 12px 16px;*/
            padding: 5px;
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 16px;
        }

		/* tombol absen start/stop */
        .absent-start-btn {
            background: linear-gradient(135deg, #198754 0%, #157347 100%);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
        }
        .absent-start-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(231, 76, 60, 0.4);
        }

        .absent-stop-btn {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
        }
        .absent-stop-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(231, 76, 60, 0.4);
        }

        .map-controls {
            position: absolute;
            right: 16px;
            top: 16px;
            z-index: 999;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .control-btn {
            background: white;
            border: none;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .control-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }

        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            border-top: 1px solid #e9ecef;
            padding: 8px 0;
            padding-bottom: calc(8px + env(safe-area-inset-bottom));
            z-index: 1001;
            box-shadow: 0 -2px 20px rgba(0,0,0,0.1);
        }
        .nav-container {
            display: flex;
            justify-content: space-around;
            align-items: center;
            max-width: 600px;
            margin: 0 auto;
            padding: 0 16px;
        }
        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: #6c757d;
            padding: 8px 12px;
            border-radius: 12px;
            transition: all 0.3s ease;
            cursor: pointer;
            min-width: 60px;
        }
        .nav-item.active {
            color: #3498db;
            background: rgba(52, 152, 219, 0.1);
        }
        .nav-item:hover {
            color: #3498db;
            background: rgba(52, 152, 219, 0.05);
            text-decoration: none;
        }
        .nav-item i {
            font-size: 20px;
            margin-bottom: 4px;
        }
        .nav-item span {
            font-size: 11px;
            font-weight: 500;
            text-align: center;
        }

        .search-overlay {
            position: absolute;
            top: 5px;
            left: 16px;
            right: 16px;
            z-index: 999;
        }
        /*#text_info {
            top: 5px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            padding: 12px 16px;
            border: none;
            font-size: 14px;
            width: 100%;
        }*/
        /*.search-box:focus {
            outline: none;
            box-shadow: 0 4px 25px rgba(0,0,0,0.15);
        }*/
    </style>
</head>
<body>

    <!-- Header -->
    <div class="navbar header">
        <div class="container-fluid">
            <div class="header-left">
                <div class="navbar-brand"><img src="<?=base_url('assets/img/FA-Logo-PCO_Horizontal-Emas-Putih.png')?>" alt="Brand Logo"></div>
            </div>
            <img src="<?=session()->get('pegawai_foto')?>" height="50" class="rounded-circle">
        </div>
    </div>

    <!-- Shift Info -->
    <div class="shift-info">
        <div class="row mb-1">
            <div class="col fw-bold">
                Hi <?=session()->get('nama')?>, <?=session()->get('email')?>
            </div>
        </div>
		<div class="row" style="font-size:8pt;">
			<?php if(!empty($data)){?>
				<div class="col">
					<?php if(!empty($data)){
						$start_latlong = explode(',', $data->start_latlong);
						?>
						<a href="#" onclick="showPosition('<?=$start_latlong[0]?>', '<?=$start_latlong[1]?>', 'Lokasi mulai kerja')" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lokasi mulai kerja: <?=$data->start_latlong?>, IPAddress: <?=$data->start_ip?>" style="text-decoration: none;">Mulai <i class="fas fa-map-marker-alt text-success"></i> <?=tanggal(substr($data->start,0,10), 4)?>, <?=substr($data->start, 10)?></a>
					<?php }?>
				</div>
				<div class="col">
					<?php if(!empty($data)){ if($data->stop<>''){
						$stop_latlong = explode(',', $data->stop_latlong);
						?>
						<a href="#" onclick="showPosition('<?=$stop_latlong[0]?>', '<?=$stop_latlong[1]?>', 'Lokasi selesai kerja')" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lokasi selesai kerja: <?=$data->stop_latlong?>, IPAddress: <?=$data->stop_ip?>" style="text-decoration: none;">Selesai <i class="fas fa-map-marker-alt text-danger"></i> <?=tanggal(substr($data->stop,0,10), 4)?>, <?=substr($data->stop, 10)?></a>
					<?php }}?>
				</div>
				<div class="col fw-bold"><a href="#" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Total Durasi Presensi" style="text-decoration:none;">Total Durasi: <?=$data->total_durasi?></a></div>
			<?php }else{?>
				<div class="col div_info_presensi"></div>
			<?php }?>
		</div>
    </div>

    <?php if(session()->get('message')){?>
        <!-- ALERT FLASHDATA -->
        <div class="alert alert-warning m-3" id="flashdata_message">
            <i class="fa fa-exclamation-circle"></i> <?=session()->get('message')?>
        </div>
    <?php }?>

    <!-- Map Container -->
    <div class="map-container">
        <!-- Search Overlay -->
        <div class="search-overlay rounded text-center">
            <span id="text_info"></span>
            <br>
            <span id="txt_address" style="font-size: 9pt;"></span>
        </div>
        <!-- Map -->
        <div id="div_map"></div>
        <div class="map-controls">
            <button class="control-btn" onclick="window.location.reload(true)" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Refresh lokasi"><i class="fas fa-crosshairs"></i></button>
            <button class="control-btn btn-warning" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Open Camera" id="btnOpenCam" style="display:none"><i class="fa fa-camera"></i></button>
        </div>
    </div>

    <!-- Bottom Panel -->
    <div class="bottom-panel" id="bottomPanel">
        <div class="info-input">
            <div class="div_tombol_presensi mt-3">
	            <button class="absent-start-btn">
	                <i class="fas fa-play"></i> Mulai Kerja
	            </button>
	        </div>
        </div>
    </div>

    <!-- Bottom Navigation -->
    <div class="bottom-nav">
        <div class="nav-container">
            <div class="nav-item" onclick="window.location.assign('<?=base_url()?>')" title="Beranda" data-bs-toggle="tooltip" data-bs-placement="bottom">
                <i class="fas fa-home"></i>
                <span>Beranda</span>
            </div>
            <?php if(return_access_link(['presensi/index'])){?>
	            <div class="nav-item active" onclick="window.location.assign('<?=site_url('presensi/index')?>')" title="Halaman Presensi" data-bs-toggle="tooltip" data-bs-placement="bottom">
	                <i class="fas fa-street-view"></i>
	                <span>Presensi</span>
	            </div>
	        <?php }?>
	        <?php if(return_access_link(['presensi/riwayat'])){?>
	            <div class="nav-item" onclick="window.location.assign('<?=site_url('presensi/riwayat')?>')" title="Riwayat Presensi" data-bs-toggle="tooltip" data-bs-placement="bottom">
	                <i class="fas fa-history"></i>
	                <span>Riwayat</span>
	            </div>
	        <?php }?>
	        <?php if(return_access_link(['presensi/harian'])){?>
	            <div class="nav-item" onclick="window.location.assign('<?=site_url('presensi/harian')?>')" title="Presensi Harian Pegawai" data-bs-toggle="tooltip" data-bs-placement="bottom">
	                <i class="fas fa-calendar-check"></i>
	                <span>Harian</span>
	            </div>
	        <?php }?>
	        <?php if(return_access_link(['presensi/bulanan'])){?>
	            <div class="nav-item" onclick="window.location.assign('<?=site_url('presensi/bulanan')?>')" title="Resume Presensi Bulanan" data-bs-toggle="tooltip" data-bs-placement="bottom">
	                <i class="fas fa-calendar-alt"></i>
	                <span>Bulanan</span>
	            </div>
	        <?php }?>
        </div>
    </div>

    <!-- modal for cam -->
    <div class="modal" tabindex="-1" id="modal_open_cam" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header color-red fw-bold d-flex" id="modal_open_cam_header">
                    <span class="flex-grow-1 fw-bold">Tag Foto & alasan</span>
                    <a href="#" class="btn btn-light" onclick="switchCamera()" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ganti kamera"><i class="fa fa-camera-retro"></i></a>
                </div>
                <div class="modal-body" id="modal_open_cam_body">
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <!-- <div class="camera-container"> -->
                                <video class="liveCam" id="video" autoplay playsinline></video>
                                <canvas class="liveCam" id="canvas"></canvas>
                                <div class="" id="imghasil" style="display:none"></div>
                            <!-- </div> -->
                            <div class="info-text mt-1" id="keterangan">
                                <b class="d-block">Keterangan:</b>
                                <?php foreach (return_referensi_list('alasan_absen_luar_area') as $k) {?>
                                    <label class="m-1"><input type="radio" name="keterangan" value="<?=$k->ref_name?>"> <?=$k->ref_name?></label>
                                <?php }?>
                                <textarea class="form-control" name="keterangan2" id="keterangan2" placeholder="Keterangan"></textarea>
                                <input type="hidden" name="file_foto" id="file_foto" value="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" id="modal_open_cam_footer">
                    <button type="button" class="btn color-red" onclick="capturePhoto()"><i class="fas fa-save"></i> Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="stopCamera()"><i class="fa fa-times"></i> Batal</button>
                </div>
            </div>
        </div>
    </div>
    <style>
        video {
            width: 100%;
            height: 400px;
            object-fit: cover;
            background: #000;
        }
        canvas {
            display: none;
        }
    </style>

	<link rel="stylesheet" href="<?=base_url('assets/vendors/leaflet/leaflet.css')?>"/>
	<script src="<?=base_url('assets/vendors/leaflet/leaflet.js')?>"></script>
	<script src="<?=base_url('assets/js/custom.js')?>"></script>
	<script type="text/javascript">
		var totalSeconds = 0;
        localStorage.setItem('open_modal_', 0)

		$(function(){
			presensi_check()
			getLocation()

            $("#flashdata_message").fadeTo(5000, 500).slideUp(500, function(){
                $("#flashdata_message").slideUp(500);
            });
		})


		function presensi_start()
		{
            var open_modal_ = localStorage.getItem('open_modal_')
            var in_area = localStorage.getItem('in_area')
            var clat = localStorage.getItem('clat')
			var clong = localStorage.getItem('clong')
            var keterangan = ''
            var keterangan2 = ''
            var file_foto = $('#file_foto').val()
            var rslt = 0
            if((in_area==1)){
                rslt = 1
            }else{
                switch (open_modal_) {
                  case '0':
                    startCamera(1)
                    // return false
                    break;
                  default:
                    keterangan = $('input[name=keterangan]:checked').val()
                    keterangan2 = $('#keterangan2').val()
                    if(in_area==0 && (keterangan=='' || keterangan==undefined))
                    {
                        keterangan = ''
                        alert('Pilih keterangan terlebih dahulu')
                    }else{
                        if(keterangan=='Lainnya')
                            keterangan += ': ' + keterangan2
                        rslt = 1
                    }
                    break;
                }
            }
            if(rslt==1){
				$.post('<?=site_url('api/presensi/start')?>', {latlong:clat+','+clong, keterangan:keterangan, file_foto:file_foto}, function(rs){
					if(rs.status==true)
					{
                        localStorage.setItem('open_modal_', 0)
                        window.location.assign('<?=site_url('presensi/riwayat')?>')
					}else{
						alert(rs.message)
					}
				})
            }
		}

		function presensi_stop(id)
		{
            var open_modal_ = localStorage.getItem('open_modal_')
			var cf = true
            if(open_modal_=='0')
                cf = confirm('Akhiri atau Selesaikan Presensi?')
			if(cf===true){
                var in_area = localStorage.getItem('in_area')
				var clat = localStorage.getItem('clat')
				var clong = localStorage.getItem('clong')
                var keterangan = ''
                var keterangan2 = ''
                var file_foto = $('#file_foto').val()
                var rslt = 0
                if((in_area==1)){
                    rslt = 1
                }else{
                    switch (open_modal_) {
                      case '0':
                        startCamera(2)
                        // return false
                        break;
                      default:
                        keterangan = $('input[name=keterangan]:checked').val()
                        keterangan2 = $('#keterangan2').val()
                        if(in_area==0 && (keterangan=='' || keterangan==undefined))
                        {
                            keterangan = ''
                            alert('Pilih keterangan terlebih dahulu')
                        }else{
                            if(keterangan=='Lainnya')
                                keterangan += ': ' + keterangan2
                            rslt = 1
                        }
                        break;
                    }
                }
                if(rslt==1){
    				$.post('<?=site_url('api/presensi/stop')?>', {id:id, latlong:clat+','+clong, keterangan:keterangan, file_foto:file_foto}, function(rs){
    					if(rs.status==true)
    					{
                            localStorage.setItem('open_modal_', 0)
                            window.location.assign('<?=site_url('presensi/riwayat')?>')
    					}else{
    						alert(rs.message)
    					}
    				})
                }
			}
		}

		function presensi_check()
		{
			$('#keterangan').hide().prop('required', false)
			$.get('<?=site_url('api/presensi/check')?>', function(rs){
				if(rs.status==true)
				{
					localStorage.setItem('durasi_in_second', parseInt(rs.data.durasi_in_second));
					totalSeconds = parseInt(localStorage.getItem('durasi_in_second'));
					var jStart = rs.data.start
					var start_latlong = rs.data.start_latlong.split(',')
					var jStartSplit = jStart.split(' ')
					if(rs.data.stop==null){
                        localStorage.setItem('presensi_id', rs.data.id)
						$('.div_tombol_presensi').html('<a href="#" class="btn btn-sm btn-outline-danger absent-stop-btn" onclick="presensi_stop('+rs.data.id+')"><i class="fa fa-stop"></i> Selesai Kerja (<span class="countUpTimes"></span>)</a>')
						setInterval(countUpTracker, 1000);
					}else{
						$('.div_tombol_presensi').html('<button class="absent-start-btn " onclick="presensi_start()"><i class="fa fa-play"></i> Mulai Kerja</button>')
						localStorage.removeItem('durasi_in_second');
					}
				}else{
					$('.div_info_presensi').html('<small class="text-danger">'+rs.message+'</small>')
					$('.div_tombol_presensi').html('<button class="absent-start-btn" onclick="presensi_start()"><i class="fa fa-play"></i> Mulai Kerja</button>')
					localStorage.removeItem('durasi_in_second');
				}
			})
		}

		function countUpTracker() {
			++totalSeconds;
			var sec_num = parseInt(totalSeconds, 10)
			var hours   = Math.floor(sec_num / 3600)
			var minutes = Math.floor(sec_num / 60) % 60
			var seconds = sec_num % 60
			var durasi = [hours,minutes,seconds]
				.map(v => v < 10 ? "0" + v : v)
				.filter((v,i) => v !== "00" || i > 0)
				.join(":")
			$('.countUpTimes').html(durasi);
		}


		function getLocation() {
			$('#text_info').html('<i class="fas fa-spinner fa-spin"></i> Mengambil lokasi perangkat..').prop('class', 'fw-bold text-primary bg-light')
            $('#txt_address').html('')
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(saveLatLong);
			} else {
				x.innerHTML = "Geolocation is not supported by this browser.";
			}
		}

		function saveLatLong(position)
		{
			var coord_lat = position.coords.latitude
			var coord_long = position.coords.longitude
			localStorage.setItem('clat', coord_lat)
			localStorage.setItem('clong', coord_long)
			console.log('LongLat:', coord_lat+', '+coord_long)
			showPosition()
			check_my_location_in_areas()
            get_my_address_by_latlong()
		}

		function showPosition(clat='', clong='', deskripsi='Lokasi Anda saat ini!')
		{
            /*var redIcon = L.icon({
                iconUrl: '<?=base_url('assets/vendors/leaflet/images/6905745.png')?>',
                // shadowUrl: '<?=base_url('assets/vendors/leaflet/images/6905745.png')?>',
                iconSize:     [50, 55],
                shadowSize:   [50, 64],
                iconAnchor:   [22, 94],
                shadowAnchor: [4, 62],
                popupAnchor:  [-3, -76]
            });*/
            var presensi_area = [
                <?php foreach ($list_area as $k) {
                	echo '["'.$k->name.'", '.$k->latlong.','.$k->range.'],';
                }?>
            ]
			var coord_lat = (clat)?clat:localStorage.getItem('clat')
			var coord_long = (clong)?clong:localStorage.getItem('clong')
            $('#div_map').html('<div id="map"></div>')
			var map = L.map('map').setView([coord_lat, coord_long], 17);
			/*L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
			    maxZoom: 19,
			    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
			}).addTo(map);*/
			L.tileLayer('http://{s}.google.com/vt/lyrs=p&x={x}&y={y}&z={z}',{
			    maxZoom: 20,
			    subdomains:['mt0','mt1','mt2','mt3']
			}).addTo(map)
			var marker = L.marker([coord_lat, coord_long]).addTo(map).bindPopup(deskripsi).openPopup()
            for (var i = 0; i < presensi_area.length; i++) {
                // FOR MARKER
                /*marker = new L.marker([presensi_area[i][1], presensi_area[i][2]], {icon:redIcon})
                .bindPopup(presensi_area[i][0]).openPopup()
                .addTo(map);*/
                // FOR RADIUS
                /*circle = L.circle([presensi_area[i][1], presensi_area[i][2]], {
                    color: 'red',
                    fillColor: '#f03',
                    fillOpacity: 0.2,
                    radius: presensi_area[i][3]
                }).addTo(map).bindPopup("Area Presensi "+presensi_area[i][0]);*/
            }
		}

        // aktifasi tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })

		function check_my_location_in_areas(clat='', clong='')
		{
			var coord_lat = (clat)?clat:localStorage.getItem('clat')
			var coord_long = (clong)?clong:localStorage.getItem('clong')
			$.get('<?=site_url('api/check_location_in_radius_absen')?>', {lat: coord_lat, long:coord_long}, function(rs){
				if(rs.status==true)
				{
					var status = rs.data.status
					if(status==false)
					{
						$('#text_info').html(rs.data.message).prop('class', 'fw-bold text-danger bg-light')
                        $('#txt_address').prop('class', 'text-danger bg-light')
						$('#keterangan').show().prop('required', true)
                        $('#btnOpenCam').show()
                        localStorage.setItem('in_area', 0)
					}else{
						$('#text_info').html(rs.data.message).prop('class', 'fw-bold text-success bg-light')
                        $('#txt_address').prop('class', 'text-success bg-light')
						$('#keterangan').hide().prop('required', false)
                        $('#btnOpenCam').hide()
                        localStorage.setItem('in_area', 1)
					}
                    checkPilihKeterangan()
				}else{
					alert(rs.message)
				}
			})
		}

        function get_my_address_by_latlong(clat='', clong='')
        {
            var coord_lat = (clat)?clat:localStorage.getItem('clat')
            var coord_long = (clong)?clong:localStorage.getItem('clong')
            $.get('<?=site_url('api/get_place')?>', {latlng: coord_lat+','+coord_long}, function(rs){
                console.log(rs.data)
                var almt = rs.data.results[0].formatted_address
                $('#txt_address').html(almt)
            })
        }

        /*
        *   open camera dll
        */
        let video = document.getElementById('video');
        let canvas = document.getElementById('canvas');
        let btnOpenCam = document.getElementById('btnOpenCam');
        // let captureBtn = document.getElementById('captureBtn');
        // let switchBtn = document.getElementById('switchBtn');
        // let gallery = document.getElementById('gallery');
        // let status = document.getElementById('status');
        // let flash = document.getElementById('flash');
        let stream = null;
        let currentFacingMode = 'user'; // 'user' untuk kamera depan, 'environment' untuk kamera belakang
        let photos = [];
        // $('#btnOpenCam').on('click', function(e){
        //     console.log('open cam...')
        //     startCamera()
        // });

        async function startCamera(opt) {
            $('#modal_open_cam').modal('show')
            // // $('#imghasil').html('').hide()
            // $('#imghasil').empty().hide()
            // $('.liveCam').show()
            try {
                const constraints = {
                    video: {
                        facingMode: currentFacingMode,
                        // width: { ideal: 100 },
                        // height: { ideal: 1080 }
                    }
                };
                stream = await navigator.mediaDevices.getUserMedia(constraints);
                video.srcObject = stream;
                localStorage.setItem('opt', opt)
            } catch (err) {
                console.error('Error accessing camera:', err);
                // status.textContent = 'Error: Tidak dapat mengakses kamera. Pastikan Anda memberikan izin kamera.';
            }
        }

        // Menghentikan kamera
        function stopCamera() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
                video.srcObject = null;
            }
        }

        function capturePhoto() {
            var lock = 0
            var opt = localStorage.getItem('opt')
            keterangan = $('input[name=keterangan]:checked').val()
            keterangan2 = $('#keterangan2').val()
            if(keterangan=='' || keterangan==undefined)
            {
                keterangan = ''
                alert('Pilih keterangan terlebih dahulu.')
            }else{
                if(keterangan=='Lainnya')
                {
                    if(keterangan2=='')
                    {
                        alert('Isi keterangan terlebih dahulu..')
                    }else{
                        lock += 1
                    }
                }else{
                    lock += 1
                }
            }
            if(lock > 0)
            {
                localStorage.setItem('open_modal_', 1)
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(video, 0, 0);
                const dataURL = canvas.toDataURL('image/png');
                var push_file = file_upload_form_capture_whit_query(dataURLToBlob(dataURL), 'selfi_cam', '?pegawai_id=<?=session()->get('pegawai_id')?>', function(push_filex){
                    console.log('__HASIL UPLOAD:__',push_filex)
                    if(push_filex.status==true)
                    {
                        $('#file_foto').val(push_filex.data.id)
                        var hasil_foto = `<img src="${dataURL}" alt="Photo hasil" class="img img-thumbnail">`
                        $('#imghasil').show().html(hasil_foto)
                        $('.liveCam').hide()
                        if(opt==='1'){
                            presensi_start()
                        }else{
                            presensi_stop(localStorage.getItem('presensi_id'))
                        }
                    }else{

                    }
                })
            }
        }
        
        async function switchCamera() {
            currentFacingMode = currentFacingMode === 'user' ? 'environment' : 'user';
            if (stream) {
                stopCamera();
                setTimeout(startCamera, 100);
            }    
            console.log(`Beralih ke kamera ${currentFacingMode === 'user' ? 'depan' : 'belakang'}`)
        }

        btnOpenCam.addEventListener('click', startCamera);

        // Menangani keyboard shortcut
        // document.addEventListener('keydown', function(e) {
        //     if (e.code === 'Space' /*&& !captureBtn.disabled*/) {
        //         e.preventDefault();
        //         capturePhoto();
        //     }
        // });

        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            console.long('Browser Anda tidak mendukung akses kamera')
        }

        /*
        *   keterangan if luar area
        */
        function checkPilihKeterangan()
        {
            keterangan = $('input[name=keterangan]:checked').val()
            if(keterangan=='Lainnya')
            {
                $('#keterangan2').show().prop('required', true)
            }else{
                $('#keterangan2').hide().prop('required', false).val('')
            }
        }

        $('input[name=keterangan]').on('change', function(){
            checkPilihKeterangan();
        })

        function file_upload_form_capture_whit_query(fieldSelect, firstName, urlQuery='', callback)
        {
            var formData = new FormData();
            formData.append('first', firstName);
            formData.append('output', 'json');
            formData.append('userfile', fieldSelect);
            formData.append('<?=csrf_token()?>', '<?=csrf_hash()?>');
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
                    callback(rt)
                }
            });
        }

        function base64ToBinary(base64String) {
            try {
                // Hapus prefix data URL jika ada
                const base64Data = base64String.replace(/^data:[^;]+;base64,/, '');
                // Decode base64 ke binary string
                const binaryString = atob(base64Data);
                // Convert ke Uint8Array
                const bytes = new Uint8Array(binaryString.length);
                for (let i = 0; i < binaryString.length; i++) {
                    bytes[i] = binaryString.charCodeAt(i);
                }
                return bytes;
            } catch (error) {
                throw new Error('Invalid base64 string: ' + error.message);
            }
        }

        function dataURLToBlob(dataURL) {
            const arr = dataURL.split(',');
            const mime = arr[0].match(/:(.*?);/)[1];
            const bstr = atob(arr[1]);
            let n = bstr.length;
            const u8arr = new Uint8Array(n);
            while(n--) {
                u8arr[n] = bstr.charCodeAt(n);
            }
            return new Blob([u8arr], {type: mime});
        }
	</script>
</body>
</html>