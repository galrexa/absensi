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
            min-height: 600px;
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
            bottom: 180px;
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

    <!-- Shift Info -->
    <div class="shift-info">
        <div class="row mb-1">
            <div class="col fw-bold">
                <?=$title_name?>
            </div>
        </div>
    </div>

    <!-- Map Container -->
    <div class="map-container">
        <!-- Search Overlay -->
        <div class="search-overlay rounded text-center">
            <!-- <span id="text_info"></span> -->
            <!-- <br> -->
            <span id="txt_address" style="font-size: 9pt;"></span>
        </div>
        <!-- Map -->
        <div id="div_map"></div>
        <!-- <div class="map-controls">
            <button class="control-btn" onclick="window.location.reload(true)" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Refresh lokasi"><i class="fas fa-crosshairs"></i></button>
        </div> -->
    </div>

	<link rel="stylesheet" href="<?=base_url('assets/vendors/leaflet/leaflet.css')?>"/>
	<script src="<?=base_url('assets/vendors/leaflet/leaflet.js')?>"></script>
	<script src="<?=base_url('assets/js/custom.js')?>"></script>
	<script type="text/javascript">
		$(function(){
            showPosition(<?=$latlng[0]?>, <?=$latlng[1]?>)
            get_my_address_by_latlong(<?=$latlng[0]?>, <?=$latlng[1]?>)
		})

		function showPosition(clat='', clong='', deskripsi='Posisi absen!')
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
                <?php /* foreach ($list_area as $k) {
                	echo '["'.$k->name.'", '.$k->latlong.','.$k->range.'],';
                } */ ?>
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
            circle = L.circle([clat, clong], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.2,
                radius: 30
            }).addTo(map).bindPopup("Radius Area Presensi ");
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

        function get_my_address_by_latlong(clat='', clong='')
        {
            var coord_lat = (clat)?clat:localStorage.getItem('clat')
            var coord_long = (clong)?clong:localStorage.getItem('clong')
            $.get('<?=site_url('api/get_place')?>', {latlng: coord_lat+','+coord_long}, function(rs){
                console.log(rs.data)
                var almt = rs.data.results[0].formatted_address
                $('#txt_address').html(almt).prop('class', 'bg-light fw-bold text-success')
            })
        }

	</script>
</body>
</html>