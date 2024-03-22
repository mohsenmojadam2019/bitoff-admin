<?php


namespace App\Services\Report;


interface ReportInterface
{
    /**
     * Data render in main report blade
     *
     * @param array $parameter
     * @return array
     */

    public function getGeneralData($parameter = []);

    /**
     * Data render in special report blade
     *
     * @param array $parameter
     * @return array
     */
    public function getData($parameter = []);

    /**
     * @param array $parameter
     * @return array
     */
    public function getDataViaAjax($parameter = []);

    /**
     * @param array $parameter
     * @return string
     */
    public function getPathView($parameter = []);

    /**
     * @param array $parameter
     * @return string
     */
    public function getTitlePage($parameter = []);
}
