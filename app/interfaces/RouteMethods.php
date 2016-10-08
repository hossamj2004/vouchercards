<?php
namespace app\interfaces;
/**
 * Created by PhpStorm.
 * User: rudy
 * Date: 2/10/15
 * Time: 3:48 PM
 * @desc this interface is responsible for common actions between controller while routing it has (get,save,edit,delete) Actions.
 * @author Rudy Zidan
 */
interface RouteMethods{
    /**
     * @param $start
     * @param $limit
     * @param $token
     * @return mixed
     * @desc is responsible for retriving data with filters, start and limit.
     * token is responsible for matching checksum for request verification.
     */
    public function getAction($start,$limit,$token);

    /**
     * @param $token
     * @return mixed
     * @desc is responsible for store data.
     * token is responsible for matching checksum for request verification.
     */
    public function saveAction($token);

    /**
     * @param $token
     * @return mixed
     * @desc is responsible for update data.
     * token is responsible for matching checksum for request verification.
     */
    public function editAction($token);

    /**
     * @param $token
     * @return mixed
     * @desc is responsible for softDelete row.
     * token is responsible for matching checksum for request verification.
     */
    public function deleteAction($token);
}