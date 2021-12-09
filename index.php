<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>OpenStreetMap &amp; OpenLayers - Marker Example</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <link rel="stylesheet" href="https://openlayers.org/en/v4.6.5/css/ol.css" type="text/css" />
    <script src="https://openlayers.org/en/v4.6.5/build/ol.js" type="text/javascript"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js" type="text/javascript"></script>

    
    <style>
        /*
            .map, .righ-panel {
                height: 500px;
                width: 80%;
                float: left;
            }
            */

        .map{
            height: 100%;
            width: 100%;
            float: left;
        }

        .map {
            border: 1px solid #000;
        }

        .ol-popup {
            position: absolute;
            background-color: white;
            -webkit-filter: drop-shadow(0 1px 4px rgba(0, 0, 0, 0.2));
            filter: drop-shadow(0 1px 4px rgba(0, 0, 0, 0.2));
            padding: 15px;
            border-radius: 10px;
            border: 1px solid #cccccc;
            bottom: 12px;
            left: -50px;
            min-width: 180px;
        }

        .ol-popup:after,
        .ol-popup:before {
            top: 100%;
            border: solid transparent;
            content: " ";
            height: 0;
            width: 0;
            position: absolute;
            pointer-events: none;
        }

        .ol-popup:after {
            border-top-color: white;
            border-width: 10px;
            left: 48px;
            margin-left: -10px;
        }

        .ol-popup:before {
            border-top-color: #cccccc;
            border-width: 11px;
            left: 48px;
            margin-left: -11px;
        }

        .ol-popup-closer {
            text-decoration: none;
            position: absolute;
            top: 2px;
            right: 8px;
        }

        .ol-popup-closer:after {
            content: "✖";
        }

    </style>
</head>

<body onload="initialize_map();">
<select id="comboA" onchange="getComboA(this)">
  <option value="">Chọn bản đồ muốn hiển thị</option>
  <option value="ohq1">Bản đồ toàn vùng</option>
  <option value="ohq2">Bản đồ phân bố vùng</option>
  <option value="ohq_chitiet">Bản đồ đất tổng quan</option>
  <option value="quyhoach">Bản đồ đất theo từng nhà</option>
</select>
    <table>

<tr>

    <td>
        <div id="map" class="map"></div>
        <div id="map" style="width: 50vw; height: 50vh;"></div>
        <div id="popup" class="ol-popup">
            <a href="#" id="popup-closer" class="ol-popup-closer"></a>
            <div id="popup-content">aa</div>
        </div>
        <!--<div id="map" style="width: 80vw; height: 100vh;"></div>-->
    </td>
    <td>
        <input type="textinput" id="ctiy"><br/>
        <button id="btnSeacher"> Tìm kiếm</button>
        <br />
        <br />
        <br />
        <input type="checkbox" id="map1" checked /><label for="map1">Bản đồ toàn vùng</label>
        <input type="checkbox" id="map2" checked /><label for="map2">Bản đồ phân bố vùng</label>
        <input type="checkbox" id="map3" checked /><label for="map3">Bản đồ đất tổng quan</label>
        <input type="checkbox" id="map4" checked /><label for="map4">Bản đồ đất theo từng nhà</label>
        <!-- <input onclick="oncheck_ohq1();" type="checkbox" id="ohq1" name="layer" checked > Ban do tong dan<br />
        <input onclick="oncheck_ohq2();" type="checkbox" id="ohq2" name="layer" checked > Ban do dan cu theo khu vuc <br />
        <input onclick="oncheck_ohqchitiet()" type="checkbox" id="ohq_chitiet" name="layer" checked> Ban do phan bo vung<br />
        <input onclick="oncheck_quyhoach()" type="checkbox" id="quyhoach" name="layer" checked > Ban do chi tiet<br /> -->
        <button id="btnRest"> Reset</button>
    </td>
</tr>
</table>
   
    <?php include 'CMR_pgsqlAPI.php' ?>
    <script>
function getVal(thiz) {
  var value =$(thiz).val()
  console.log(value);
}


        var format = 'image/png';
        var map;
        var minX =105.79859161377;
        var minY =21.0162925720215;
        var maxX = 105.810653686523;
        var maxY = 21.0293407440186;
        var cenX = (minX + maxX) / 2;
        var cenY = (minY + maxY) / 2;
        var mapLat = cenY;
        var mapLng = cenX;
        var mapDefaultZoom = 15;
        var vectorLayer;
        var styleFunction;
        var styles;
        var container = document.getElementById('popup');
        var content = document.getElementById('popup-content');
        var closer = document.getElementById('popup-closer');
        var ctiy = document.getElementById("ctiy");
        var value ;
        var layer_oqh2;
        var layer_oqh1;
        var layer_oqh_chi_tiet;
        var layer_thong_tin_quy_hoach;

        var overlay = new ol.Overlay( /** @type {olx.OverlayOptions} */ ({
            element: container,
            autoPan: true,
            autoPanAnimation: {
                duration: 250
            }
        }));
        closer.onclick = function() {
            overlay.setPosition(undefined);
            closer.blur();
            return false;
        };  

                function handleOnCheck(id, layer) {
            if (document.getElementById(id).checked) {
                value = document.getElementById(id).value;
                // map.setLayerGroup(new ol.layer.Group())
                map.addLayer(layer)
                vectorLayer = new ol.layer.Vector({});
                map.addLayer(vectorLayer);
            } else {
                map.removeLayer(layer);
                map.removeLayer(vectorLayer);
            }
        }
        function myFunction() {
            var popup = document.getElementById("popup");
            popup.classList.toggle("show");
        }
        // function oncheck_ohq1() {
        //     handleOnCheck('ohq1',  layer_oqh1);

        // }
        // function oncheck_ohq2() {
        //     handleOnCheck('ohq2',  layer_oqh2);

        // }
        // function oncheck_ohqchitiet() {
        //     handleOnCheck('ohq_chitiet', layer_oqh_chi_tiet);
        // }               
        // function oncheck_quyhoach() {
        //     handleOnCheck('quyhoach', layer_thong_tin_quy_hoach);
        // } 
        function getComboA(selectObject) {
  var value = selectObject.value;  
  console.log(value);
}            


        function initialize_map() {

            layerBG = new ol.layer.Tile({
                source: new ol.source.OSM({})
            });

          
            layer_oqh1 = new ol.layer.Image({
                source: new ol.source.ImageWMS({
                    ratio: 1,
                    url: 'http://localhost:8080/geoserver/DATN/wms?',
                    params: {
                        'FORMAT': format,
                        'VERSION': '1.1.0',
                        STYLES: '',
                        LAYERS: 'oqh1',
                    }
                })

            });

            layer_oqh2 = new ol.layer.Image({
                source: new ol.source.ImageWMS({
                    ratio: 1,
                    url: 'http://localhost:8080/geoserver/DATN/wms?',
                    params: {
                        'FORMAT': format,
                        'VERSION': '1.1.0',
                        STYLES: '',
                        LAYERS: 'oqh2',
                    }
                })

            });

            layer_oqh_chi_tiet = new ol.layer.Image({
                source: new ol.source.ImageWMS({
                    ratio: 1,
                    url: 'http://localhost:8080/geoserver/DATN/wms?',
                    params: {
                        'FORMAT': format,
                        'VERSION': '1.1.0',
                        STYLES: '',
                        LAYERS: 'oqh_chi_tiet',
                    }
                })

            });

            layer_thong_tin_quy_hoach = new ol.layer.Image({
                source: new ol.source.ImageWMS({
                    ratio: 1,
                    url: 'http://localhost:8080/geoserver/DATN/wms?',
                    params: {
                        'FORMAT': format,
                        'VERSION': '1.1.0',
                        STYLES: '',
                        LAYERS: 'thong_tin_quy_hoach',
                    }
                })

            });

            // cai dat khung nhin ban do
            var viewMap = new ol.View({
                center: ol.proj.fromLonLat([mapLng, mapLat]),
                zoom: mapDefaultZoom
            });

            map = new ol.Map({
                target: "map",
                layers: [layerBG],
                view: viewMap,
                 overlays: [overlay],
            });
            
          // ham nay de add layer nay
            map.addLayer(layer_oqh1);
            map.addLayer(layer_oqh2);
            map.addLayer(layer_oqh_chi_tiet);
            map.addLayer(layer_thong_tin_quy_hoach);


styles = {
                'Point': new ol.style.Style({
                    stroke: new ol.style.Stroke({
                        color: 'red',
                        width: 3
                    })
                }),
                'MultiLineString': new ol.style.Style({

                    stroke: new ol.style.Stroke({
                        color: 'red',
                        width: 3
                    })
                }),
                'Polygon': new ol.style.Style({
                    stroke: new ol.style.Stroke({
                        color: 'red',
                        width: 3
                    })
                }),
                'MultiPolygon': new ol.style.Style({
                    fill: new ol.style.Fill({
                        color: 'red'
                    }),
                    stroke: new ol.style.Stroke({
                        color: 'red',
                        width: 2
                    })
                })
            };
styleFunction = function(feature) {
                return styles[feature.getGeometry().getType()];
            };
vectorLayer = new ol.layer.Vector({
                style: styleFunction
            });
            map.addLayer(vectorLayer);

            var buttonReset = document.getElementById("btnRest").addEventListener("click", () => {
                location.reload();
            })

            var button = document.getElementById("btnSeacher").addEventListener("click",
                () => { 
                    vectorLayer.setStyle(styleFunction);
                    ctiy.value.length ?
                        $.ajax({
                            type: "POST",
                            url: "CMR_pgsqlAPI.php",
                            data: {
                                name: ctiy.value
                            },
                            success: function(result, status, erro) {
                                if (result == 'null')
                                    alert("không tìm thấy đối tượng");
                                else
                                    highLightObj(result);
                            },
                            error: function(req, status, error) {
                                alert(req + " " + status + " " + error);
                            }
                        }) : alert("Nhập dữ liệu tìm kiếm")
                });


                $("#map1").change(function () {
                    if($("#map1").is(":checked"))
                    {
                        layer_oqh1.setVisible(true);
                    }
                    else
                    {
                        layer_oqh1.setVisible(false);
                    }
                });
                $("#map2").change(function () {
                    if($("#map2").is(":checked"))
                    {
                        layer_oqh2.setVisible(true);
                    }
                    else
                    {
                        layer_oqh2.setVisible(false);
                    }
                });
                $("#map3").change(function () {
                    if($("#map3").is(":checked"))
                    {
                        layer_oqh_chi_tiet.setVisible(true);
                    }
                    else
                    {
                        layer_oqh_chi_tiet.setVisible(false);
                    }
                });

                $("#map4").change(function () {
                    if($("#map4").is(":checked"))
                    {
                        layer_thong_tin_quy_hoach.setVisible(true);
                    }
                    else
                    {
                        layer_thong_tin_quy_hoach.setVisible(false);
                    }
                });


        
function createJsonObj(result) {
                var geojsonObject = '{' +
                    '"type": "FeatureCollection",' +
                    '"crs": {' +
                    '"type": "name",' +
                    '"properties": {' +
                    '"name": "EPSG:4326"' +
                    '}' +
                    '},' +
                    '"features": [{' +
                    '"type": "Feature",' +
                    '"geometry": ' + result +
                    '}]' +
                    '}';
                return geojsonObject;
            }
            // loi trong ham nay nay readFeatures
            function highLightGeoJsonObj(paObjJson) {
                var vectorSource = new ol.source.Vector({
                    features: (new ol.format.GeoJSON()).readFeatures(paObjJson, {
                        dataProjection: 'EPSG:4326',
                        featureProjection: 'EPSG:3857'
                    })
                });
                vectorLayer.setSource(vectorSource);

            }

            function highLightObj(result) {
                var strObjJson = createJsonObj(result);
                var objJson = JSON.parse(strObjJson);
                highLightGeoJsonObj(objJson);
            }

            function displayObjInfo(result, coordinate) {
                $("#popup-content").html(result);
                overlay.setPosition(coordinate);

            }

        map.on('singleclick', function(evt) {
           
                var myPoint = 'POINT(12,5)';
                var lonlat = ol.proj.transform(evt.coordinate, 'EPSG:3857', 'EPSG:4326');
                var lon = lonlat[0];
                var lat = lonlat[1];
                var myPoint = 'POINT(' + lon + ' ' + lat + ')';
                var x = document.getElementById("comboA").value;
                console.log(x);
               
                if (x=='quyhoach'){
                    vectorLayer.setStyle(styleFunction);
                $.ajax({
                        type: "POST",
                        url: "CMR_pgsqlAPI.php",
                        data: {
                            functionname: 'getInfoCMRToAjax',
                            paPoint: myPoint
                        },
                        success: function(result, status, erro) {
                            displayObjInfo(result, evt.coordinate);
                        },
                        error: function(req, status, error) {
                            alert(req + " " + status + " " + error);
                        }
                    });
                $.ajax({
                        type: "POST",
                        url: "CMR_pgsqlAPI.php",
                        data: {
                            functionname: 'getGeoCMRToAjax',
                            paPoint: myPoint
                        },
                        success: function(result, status, erro) {
                            json = JSON.parse(result);
                            highLightObj(result);
                        },
                        error: function(req, status, error) {
                            alert(req + " " + status + " " + error);
                        }
                    });
                }
                else if (x=='ohq_chitiet'){
                    vectorLayer.setStyle(styleFunction);
                $.ajax({
                        type: "POST",
                        url: "CMR_pgsqlAPI.php",
                        data: {
                            functionname: 'getInfoOqhToAjax',
                            paPoint: myPoint
                        },
                        success: function(result, status, erro) {
                            displayObjInfo(result, evt.coordinate);
                        },
                        error: function(req, status, error) {
                            alert(req + " " + status + " " + error);
                        }
                    });
                $.ajax({
                        type: "POST",
                        url: "CMR_pgsqlAPI.php",
                        data: {
                            functionname: 'getGeoOqhToAjax',
                            paPoint: myPoint
                        },
                        success: function(result, status, erro) {
                            json = JSON.parse(result);
                            highLightObj(result);
                        },
                        error: function(req, status, error) {
                            alert(req + " " + status + " " + error);
                        }
                    });
                }
                else if (x=='ohq1'){
                    vectorLayer.setStyle(styleFunction);
                $.ajax({
                        type: "POST",
                        url: "CMR_pgsqlAPI.php",
                        data: {
                            functionname: 'getInfoOqh1ToAjax',
                            paPoint: myPoint
                        },
                        success: function(result, status, erro) {
                            displayObjInfo(result, evt.coordinate);
                        },
                        error: function(req, status, error) {
                            alert(req + " " + status + " " + error);
                        }
                    });
                $.ajax({
                        type: "POST",
                        url: "CMR_pgsqlAPI.php",
                        data: {
                            functionname: 'getGeoOqh1ToAjax',
                            paPoint: myPoint
                        },
                        success: function(result, status, erro) {
                            json = JSON.parse(result);
                            highLightObj(result);
                        },
                        error: function(req, status, error) {
                            alert(req + " " + status + " " + error);
                        }
                    });
                }
                else if (x=='ohq2'){
                    vectorLayer.setStyle(styleFunction);
                $.ajax({
                        type: "POST",
                        url: "CMR_pgsqlAPI.php",
                        data: {
                            functionname: 'getInfoOqh2ToAjax',
                            paPoint: myPoint
                        },
                        success: function(result, status, erro) {
                            displayObjInfo(result, evt.coordinate);
                        },
                        error: function(req, status, error) {
                            alert(req + " " + status + " " + error);
                        }
                    });
                $.ajax({
                        type: "POST",
                        url: "CMR_pgsqlAPI.php",
                        data: {
                            functionname: 'getGeoOqh2ToAjax',
                            paPoint: myPoint
                        },
                        success: function(result, status, erro) {
                            json = JSON.parse(result);
                            highLightObj(result);
                        },
                        error: function(req, status, error) {
                            alert(req + " " + status + " " + error);
                        }
                    });
                }
               

            });
        };
    </script>
</body>

</html>