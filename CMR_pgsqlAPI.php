


<?php
$paPDO = initDB();
$paSRID = '4326';
if (isset($_POST['functionname'])) {
    $paPoint = $_POST['paPoint'];

    $functionname = $_POST['functionname'];

    $aResult = "null";
    if ($functionname == 'getGeoCMRToAjax')
        $aResult = getGeoCMRToAjax($paPDO, $paSRID, $paPoint);
    else if ($functionname == 'getInfoCMRToAjax')
        $aResult = getInfoCMRToAjax($paPDO, $paSRID, $paPoint);
    else if ($functionname == 'getGeoOqhToAjax')
        $aResult = getGeoOqhToAjax($paPDO, $paSRID, $paPoint);
    else if ($functionname == 'getInfoOqhToAjax')
        $aResult = getInfoOqhToAjax($paPDO, $paSRID, $paPoint);        
    else if ($functionname == 'getGeoOqh1ToAjax')
        $aResult = getGeoOqh1ToAjax($paPDO, $paSRID, $paPoint);
    else if ($functionname == 'getInfoOqh1ToAjax')
        $aResult = getInfoOqh1ToAjax($paPDO, $paSRID, $paPoint);
    else if ($functionname == 'getGeoOqh2ToAjax')
        $aResult = getGeoOqh2ToAjax($paPDO, $paSRID, $paPoint);
    else if ($functionname == 'getInfoOqh2ToAjax')
        $aResult = getInfoOqh2ToAjax($paPDO, $paSRID, $paPoint);

    
    // echo $functionname;
    echo $aResult;

    closeDB($paPDO);
}
if (isset($_POST['name'])) {
    $name = $_POST['name'];
    $aResult = seacherCity($paPDO, $paSRID, $name);
    echo $aResult;
}

function initDB()
{
    // Kết nối CSDL
   
    $paPDO = new PDO('pgsql:host=localhost;dbname=QLDT;port=5432', 'postgres', 'postgres');
    return $paPDO;
}
function query($paPDO, $paSQLStr)
{
    try {
        // Khai báo exception
        $paPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Sử đụng Prepare 
        $stmt = $paPDO->prepare($paSQLStr);
        // Thực thi câu truy vấn
        $stmt->execute();

        // Khai báo fetch kiểu mảng kết hợp
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        // Lấy danh sách kết quả
        $paResult = $stmt->fetchAll();
        return $paResult;
    } catch (PDOException $e) {
        echo "Thất bại, Lỗi: " . $e->getMessage();
        return null;
    }
}
function closeDB($paPDO)
{
    // Ngắt kết nối
    $paPDO = null;
}

// hightlight Ban do
function getGeoCMRToAjax($paPDO, $paSRID, $paPoint)
{
    
    $paPoint = str_replace(',', ' ', $paPoint);
    $mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from thong_tin_quy_hoach where ST_Within('SRID=4326;" . $paPoint . "'::geometry,geom)";
    $result = query($paPDO, $mySQLStr);
    if ($result != null) {
        foreach ($result as $item) {
            return $item['geo'];
        }
    } else{
        
        return "null";
    }
        
}
// hightlight Ohq
function getGeoOqhToAjax($paPDO, $paSRID, $paPoint)
{
    
    $paPoint = str_replace(',', ' ', $paPoint);
    $mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from oqh_chi_tiet where ST_Within('SRID=4326;" . $paPoint . "'::geometry,geom)";
    $result = query($paPDO, $mySQLStr);
    if ($result != null) {
        foreach ($result as $item) {
            return $item['geo'];
        }
    } else{
        
        return "null";
    }
        
}
// hightlight Ohq1
function getGeoOqh1ToAjax($paPDO, $paSRID, $paPoint)
{
    
    $paPoint = str_replace(',', ' ', $paPoint);
    $mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from oqh1 where ST_Within('SRID=4326;" . $paPoint . "'::geometry,geom)";
    $result = query($paPDO, $mySQLStr);
    if ($result != null) {
        foreach ($result as $item) {
            return $item['geo'];
        }
    } else{
        
        return "null";
    }
        
}

// hightlight Ohq2
function getGeoOqh2ToAjax($paPDO, $paSRID, $paPoint)
{
    $paPoint = str_replace(',', ' ', $paPoint);
    $mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from oqh2 where ST_Within('SRID=4326;" . $paPoint . "'::geometry,geom)";
    $result = query($paPDO, $mySQLStr);
    if ($result != null) {
        // Lặp kết quả
        foreach ($result as $item) {
            return $item['geo'];
        }
    } else
        return "null";
}

// Truy van thong tin VN
function getInfoCMRToAjax($paPDO, $paSRID, $paPoint)
{
    $paPoint = str_replace(',', ' ', $paPoint);
    $mySQLStr = "SELECT  * from \"thong_tin_quy_hoach\" where ST_Within('SRID=4326;" . $paPoint . "'::geometry,geom)";
    
    $result = query($paPDO, $mySQLStr);

    if ($result != null) {
        $resFin = '<table>';
        // Lặp kết quả
        foreach ($result as $item) {
            $resFin = $resFin . '<tr><td>Địa chỉ: ' . $item['dia_chi'] . '</td></tr>';
            $resFin = $resFin . '<tr><td>Tên Tỉnh: ' . $item['ten_xa'] . '</td></tr>';
            $resFin = $resFin . '<tr><td>Mã Đất: ' . $item['madat'] . '</td></tr>';
            $resFin = $resFin . '<tr><td>ID Thửa đất: ' . $item['sh_thua'] . '</td></tr>';
            break;
        }
        $resFin = $resFin . '</table>';
        return $resFin;
    } else
        return "null";
}

//Truy van thong tin Ohq chi tiet
function getInfoOqhToAjax($paPDO, $paSRID, $paPoint)
{
    $paPoint = str_replace(',', ' ', $paPoint);
 
    $mySQLStr = "SELECT  * from \"oqh_chi_tiet\" where ST_Within('SRID=4326;" . $paPoint . "'::geometry,geom)";
    $result = query($paPDO, $mySQLStr);

    if ($result != null) {
        $resFin = '<table>';
        // Lặp kết quả
        foreach ($result as $item) {
            $resFin = $resFin . '<tr><td>Mã Vùng: ' . $item['ten_oqhct'] . '</td></tr>';
            $resFin = $resFin . '<tr><td>Mã Sử Dụng Đất: ' . $item['masdd'] . '</td></tr>';
            $resFin = $resFin . '<tr><td>Tiền Tố: ' . $item['mdsdd'] . '</td></tr>';
            break;
        }
        $resFin = $resFin . '</table>';
        return $resFin;
    } else
        return "null";
}

//Truy van thong tin Ohq1
function getInfoOqh1ToAjax($paPDO, $paSRID, $paPoint)
{
    $paPoint = str_replace(',', ' ', $paPoint);
    $mySQLStr = "SELECT  * from \"oqh1\" where ST_Within('SRID=4326;" . $paPoint . "'::geometry,geom)";
    $result = query($paPDO, $mySQLStr);

    if ($result != null) {
        $resFin = '<table>';
        // Lặp kết quả
        foreach ($result as $item) {
            $resFin = $resFin . '<tr><td>Mã Vùng: ' . $item['ten_oqh1'] . '</td></tr>';
            $resFin = $resFin . '<tr><td>Mã Định Danh: ' . $item['ma_oqh1'] . '</td></tr>';
            $resFin = $resFin . '<tr><td>Dân Số: ' . $item['dan_so'] . '</td></tr>';
            $resFin = $resFin . '<tr><td>Diện Tích: ' . $item['dien_tich'] . '</td></tr>';
            break;
        }
        $resFin = $resFin . '</table>';
        return $resFin;
    } else
        return "null";
}

// truy van thong tin Ohq2
function getInfoOqh2ToAjax($paPDO, $paSRID, $paPoint)
{
    $paPoint = str_replace(',', ' ', $paPoint);
    $mySQLStr = "SELECT  * from \"oqh2\" where ST_Within('SRID=4326;" . $paPoint . "'::geometry,geom)";
    $result = query($paPDO, $mySQLStr);

    if ($result != null) {
        $resFin = '<table>';
        // Lặp kết quả
        foreach ($result as $item) {
            $resFin = $resFin . '<tr><td>Mã Vùng: ' . $item['ten_oqh2'] . '</td></tr>';
            $resFin = $resFin . '<tr><td>Mã Định Danh: ' . $item['ma_oqh1'] . '</td></tr>';
            $resFin = $resFin . '<tr><td>Dân Số: ' . $item['dan_so'] . '</td></tr>';
            $resFin = $resFin . '<tr><td>Diện Tích: ' . $item['dien_tich'] . '</td></tr>';
            break;
        }
        $resFin = $resFin . '</table>';
        return $resFin;
    } else
        return "null";
}

//tim kiem
function seacherCity($paPDO, $paSRID, $name)
{
    
    $mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from thong_tin_quy_hoach where sh_thua ::varchar like '$name'";
    $result = query($paPDO, $mySQLStr);

    if ($result != null) {
        // Lặp kết quả
        foreach ($result as $item) {
            return $item['geo'];
        }
    } else
        return "null";
}
