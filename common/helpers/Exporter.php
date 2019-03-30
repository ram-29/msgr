<?php

namespace common\helpers;

use Yii;
use LSS\Array2XML;
use Ramsey\Uuid\Uuid;
use JasperPHP\JasperPHP;
use yii\helpers\ArrayHelper;
use Underscore\Underscore as __;

use common\helpers\Getter;
use common\helpers\Logger;

/**
 * A standard class used for exporting data via JasperReports.
 * 
 * @ignore Debugging
 * $this->JASPER->process($iar, $outDir, $this->OPTS);
 * return $this->JASPER->output();
 */
class Exporter {

    /**
     * Private vars
     */
    private $EXT;
    private $OPTS;
    private $JASPER;
    private $JWEBROOT;

    /**
     * Object constructor
     */
    public function __construct()
    {
        // Extract Host & DB
        list($host, $db) = explode(';', Yii::$app->db->dsn);
        $host = explode('=', $host)[1];
        $db = explode('=', $db)[1];

        $this->EXT = '.pdf';
        $this->OPTS = [
            'format' => ['pdf'],
            'locale' => 'en',
            'db_connection' => [
                'host' => $host,
                'port' => '3306',
                'database' => $db,
                'driver' => Yii::$app->db->driverName,
                'username' => Yii::$app->db->username,
                'jdbc_driver' => 'com.mysql.jdbc.Driver',
                'jdbc_url' => "jdbc:mysql://{$host}/{$db}",
                'jdbc_dir' => Yii::getAlias('@webroot').'/jdbc'
            ]
        ];

        // Inject a password.
        if(!empty(Yii::$app->db->password)) {
            $this->OPTS['db_connection']['password'] = Yii::$app->db->password;
        }

        $this->JASPER = new JasperPHP();
        $this->JWEBROOT = Yii::getAlias('@webroot').'/jasper';
        
        return $this;
    }

    /**
     * Main Export function
     * @param string $type
     * @param json $params
     * @return void
     */
    public function export($type, $params)
    {
        $uuid = Uuid::uuid4()->toString();

        switch($type) {

            // @todo Define your logic here ..
            
        }
    }

    /**
     * Function used for generating the actual report
     * @param array $configOpts
     * @param string $jrxmlPath
     * @param string $jasperPath
     * @param string $outFilePath
     * @param array[string] $subReportPath
     * @return array
     */
    protected function generate($configOpts, $jrxmlPath, $jasperPath, $outFilePath, ...$subReportPath)
    {
        // Compile .jrxml main template
        $this->JASPER->compile($jrxmlPath)->execute();

        // Compile .jrxml subreport template
        if(!empty($subReportPath)) {
            foreach ($subReportPath as $mPath) {
                $this->JASPER->compile($mPath)->execute();
            }
        }

        // Check .jasper compiled file
        if(file_exists($jasperPath)) {
            
            // Check directory existence
            $mDir = pathinfo($outFilePath, PATHINFO_DIRNAME);
            if (!file_exists($mDir)) {
                mkdir($mDir, 0777, true);
            }

            // Generate report
            try {
                $this->JASPER->process(
                    $jasperPath,
                    $outFilePath,
                    $configOpts
                )->execute();

                if(file_exists($outFilePath.$this->EXT)) {
                    return [
                        'success' => true,
                        'file' => basename($outFilePath).$this->EXT
                    ];
                }
            } catch(Exception $e) {
                return [
                    'success' => false,
                    'message' => $e->getMessage()
                ];
            }
        } else {
            return Logger::log('Your .jasper file hasn\'t been generated.');
        }
    }

    /**
     * Function used for force creating a directory & file
     * @param string $filename
     * @param mixed $data
     * @param integer $flags
     * @return bool
     */
    protected function file_force_contents($filename, $data, $flags = 0) 
    {
        if(!is_dir(dirname($filename)))
            mkdir(dirname($filename).'/', 0777, TRUE);
        return file_put_contents($filename, $data, $flags);
    }

    /**
     * Function used for generating DataAdapter used in reports
     * @param string $name
     * @param string $dataSource
     * @param string $location
     * @return void
     */
    protected function generateAdapter($name, $dataSource, $location) 
    {
        // Param attributes
        $mJava = 'http://java.sun.com';
        $mXsi = 'http://www.w3.org/2001/XMLSchema-instance';
        $mType = ['repositoryDataLocation', 'java:java.lang.String'];

        $mXml = [
            '@attributes' => [
                'class' => 'net.sf.jasperreports.data.json.JsonDataAdapterImpl',
            ],
            'name' => $name,
            'dataFile' => [
                '@attributes' => [
                    'xmlns:xsi' => $mXsi,
                    'xsi:type' => $mType[0]
                ],
                'location' => $dataSource
            ],
            'language' => 'json',
            'useConnection' => 'true',
            'timeZone' => [
                '@value' => 'Asia/Taipei',
                '@attributes' => [
                    'xmlns:xsi' => $mXsi,
                    'xmlns:java' => $mJava,
                    'xsi:type' => $mType[1]
                ]
            ],
            'locale' => [
                '@value' => 'en_PH',
                '@attributes' => [
                    'xmlns:xsi' => $mXsi,
                    'xmlns:java' => $mJava,
                    'xsi:type' => $mType[1]
                ]
            ],
            'selectExpression' => []
        ];

        // Create the adapter.
        $mAdapter = Array2XML::createXML('jsonDataAdapter', $mXml);
        $mAdapter->saveXML();
        $mAdapter->save($location);
    }
}