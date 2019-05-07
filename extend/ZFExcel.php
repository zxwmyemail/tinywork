<?php
namespace app\extend;

use app\system\Application;

class ZFExcel {
    public function __construct() {
        Application::registerAutoload(false);
        require_once(SYS_FRAMEWORK_PATH . DS . 'phpexcel' . DS . 'PHPExcel.php');
    }

    /*-------------------------------------------------------------------------------------------------------------------
    | 将数组导出成excel文件
    |--------------------------------------------------------------------------------------------------------------------
    | @param   $data        要导出的数据 ，二维数组 array(array('1','李逵','男'), array('2','松江','男'))
    | @param   $title       字段名称，一维数组 array('ID','姓名','性别')
    | @param   $filename    导出的文件名
    | @param   $author      导出的excel作者
    --------------------------------------------------------------------------------------------------------------------*/
    public function exportExcel($fields, $data = null, $filename = 'exportExcel', $author = 'iProg') {
        $objPHPExcel = new \PHPExcel();
        /*以下是一些设置 ，什么作者  标题啊之类的*/
        $objPHPExcel->getProperties()
                    ->setCreator("system_gm_tool")
                    ->setLastModifiedBy("system_gm_tool")
                    ->setTitle($filename)
                    ->setSubject($filename)
                    ->setDescription($filename)
                    ->setKeywords($filename)
                    ->setCategory($filename);

        $objPHPExcel->getDefaultStyle()->getFont()->setName('微软雅黑');
        $objPHPExcel->getDefaultStyle()->getFont()->setSize(11);
        $cellObj = $objPHPExcel->setActiveSheetIndex(0);

        $columnIndex = array(
            'A',  'B',  'C',  'D',  'E',  'F',  'G',  'H',  'I',  'J',  'K',  'L',  'M', 
            'N',  'O',  'P',  'Q',  'R',  'S',  'T',  'U',  'V',  'W',  'X',  'Y',  'Z',
            'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 
            'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ',
            'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 
            'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ',
            'CA', 'CB', 'CC', 'CD', 'CE', 'CF', 'CG', 'CH', 'CI', 'CJ', 'CK', 'CL', 'CM', 
            'CN', 'CO', 'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ',
            'DA', 'DB', 'DC', 'DD', 'DE', 'DF', 'DG', 'DH', 'DI', 'DJ', 'DK', 'DL', 'DM', 
            'DN', 'DO', 'DP', 'DQ', 'DR', 'DS', 'DT', 'DU', 'DV', 'DW', 'DX', 'DY', 'DZ',
        );

        $step = 0;
        if ($fields) {
            foreach ($fields as $key => $value) {
                $cellObj->getColumnDimension($columnIndex[$key])->setWidth(20);
                $cellObj->setCellValue($columnIndex[$key].'1', $value);
                $objPHPExcel->getActiveSheet()->getStyle($columnIndex[$key].'1')->getFont()->setBold(true);
            }
            $step = 2;
        }
        
        //设置表格数据
        if (!empty($data)) {
            foreach($data as $k => $v){
                $num = $k + $step;
                foreach ($v as $key => $value) {
                    $cellObj->setCellValue($columnIndex[$key].$num, $value);
                } 
            }
        }
        
        $objPHPExcel->getActiveSheet()->setTitle($filename);
        $objPHPExcel->setActiveSheetIndex(0);

        ob_end_clean();  

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-excel;");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition: attachment;filename='.$filename.'.xlsx');
        header("Content-Transfer-Encoding:binary");
        header('Cache-Control: max-age=0');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); //Excel2007 Excel5
        $objWriter->save('php://output');
    }

    /*-------------------------------------------------------------------------------------------------------------------
    | 将excel文件导入成php的数组，方便存入数据库
    --------------------------------------------------------------------------------------------------------------------*/
    public function importExcel($filename, $encode ='utf-8'){

        $objReader = \PHPExcel_IOFactory::createReader('Excel2007'); //Excel2007 Excel5
        $objReader->setReadDataOnly(true);
        $objPHPExcel   = $objReader->load($filename);
        $objWorksheet  = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow(); 
        $highestColumn = $objWorksheet->getHighestColumn(); 

        $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn); 

        $excelData = array(); 
        for($row = 1; $row <= $highestRow; $row++) { 
            for ($col = 0; $col < $highestColumnIndex; $col++) { 
                $excelData[$row][] = (string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
            } 
        } 

        return $excelData;
    }  
    
}

